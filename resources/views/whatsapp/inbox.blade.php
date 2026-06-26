@extends('layouts.app')

@section('header_title', 'WhatsApp Shared Inbox')

@section('content')
<div style="display:flex; height:calc(100vh - 120px); background:#fff; border-radius:12px; border:1px solid #e2e8f0; overflow:hidden;">
    
    {{-- Left Sidebar: Conversation List --}}
    <div style="width:350px; border-right:1px solid #e2e8f0; background:#f8fafc; display:flex; flex-direction:column;">
        <div style="padding:16px; border-bottom:1px solid #e2e8f0; background:#fff; display:flex; justify-content:space-between; align-items:center;">
            <h3 style="margin:0; font-size:16px; font-weight:700; color:#1e293b;">Chats <span style="background:#dcf8c6; color:#059669; padding:2px 6px; border-radius:12px; font-size:11px; margin-left:6px;">{{ $conversations->count() }}</span></h3>
            <button onclick="document.getElementById('newChatModal').style.display='flex'" class="btn btn-sm btn-primary" style="padding:4px 8px; border-radius:6px; font-size:11px;">
                <i class="fa-solid fa-plus"></i> New Chat
            </button>
        </div>

        {{-- New Chat Modal --}}
        <div id="newChatModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:100; align-items:center; justify-content:center;">
            <div style="background:#fff; width:400px; border-radius:12px; padding:24px; box-shadow:0 10px 25px rgba(0,0,0,0.2);">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                    <h3 style="margin:0; font-size:16px; font-weight:700;">Start New WhatsApp Chat</h3>
                    <button type="button" onclick="document.getElementById('newChatModal').style.display='none'" style="background:none; border:none; cursor:pointer; font-size:16px; color:#94a3b8;"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <form action="{{ route('whatsapp.store') }}" method="POST">
                    @csrf
                    <div style="margin-bottom:12px;">
                        <label style="display:block; font-size:12px; font-weight:600; margin-bottom:4px; color:#475569;">Select Existing Lead (Optional)</label>
                        <select name="lead_id" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:8px; font-size:13px; outline:none;">
                            <option value="">-- Or enter phone number below --</option>
                            @foreach($leads as $lead)
                                <option value="{{ $lead->id }}">{{ $lead->full_name }} ({{ $lead->company_name ?: 'No Company' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="margin-bottom:12px;">
                        <label style="display:block; font-size:12px; font-weight:600; margin-bottom:4px; color:#475569;">Or Enter Phone Number</label>
                        <input type="text" name="phone_number" placeholder="+91 9876543210" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:8px; font-size:13px; outline:none;">
                    </div>
                    <div style="margin-bottom:16px;">
                        <label style="display:block; font-size:12px; font-weight:600; margin-bottom:4px; color:#475569;">Initial Message</label>
                        <textarea name="message" rows="3" placeholder="Hello, how can we help you?" required style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:8px; font-size:13px; outline:none; resize:none;"></textarea>
                        <p style="font-size:10px; color:#94a3b8; margin-top:4px;">Note: Outside of the 24-hour window, Meta requires sending an approved Template message.</p>
                    </div>
                    <div style="display:flex; justify-content:flex-end; gap:8px;">
                        <button type="button" onclick="document.getElementById('newChatModal').style.display='none'" class="btn btn-light">Cancel</button>
                        <button type="submit" class="btn btn-primary">Start Chat</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div style="flex:1; overflow-y:auto; padding:10px;">
            @forelse($conversations as $conv)
                <a href="{{ route('whatsapp.show', $conv->id) }}" style="display:block; text-decoration:none; color:inherit; padding:12px; margin-bottom:8px; border-radius:8px; background:{{ (isset($conversation) && $conversation->id == $conv->id) ? '#eef2ff' : '#fff' }}; border:1px solid {{ (isset($conversation) && $conversation->id == $conv->id) ? '#c7d2fe' : '#e2e8f0' }}; transition:all 0.2s;">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:6px;">
                        <strong style="font-size:14px; color:#1e293b;">
                            {{ $conv->lead ? $conv->lead->full_name : $conv->phone_number }}
                        </strong>
                        <span style="font-size:11px; color:#94a3b8;">
                            {{ $conv->last_message_at ? \Carbon\Carbon::parse($conv->last_message_at)->diffForHumans(null, true, true) : 'New' }}
                        </span>
                    </div>
                    <div style="font-size:12px; color:#64748b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        @if($conv->messages->count() > 0)
                            @if($conv->messages->first()->sender_type == 'ai')
                                <i class="fa-solid fa-robot" style="color:#6366f1; margin-right:4px;"></i>
                            @elseif($conv->messages->first()->sender_type == 'user')
                                <i class="fa-solid fa-check-double" style="color:#059669; margin-right:4px;"></i>
                            @endif
                            {{ $conv->messages->first()->message_body }}
                        @else
                            No messages yet
                        @endif
                    </div>
                </a>
            @empty
                <div style="padding:20px; text-align:center; color:#94a3b8; font-size:13px;">
                    No WhatsApp conversations found.
                </div>
            @endforelse
        </div>
    </div>

    {{-- Right Area: Chat Window --}}
    <div style="flex:1; display:flex; flex-direction:column; background:#f1f5f9;">
        @if(isset($conversation))
            {{-- Chat Header --}}
            <div style="padding:16px 24px; background:#fff; border-bottom:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <h2 style="margin:0; font-size:18px; font-weight:700; color:#1e293b;">
                        {{ $conversation->lead ? $conversation->lead->full_name : $conversation->phone_number }}
                    </h2>
                    <span style="font-size:12px; color:#64748b;">
                        {{ $conversation->phone_number }} &bull; Status: {{ ucfirst($conversation->status) }}
                    </span>
                </div>
                <div>
                    @if($conversation->lead)
                        <a href="{{ route('leads.show', $conversation->lead->id) }}" class="btn btn-light btn-sm" style="background:#f8fafc; border:1px solid #e2e8f0; padding:6px 12px; border-radius:6px; font-size:12px; color:#475569; text-decoration:none;">
                            View CRM Lead
                        </a>
                    @endif
                </div>
            </div>

            {{-- Chat Messages --}}
            <div style="flex:1; padding:24px; overflow-y:auto; display:flex; flex-direction:column; gap:16px;">
                @foreach($conversation->messages as $msg)
                    @php
                        $isIncoming = $msg->sender_type == 'lead';
                        $isAI = $msg->sender_type == 'ai';
                        $align = $isIncoming ? 'flex-start' : 'flex-end';
                        $bg = $isIncoming ? '#fff' : ($isAI ? '#eef2ff' : '#dcf8c6');
                        $color = '#1e293b';
                        $border = $isIncoming ? '1px solid #e2e8f0' : 'none';
                        $borderRadius = $isIncoming ? '12px 12px 12px 0' : '12px 12px 0 12px';
                    @endphp
                    
                    <div style="align-self:{{ $align }}; max-width:70%; display:flex; flex-direction:column;">
                        @if($isAI)
                            <span style="font-size:10px; color:#6366f1; margin-bottom:4px; font-weight:600; text-align:right;">
                                <i class="fa-solid fa-robot"></i> AI Assistant
                            </span>
                        @endif
                        <div style="background:{{ $bg }}; color:{{ $color }}; padding:12px 16px; border-radius:{{ $borderRadius }}; border:{{ $border }}; box-shadow:0 1px 2px rgba(0,0,0,0.05); font-size:14px; line-height:1.5;">
                            {{ $msg->message_body }}
                        </div>
                        <span style="font-size:10px; color:#94a3b8; margin-top:4px; text-align:{{ $isIncoming ? 'left' : 'right' }};">
                            {{ $msg->created_at->format('h:i A') }}
                            @if(!$isIncoming)
                                <i class="fa-solid fa-check{{ $msg->is_read ? '-double' : '' }}" style="color:{{ $msg->is_read ? '#3b82f6' : '#94a3b8' }}; margin-left:4px;"></i>
                            @endif
                        </span>
                    </div>
                @endforeach
            </div>

            {{-- Reply Form --}}
            <div style="padding:16px 24px; background:#fff; border-top:1px solid #e2e8f0;">
                <form action="{{ route('whatsapp.reply', $conversation->id) }}" method="POST" style="display:flex; gap:12px; margin:0;">
                    @csrf
                    <input type="text" name="message" placeholder="Type a message to override AI..." required autocomplete="off" style="flex:1; padding:12px 16px; border:1px solid #cbd5e1; border-radius:24px; font-size:14px; outline:none; transition:border-color 0.2s;" onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#cbd5e1'">
                    <button type="submit" style="background:#6366f1; color:#fff; border:none; border-radius:50%; width:44px; height:44px; display:flex; align-items:center; justify-content:center; cursor:pointer; transition:background 0.2s;" onmouseover="this.style.background='#4f46e5'" onmouseout="this.style.background='#6366f1'">
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        @else
            <div style="flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center; color:#94a3b8;">
                <div style="font-size:64px; color:#e2e8f0; margin-bottom:16px;">
                    <i class="fa-brands fa-whatsapp"></i>
                </div>
                <h3 style="margin:0 0 8px 0; font-size:18px; color:#64748b; font-weight:600;">Select a conversation to start messaging</h3>
                <p style="font-size:14px; margin-top:0; margin-bottom:24px;">AI automatically handles new inquiries until you intervene.</p>
                <button type="button" onclick="document.getElementById('newChatModal').style.display='flex'" class="btn btn-primary" style="padding:10px 20px; border-radius:8px; font-weight:600;">
                    <i class="fa-solid fa-plus" style="margin-right:6px;"></i> Start New Chat
                </button>
            </div>
        @endif
    </div>

</div>

<script>
// Auto-scroll chat to bottom
document.addEventListener('DOMContentLoaded', function() {
    const chatBox = document.querySelector('.flex-1.overflow-y-auto');
    if(chatBox) {
        chatBox.scrollTop = chatBox.scrollHeight;
    }
});
</script>
@endsection
