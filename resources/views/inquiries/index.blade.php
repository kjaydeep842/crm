@extends('layouts.app')

@section('header_title', 'Inquiry Capture Inbox')

@section('content')
<div x-data="{ openLogModal: false }">

    <!-- Header Panel -->
    <div class="card p-6 mb-8 flex flex-col md:flex-row items-center justify-between gap-4">
        <div>
            <h2 style="font-size:14px; font-weight:700; color:#1e293b; margin-bottom:4px;">Omnichannel Lead Ingestion</h2>
            <p style="font-size:11px; color:#64748b;">Capturing and parsing customer requirements from Website, WhatsApp, Email, Facebook, and Instagram.</p>
        </div>

        <button @click="openLogModal = true" class="btn btn-primary btn-md">
            <i class="fa-solid fa-plus"></i>
            <span>Log Manual Inquiry</span>
        </button>
    </div>

    <!-- Inbox List -->
    <div style="display:flex; flex-direction:column; gap:20px;">
        @forelse($inquiries as $inq)
            <div class="card p-6 relative transition-all duration-300 hover:shadow-md" 
                 style="position:relative; @if($inq->status === 'Pending') border-left:4px solid #6366f1 !important; @endif">
                
                <!-- Corner Source & Status badges -->
                <div style="position:absolute; top:24px; right:24px; display:flex; align-items:center; gap:8px;">
                    <span class="badge" style="background:#f1f5f9; border-color:#e2e8f0; color:#475569;">
                        @if($inq->source === 'WhatsApp')
                            <i class="fa-brands fa-whatsapp" style="color:#16a34a;"></i>
                        @elseif($inq->source === 'Email')
                            <i class="fa-regular fa-envelope" style="color:#6366f1;"></i>
                        @elseif($inq->source === 'Website')
                            <i class="fa-solid fa-globe" style="color:#0891b2;"></i>
                        @elseif($inq->source === 'Facebook')
                            <i class="fa-brands fa-facebook" style="color:#1d4ed8;"></i>
                        @elseif($inq->source === 'Instagram')
                            <i class="fa-brands fa-instagram" style="color:#db2777;"></i>
                        @else
                            <i class="fa-solid fa-user-pen" style="color:#94a3b8;"></i>
                        @endif
                        <span>{{ $inq->source }}</span>
                    </span>

                    <span class="badge @if($inq->status === 'Pending') b-new @else b-qualified @endif">
                        {{ $inq->status }}
                    </span>
                </div>

                <!-- Customer Title -->
                <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px;">
                    <div style="width:40px; height:40px; border-radius:10px; background:#eef2ff; color:#4f46e5; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:14px; border:1px solid rgba(99,102,241,0.12);">
                        {{ substr($inq->customer_name, 0, 1) }}
                    </div>
                    <div>
                        <h3 style="font-size:14px; font-weight:700; color:#1e293b;">{{ $inq->customer_name }}</h3>
                        <div style="display:flex; align-items:center; gap:16px; font-size:11px; color:#64748b; margin-top:3px;">
                            <span><i class="fa-regular fa-address-book" style="margin-right:4px; color:#94a3b8;"></i>{{ $inq->contact }}</span>
                            <span><i class="fa-regular fa-calendar-days" style="margin-right:4px; color:#94a3b8;"></i>{{ $inq->date->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Inquiry Message content -->
                <div style="margin-bottom:20px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:16px; font-size:12px; color:#334155; line-height:1.6; whitespace:pre-line;">
                    "{{ $inq->message }}"
                </div>

                <!-- AI Analysis Panel -->
                <div style="background:#fafbff; border:1px solid #f1f5f9; border-radius:12px; padding:18px; margin-bottom:20px; display:grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap:16px; font-size:12px; position:relative;">
                    <!-- Intent -->
                    <div>
                        <span style="display:block; font-size:9px; font-weight:700; text-transform:uppercase; color:#94a3b8; margin-bottom:4px;">Intent Category</span>
                        <span style="font-weight:700; color:#1e293b;">{{ $inq->ai_intent ?: 'Unparsed' }}</span>
                    </div>

                    <!-- Urgency -->
                    <div>
                        <span style="display:block; font-size:9px; font-weight:700; text-transform:uppercase; color:#94a3b8; margin-bottom:4px;">Urgency Level</span>
                        <span class="badge @if($inq->ai_urgency === 'High') b-high @elseif($inq->ai_urgency === 'Medium') b-medium @else b-low @endif">
                            {{ $inq->ai_urgency ?: 'Unparsed' }}
                        </span>
                    </div>

                    <!-- Budget Estimate -->
                    <div>
                        <span style="display:block; font-size:9px; font-weight:700; text-transform:uppercase; color:#94a3b8; margin-bottom:4px;">Est. Deal Value</span>
                        <span style="font-weight:700; color:#0f766e;">{{ $inq->ai_budget_estimate ?: 'Unparsed' }}</span>
                    </div>

                    <!-- Recommended Department -->
                    <div>
                        <span style="display:block; font-size:9px; font-weight:700; text-transform:uppercase; color:#94a3b8; margin-bottom:4px;">Rec. Department</span>
                        <span style="font-weight:700; color:#4f46e5;">{{ $inq->ai_recommended_department ?: 'Unparsed' }}</span>
                    </div>

                    <!-- Trigger AI analysis if empty -->
                    <div style="display:flex; align-items:center; justify-content:flex-end;">
                        @if(empty($inq->ai_intent))
                            <form action="{{ route('inquiries.analyze', $inq->id) }}" method="POST" style="margin:0;">
                                @csrf
                                <button type="submit" class="btn btn-violet btn-sm">
                                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                                    <span>Enrich AI</span>
                                </button>
                            </form>
                        @else
                            <div style="font-size:11px; color:#059669; font-weight:600; display:flex; align-items:center; gap:4px;">
                                <i class="fa-solid fa-circle-check"></i>
                                <span>AI Analyzed</span>
                            </div>
                        @endif
                    </div>

                    <!-- Summary -->
                    @if($inq->ai_summary)
                        <div style="grid-column: 1 / -1; pt:12px; border-top:1px solid #f1f5f9; margin-top:8px;">
                            <span style="display:block; font-size:9px; font-weight:700; text-transform:uppercase; color:#94a3b8; margin-bottom:4px;">AI Brief Summary</span>
                            <p style="color:#475569; line-height:1.5;">{{ $inq->ai_summary }}</p>
                        </div>
                    @endif
                </div>

                <!-- Footer Operations -->
                <div style="display:flex; align-items:center; justify-content:space-between; border-top:1px solid #f1f5f9; pt:16px; padding-top:14px;">
                    <div style="font-size:11px; color:#64748b; display:flex; align-items:center; gap:6px;">
                        <i class="fa-solid fa-user-tag" style="color:#94a3b8;"></i>
                        <span>Routed To: <b>{{ $inq->assignedAgent->name ?? 'None' }}</b></span>
                    </div>

                    <div style="display:flex; align-items:center; gap:12px;">
                        @if($inq->status === 'Pending')
                            <form action="{{ route('inquiries.convert', $inq->id) }}" method="POST" style="margin:0;">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fa-solid fa-check-double"></i>
                                    <span>Approve & Create Lead</span>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('leads.show', $inq->lead_id) }}" class="btn btn-light btn-sm">
                                <span>View Lead Console</span>
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        @endif
                    </div>
                </div>

            </div>
        @empty
            <div class="card p-12 text-center" style="color:#94a3b8;">
                <i class="fa-solid fa-inbox" style="font-size:32px; display:block; margin-bottom:12px; opacity:0.5;"></i>
                No inquiries registered in the inbox.
            </div>
        @endforelse
    </div>

    <!-- Manual Log Inquiry Slide-over Drawer -->
    <div x-show="openLogModal" class="fixed inset-0 z-50" style="overflow:hidden; display:none;">
        <div style="position:absolute; inset:0; overflow:hidden;">
            {{-- Backdrop --}}
            <div x-show="openLogModal"
                 x-transition:enter="ease-in-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in-out duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 style="position:absolute; inset:0; background:rgba(15,23,42,0.4); backdrop-filter:blur(4px);"
                 @click="openLogModal = false"></div>

            <div style="pointer-events:none; position:fixed; inset-y:0; right:0; display:flex; max-width:100%; padding-left:40px;">
                <div x-show="openLogModal"
                     x-transition:enter="transform transition ease-in-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                     x-transition:leave="transform transition ease-in-out duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                     style="pointer-events:auto; width:100%; max-width:480px;">
                    <div class="drawer-panel" style="display:flex; flex-direction:column; height:100%; overflow:hidden;">

                        <div class="drawer-header">
                            <h2><i class="fa-solid fa-plus" style="color:#6366f1;"></i> Log Manual Inquiry</h2>
                            <button @click="openLogModal = false" style="background:none; border:none; color:#64748b; cursor:pointer; font-size:18px; line-height:1; padding:4px;">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                        
                        <div class="drawer-body">
                            <form action="{{ route('inquiries.store') }}" method="POST" style="display:flex; flex-direction:column; gap:14px; margin:0;">
                                @csrf
                                
                                <div>
                                    <label class="form-label">Customer Name *</label>
                                    <input type="text" name="customer_name" required placeholder="Aarav Sharma" class="form-input">
                                </div>

                                <div>
                                    <label class="form-label">Contact Detail (Email or Mobile) *</label>
                                    <input type="text" name="contact" required placeholder="aarav@gmail.com or 9988776655" class="form-input">
                                </div>

                                <div>
                                    <label class="form-label">Channel Source *</label>
                                    <select name="source" required class="form-input" style="background:#fff;">
                                        <option value="Manual">Manual Entry</option>
                                        <option value="Website">Website Form</option>
                                        <option value="WhatsApp">WhatsApp Message</option>
                                        <option value="Email">Email Message</option>
                                        <option value="Facebook">Facebook Messenger</option>
                                        <option value="Instagram">Instagram Direct</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="form-label">Assign To Employee</label>
                                    <select name="assigned_to" class="form-input" style="background:#fff;">
                                        <option value="">Choose Agent</option>
                                        @foreach($agents as $agent)
                                            <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="form-label">Inquiry Message *</label>
                                    <textarea name="message" required rows="4" placeholder="I need a custom customer portal site." class="form-input" style="resize:vertical;"></textarea>
                                </div>

                                <div style="margin-top:12px; display:flex; justify-content:flex-end; gap:8px;">
                                    <button type="button" @click="openLogModal = false" class="btn btn-light">
                                        Cancel
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        Log Inbound
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
