<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inquiry;
use App\Models\Lead;
use App\Models\User;
use App\Models\Activity;
use App\Services\AIService;
use Illuminate\Support\Facades\Auth;

class InquiryController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        abort_if($user->isStaff(), 403, 'Unauthorized access.');

        $query = Inquiry::with(['assignedAgent', 'lead']);

        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('contact', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        // Scope by multi-org tenancy
        if ($user->isSuperAdmin()) {
            $agents = User::whereIn('role', ['sales', 'staff'])->get();
        } else {
            $orgUserIds = User::where('organization_id', $user->organization_id)->pluck('id');
            $query->whereIn('assigned_to', $orgUserIds);
            $agents = User::where('organization_id', $user->organization_id)->whereIn('role', ['sales', 'staff'])->get();
        }

        $inquiries = $query->latest()->paginate(10)->withQueryString();

        return view('inquiries.index', compact('inquiries', 'agents'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        abort_if($user->isStaff(), 403, 'Unauthorized.');

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'message' => 'required|string',
            'source' => 'required|string',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        // Security check for Org Admin
        if ($user->isAdmin() && $request->filled('assigned_to')) {
            $assignedUser = User::find($request->assigned_to);
            if (!$assignedUser || $assignedUser->organization_id !== $user->organization_id) {
                abort(403, 'Cannot assign inquiry to users outside your organization.');
            }
        }

        $inquiry = Inquiry::create([
            'customer_name' => $request->customer_name,
            'contact' => $request->contact,
            'message' => $request->message,
            'source' => $request->source,
            'date' => now(),
            'assigned_to' => $request->assigned_to,
            'status' => 'Pending'
        ]);

        // Auto-run AI Analysis immediately
        $this->analyzeInquiryAI($inquiry);

        return redirect()->route('inquiries.index')->with('success', 'Inquiry logged manually and enriched by AI.');
    }

    public function analyze($id)
    {
        $user = Auth::user();
        abort_if($user->isStaff(), 403, 'Unauthorized.');

        $inquiry = Inquiry::findOrFail($id);

        // Security check
        if ($user->isAdmin() && $inquiry->assigned_to) {
            $assignedUser = User::find($inquiry->assigned_to);
            if (!$assignedUser || $assignedUser->organization_id !== $user->organization_id) {
                abort(403, 'Unauthorized.');
            }
        }

        $this->analyzeInquiryAI($inquiry);
        return redirect()->back()->with('success', 'AI Enrichment completed.');
    }

    protected function analyzeInquiryAI(Inquiry $inquiry)
    {
        $user = Auth::user();
        $org = $user ? clone $user->organization : null;
        if (!$org && $user && $user->isSuperAdmin()) {
            $org = clone \App\Models\Organization::first();
        }

        if ($org && !$org->hasAiCredits(2)) {
            \Illuminate\Support\Facades\Log::warning("AI Analysis skipped for inquiry {$inquiry->id} due to package limit.");
            return;
        }
        if ($org) {
            $org->useAiCredits(2);
        }

        $analysis = $this->aiService->analyzeLead($inquiry->message);

        $inquiry->update([
            'ai_summary' => $analysis['summary'] ?? '',
            'ai_intent' => $analysis['buying_intent'] ?? '',
            'ai_urgency' => $analysis['urgency'] ?? 'Medium',
            'ai_budget_estimate' => $analysis['budget_estimate'] ?? '',
            'ai_recommended_department' => $analysis['recommended_department'] ?? 'Sales',
        ]);
    }

    public function convertToLead($id)
    {
        $user = Auth::user();
        abort_if($user->isStaff(), 403, 'Unauthorized.');

        $inquiry = Inquiry::findOrFail($id);

        // Security check
        if ($user->isAdmin() && $inquiry->assigned_to) {
            $assignedUser = User::find($inquiry->assigned_to);
            if (!$assignedUser || $assignedUser->organization_id !== $user->organization_id) {
                abort(403, 'Unauthorized.');
            }
        }

        // Parse contact info (very simple regex or default checks)
        $email = null;
        $mobile = null;
        if (filter_var(trim($inquiry->contact), FILTER_VALIDATE_EMAIL)) {
            $email = trim($inquiry->contact);
        } else {
            // Assume it has numbers or is a mobile number
            $mobile = $inquiry->contact;
            // Try to extract email from message if any
            preg_match('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $inquiry->message, $matches);
            if (isset($matches[0])) {
                $email = $matches[0];
            }
        }

        // Create Lead
        $lead = Lead::create([
            'full_name' => $inquiry->customer_name,
            'company_name' => $inquiry->customer_name . ' Co',
            'mobile' => $mobile,
            'email' => $email,
            'lead_source' => $inquiry->source,
            'requirement' => $inquiry->message,
            'status' => 'New',
            'assigned_to' => $inquiry->assigned_to ?: $user->id,
            // Copy AI analysis fields
            'ai_summary' => $inquiry->ai_summary,
            'ai_intent' => $inquiry->ai_intent,
            'ai_urgency' => $inquiry->ai_urgency,
            'ai_budget_estimate' => $inquiry->ai_budget_estimate,
            'ai_recommended_department' => $inquiry->ai_recommended_department,
        ]);

        $org = $user ? clone $user->organization : null;
        if (!$org && $user && $user->isSuperAdmin()) {
            $org = clone \App\Models\Organization::first();
        }
        
        if ($org && !$org->hasAiCredits(10)) {
            \Illuminate\Support\Facades\Log::warning("AI Analysis skipped for lead {$lead->id} during conversion due to package limit.");
        } else {
            if ($org) {
                $org->useAiCredits(10);
            }
            // Trigger detailed Lead scoring & analysis
            $analysis = $this->aiService->analyzeLead($lead->requirement);
            $lead->update([
                'ai_score' => $analysis['score'] ?? 60,
                'ai_qualification' => $analysis['qualification'] ?? 'Warm Lead',
                'ai_priority' => $analysis['priority'] ?? 'Medium',
                'ai_recommended_followup' => $analysis['recommended_followup'] ?? '',
                'ai_sales_probability' => $analysis['sales_probability'] ?? 50,
                'ai_recommended_service' => $analysis['recommended_service'] ?? '',
            ]);
        }

        // Link inquiry to lead and mark processed
        $inquiry->update([
            'lead_id' => $lead->id,
            'status' => 'Processed'
        ]);

        // Log creation activity
        Activity::create([
            'lead_id' => $lead->id,
            'user_id' => $user->id,
            'type' => 'Call',
            'description' => "Lead automatically created by processing Inquiry #{$inquiry->id}."
        ]);

        return redirect()->route('leads.show', $lead->id)->with('success', 'Inquiry converted to Lead successfully.');
    }
}
