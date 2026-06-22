<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Meeting;
use App\Models\Lead;
use App\Models\Task;
use App\Models\Activity;
use App\Models\User;
use App\Services\AIService;
use Illuminate\Support\Facades\Auth;

class MeetingController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Meeting::with('lead');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Scope queries by multi-org hierarchy
        if ($user->isSuperAdmin()) {
            $leads = Lead::all();
        } elseif ($user->isAdmin()) {
            $orgUserIds = User::where('organization_id', $user->organization_id)->pluck('id');
            $query->whereHas('lead', function($q) use ($orgUserIds) {
                $q->whereIn('assigned_to', $orgUserIds);
            });
            $leads = Lead::whereIn('assigned_to', $orgUserIds)->get();
        } else {
            $query->whereHas('lead', function($q) use ($user) {
                $q->where('assigned_to', $user->id);
            });
            $leads = Lead::where('assigned_to', $user->id)->get();
        }

        $meetings = $query->orderBy('date', 'desc')->orderBy('time', 'desc')->paginate(10);

        return view('meetings.index', compact('meetings', 'leads'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'title' => 'required|string|max:255',
            'customer_name' => 'nullable|string|max:255',
            'lead_id' => 'required|exists:leads,id',
            'date' => 'required|date',
            'time' => 'required',
            'meeting_link' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $lead = Lead::findOrFail($request->lead_id);
        
        // Security check
        if (!$user->isSuperAdmin()) {
            if ($user->isAdmin()) {
                $assignedUser = User::find($lead->assigned_to);
                if (!$assignedUser || $assignedUser->organization_id !== $user->organization_id) {
                    abort(403, 'Unauthorized lead for scheduling meeting.');
                }
            } else {
                if ($lead->assigned_to !== $user->id) {
                    abort(403, 'Unauthorized lead for scheduling meeting.');
                }
            }
        }

        $meeting = Meeting::create([
            'title' => $request->title,
            'customer_name' => $request->customer_name ?: $lead->full_name,
            'lead_id' => $request->lead_id,
            'date' => $request->date,
            'time' => $request->time,
            'meeting_link' => $request->meeting_link,
            'location' => $request->location,
            'notes' => $request->notes,
            'status' => 'Scheduled'
        ]);

        // Trigger AI Meeting Prep automatically
        $this->prepAI($meeting->id);

        // Log activity
        Activity::create([
            'lead_id' => $lead->id,
            'user_id' => $user->id,
            'type' => 'Meeting',
            'description' => "Scheduled meeting: '{$meeting->title}' on {$meeting->date} at {$meeting->time}."
        ]);

        return redirect()->route('meetings.index')->with('success', 'Meeting scheduled and AI preparation generated.');
    }

    public function show($id)
    {
        $user = Auth::user();
        $meeting = Meeting::with('lead')->findOrFail($id);
        
        // Security check
        if (!$user->isSuperAdmin()) {
            $lead = $meeting->lead;
            if ($user->isAdmin()) {
                $assignedUser = User::find($lead->assigned_to);
                if (!$assignedUser || $assignedUser->organization_id !== $user->organization_id) {
                    abort(403, 'Unauthorized meeting access.');
                }
            } else {
                if ($lead->assigned_to !== $user->id) {
                    abort(403, 'Unauthorized meeting access.');
                }
            }
        }

        return view('meetings.show', compact('meeting'));
    }

    public function prepAI($id)
    {
        $user = Auth::user();
        $meeting = Meeting::with('lead')->findOrFail($id);
        $lead = $meeting->lead;

        // Security check
        if (!$user->isSuperAdmin()) {
            if ($user->isAdmin()) {
                $assignedUser = User::find($lead->assigned_to);
                if (!$assignedUser || $assignedUser->organization_id !== $user->organization_id) {
                    abort(403, 'Unauthorized.');
                }
            } else {
                if ($lead->assigned_to !== $user->id) {
                    abort(403, 'Unauthorized.');
                }
            }
        }

        // Call AI service to prep meeting details (we pass current lead details)
        $analysis = $this->aiService->analyzeLead($lead->requirement);

        $meeting->update([
            'ai_customer_summary' => $lead->ai_summary ?: $analysis['summary'] ?: 'New lead with core requirements: ' . $lead->requirement,
            'ai_previous_interactions' => 'Initial inquiry registered via ' . $lead->lead_source . '.',
            'ai_suggested_topics' => "1. Detail scope of {$lead->ai_intent}.\n2. Confirm budget and milestones (Target: {$lead->ai_budget_estimate}).\n3. Walkthrough recommended service: {$lead->ai_recommended_service}."
        ]);

        return redirect()->back()->with('success', 'AI Meeting Prep updated.');
    }

    public function processNotesAI(Request $request, $id)
    {
        $user = Auth::user();
        $meeting = Meeting::with('lead')->findOrFail($id);
        $lead = $meeting->lead;

        // Security check
        if (!$user->isSuperAdmin()) {
            if ($user->isAdmin()) {
                $assignedUser = User::find($lead->assigned_to);
                if (!$assignedUser || $assignedUser->organization_id !== $user->organization_id) {
                    abort(403, 'Unauthorized.');
                }
            } else {
                if ($lead->assigned_to !== $user->id) {
                    abort(403, 'Unauthorized.');
                }
            }
        }
        
        $request->validate([
            'transcript' => 'required|string',
        ]);

        // Call AI Summary Service
        $summary = $this->aiService->summarizeMeeting($request->transcript);

        $meeting->update([
            'transcript' => $request->transcript,
            'ai_summary' => $summary['summary'] ?? '',
            'ai_action_items' => implode("\n", $summary['key_decisions'] ?? []),
            'ai_followup_tasks' => implode("\n", $summary['action_items'] ?? []),
            'ai_next_meeting_suggestions' => implode("\n", $summary['followup_tasks'] ?? []),
            'status' => 'Completed'
        ]);

        // Auto-generate follow-up tasks in the tasks table
        if (isset($summary['action_items']) && count($summary['action_items']) > 0) {
            foreach ($summary['action_items'] as $item) {
                Task::create([
                    'title' => substr($item, 0, 100),
                    'type' => 'Follow-up',
                    'lead_id' => $meeting->lead_id,
                    'user_id' => $meeting->lead->assigned_to ?: $user->id,
                    'due_date' => date('Y-m-d', strtotime('+2 days')),
                    'priority' => 'Medium',
                    'status' => 'Pending',
                    'notes' => 'Auto-generated task from Meeting: ' . $meeting->title,
                    'ai_suggested' => true
                ]);
            }
        }

        // Log activity
        Activity::create([
            'lead_id' => $meeting->lead_id,
            'user_id' => $user->id,
            'type' => 'Meeting',
            'description' => "Completed meeting '{$meeting->title}' and processed notes with AI."
        ]);

        return redirect()->back()->with('success', 'Meeting notes processed and tasks auto-scheduled.');
    }
}
