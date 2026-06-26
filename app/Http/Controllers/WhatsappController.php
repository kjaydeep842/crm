<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WhatsappConversation;
use App\Models\WhatsappMessage;
use Illuminate\Support\Facades\Http;

class WhatsappController extends Controller
{
    public function inbox()
    {
        $conversations = WhatsappConversation::with(['lead', 'messages' => function($q) {
            $q->latest()->limit(1);
        }])->orderByDesc('last_message_at')->get();

        $conversation = null;
        $leads = \App\Models\Lead::orderBy('full_name')->get();

        return view('whatsapp.inbox', compact('conversations', 'conversation', 'leads'));
    }

    public function show(WhatsappConversation $conversation)
    {
        $conversation->load(['lead', 'messages' => function($q) {
            $q->oldest();
        }]);
        
        // Mark as read
        $conversation->messages()->where('is_read', false)->where('sender_type', 'lead')->update(['is_read' => true]);
        
        $conversations = WhatsappConversation::with(['lead', 'messages' => function($q) {
            $q->latest()->limit(1);
        }])->orderByDesc('last_message_at')->get();

        $leads = \App\Models\Lead::orderBy('full_name')->get();

        return view('whatsapp.inbox', compact('conversations', 'conversation', 'leads'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'phone_number' => 'nullable|string',
            'lead_id' => 'nullable|exists:leads,id',
            'message' => 'required|string'
        ]);

        if (!$request->phone_number && !$request->lead_id) {
            return back()->with('error', 'Please provide a phone number or select a lead.');
        }

        $lead = null;
        $phone = null;

        if ($request->lead_id) {
            $lead = \App\Models\Lead::find($request->lead_id);
            $phone = preg_replace('/[^0-9]/', '', $lead->mobile);
        } else {
            $phone = preg_replace('/[^0-9]/', '', $request->phone_number);
            $lead = \App\Models\Lead::where('mobile', 'like', "%{$phone}%")->first();
        }

        if (!$phone) {
            return back()->with('error', 'Valid phone number not found.');
        }

        if (strlen($phone) == 10) {
            $phone = '91' . $phone; // Default to India if 10 digits
        }

        // Create or get conversation
        $conversation = WhatsappConversation::firstOrCreate(
            ['phone_number' => $phone],
            ['lead_id' => $lead ? $lead->id : null, 'status' => 'open']
        );

        $messageBody = $request->message;

        // Save to DB
        $message = WhatsappMessage::create([
            'whatsapp_conversation_id' => $conversation->id,
            'sender_type' => 'user',
            'user_id' => auth()->id(),
            'message_body' => $messageBody,
            'status' => 'sent'
        ]);

        $conversation->update(['last_message_at' => now(), 'status' => 'open']);

        // Send via WhatsApp API
        $token = config('services.whatsapp.token') ?: env('WHATSAPP_ACCESS_TOKEN');
        $phoneId = config('services.whatsapp.phone_id') ?: env('WHATSAPP_PHONE_NUMBER_ID');

        if ($token && $phoneId) {
            Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ])->post("https://graph.facebook.com/v17.0/{$phoneId}/messages", [
                'messaging_product' => 'whatsapp',
                'to' => $phone,
                'type' => 'text',
                'text' => ['body' => $messageBody]
            ]);
        }

        return redirect()->route('whatsapp.show', $conversation->id)->with('success', 'Conversation started successfully!');
    }

    public function reply(Request $request, WhatsappConversation $conversation)
    {
        $request->validate(['message' => 'required|string']);

        $messageBody = $request->message;

        // Save to DB
        $message = WhatsappMessage::create([
            'whatsapp_conversation_id' => $conversation->id,
            'sender_type' => 'user',
            'user_id' => auth()->id(),
            'message_body' => $messageBody,
            'status' => 'sent'
        ]);

        $conversation->update(['last_message_at' => now(), 'status' => 'open']);

        // Send via WhatsApp API
        $token = config('services.whatsapp.token');
        $phoneId = config('services.whatsapp.phone_id');

        if ($token && $phoneId) {
            Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ])->post("https://graph.facebook.com/v17.0/{$phoneId}/messages", [
                'messaging_product' => 'whatsapp',
                'to' => $conversation->phone_number,
                'type' => 'text',
                'text' => ['body' => $messageBody]
            ]);
        }

        return back()->with('success', 'Message sent!');
    }
}
