<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Lead;
use App\Models\WhatsappConversation;
use App\Models\WhatsappMessage;
use Illuminate\Support\Facades\Http;

class WhatsAppWebhookController extends Controller
{
    public function verify(Request $request)
    {
        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        if ($mode === 'subscribe' && $token === config('services.whatsapp.verify_token')) {
            return response($challenge, 200);
        }

        return response('Forbidden', 403);
    }

    public function handle(Request $request)
    {
        $data = $request->all();

        if (isset($data['object']) && $data['object'] == 'whatsapp_business_account') {
            foreach ($data['entry'] as $entry) {
                foreach ($entry['changes'] as $change) {
                    if ($change['value']['messages'] ?? false) {
                        $this->processMessage($change['value']['messages'][0], $change['value']['contacts'][0] ?? null);
                    }
                }
            }
        }

        return response('EVENT_RECEIVED', 200);
    }

    protected function processMessage($message, $contact)
    {
        $from = $message['from']; // Phone number
        $messageBody = $message['text']['body'] ?? '';
        $messageId = $message['id'];

        // Find or create lead
        $lead = Lead::firstOrCreate(
            ['phone' => $from],
            [
                'first_name' => $contact['profile']['name'] ?? 'Unknown',
                'last_name' => 'WhatsApp User',
                'source' => 'WhatsApp',
                'status' => 'New'
            ]
        );

        // Find or create conversation
        $conversation = WhatsappConversation::firstOrCreate(
            ['phone_number' => $from, 'status' => 'open'],
            ['lead_id' => $lead->id]
        );

        $conversation->update(['last_message_at' => now()]);

        // Save incoming message
        WhatsappMessage::firstOrCreate(
            ['message_id' => $messageId],
            [
                'whatsapp_conversation_id' => $conversation->id,
                'sender_type' => 'lead',
                'message_body' => $messageBody,
                'status' => 'delivered'
            ]
        );

        // Process with AI Chatbot
        $this->processAiReply($conversation, $messageBody, $from);
    }

    protected function processAiReply($conversation, $messageBody, $to)
    {
        $org = null;
        if ($conversation->lead && $conversation->lead->assigned_to) {
            $user = \App\Models\User::find($conversation->lead->assigned_to);
            if ($user) $org = $user->organization;
        }
        if (!$org) $org = \App\Models\Organization::first();
        
        if ($org && !$org->hasAiCredits(1)) {
            Log::warning("AI Auto-reply skipped for {$to} due to package limit.");
            // Send default non-AI message
            $this->sendWhatsAppMessage($to, "Thanks for reaching out! A human agent will be with you shortly. (Automated reply: AI features suspended due to package limits)");
            return;
        }
        
        if ($org) $org->useAiCredits(1);

        // Simple AI Logic (Mocked or Basic OpenAI)
        // We will call the AI service to get the reply.
        $aiReply = "Thanks for your message! This is an AI agent. I received: " . $messageBody;
        
        // Actually, let's inject OpenAI via Http Client if we can.
        $apiKey = config('services.openai.key');
        if ($apiKey) {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful sales assistant for DevineSky CRM. Answer questions briefly and try to qualify the lead.'],
                    ['role' => 'user', 'content' => $messageBody]
                ]
            ]);

            if ($response->successful()) {
                $aiReply = $response->json('choices.0.message.content');
            }
        }

        // Send reply via WhatsApp API
        $this->sendWhatsAppMessage($to, $aiReply);

        // Save AI message to DB
        WhatsappMessage::create([
            'whatsapp_conversation_id' => $conversation->id,
            'sender_type' => 'ai',
            'message_body' => $aiReply,
            'status' => 'sent'
        ]);
    }

    protected function sendWhatsAppMessage($to, $body)
    {
        $token = config('services.whatsapp.token');
        $phoneId = config('services.whatsapp.phone_id');

        if (!$token || !$phoneId) return;

        Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        ])->post("https://graph.facebook.com/v17.0/{$phoneId}/messages", [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'text',
            'text' => ['body' => $body]
        ]);
    }
}
