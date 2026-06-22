<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inquiry;
use App\Models\Lead;
use App\Models\Activity;
use App\Models\User;
use App\Services\AIService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AIAgentController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function index()
    {
        $recentLeads = Lead::whereIn('lead_source', ['WhatsApp', 'Email'])->latest()->take(5)->get();
        $recentInquiries = Inquiry::whereIn('source', ['WhatsApp', 'Email'])->latest()->take(5)->get();
        return view('agents.index', compact('recentLeads', 'recentInquiries'));
    }

    /**
     * Webhook verification for Meta WhatsApp API.
     */
    public function whatsappVerify(Request $request)
    {
        $verifyToken = 'crm_secret_token_123';
        
        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');
        
        if ($mode && $token) {
            if ($mode === 'subscribe' && $token === $verifyToken) {
                return response($challenge, 200);
            }
        }
        return response('Unauthorized', 403);
    }

    /**
     * Webhook payload handler for Meta WhatsApp API.
     */
    public function whatsappWebhook(Request $request)
    {
        $payload = $request->all();
        Log::info('WhatsApp Webhook payload received:', $payload);

        // Extract message content
        if (isset($payload['entry'][0]['changes'][0]['value']['messages'][0])) {
            $msgObj = $payload['entry'][0]['changes'][0]['value']['messages'][0];
            $sender = $msgObj['from']; // Mobile number
            $text = $msgObj['text']['body'] ?? '';
            $contactName = $payload['entry'][0]['changes'][0]['value']['contacts'][0]['profile']['name'] ?? 'WhatsApp Contact';

            $this->processIncomingWhatsApp($sender, $contactName, $text);
        }

        return response('EVENT_RECEIVED', 200);
    }

    /**
     * Process WhatsApp Message (creates lead & inquiry if buying intent detected)
     */
    protected function processIncomingWhatsApp($mobile, $name, $messageText)
    {
        // 1. Log Inquiry
        $inquiry = Inquiry::create([
            'customer_name' => $name,
            'contact' => $mobile,
            'message' => $messageText,
            'source' => 'WhatsApp',
            'date' => now(),
            'status' => 'Pending'
        ]);

        // 2. Run AI Analysis
        $analysis = $this->aiService->analyzeLead($messageText);
        $inquiry->update([
            'ai_summary' => $analysis['summary'],
            'ai_intent' => $analysis['buying_intent'],
            'ai_urgency' => $analysis['urgency'],
            'ai_budget_estimate' => $analysis['budget_estimate'],
            'ai_recommended_department' => $analysis['recommended_department'],
        ]);

        // 3. Check qualification & intent - create lead automatically
        if (in_array($analysis['qualification'], ['Hot Lead', 'Warm Lead']) || $analysis['score'] >= 40) {
            $agent = User::where('role', 'sales')->inRandomOrder()->first();
            
            $lead = Lead::create([
                'full_name' => $name,
                'company_name' => $name . ' Co',
                'mobile' => $mobile,
                'lead_source' => 'WhatsApp',
                'requirement' => $messageText,
                'status' => 'New',
                'assigned_to' => $agent ? $agent->id : null,
                'ai_score' => $analysis['score'],
                'ai_qualification' => $analysis['qualification'],
                'ai_priority' => $analysis['priority'],
                'ai_recommended_followup' => $analysis['recommended_followup'],
                'ai_summary' => $analysis['summary'],
                'ai_intent' => $analysis['buying_intent'],
                'ai_urgency' => $analysis['urgency'],
                'ai_budget_estimate' => $analysis['budget_estimate'],
                'ai_recommended_department' => $analysis['recommended_department'],
                'ai_sales_probability' => $analysis['sales_probability'],
                'ai_recommended_service' => $analysis['recommended_service'],
            ]);

            $inquiry->update([
                'lead_id' => $lead->id,
                'status' => 'Processed',
                'assigned_to' => $agent ? $agent->id : null,
            ]);

            Activity::create([
                'lead_id' => $lead->id,
                'type' => 'WhatsApp',
                'description' => "Autonomously captured WhatsApp inquiry from {$name} ({$mobile}). Automated lead scored at {$lead->ai_score}/100 and assigned to sales."
            ]);
            
            return [
                'bot_response' => "Thank you {$name}! 👋 I've passed your interest in \"{$analysis['buying_intent']}\" to our sales team. An agent will contact you shortly.",
                'lead' => $lead
            ];
        }

        // Default automated response
        return [
            'bot_response' => "Hello 👋\n\nHow can I help you today?\n1. Product Inquiry\n2. Pricing\n3. Book Demo\n4. Speak to Sales Team\n\nSimply type your query, and our AI assistant will help you!",
            'lead' => null
        ];
    }

    /**
     * Simulated WhatsApp chat submission.
     */
    public function simulateWhatsApp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|string',
            'name' => 'required|string',
            'message' => 'required|string',
        ]);

        $result = $this->processIncomingWhatsApp(
            $request->mobile,
            $request->name,
            $request->message
        );

        return response()->json($result);
    }

    /**
     * Simulated Email parsing & response drafting.
     */
    public function simulateEmail(Request $request)
    {
        $request->validate([
            'subject' => 'required|string',
            'body' => 'required|string',
            'sender_email' => 'required|email',
            'sender_name' => 'required|string',
        ]);

        // Run AI Analysis on email body
        $analysis = $this->aiService->analyzeLead("Subject: {$request->subject}\n\n{$request->body}");

        // Create inquiry log
        $inquiry = Inquiry::create([
            'customer_name' => $request->sender_name,
            'contact' => $request->sender_email,
            'message' => "Subject: {$request->subject}\n\n{$request->body}",
            'source' => 'Email',
            'date' => now(),
            'status' => 'Processed',
            'ai_summary' => $analysis['summary'],
            'ai_intent' => $analysis['buying_intent'],
            'ai_urgency' => $analysis['urgency'],
            'ai_budget_estimate' => $analysis['budget_estimate'],
            'ai_recommended_department' => $analysis['recommended_department'],
        ]);

        $agent = User::where('role', 'sales')->inRandomOrder()->first();

        // Create Lead automatically
        $lead = Lead::create([
            'full_name' => $request->sender_name,
            'company_name' => $request->sender_name . ' Inc',
            'email' => $request->sender_email,
            'lead_source' => 'Email',
            'requirement' => "Subject: {$request->subject}\n\n{$request->body}",
            'status' => 'New',
            'assigned_to' => $agent ? $agent->id : null,
            'ai_score' => $analysis['score'],
            'ai_qualification' => $analysis['qualification'],
            'ai_priority' => $analysis['priority'],
            'ai_recommended_followup' => $analysis['recommended_followup'],
            'ai_summary' => $analysis['summary'],
            'ai_intent' => $analysis['buying_intent'],
            'ai_urgency' => $analysis['urgency'],
            'ai_budget_estimate' => $analysis['budget_estimate'],
            'ai_recommended_department' => $analysis['recommended_department'],
            'ai_sales_probability' => $analysis['sales_probability'],
            'ai_recommended_service' => $analysis['recommended_service'],
        ]);

        $inquiry->update(['lead_id' => $lead->id]);

        Activity::create([
            'lead_id' => $lead->id,
            'type' => 'Email',
            'description' => "Autonomously processed email from {$request->sender_name}. Lead scored at {$lead->ai_score}/100."
        ]);

        // Generate AI draft reply email
        $customerData = [
            'full_name' => $request->sender_name,
            'email' => $request->sender_email
        ];
        $followupTexts = $this->aiService->generateFollowUp($customerData, $analysis['summary']);

        return response()->json([
            'extracted_details' => [
                'name' => $request->sender_name,
                'email' => $request->sender_email,
                'intent' => $analysis['buying_intent'],
                'budget' => $analysis['budget_estimate'],
                'urgency' => $analysis['urgency'],
                'qualification' => $analysis['qualification'],
            ],
            'draft_reply' => $followupTexts['email'],
            'lead' => $lead
        ]);
    }
}
