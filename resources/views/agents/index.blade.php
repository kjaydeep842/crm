@extends('layouts.app')

@section('header_title', 'AI Agents Workstation')

@section('content')
<div x-data="{ 
    // WhatsApp State
    waMobile: '9988776655',
    waName: 'Kunal Patel',
    waMessage: 'Hi, I want to build a school management system with fee payments. Our budget is around 3 Lakh. Call me.',
    waChats: [
        { sender: 'customer', text: 'Hello! I had some software requirements.', name: 'Kunal Patel', time: '10:00 AM' }
    ],
    waTyping: false,
    sendWhatsApp() {
        if (!this.waMessage.trim()) return;
        
        // Push user message
        this.waChats.push({
            sender: 'customer',
            text: this.waMessage,
            name: this.waName,
            time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
        });
        
        const msgToSend = this.waMessage;
        this.waMessage = '';
        this.waTyping = true;
        
        fetch('{{ route('agents.simulate-whatsapp') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                mobile: this.waMobile,
                name: this.waName,
                message: msgToSend
            })
        })
        .then(res => res.json())
        .then(data => {
            this.waTyping = false;
            // Push bot reply
            this.waChats.push({
                sender: 'bot',
                text: data.bot_response,
                name: 'Aura AI',
                time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
            });
            alert('AI processed WhatsApp and created Lead ID: #L-' + data.lead.id);
        })
        .catch(err => {
            console.error(err);
            this.waTyping = false;
        });
    },

    // Email State
    emailSenderName: 'Neha Rao',
    emailSenderEmail: 'neha.rao@retailcorp.in',
    emailSubject: 'Inquiry: B2B E-commerce Portal development',
    emailBody: 'Dear Aura sales team,\n\nWe are looking to develop a B2B e-commerce store to manage distributor orders. The system needs to sync inventory. We have allocated a budget of ₹4,0,000.\n\nThanks,\nNeha Rao\nRetailCorp India',
    emailParsing: false,
    emailResult: null,
    parseEmail() {
        this.emailParsing = true;
        this.emailResult = null;
        
        fetch('{{ route('agents.simulate-email') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                sender_name: this.emailSenderName,
                sender_email: this.emailSenderEmail,
                subject: this.emailSubject,
                body: this.emailBody
            })
        })
        .then(res => res.json())
        .then(data => {
            this.emailParsing = false;
            this.emailResult = data;
        })
        .catch(err => {
            console.error(err);
            this.emailParsing = false;
        });
    }
}">

    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap:24px;">
        
        <!-- WhatsApp AI Agent Simulator -->
        <div>
            <div class="card p-6" style="border-radius:20px;">
                <div style="display:flex; align-items:center; justify-content:between; border-bottom:1px solid #e2e8f0; padding-bottom:12px; margin-bottom:16px;">
                    <h3 style="font-size:14px; font-weight:700; color:#1e293b; display:flex; align-items:center; gap:8px;">
                        <i class="fa-brands fa-whatsapp" style="color:#16a34a; font-size:18px;"></i>
                        <span>WhatsApp AI Agent Simulator</span>
                    </h3>
                    <span style="width:8px; height:8px; border-radius:50%; background:#22c55e;"></span>
                </div>
                
                <!-- Mock Phone Container (Light Theme WhatsApp style!) -->
                <div style="border: 1px solid #cbd5e1; border-radius: 16px; overflow: hidden; background: #efeae2; display: flex; flex-direction: column; height: 480px; max-width: 380px; margin: 0 auto; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                    <!-- WhatsApp Top Header bar -->
                    <div style="background: #075e54; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between; color: #ffffff;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: #128c7e; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 11px;">
                                AI
                            </div>
                            <div>
                                <span style="font-weight: 700; font-size: 12px; display: block;">AURA AI Assistant</span>
                                <span style="font-size: 9px; opacity: 0.85; display: block;">online</span>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 14px; font-size: 14px;">
                            <i class="fa-solid fa-phone"></i>
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                        </div>
                    </div>
                    
                    <!-- Chat History screen -->
                    <div style="flex: 1; padding: 16px; overflow-y: auto; display:flex; flex-direction:column; gap:12px; background-image: url('https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png'); background-blend-mode: overlay; background-color: #efeae2;">
                        <template x-for="chat in waChats">
                            <div style="display: flex; flex-direction: column;" :style="chat.sender === 'customer' ? 'align-items: flex-end;' : 'align-items: flex-start;'">
                                <div style="max-width: 85%; rounded-xl; padding: 8px 12px; font-size: 12px; border-radius: 12px; position: relative; box-shadow: 0 1px 2px rgba(0,0,0,0.15);"
                                     :style="chat.sender === 'customer' ? 'background: #d9fdd3; color: #303030; border-top-right-radius: 0px;' : 'background: #ffffff; color: #303030; border-top-left-radius: 0px;'">
                                    <span style="font-size: 9px; font-weight: 800; color: #075e54; display: block; margin-bottom: 2px;" x-show="chat.sender === 'customer'" x-text="chat.name"></span>
                                    <p style="line-height: 1.5; margin: 0; white-space: pre-line;" x-text="chat.text"></p>
                                    <span style="font-size: 8px; color: #8696a0; display: block; text-align: right; margin-top: 4px;" x-text="chat.time"></span>
                                </div>
                            </div>
                        </template>

                        <div x-show="waTyping" style="display: flex; align-items: center;">
                            <div style="background: #ffffff; border-radius: 12px; border-top-left-radius: 0; padding: 8px 12px; font-size: 12px; color: #667781; box-shadow: 0 1px 2px rgba(0,0,0,0.15);">
                                <i class="fa-solid fa-circle-notch fa-spin style='color:#6366f1; margin-right:6px;'"></i> Aura AI typing...
                            </div>
                        </div>
                    </div>

                    <!-- Input message box bar -->
                    <div style="padding: 10px; background: #f0f2f5; display: flex; align-items: center; gap: 8px; border-top: 1px solid #e1e3e6;">
                        <input type="text" x-model="waMessage" @keydown.enter="sendWhatsApp()" placeholder="Type simulated inquiry message..." style="flex: 1; border: none; background: #ffffff; border-radius: 20px; font-size: 12px; padding: 8px 16px; outline: none; box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);">
                        <button @click="sendWhatsApp()" style="width: 36px; height: 36px; border-radius: 50%; background: #075e54; border: none; color: #ffffff; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: background 0.15s;">
                            <i class="fa-solid fa-paper-plane" style="font-size: 11px;"></i>
                        </button>
                    </div>
                </div>

                <!-- Simulation parameters -->
                <div style="margin-top: 24px; padding: 16px; border-radius: 12px; background: #f8fafc; border: 1px solid #e2e8f0; font-size: 12px;">
                    <span style="font-size: 10px; color: #4f46e5; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 12px;">Simulated Customer Credentials</span>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                        <div>
                            <label style="display:block; font-size:10px; color:#64748b; margin-bottom:4px;">Customer Name</label>
                            <input type="text" x-model="waName" class="form-input" style="background:#fff;">
                        </div>
                        <div>
                            <label style="display:block; font-size:10px; color:#64748b; margin-bottom:4px;">Mobile No</label>
                            <input type="text" x-model="waMobile" class="form-input" style="background:#fff;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Email AI Agent Simulator -->
        <div>
            <div class="card p-6" style="border-radius:20px;">
                <div style="display:flex; align-items:center; justify-content:between; border-bottom:1px solid #e2e8f0; padding-bottom:12px; margin-bottom:16px;">
                    <h3 style="font-size:14px; font-weight:700; color:#1e293b; display:flex; align-items:center; gap:8px;">
                        <i class="fa-regular fa-envelope" style="color:#6366f1; font-size:18px;"></i>
                        <span>Email AI Agent Simulator</span>
                    </h3>
                    <span style="width:8px; height:8px; border-radius:50%; background:#6366f1;"></span>
                </div>

                <div style="display:flex; flex-direction:column; gap:14px; font-size:12px;">
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                        <div>
                            <label class="form-label">Sender Name *</label>
                            <input type="text" x-model="emailSenderName" class="form-input" style="background:#fff;">
                        </div>
                        <div>
                            <label class="form-label">Sender Email *</label>
                            <input type="email" x-model="emailSenderEmail" class="form-input" style="background:#fff;">
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Subject Line *</label>
                        <input type="text" x-model="emailSubject" class="form-input" style="background:#fff;">
                    </div>

                    <div>
                        <label class="form-label">Email Body *</label>
                        <textarea x-model="emailBody" rows="6" class="form-input" style="background:#fff; font-family:monospace; resize:vertical;"></textarea>
                    </div>

                    <div style="display:flex; justify-content:flex-end;">
                        <button @click="parseEmail()" class="btn btn-primary">
                            <span x-show="!emailParsing">Parse Email & Generate Draft Reply</span>
                            <span x-show="emailParsing"><i class="fa-solid fa-circle-notch fa-spin" style="margin-right:6px;"></i>Analyzing Email...</span>
                        </button>
                    </div>
                </div>

                <!-- Email Results -->
                <div x-show="emailResult" x-collapse style="margin-top: 24px; padding-top: 20px; border-top: 1px solid #e2e8f0; display: none;">
                    <h4 style="font-size: 13px; font-weight: 700; color: #1e293b; display: flex; align-items: center; gap: 6px; margin-bottom: 12px;">
                        <i class="fa-solid fa-circle-check" style="color:#059669;"></i> Email Parsed Outcomes
                    </h4>

                    <!-- Parsed details -->
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; background:#f8fafc; border:1px solid #e2e8f0; padding:16px; border-radius:12px; margin-bottom:16px;">
                        <div>
                            <span style="font-size:9px; color:#64748b; uppercase; font-weight:700; display:block;">Intent Category</span>
                            <span style="font-weight:700; color:#1e293b; font-size:12px;" x-text="emailResult?.extracted_details.intent"></span>
                        </div>
                        <div>
                            <span style="font-size:9px; color:#64748b; uppercase; font-weight:700; display:block;">Est. Budget</span>
                            <span style="font-weight:700; color:#0f766e; font-size:12px;" x-text="emailResult?.extracted_details.budget"></span>
                        </div>
                        <div>
                            <span style="font-size:9px; color:#64748b; uppercase; font-weight:700; display:block;">Urgency</span>
                            <span style="font-weight:700; color:#4f46e5; font-size:12px;" x-text="emailResult?.extracted_details.urgency"></span>
                        </div>
                        <div>
                            <span style="font-size:9px; color:#64748b; uppercase; font-weight:700; display:block;">AI Rating</span>
                            <span class="badge b-high" style="margin-top:2px;" x-text="emailResult?.extracted_details.qualification"></span>
                        </div>
                    </div>

                    <!-- AI draft response email -->
                    <div>
                        <span style="font-size:10px; color:#64748b; uppercase; font-weight:700; display:block; margin-bottom:8px;">Automated AI Email Draft Reply</span>
                        <div style="position:relative;">
                            <textarea readonly rows="6" x-text="emailResult?.draft_reply" style="width:100%; border-radius:12px; background:#fafbff; border:1px solid #e2e8f0; padding:12px; font-size:11px; font-family:monospace; color:#334155; resize:none; outline:none;"></textarea>
                            <button @click="navigator.clipboard.writeText(emailResult?.draft_reply); alert('Email reply copied!')" style="position:absolute; top:8px; right:8px; background:#fff; border:1px solid #e2e8f0; padding:6px; border-radius:8px; color:#64748b; cursor:pointer;" title="Copy reply">
                                <i class="fa-regular fa-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
