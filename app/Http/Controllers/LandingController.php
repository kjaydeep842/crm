<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inquiry;
use App\Models\Lead;
use App\Models\Activity;
use App\Models\User;
use App\Services\AIService;
use Illuminate\Support\Facades\Auth;

class LandingController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('landing');
    }

    public function submitInquiry(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile' => 'nullable|string|max:20',
            'requirement' => 'required|string',
            'company' => 'nullable|string|max:255',
        ]);

        // Create the Inquiry
        $inquiry = Inquiry::create([
            'customer_name' => $request->name,
            'contact' => $request->email . ($request->mobile ? ' | ' . $request->mobile : ''),
            'message' => $request->requirement,
            'source' => 'Website',
            'date' => now(),
            'status' => 'Pending'
        ]);

        // Process with AI
        $analysis = $this->aiService->analyzeLead($request->requirement);
        
        $inquiry->update([
            'ai_summary' => $analysis['summary'] ?? '',
            'ai_intent' => $analysis['buying_intent'] ?? '',
            'ai_urgency' => $analysis['urgency'] ?? 'Medium',
            'ai_budget_estimate' => $analysis['budget_estimate'] ?? '',
            'ai_recommended_department' => $analysis['recommended_department'] ?? 'Sales',
        ]);

        // If qualified, elevate to Lead
        if (isset($analysis['qualification']) && in_array($analysis['qualification'], ['Hot Lead', 'Warm Lead'])) {
            $agent = User::where('role', 'sales')->inRandomOrder()->first();
            
            $lead = Lead::create([
                'full_name' => $request->name,
                'company_name' => $request->company ?: ($request->name . ' Co'),
                'mobile' => $request->mobile,
                'email' => $request->email,
                'lead_source' => 'Website',
                'requirement' => $request->requirement,
                'status' => 'New',
                'assigned_to' => $agent ? $agent->id : null,
                'ai_score' => $analysis['score'] ?? 50,
                'ai_qualification' => $analysis['qualification'] ?? 'Warm Lead',
                'ai_priority' => $analysis['priority'] ?? 'Medium',
                'ai_recommended_followup' => $analysis['recommended_followup'] ?? '',
                'ai_summary' => $analysis['summary'] ?? '',
                'ai_intent' => $analysis['buying_intent'] ?? '',
                'ai_urgency' => $analysis['urgency'] ?? 'Medium',
                'ai_budget_estimate' => $analysis['budget_estimate'] ?? '',
                'ai_recommended_department' => $analysis['recommended_department'] ?? 'Sales',
                'ai_sales_probability' => $analysis['sales_probability'] ?? 50,
                'ai_recommended_service' => $analysis['recommended_service'] ?? '',
            ]);

            $inquiry->update([
                'lead_id' => $lead->id,
                'status' => 'Processed',
                'assigned_to' => $agent ? $agent->id : null,
            ]);

            Activity::create([
                'lead_id' => $lead->id,
                'type' => 'Website Inquiry',
                'description' => "Captured inquiry via website landing page. Qualified as {$lead->ai_qualification}."
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Thank you for your inquiry! Our AI system has qualified your request and assigned a sales agent.'
        ]);
    }
}
