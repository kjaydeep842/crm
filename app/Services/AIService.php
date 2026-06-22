<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    protected $openaiKey;
    protected $geminiKey;

    public function __construct()
    {
        $this->openaiKey = env('OPENAI_API_KEY');
        $this->geminiKey = env('GEMINI_API_KEY');
    }

    /**
     * General function to call OpenAI/Gemini or return mock data.
     */
    protected function callAI(string $systemPrompt, string $userPrompt, array $mockFallback)
    {
        // 1. Try OpenAI if key is present
        if (!empty($this->openaiKey)) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->openaiKey,
                    'Content-Type' => 'application/json',
                ])->timeout(10)->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt . " You MUST return ONLY a valid JSON object. Do not include markdown code block formatting (e.g. ```json)."],
                        ['role' => 'user', 'content' => $userPrompt]
                    ],
                    'response_format' => ['type' => 'json_object'],
                    'temperature' => 0.3
                ]);

                if ($response->successful()) {
                    $data = json_decode($response->body(), true);
                    if ($data && isset($data['choices'][0]['message']['content'])) {
                        $content = trim($data['choices'][0]['message']['content']);
                        // Strip markdown formatting if any
                        $content = preg_replace('/^```json\s*/i', '', $content);
                        $content = preg_replace('/```$/', '', $content);
                        $decoded = json_decode($content, true);
                        if ($decoded) return $decoded;
                    }
                }
            } catch (\Exception $e) {
                Log::error('OpenAI API Error: ' . $e->getMessage());
            }
        }

        // 2. Try Gemini if key is present
        if (!empty($this->geminiKey)) {
            try {
                $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $this->geminiKey;
                
                $instruction = $systemPrompt . " Output MUST be raw JSON only without markdown formatting.";
                
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])->timeout(10)->post($url, [
                    'contents' => [
                        ['parts' => [['text' => $userPrompt]]]
                    ],
                    'systemInstruction' => [
                        'parts' => [['text' => $instruction]]
                    ],
                    'generationConfig' => [
                        'responseMimeType' => 'application/json',
                        'temperature' => 0.2
                    ]
                ]);

                if ($response->successful()) {
                    $data = json_decode($response->body(), true);
                    if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                        $content = trim($data['candidates'][0]['content']['parts'][0]['text']);
                        $decoded = json_decode($content, true);
                        if ($decoded) return $decoded;
                    }
                }
            } catch (\Exception $e) {
                Log::error('Gemini API Error: ' . $e->getMessage());
            }
        }

        // 3. Fallback to mock data
        return $mockFallback;
    }

    /**
     * Analyze incoming inquiry to create a qualified lead.
     */
    public function analyzeLead(string $inquiryText): array
    {
        $systemPrompt = "You are an expert sales consultant. Analyze the customer inquiry and extract key details. Return a JSON object with: summary (string), score (int 0-100), qualification (string: 'Hot Lead' or 'Warm Lead' or 'Cold Lead'), priority (string: 'High' or 'Medium' or 'Low'), buying_intent (string), budget_estimate (string), urgency (string: 'High' or 'Medium' or 'Low'), recommended_department (string), recommended_service (string), sales_probability (int 0-100), next_action (string), recommended_followup (string).";
        
        $userPrompt = "Customer Inquiry:\n\"" . $inquiryText . "\"";

        // Dynamic mock data based on input keywords
        $isSchool = stripos($inquiryText, 'school') !== false || stripos($inquiryText, 'erp') !== false;
        $isEcommerce = stripos($inquiryText, 'ecommerce') !== false || stripos($inquiryText, 'shop') !== false || stripos($inquiryText, 'website') !== false;
        
        if ($isSchool) {
            $mockFallback = [
                'summary' => 'Customer is seeking a custom ERP system for school management, covering student records, exams, fees, and grading.',
                'score' => 88,
                'qualification' => 'Hot Lead',
                'priority' => 'High',
                'buying_intent' => 'School ERP Software',
                'budget_estimate' => '₹1,50,000 - ₹3,00,000',
                'urgency' => 'High',
                'recommended_department' => 'Enterprise Solutions',
                'recommended_service' => 'ERP Development & Customization',
                'sales_probability' => 75,
                'next_action' => 'Schedule a live demo showcasing the School Management features.',
                'recommended_followup' => 'Hi, thanks for reaching out! We can build a custom School ERP to automate fees, grades, and communications. Let\'s schedule a brief 10-minute demo this week. What time works for you?'
            ];
        } elseif ($isEcommerce) {
            $mockFallback = [
                'summary' => 'Customer wants to launch an online shop/e-commerce platform with payment integration and inventory tracking.',
                'score' => 75,
                'qualification' => 'Warm Lead',
                'priority' => 'Medium',
                'buying_intent' => 'E-Commerce Website',
                'budget_estimate' => '₹80,000 - ₹1,50,000',
                'urgency' => 'Medium',
                'recommended_department' => 'Web Development',
                'recommended_service' => 'E-Commerce Platform Deployment',
                'sales_probability' => 60,
                'next_action' => 'Send portofolio of previous e-commerce websites and schedule a call.',
                'recommended_followup' => 'Hello! We can build a high-performance e-commerce store with secure payment gateways and inventory dashboards. I\'d love to share some of our recent work. When are you free for a call?'
            ];
        } else {
            $mockFallback = [
                'summary' => 'Customer inquired about software development services and custom software upgrades.',
                'score' => 50,
                'qualification' => 'Warm Lead',
                'priority' => 'Medium',
                'buying_intent' => 'Custom Software Solution',
                'budget_estimate' => '₹50,000 - ₹1,00,000',
                'urgency' => 'Medium',
                'recommended_department' => 'Sales',
                'recommended_service' => 'Custom IT Consulting',
                'sales_probability' => 45,
                'next_action' => 'Call the customer to understand their specific development requirements.',
                'recommended_followup' => 'Hi! Thank you for contacting us. I would love to connect and discuss your software requirements. Are you available for a quick phone call today?'
            ];
        }

        return $this->callAI($systemPrompt, $userPrompt, $mockFallback);
    }

    /**
     * Summarize meeting transcripts.
     */
    public function summarizeMeeting(string $transcript): array
    {
        $systemPrompt = "You are an AI meeting assistant. Analyze the meeting transcript. Return a JSON object with: summary (string), key_decisions (array of strings), action_items (array of strings), risks (array of strings), followup_tasks (array of strings), next_meeting_date (string).";
        
        $userPrompt = "Transcript:\n" . $transcript;

        $mockFallback = [
            'summary' => 'Discussed project scope, timeline, and budget. The client approved the modular structure but requested an additional payment gateway integrations, which will slightly adjust the budget. We agreed to deliver the prototype in 3 weeks.',
            'key_decisions' => [
                'Client approved the initial UI design mockup.',
                'Agreed to include Stripe and Razorpay integrations.',
                'Project timeline locked to 8 weeks in total.'
            ],
            'action_items' => [
                'John to draft and send the revised Quotation including additional gateway costs.',
                'Design team to finalize high-fidelity wireframes by Wednesday.',
                'Dev team to initialize repository and draft Database schema.'
            ],
            'risks' => [
                'API credentials for Stripe may take time to verify from client side.',
                'Strict deadline due to client launch schedule.'
            ],
            'followup_tasks' => [
                'Send updated proposal PDF by email.',
                'Call client on Thursday for feedback on wireframes.'
            ],
            'next_meeting_date' => date('Y-m-d', strtotime('+7 days'))
        ];

        return $this->callAI($systemPrompt, $userPrompt, $mockFallback);
    }

    /**
     * Generate professional follow-up text.
     */
    public function generateFollowUp(array $customer, string $summary): array
    {
        $systemPrompt = "Generate a professional follow-up email and WhatsApp message based on the customer interaction summary. Return a JSON object with: email (string - HTML formatted or plain text), whatsapp (string - text message with emojis). Tone: Professional and persuasive.";
        
        $userPrompt = "Customer Details:\n" . json_encode($customer) . "\n\nInteraction Summary:\n" . $summary;

        $mockFallback = [
            'email' => "Subject: Next Steps - Our Discussion regarding " . ($customer['company_name'] ?? 'your project') . "\n\nDear " . $customer['full_name'] . ",\n\nThank you for taking the time to speak with us. Based on our discussion, we understand you are looking for a software solution to streamline your operations.\n\nHere is a summary of what we discussed:\n" . $summary . "\n\nWe are preparing the detailed proposal and will share it shortly. Please let us know if you have any questions in the meantime.\n\nBest regards,\nSales Team",
            'whatsapp' => "Hi " . $customer['full_name'] . "! 👋 Great speaking with you today. Just a quick summary of our discussion:\n\n" . substr($summary, 0, 120) . "...\n\nI will send over the detailed proposal shortly. Let me know if you need anything else! 😊"
        ];

        return $this->callAI($systemPrompt, $userPrompt, $mockFallback);
    }

    /**
     * Generate software proposal content.
     */
    public function generateProposal(string $company, string $requirement, string $budget): array
    {
        $systemPrompt = "Generate a software proposal. Return a JSON object with: executive_summary (string), scope (array of strings), features (array of strings), timeline (string), pricing (array of objects with 'item' and 'cost'), terms (array of strings).";
        
        $userPrompt = "Company: " . $company . "\nRequirement: " . $requirement . "\nBudget: " . $budget;

        $mockFallback = [
            'executive_summary' => "This proposal outlines our plan to develop a customized software solution tailored for " . $company . ". Our solution will address the core requirements: " . $requirement . ", ensuring a high-performance, secure, and user-friendly experience that accelerates growth and improves operational productivity.",
            'scope' => [
                'Requirements gathering & systems analysis',
                'UI/UX dashboard and interface design',
                'Backend architecture & API integration',
                'Quality assurance and user acceptance testing',
                'Deployment and cloud hosting setup'
            ],
            'features' => [
                'Secure Authentication & Role-Based Access Control',
                'Interactive Dashboards & Custom Reporting Modules',
                'Automated Workflows & Notifications (Email/SMS)',
                'Responsive Design optimized for Mobile & Desktop',
                'Advanced Data Security & Daily Backups'
            ],
            'timeline' => "6-8 Weeks from contract signing.",
            'pricing' => [
                ['item' => 'Phase 1: Discovery & UI Design', 'cost' => '₹40,000'],
                ['item' => 'Phase 2: Core Development & Integrations', 'cost' => '₹80,000'],
                ['item' => 'Phase 3: QA, Testing & Deployment', 'cost' => '₹30,000']
            ],
            'terms' => [
                '50% Advance payment at project commencement.',
                '30% Upon milestone approval (Phase 2 completion).',
                '20% Upon final delivery & hosting hand-over.',
                'Includes 3 months of complimentary post-launch support.'
            ]
        ];

        return $this->callAI($systemPrompt, $userPrompt, $mockFallback);
    }

    /**
     * Generates daily priority tasks.
     */
    public function generateDailyTasks(array $leads, array $meetings): array
    {
        $systemPrompt = "Generate 3-5 priority tasks for today based on current active leads and scheduled meetings. Return a JSON array of tasks where each task is: { 'title': string, 'type': string (e.g. 'Call Customer', 'Send Proposal', 'Follow-up', 'Demo Meeting', 'Payment Reminder'), 'priority': string ('High', 'Medium', 'Low'), 'lead_id': integer|null, 'due_date': string (YYYY-MM-DD), 'notes': string }.";
        
        $userPrompt = "Leads:\n" . json_encode($leads) . "\n\nMeetings:\n" . json_encode($meetings);

        $mockFallback = [
            [
                'title' => 'Follow up with ' . ($leads[0]['full_name'] ?? 'Hot Lead'),
                'type' => 'Call Customer',
                'priority' => 'High',
                'lead_id' => $leads[0]['id'] ?? null,
                'due_date' => date('Y-m-d'),
                'notes' => 'Discuss their feedback on the ERP demo and review their decision timeline.'
            ],
            [
                'title' => 'Prepare Quotation/Proposal Draft',
                'type' => 'Send Proposal',
                'priority' => 'High',
                'lead_id' => $leads[1]['id'] ?? null,
                'due_date' => date('Y-m-d'),
                'notes' => 'Draft invoice/proposal based on requirements discuss in yesterday\'s meeting.'
            ],
            [
                'title' => 'Confirm attendance for today\'s demo',
                'type' => 'Demo Meeting',
                'priority' => 'Medium',
                'lead_id' => $leads[0]['id'] ?? null,
                'due_date' => date('Y-m-d'),
                'notes' => 'Send meeting link via WhatsApp to verify scheduled slot.'
            ]
        ];

        return $this->callAI($systemPrompt, $userPrompt, $mockFallback);
    }

    /**
     * Generate weekly performance report.
     */
    public function generateWeeklyPerformanceReport(array $employeeStats): array
    {
        $systemPrompt = "You are an AI sales performance consultant. Analyze employee performance stats and output a JSON object: { 'report_text': string (markdown), 'top_performers': array of strings, 'suggestions': array of strings }.";
        
        $userPrompt = "Stats:\n" . json_encode($employeeStats);

        $mockFallback = [
            'report_text' => "### Weekly Performance Summary\n\nAll team members showed strong engagement this week. Lead conversions increased by **12%** compared to the previous week. Meeting completions reached an all-time high of **88%**.\n\n#### Key Findings:\n- Total Leads Handled: **142**\n- Deals Won: **18**\n- Revenue Booked: **₹8,40,000**\n- Top Lead Sources: Website Form and WhatsApp Automation.",
            'top_performers' => [
                'John Doe (5 Deals Won, Conversion Ratio: 25%)',
                'Sarah Smith (4 Deals Won, Conversion Ratio: 22%)'
            ],
            'suggestions' => [
                'Improve follow-up response times on WhatsApp, especially for inquiries arriving after 6 PM.',
                'Provide additional training for negotiating with corporate accounts where buying intent is high but conversion duration is long.',
                'Encourage sales team to log meeting notes immediately to automate next-action reminders.'
            ]
        ];

        return $this->callAI($systemPrompt, $userPrompt, $mockFallback);
    }
}
