<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Lead;
use App\Models\User;
use App\Models\Meeting;
use App\Services\AIService;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Task::with(['lead', 'user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Scope queries by multi-org hierarchy
        if ($user->isSuperAdmin()) {
            $leads = Lead::all();
            $agents = User::whereIn('role', ['sales', 'staff'])->get();
        } elseif ($user->isAdmin()) {
            $orgUserIds = User::where('organization_id', $user->organization_id)->pluck('id');
            $query->whereIn('user_id', $orgUserIds);
            
            $leads = Lead::whereIn('assigned_to', $orgUserIds)->get();
            $agents = User::where('organization_id', $user->organization_id)->whereIn('role', ['sales', 'staff'])->get();
        } else {
            $query->where('user_id', $user->id);
            $leads = Lead::where('assigned_to', $user->id)->get();
            $agents = User::where('id', $user->id)->get();
        }

        $tasks = $query->orderBy('due_date')->orderBy('priority', 'desc')->paginate(15);

        return view('tasks.index', compact('tasks', 'leads', 'agents'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string',
            'lead_id' => 'nullable|exists:leads,id',
            'user_id' => 'required|exists:users,id',
            'due_date' => 'required|date',
            'priority' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        // Security check for Org Admin
        if ($user->isAdmin()) {
            $assignedUser = User::find($request->user_id);
            if (!$assignedUser || $assignedUser->organization_id !== $user->organization_id) {
                abort(403, 'Unauthorized target user.');
            }
        } elseif (!$user->isSuperAdmin()) {
            // Staff can only assign tasks to themselves
            if ((int)$request->user_id !== $user->id) {
                abort(403, 'Cannot assign tasks to other users.');
            }
        }

        Task::create($request->all());

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function toggle($id)
    {
        $user = Auth::user();
        $task = Task::findOrFail($id);

        // Security check
        if (!$user->isSuperAdmin()) {
            if ($user->isAdmin()) {
                $assignedUser = User::find($task->user_id);
                if (!$assignedUser || $assignedUser->organization_id !== $user->organization_id) {
                    abort(403, 'Unauthorized.');
                }
            } else {
                if ($task->user_id !== $user->id) {
                    abort(403, 'Unauthorized.');
                }
            }
        }

        $task->update([
            'status' => $task->status === 'Completed' ? 'Pending' : 'Completed'
        ]);

        return redirect()->back()->with('success', 'Task status updated.');
    }

    public function generatePriorityTasks()
    {
        $user = Auth::user();
        
        // Fetch active leads and meetings for context
        $leadsQuery = Lead::whereIn('status', ['New', 'Contacted', 'Qualified', 'Proposal Sent', 'Negotiation']);
        
        if ($user->isSuperAdmin()) {
            // No additional filter
        } elseif ($user->isAdmin()) {
            $orgUserIds = User::where('organization_id', $user->organization_id)->pluck('id');
            $leadsQuery->whereIn('assigned_to', $orgUserIds);
        } else {
            $leadsQuery->where('assigned_to', $user->id);
        }
        $leads = $leadsQuery->take(5)->get()->toArray();

        $meetingsQuery = Meeting::where('status', 'Scheduled');
        if ($user->isSuperAdmin()) {
            // No additional filter
        } elseif ($user->isAdmin()) {
            $orgUserIds = User::where('organization_id', $user->organization_id)->pluck('id');
            $meetingsQuery->whereHas('lead', function($q) use ($orgUserIds) {
                $q->whereIn('assigned_to', $orgUserIds);
            });
        } else {
            $meetingsQuery->whereHas('lead', function($q) use ($user) {
                $q->where('assigned_to', $user->id);
            });
        }
        $meetings = $meetingsQuery->take(5)->get()->toArray();

        // Call AI Daily Priority Generator
        $aiTasks = $this->aiService->generateDailyTasks($leads, $meetings);

        foreach ($aiTasks as $t) {
            Task::create([
                'title' => $t['title'],
                'type' => $t['type'] ?? 'Follow-up',
                'lead_id' => $t['lead_id'] ?? null,
                'user_id' => $user->id,
                'due_date' => $t['due_date'] ?? date('Y-m-d'),
                'priority' => $t['priority'] ?? 'Medium',
                'status' => 'Pending',
                'notes' => $t['notes'] ?? '',
                'ai_suggested' => true
            ]);
        }

        return redirect()->back()->with('success', 'AI Daily Priority Tasks generated successfully.');
    }
}
