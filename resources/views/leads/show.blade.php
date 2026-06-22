@extends('layouts.app')

@section('header_title')
    Lead Console: {{ $lead->full_name }}
@endsection

@section('content')
<div x-data="{ 
    openEditModal: false, 
    showFollowup: false, 
    emailDraft: '', 
    whatsappDraft: '', 
    loadingFollowup: false,
    generateFollowUp() {
        this.loadingFollowup = true;
        this.showFollowup = true;
        fetch('{{ route('leads.followup', $lead->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ transcript: '' })
        })
        .then(res => res.json())
        .then(data => {
            this.emailDraft = data.email;
            this.whatsappDraft = data.whatsapp;
            this.loadingFollowup = false;
        })
        .catch(err => {
            console.error(err);
            this.loadingFollowup = false;
            this.emailDraft = 'Failed to generate email follow-up.';
            this.whatsappDraft = 'Failed to generate WhatsApp follow-up.';
        });
    }
}">

    <!-- Top Action Bar -->
    <div style="display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:16px; margin-bottom:32px;">
        <div style="display:flex; align-items:center; gap:12px;">
            <span class="badge" style="background:#f1f5f9; border-color:#e2e8f0; color:#475569; padding:4px 10px !important;">
                ID: #L-{{ str_pad($lead->id, 4, '0', STR_PAD_LEFT) }}
            </span>
            <span class="badge @if($lead->status == 'Won') b-won @elseif($lead->status == 'Lost') b-lost @elseif($lead->status == 'Qualified') b-qualified @elseif($lead->status == 'Contacted') b-contacted @else b-new @endif" style="padding:4px 10px !important;">
                {{ $lead->status }}
            </span>
        </div>
        
        <div style="display:flex; align-items:center; gap:12px;">
            <!-- Edit details button -->
            <button @click="openEditModal = true" class="btn btn-secondary">
                <i class="fa-solid fa-pen-to-square"></i> Edit Details
            </button>

            <!-- Re-run AI Analysis -->
            <form action="{{ route('leads.trigger-ai', $lead->id) }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="btn btn-light" style="color:#4f46e5;">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                    <span>Re-score AI</span>
                </button>
            </form>

            <!-- Create Document Shortcut -->
            <a href="{{ route('documents.index') }}" class="btn btn-primary">
                <i class="fa-solid fa-file-invoice"></i> Generate Quotation / Proposal
            </a>
        </div>
    </div>

    <!-- Main Content Layout -->
    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap:24px; align-items:start;">
        
        <!-- Left Column: Core Lead Information -->
        <div style="display:flex; flex-direction:column; gap:24px;">
            <!-- Client Info Card -->
            <div class="card p-6">
                <h3 style="font-size:14px; font-weight:700; color:#1e293b; border-bottom:1px solid #f1f5f9; padding-bottom:12px; margin-bottom:16px; display:flex; align-items:center; gap:8px;">
                    <i class="fa-regular fa-id-card" style="color:#6366f1;"></i> Contact Information
                </h3>
                <div style="display:flex; flex-direction:column; gap:16px; font-size:12px;">
                    <div>
                        <span style="display:block; font-size:10px; color:#64748b; uppercase; font-weight:700; text-transform:uppercase;">Contact Person</span>
                        <span style="font-size:14px; font-weight:700; color:#1e293b; margin-top:4px; display:block;">{{ $lead->full_name }}</span>
                    </div>
                    <div>
                        <span style="display:block; font-size:10px; color:#64748b; uppercase; font-weight:700; text-transform:uppercase;">Company Name</span>
                        <span style="color:#334155; margin-top:4px; display:block; font-weight:600;">{{ $lead->company_name ?: 'Individual Customer' }}</span>
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                        <div>
                            <span style="display:block; font-size:10px; color:#64748b; uppercase; font-weight:700; text-transform:uppercase;">Mobile</span>
                            <span style="color:#334155; margin-top:4px; display:block; font-weight:600;">{{ $lead->mobile ?: 'N/A' }}</span>
                        </div>
                        <div>
                            <span style="display:block; font-size:10px; color:#64748b; uppercase; font-weight:700; text-transform:uppercase;">Email</span>
                            <span style="color:#334155; margin-top:4px; display:block; font-weight:600; word-break:break-all;">{{ $lead->email ?: 'N/A' }}</span>
                        </div>
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                        <div>
                            <span style="display:block; font-size:10px; color:#64748b; uppercase; font-weight:700; text-transform:uppercase;">Website</span>
                            <a href="{{ $lead->website ? (str_starts_with($lead->website, 'http') ? $lead->website : 'https://'.$lead->website) : '#' }}" target="_blank" style="color:#4f46e5; margin-top:4px; display:block; text-decoration:none; font-weight:600;">{{ $lead->website ?: 'N/A' }}</a>
                        </div>
                        <div>
                            <span style="display:block; font-size:10px; color:#64748b; uppercase; font-weight:700; text-transform:uppercase;">Industry</span>
                            <span style="color:#334155; margin-top:4px; display:block; font-weight:600;">{{ $lead->industry ?: 'N/A' }}</span>
                        </div>
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                        <div>
                            <span style="display:block; font-size:10px; color:#64748b; uppercase; font-weight:700; text-transform:uppercase;">Lead Source</span>
                            <span style="color:#334155; margin-top:4px; display:block; font-weight:600;">{{ $lead->lead_source }}</span>
                        </div>
                        <div>
                            <span style="display:block; font-size:10px; color:#64748b; uppercase; font-weight:700; text-transform:uppercase;">Client Budget</span>
                            <span style="color:#0f766e; font-weight:700; margin-top:4px; display:block;">₹{{ number_format($lead->budget, 2) }}</span>
                        </div>
                    </div>
                    <div>
                        <span style="display:block; font-size:10px; color:#64748b; uppercase; font-weight:700; text-transform:uppercase;">Assigned Agent</span>
                        <span style="color:#334155; margin-top:4px; display:block; font-weight:600;">{{ $lead->assignedAgent->name ?? 'Unassigned' }}</span>
                    </div>
                </div>
            </div>

            <!-- Lead Requirement Card -->
            <div class="card p-6">
                <h3 style="font-size:14px; font-weight:700; color:#1e293b; border-bottom:1px solid #f1f5f9; padding-bottom:12px; margin-bottom:16px;">
                    Customer Requirements
                </h3>
                <p style="font-size:12px; color:#334155; leading-relaxed; line-height:1.6; whitespace:pre-line;">
                    {{ $lead->requirement }}
                </p>
                @if($lead->notes)
                    <div style="margin-top:16px; padding-top:16px; border-top:1px solid #f1f5f9;">
                        <span style="display:block; font-size:10px; color:#64748b; uppercase; font-weight:700; text-transform:uppercase; margin-bottom:4px;">Internal Notes</span>
                        <p style="font-size:12px; color:#475569; font-style:italic;">{{ $lead->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Middle Column: AI Sales Assistant Dashboard -->
        <div style="grid-column: span 2; display:flex; flex-direction:column; gap:24px;">
            
            <!-- AI Sales Assistant Dashboard Panel -->
            <div class="card p-6" style="border-radius:20px; position:relative; overflow:hidden;">
                <div style="position:absolute; top:12px; right:12px; opacity:0.04; pointer-events:none;">
                    <i class="fa-solid fa-robot" style="font-size:80px; color:#6366f1;"></i>
                </div>
                
                <h3 style="font-size:16px; font-weight:800; color:#1e293b; padding-bottom:12px; margin-bottom:24px; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; gap:8px;">
                    <i class="fa-solid fa-wand-magic-sparkles" style="color:#6366f1;"></i> AI Sales Copilot
                </h3>

                <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:20px; font-size:12px; margin-bottom:24px;">
                    <!-- Gauge Scoring -->
                    <div style="background:#f8fafc; padding:16px; border-radius:12px; border:1px solid #e2e8f0;">
                        <span style="display:block; font-size:10px; color:#64748b; uppercase; font-weight:700; text-transform:uppercase; margin-bottom:12px;">Lead Qualification Grade</span>
                        <div style="display:flex; align-items:center; gap:16px;">
                            <div style="width:52px; height:52px; border-radius:50%; border:3px solid #6366f1; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:16px; color:#4f46e5; background:#eef2ff;">
                                {{ $lead->ai_score }}
                            </div>
                            <div>
                                <span style="font-size:14px; font-weight:800; color:#1e293b; display:block;">
                                    {{ $lead->ai_qualification ?: 'Warm Lead' }}
                                </span>
                                <span style="font-size:10px; color:#64748b; display:block; margin-top:2px;">
                                    Priority: <b>{{ $lead->ai_priority ?: 'Medium' }}</b>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Conversion Probability -->
                    <div style="background:#f8fafc; padding:16px; border-radius:12px; border:1px solid #e2e8f0;">
                        <span style="display:block; font-size:10px; color:#64748b; uppercase; font-weight:700; text-transform:uppercase; margin-bottom:12px;">Conversion Probability</span>
                        <div style="display:flex; align-items:center; gap:12px;">
                            <span style="font-size:24px; font-weight:800; color:#059669;">{{ $lead->ai_sales_probability ?? 50 }}%</span>
                            <div style="flex:1; height:8px; border-radius:999px; background:#e2e8f0; overflow:hidden;">
                                <div style="height:100%; background:linear-gradient(135deg, #6366f1, #059669); width: {{ $lead->ai_sales_probability ?? 50 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="display:flex; flex-direction:column; gap:16px; background:#fafbff; padding:20px; border-radius:12px; border:1px solid #f1f5f9; font-size:12px;">
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                        <div>
                            <span style="display:block; font-size:10px; color:#64748b; uppercase; font-weight:700; text-transform:uppercase;">Extracted Intent</span>
                            <span style="color:#1e293b; margin-top:4px; display:block; font-weight:700;">{{ $lead->ai_intent ?: 'Custom Solutions' }}</span>
                        </div>
                        <div>
                            <span style="display:block; font-size:10px; color:#64748b; uppercase; font-weight:700; text-transform:uppercase;">Deal Urgency</span>
                            <span style="color:#1e293b; margin-top:4px; display:block; font-weight:700; display:flex; align-items:center; gap:6px;">
                                <i class="fa-solid fa-circle" style="font-size:8px; @if($lead->ai_urgency==='High') color:#be123c; @elseif($lead->ai_urgency==='Medium') color:#d97706; @else color:#2563eb; @endif"></i>
                                {{ $lead->ai_urgency ?: 'Medium' }}
                            </span>
                        </div>
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; padding-top:12px; border-top:1px solid #f1f5f9;">
                        <div>
                            <span style="display:block; font-size:10px; color:#64748b; uppercase; font-weight:700; text-transform:uppercase;">Deal Budget Estimate</span>
                            <span style="color:#0f766e; margin-top:4px; display:block; font-weight:700;">{{ $lead->ai_budget_estimate ?: '₹50,000 - ₹1,00,000' }}</span>
                        </div>
                        <div>
                            <span style="display:block; font-size:10px; color:#64748b; uppercase; font-weight:700; text-transform:uppercase;">Recommended Department</span>
                            <span style="color:#1e293b; margin-top:4px; display:block; font-weight:700;">{{ $lead->ai_recommended_department ?: 'Sales' }}</span>
                        </div>
                    </div>
                    <div style="padding-top:12px; border-top:1px solid #f1f5f9;">
                        <span style="display:block; font-size:10px; color:#64748b; uppercase; font-weight:700; text-transform:uppercase;">AI Recommended Service</span>
                        <span style="color:#4f46e5; margin-top:4px; display:block; font-weight:700;">{{ $lead->ai_recommended_service ?: 'IT Consulting' }}</span>
                    </div>
                    <div style="padding-top:12px; border-top:1px solid #f1f5f9;">
                        <span style="display:block; font-size:10px; color:#64748b; uppercase; font-weight:700; text-transform:uppercase;">AI Summary</span>
                        <p style="color:#475569; margin-top:4px; line-height:1.6;">{{ $lead->ai_summary ?: 'Waiting for AI processing summary...' }}</p>
                    </div>
                    @if($lead->ai_recommended_followup)
                        <div style="padding-top:12px; border-top:1px solid #f1f5f9;">
                            <span style="display:block; font-size:10px; color:#64748b; uppercase; font-weight:700; text-transform:uppercase;">Recommended Next Action</span>
                            <p style="color:#475569; margin-top:4px; line-height:1.6;">{{ $lead->ai_recommended_followup }}</p>
                        </div>
                    @endif
                </div>

                <div style="margin-top:20px; display:flex; justify-content:flex-end;">
                    <button @click="generateFollowUp()" class="btn btn-violet">
                        <i class="fa-solid fa-message"></i>
                        <span>Generate Follow-Up Drafts</span>
                    </button>
                </div>
            </div>

            <!-- Follow-up Drawer -->
            <div x-show="showFollowup" x-collapse class="card p-6" style="display: none; border-radius:16px;">
                <div style="display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid #e2e8f0; padding-bottom:12px; margin-bottom:16px;">
                    <h3 style="font-size:14px; font-weight:700; color:#1e293b; display:flex; align-items:center; gap:8px;">
                        <i class="fa-regular fa-comment-dots" style="color:#6366f1;"></i> AI Generated Follow-Up Message
                    </h3>
                    <button @click="showFollowup = false" style="background:none; border:none; color:#64748b; cursor:pointer; font-size:18px;">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                
                <div x-show="loadingFollowup" style="padding:32px 0; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:12px;">
                    <i class="fa-solid fa-circle-notch fa-spin" style="font-size:24px; color:#6366f1;"></i>
                    <span style="font-size:12px; color:#64748b; font-weight:600;">Generating email & WhatsApp message drafts...</span>
                </div>

                <div x-show="!loadingFollowup" style="display:flex; flex-direction:column; gap:20px; font-size:12px;">
                    <!-- Email follow up -->
                    <div>
                        <span style="display:block; font-size:10px; color:#64748b; uppercase; font-weight:700; text-transform:uppercase; margin-bottom:8px;">Persuasive Email draft</span>
                        <div style="position:relative;">
                            <textarea readonly rows="6" x-text="emailDraft" style="width:100%; border-radius:12px; background:#f8fafc; border:1px solid #e2e8f0; padding:12px; font-size:11px; font-family:monospace; color:#334155; resize:none; outline:none;"></textarea>
                            <button @click="navigator.clipboard.writeText(emailDraft); alert('Email draft copied to clipboard!')" style="position:absolute; top:8px; right:8px; background:#fff; border:1px solid #e2e8f0; padding:6px; border-radius:8px; color:#64748b; cursor:pointer;" title="Copy to clipboard">
                                <i class="fa-regular fa-copy"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Whatsapp follow up -->
                    <div>
                        <span style="display:block; font-size:10px; color:#64748b; uppercase; font-weight:700; text-transform:uppercase; margin-bottom:8px;">WhatsApp message draft</span>
                        <div style="position:relative;">
                            <textarea readonly rows="3" x-text="whatsappDraft" style="width:100%; border-radius:12px; background:#f8fafc; border:1px solid #e2e8f0; padding:12px; font-size:11px; font-family:monospace; color:#334155; resize:none; outline:none;"></textarea>
                            <button @click="navigator.clipboard.writeText(whatsappDraft); alert('WhatsApp draft copied to clipboard!')" style="position:absolute; top:8px; right:8px; background:#fff; border:1px solid #e2e8f0; padding:6px; border-radius:8px; color:#64748b; cursor:pointer;" title="Copy to clipboard">
                                <i class="fa-regular fa-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents, Meetings, & Tasks grid -->
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap:24px;">
                
                <!-- Meetings & Tasks List -->
                <div style="display:flex; flex-direction:column; gap:24px;">
                    <!-- Meetings -->
                    <div class="card p-6">
                        <div style="display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid #f1f5f9; padding-bottom:12px; margin-bottom:16px;">
                            <h3 style="font-size:13px; font-weight:700; color:#1e293b; display:flex; align-items:center; gap:6px;">
                                <i class="fa-regular fa-calendar-check" style="color:#7c3aed;"></i> Meetings ({{ $lead->meetings->count() }})
                            </h3>
                            <a href="{{ route('meetings.index') }}" class="btn btn-light btn-sm" style="padding:4px 8px !important; font-size:10px !important;">Add</a>
                        </div>
                        <div style="display:flex; flex-direction:column; gap:12px;">
                            @forelse($lead->meetings as $meeting)
                                <a href="{{ route('meetings.show', $meeting->id) }}" style="display:block; padding:12px; border-radius:10px; background:#f8fafc; border:1px solid #e2e8f0; text-decoration:none; transition:all 0.15s;" onmouseover="this.style.borderColor='#cbd5e1'" onmouseout="this.style.borderColor='#e2e8f0'">
                                    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:6px;">
                                        <span style="font-weight:700; color:#1e293b; font-size:12px; text-overflow:ellipsis; overflow:hidden; white-space:nowrap; max-width:160px;">{{ $meeting->title }}</span>
                                        <span class="badge @if($meeting->status === 'Scheduled') b-proposal @else b-qualified @endif">
                                            {{ $meeting->status }}
                                        </span>
                                    </div>
                                    <span style="font-size:10px; color:#64748b; display:block;"><i class="fa-regular fa-clock" style="margin-right:4px;"></i>{{ date('d M', strtotime($meeting->date)) }} @ {{ date('h:i A', strtotime($meeting->time)) }}</span>
                                </a>
                            @empty
                                <div style="font-size:11px; color:#94a3b8; text-align:center; padding:16px 0;">No meetings scheduled.</div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Tasks -->
                    <div class="card p-6">
                        <div style="display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid #f1f5f9; padding-bottom:12px; margin-bottom:16px;">
                            <h3 style="font-size:13px; font-weight:700; color:#1e293b; display:flex; align-items:center; gap:6px;">
                                <i class="fa-solid fa-list-check" style="color:#e11d48;"></i> Tasks ({{ $lead->tasks->count() }})
                            </h3>
                            <a href="{{ route('tasks.index') }}" class="btn btn-light btn-sm" style="padding:4px 8px !important; font-size:10px !important;">Add</a>
                        </div>
                        <div style="display:flex; flex-direction:column; gap:12px;">
                            @forelse($lead->tasks as $task)
                                <div style="padding:12px; border-radius:10px; background:#f8fafc; border:1px solid #e2e8f0; display:flex; align-items:center; justify-content:space-between;">
                                    <div>
                                        <span style="font-weight:700; color:#1e293b; font-size:12px; display:block; @if($task->status === 'Completed') text-decoration:line-through; color:#94a3b8; @endif">{{ $task->title }}</span>
                                        <span style="font-size:10px; color:#8696a0; margin-top:4px; display:block;">Due: {{ date('d M', strtotime($task->due_date)) }}</span>
                                    </div>
                                    <form action="{{ route('tasks.toggle', $task->id) }}" method="POST" style="margin:0;">
                                        @csrf
                                        <button type="submit" style="background:none; border:none; cursor:pointer; padding:4px;">
                                            @if($task->status === 'Completed')
                                                <i class="fa-solid fa-square-check" style="font-size:18px; color:#10b981;"></i>
                                            @else
                                                <i class="fa-regular fa-square" style="font-size:18px; color:#cbd5e1;"></i>
                                            @endif
                                        </button>
                                    </form>
                                </div>
                            @empty
                                <div style="font-size:11px; color:#94a3b8; text-align:center; padding:16px 0;">No tasks found.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Generated Documents & Timeline activities -->
                <div style="display:flex; flex-direction:column; gap:24px;">
                    <!-- Documents -->
                    <div class="card p-6">
                        <div style="display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid #f1f5f9; padding-bottom:12px; margin-bottom:16px;">
                            <h3 style="font-size:13px; font-weight:700; color:#1e293b; display:flex; align-items:center; gap:6px;">
                                <i class="fa-regular fa-file-pdf" style="color:#059669;"></i> Proposals & Invoices
                            </h3>
                            <!-- Quick PDF Create Form -->
                            <form action="{{ route('documents.store') }}" method="POST" style="margin:0; display:flex; align-items:center; gap:6px;">
                                @csrf
                                <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                                <input type="hidden" name="title" value="{{ $lead->company_name ?: $lead->full_name }} Software Contract">
                                <input type="hidden" name="amount" value="{{ $lead->budget ?: 100000 }}">
                                
                                <select name="type" onchange="this.form.submit()" class="form-input" style="padding:4px 8px !important; font-size:10px !important; width:100px; background:#fff;">
                                    <option value="" selected disabled>+ Generate</option>
                                    <option value="Proposal">Proposal</option>
                                    <option value="Quotation">Quotation</option>
                                    <option value="Invoice">Invoice</option>
                                </select>
                            </form>
                        </div>
                        <div style="display:flex; flex-direction:column; gap:12px;">
                            @forelse($lead->documents as $doc)
                                <a href="{{ route('documents.show', $doc->id) }}" style="display:block; padding:12px; border-radius:10px; background:#f8fafc; border:1px solid #e2e8f0; text-decoration:none; transition:all 0.15s;" onmouseover="this.style.borderColor='#cbd5e1'" onmouseout="this.style.borderColor='#e2e8f0'">
                                    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:6px;">
                                        <span style="font-weight:700; color:#1e293b; font-size:12px;">{{ $doc->document_number }}</span>
                                        <span class="badge b-proposal">{{ $doc->type }}</span>
                                    </div>
                                    <div style="display:flex; justify-content:space-between; align-items:center; font-size:11px;">
                                        <span style="color:#64748b; text-overflow:ellipsis; overflow:hidden; white-space:nowrap; max-width:140px;">{{ $doc->title }}</span>
                                        <span style="color:#0f766e; font-weight:700;">₹{{ number_format($doc->amount, 0) }}</span>
                                    </div>
                                </a>
                            @empty
                                <div style="font-size:11px; color:#94a3b8; text-align:center; padding:16px 0;">No documents generated yet.</div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Communication Activity Logs Timeline -->
                    <div class="card p-6">
                        <h3 style="font-size:13px; font-weight:700; color:#1e293b; border-bottom:1px solid #f1f5f9; padding-bottom:12px; margin-bottom:16px; display:flex; align-items:center; gap:6px;">
                            <i class="fa-solid fa-clock-rotate-left" style="color:#64748b;"></i> Communication logs
                        </h3>
                        <div style="position:relative; padding-left:16px; border-left:2px solid #e2e8f0; display:flex; flex-direction:column; gap:16px;">
                            @forelse($lead->activities as $act)
                                <div style="position:relative; font-size:12px;">
                                    <!-- Bullet marker -->
                                    <span style="position:absolute; left:-22px; top:4px; w:10px; height:10px; border-radius:50%; border:2px solid #ffffff; display:inline-block; width:10px; height:10px;
                                        @if($act->type==='AI Insight') background:#6366f1;
                                        @elseif($act->type==='Call') background:#06b6d4;
                                        @elseif($act->type==='WhatsApp') background:#10b981;
                                        @elseif($act->type==='Email') background:#6366f1;
                                        @elseif($act->type==='Status Change') background:#f59e0b;
                                        @else background:#94a3b8; @endif"></span>
                                    
                                    <div style="display:flex; justify-content:space-between; align-items:baseline; margin-bottom:4px;">
                                        <span style="font-weight:700; color:#1e293b;">{{ $act->type }}</span>
                                        <span style="font-size:9px; color:#94a3b8;">{{ $act->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p style="color:#475569; margin:0; line-height:1.5; font-size:11px;">{{ $act->description }}</p>
                                    @if($act->user)
                                        <span style="font-size:9px; color:#94a3b8; font-style:italic; display:block; margin-top:2px;">by {{ $act->user->name }}</span>
                                    @endif
                                </div>
                            @empty
                                <div style="font-size:11px; color:#94a3b8; text-align:center; padding:16px 0;">No recent activities logged.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Edit Lead Slide-over Modal -->
    <div x-show="openEditModal" class="fixed inset-0 z-50" style="overflow:hidden; display:none;">
        <div style="position:absolute; inset:0; overflow:hidden;">
            {{-- Backdrop --}}
            <div x-show="openEditModal"
                 x-transition:enter="ease-in-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in-out duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 style="position:absolute; inset:0; background:rgba(15,23,42,0.4); backdrop-filter:blur(4px);"
                 @click="openEditModal = false"></div>

            <div style="pointer-events:none; position:fixed; inset-y:0; right:0; display:flex; max-width:100%; padding-left:40px;">
                <div x-show="openEditModal"
                     x-transition:enter="transform transition ease-in-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                     x-transition:leave="transform transition ease-in-out duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                     style="pointer-events:auto; width:100%; max-width:480px;">
                    <div class="drawer-panel" style="display:flex; flex-direction:column; height:100%; overflow:hidden;">

                        <div class="drawer-header">
                            <h2><i class="fa-solid fa-pen-to-square" style="color:#6366f1;"></i> Edit Lead Details</h2>
                            <button @click="openEditModal = false" style="background:none; border:none; color:#64748b; cursor:pointer; font-size:18px; line-height:1; padding:4px;">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                        
                        <div class="drawer-body">
                            <form action="{{ route('leads.update', $lead->id) }}" method="POST" style="display:flex; flex-direction:column; gap:14px; margin:0;">
                                @csrf
                                @method('PUT')
                                
                                <div>
                                    <label class="form-label">Full Name *</label>
                                    <input type="text" name="full_name" required value="{{ $lead->full_name }}" class="form-input">
                                </div>

                                <div>
                                    <label class="form-label">Company Name</label>
                                    <input type="text" name="company_name" value="{{ $lead->company_name }}" class="form-input">
                                </div>

                                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                                    <div>
                                        <label class="form-label">Mobile Number</label>
                                        <input type="text" name="mobile" value="{{ $lead->mobile }}" class="form-input">
                                    </div>
                                    <div>
                                        <label class="form-label">Email Address</label>
                                        <input type="email" name="email" value="{{ $lead->email }}" class="form-input">
                                    </div>
                                </div>

                                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                                    <div>
                                        <label class="form-label">Website</label>
                                        <input type="text" name="website" value="{{ $lead->website }}" class="form-input">
                                    </div>
                                    <div>
                                        <label class="form-label">Industry</label>
                                        <input type="text" name="industry" value="{{ $lead->industry }}" class="form-input">
                                    </div>
                                </div>

                                <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:10px;">
                                    <div>
                                        <label class="form-label">Source</label>
                                        <select name="lead_source" class="form-input" style="background:#fff;">
                                            <option value="Manual" {{ $lead->lead_source === 'Manual' ? 'selected' : '' }}>Manual</option>
                                            <option value="Website" {{ $lead->lead_source === 'Website' ? 'selected' : '' }}>Website</option>
                                            <option value="WhatsApp" {{ $lead->lead_source === 'WhatsApp' ? 'selected' : '' }}>WhatsApp</option>
                                            <option value="Facebook" {{ $lead->lead_source === 'Facebook' ? 'selected' : '' }}>Facebook</option>
                                            <option value="Instagram" {{ $lead->lead_source === 'Instagram' ? 'selected' : '' }}>Instagram</option>
                                            <option value="Email" {{ $lead->lead_source === 'Email' ? 'selected' : '' }}>Email</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="form-label">Budget (INR)</label>
                                        <input type="number" name="budget" value="{{ $lead->budget }}" class="form-input">
                                    </div>
                                    <div>
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-input" style="background:#fff;">
                                            <option value="New" {{ $lead->status === 'New' ? 'selected' : '' }}>New</option>
                                            <option value="Contacted" {{ $lead->status === 'Contacted' ? 'selected' : '' }}>Contacted</option>
                                            <option value="Qualified" {{ $lead->status === 'Qualified' ? 'selected' : '' }}>Qualified</option>
                                            <option value="Proposal Sent" {{ $lead->status === 'Proposal Sent' ? 'selected' : '' }}>Proposal Sent</option>
                                            <option value="Negotiation" {{ $lead->status === 'Negotiation' ? 'selected' : '' }}>Negotiation</option>
                                            <option value="Won" {{ $lead->status === 'Won' ? 'selected' : '' }}>Won</option>
                                            <option value="Lost" {{ $lead->status === 'Lost' ? 'selected' : '' }}>Lost</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label class="form-label">Assign Sales Agent</label>
                                    <select name="assigned_to" class="form-input" style="background:#fff;">
                                        <option value="">Unassigned</option>
                                        @foreach($agents as $agent)
                                            <option value="{{ $agent->id }}" {{ $lead->assigned_to === $agent->id ? 'selected' : '' }}>{{ $agent->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="form-label">Core Requirement *</label>
                                    <textarea name="requirement" required rows="3" class="form-input" style="resize:vertical;">{{ $lead->requirement }}</textarea>
                                </div>

                                <div>
                                    <label class="form-label">Internal Notes</label>
                                    <textarea name="notes" rows="2" class="form-input" style="resize:vertical;">{{ $lead->notes }}</textarea>
                                </div>

                                <div style="margin-top:12px; display:flex; justify-content:flex-end; gap:8px;">
                                    <button type="button" @click="openEditModal = false" class="btn btn-light">
                                        Cancel
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        Save Changes
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
