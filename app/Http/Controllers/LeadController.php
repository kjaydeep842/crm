<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Activity;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\NotificationLog;
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

        // Audit Log
        ActivityLog::log('created', "New lead '{$lead->full_name}' created", 'Lead', $lead->id);

        // Notify all org admins
        if ($user->organization_id) {
            $admins = User::where('organization_id', $user->organization_id)->where('role', 'admin')->pluck('id');
            foreach ($admins as $adminId) {
                if ($adminId != $user->id) {
                    NotificationLog::send($adminId, 'lead_created', "New Lead: {$lead->full_name}", "{$user->name} added a new lead from {$lead->company_name}.", route('leads.show', $lead->id));
                }
            }
        }

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
        $org = null;
        if ($lead->assigned_to) {
            $user = User::find($lead->assigned_to);
            if ($user) $org = $user->organization;
        }
        if (!$org) $org = Auth::user() ? Auth::user()->organization : \App\Models\Organization::first();
        
        if ($org && !$org->hasAiCredits(10)) {
            Activity::create([
                'lead_id' => $lead->id,
                'user_id' => Auth::id(),
                'type' => 'AI Insight Error',
                'description' => "AI Analysis failed: Package AI credit limit reached. Please upgrade your plan."
            ]);
            return;
        }
        
        if ($org) $org->useAiCredits(10); // cost 10 credits

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

        $org = Auth::user()->organization;
        if ($org && !$org->hasAiCredits(5)) {
            return response()->json(['error' => 'Package AI credit limit reached. Please upgrade your plan.'], 403);
        }
        if ($org) $org->useAiCredits(5); // cost 5 credits

        $followup = $this->aiService->generateFollowUp($customerDetails, $summary);

        return response()->json($followup);
    }

    public function sendEmail(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);
        $content = $request->input('content', '');

        if (empty($lead->email)) {
            return response()->json(['success' => false, 'message' => 'No email address registered for this lead.']);
        }

        try {
            // Attempt live send if SMTP configuration is set in .env
            if (config('mail.mailers.smtp.host') && config('mail.mailers.smtp.username')) {
                \Illuminate\Support\Facades\Mail::raw($content, function ($message) use ($lead) {
                    $message->to($lead->email)
                            ->subject('Follow-up from DevineSky CRM');
                });
                $status = 'Live email sent successfully to ' . $lead->email;
            } else {
                // Graceful fallback
                $status = 'Email simulated: Saved to activity logs (Configure SMTP mailer in .env to send live emails).';
            }

            // Create activity
            Activity::create([
                'lead_id' => $lead->id,
                'user_id' => Auth::id(),
                'type' => 'Email',
                'description' => "Outgoing Email Follow-up Sent:\n" . substr($content, 0, 150) . "..."
            ]);

            return response()->json(['success' => true, 'message' => $status]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error sending email: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error sending email: ' . $e->getMessage()]);
        }
    }

    public function sendWhatsApp(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);
        $content = $request->input('content', '');

        if (empty($lead->mobile)) {
            return response()->json(['success' => false, 'message' => 'No mobile number registered for this lead.']);
        }

        $formattedPhone = preg_replace('/[^0-9]/', '', $lead->mobile);
        // Ensure country code
        if (strlen($formattedPhone) === 10) {
            $formattedPhone = '91' . $formattedPhone; // default to India code
        }

        try {
            $liveSent = false;
            $status = '';

            // Check Meta Cloud API settings in .env
            $whatsappToken = env('WHATSAPP_API_TOKEN');
            $phoneId = env('WHATSAPP_PHONE_NUMBER_ID');

            if ($whatsappToken && $phoneId) {
                // Meta Cloud API call
                $response = \Illuminate\Support\Facades\Http::withToken($whatsappToken)
                    ->post("https://graph.facebook.com/v17.0/{$phoneId}/messages", [
                        'messaging_product' => 'whatsapp',
                        'to' => $formattedPhone,
                        'type' => 'text',
                        'text' => ['body' => $content]
                    ]);

                if ($response->successful()) {
                    $liveSent = true;
                    $status = 'WhatsApp message sent live via Meta Cloud API!';
                } else {
                    \Illuminate\Support\Facades\Log::warning('Meta API failed: ' . $response->body());
                }
            }

            // Check Twilio WhatsApp settings if Meta is not configured
            if (!$liveSent && env('TWILIO_SID') && env('TWILIO_AUTH_TOKEN') && env('TWILIO_WHATSAPP_FROM')) {
                // Twilio call
                $sid = env('TWILIO_SID');
                $token = env('TWILIO_AUTH_TOKEN');
                $from = env('TWILIO_WHATSAPP_FROM');

                $response = \Illuminate\Support\Facades\Http::asForm()
                    ->withBasicAuth($sid, $token)
                    ->post("https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json", [
                        'From' => 'whatsapp:' . $from,
                        'To' => 'whatsapp:+' . $formattedPhone,
                        'Body' => $content
                    ]);

                if ($response->successful()) {
                    $liveSent = true;
                    $status = 'WhatsApp message sent live via Twilio API!';
                } else {
                    \Illuminate\Support\Facades\Log::warning('Twilio API failed: ' . $response->body());
                }
            }

            // Generate desktop/mobile fallback redirection link
            $whatsappWebUrl = 'https://wa.me/' . $formattedPhone . '?text=' . urlencode($content);

            if (!$liveSent) {
                $status = 'WhatsApp message logged. Click the WhatsApp Web link below to send manually.';
            }

            // Create activity
            Activity::create([
                'lead_id' => $lead->id,
                'user_id' => Auth::id(),
                'type' => 'WhatsApp',
                'description' => "Outgoing WhatsApp Follow-up Sent:\n" . substr($content, 0, 150) . "..."
            ]);

            return response()->json([
                'success' => true,
                'message' => $status,
                'live' => $liveSent,
                'whatsapp_url' => $whatsappWebUrl
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error sending WhatsApp: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error sending WhatsApp: ' . $e->getMessage()]);
        }
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
