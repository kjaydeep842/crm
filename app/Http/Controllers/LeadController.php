<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\User;
use App\Models\Activity;
use App\Models\Meeting;
use App\Models\Task;
use App\Services\AIService;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Lead::with('assignedAgent');

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('source')) {
            $query->where('lead_source', $request->source);
        }
        if ($request->filled('agent')) {
            $query->where('assigned_to', $request->agent);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%")
                  ->orWhere('requirement', 'like', "%{$search}%");
            });
        }

        // Scope by multi-org hierarchy
        if ($user->isSuperAdmin()) {
            // SuperAdmin sees all
            $agents = User::whereIn('role', ['sales', 'staff'])->get();
        } elseif ($user->isAdmin()) {
            // Org Admin sees only their organization's leads
            $orgUserIds = User::where('organization_id', $user->organization_id)->pluck('id');
            $query->whereIn('assigned_to', $orgUserIds);
            $agents = User::where('organization_id', $user->organization_id)->whereIn('role', ['sales', 'staff'])->get();
        } else {
            // Staff sees only their assigned leads
            $query->where('assigned_to', $user->id);
            $agents = User::where('id', $user->id)->get();
        }

        $leads = $query->latest()->paginate(10)->withQueryString();

        return view('leads.index', compact('leads', 'agents'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'full_name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:255',
            'lead_source' => 'required|string',
            'budget' => 'nullable|numeric',
            'requirement' => 'required|string',
            'notes' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $data = $request->all();
        // If not specified, assign to the creator if they are staff
        if (empty($data['assigned_to']) && !$user->isSuperAdmin() && !$user->isAdmin()) {
            $data['assigned_to'] = $user->id;
        }

        $lead = Lead::create($data);

        // Log manual creation activity
        Activity::create([
            'lead_id' => $lead->id,
            'user_id' => $user->id,
            'type' => 'Call',
            'description' => 'Lead manually created in the system.'
        ]);

        // Auto-trigger AI Analysis
        $this->analyzeLeadAI($lead);

        return redirect()->route('leads.show', $lead->id)->with('success', 'Lead created successfully and analyzed by AI.');
    }

    public function show($id)
    {
        $user = Auth::user();
        $lead = Lead::with(['assignedAgent', 'activities.user', 'meetings', 'tasks', 'documents'])->findOrFail($id);
        
        // Security check
        if (!$user->isSuperAdmin()) {
            if ($user->isAdmin()) {
                $assignedUser = User::find($lead->assigned_to);
                if (!$assignedUser || $assignedUser->organization_id !== $user->organization_id) {
                    abort(403, 'Unauthorized lead access.');
                }
            } else {
                if ($lead->assigned_to !== $user->id) {
                    abort(403, 'Unauthorized lead access.');
                }
            }
        }

        if ($user->isSuperAdmin()) {
            $agents = User::whereIn('role', ['sales', 'staff'])->get();
        } elseif ($user->isAdmin()) {
            $agents = User::where('organization_id', $user->organization_id)->whereIn('role', ['sales', 'staff'])->get();
        } else {
            $agents = User::where('id', $user->id)->get();
        }

        return view('leads.show', compact('lead', 'agents'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $lead = Lead::findOrFail($id);

        // Security check
        if (!$user->isSuperAdmin()) {
            if ($user->isAdmin()) {
                $assignedUser = User::find($lead->assigned_to);
                if (!$assignedUser || $assignedUser->organization_id !== $user->organization_id) {
                    abort(403, 'Unauthorized action.');
                }
            } else {
                if ($lead->assigned_to !== $user->id) {
                    abort(403, 'Unauthorized action.');
                }
            }
        }
        
        $request->validate([
            'full_name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|string|max:255',
            'industry' => 'nullable|string|max:255',
            'lead_source' => 'required|string',
            'budget' => 'nullable|numeric',
            'requirement' => 'required|string',
            'notes' => 'nullable|string',
            'status' => 'required|string',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $oldStatus = $lead->status;
        $lead->update($request->all());

        if ($oldStatus !== $lead->status) {
            Activity::create([
                'lead_id' => $lead->id,
                'user_id' => $user->id,
                'type' => 'Status Change',
                'description' => "Status updated from '{$oldStatus}' to '{$lead->status}'."
            ]);
        }

        return redirect()->back()->with('success', 'Lead updated successfully.');
    }

    public function triggerAI($id)
    {
        $user = Auth::user();
        $lead = Lead::findOrFail($id);

        // Security check
        if (!$user->isSuperAdmin()) {
            if ($user->isAdmin()) {
                $assignedUser = User::find($lead->assigned_to);
                if (!$assignedUser || $assignedUser->organization_id !== $user->organization_id) {
                    abort(403, 'Unauthorized action.');
                }
            } else {
                if ($lead->assigned_to !== $user->id) {
                    abort(403, 'Unauthorized action.');
                }
            }
        }

        $this->analyzeLeadAI($lead);
        return redirect()->back()->with('success', 'AI Analysis re-run successfully.');
    }

    protected function analyzeLeadAI(Lead $lead)
    {
        // Call AI Service
        $analysis = $this->aiService->analyzeLead($lead->requirement);

        $lead->update([
            'ai_score' => $analysis['score'] ?? 50,
            'ai_qualification' => $analysis['qualification'] ?? 'Warm Lead',
            'ai_priority' => $analysis['priority'] ?? 'Medium',
            'ai_recommended_followup' => $analysis['recommended_followup'] ?? '',
            'ai_summary' => $analysis['summary'] ?? '',
            'ai_intent' => $analysis['buying_intent'] ?? '',
            'ai_urgency' => $analysis['urgency'] ?? 'Medium',
            'ai_budget_estimate' => $analysis['budget_estimate'] ?? '',
            'ai_recommended_department' => $analysis['recommended_department'] ?? '',
            'ai_sales_probability' => $analysis['sales_probability'] ?? 50,
            'ai_recommended_service' => $analysis['recommended_service'] ?? '',
        ]);

        Activity::create([
            'lead_id' => $lead->id,
            'user_id' => null,
            'type' => 'AI Insight',
            'description' => "AI completed lead analysis. Qualification: {$lead->ai_qualification}, Priority: {$lead->ai_priority}, Score: {$lead->ai_score}/100."
        ]);
    }

    public function generateFollowUp(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);
        $transcript = $request->input('transcript', '');

        $customerDetails = [
            'full_name' => $lead->full_name,
            'company_name' => $lead->company_name,
            'mobile' => $lead->mobile,
            'email' => $lead->email,
        ];

        $summary = $transcript ?: $lead->ai_summary ?: $lead->requirement;

        $followup = $this->aiService->generateFollowUp($customerDetails, $summary);

        return response()->json($followup);
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $lead = Lead::findOrFail($id);

        // Security check
        if (!$user->isSuperAdmin()) {
            if ($user->isAdmin()) {
                $assignedUser = User::find($lead->assigned_to);
                if (!$assignedUser || $assignedUser->organization_id !== $user->organization_id) {
                    abort(403, 'Unauthorized action.');
                }
            } else {
                if ($lead->assigned_to !== $user->id) {
                    abort(403, 'Unauthorized action.');
                }
            }
        }

        $lead->delete();
        return redirect()->route('leads.index')->with('success', 'Lead deleted successfully.');
    }
}
