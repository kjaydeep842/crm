@extends('layouts.app')

@section('header_title')
    Meeting Workspace: {{ $meeting->title }}
@endsection

@section('content')
<!-- Top Back Panel -->
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:32px;">
    <a href="{{ route('meetings.index') }}" class="btn btn-secondary btn-sm">
        <i class="fa-solid fa-arrow-left"></i>
        <span>Back to Meetings</span>
    </a>
    
    <span class="badge @if($meeting->status == 'Scheduled') b-scheduled @elseif($meeting->status == 'Completed') b-completed @else b-cancelled @endif" style="padding:4px 12px !important;">
        {{ $meeting->status }}
    </span>
</div>

<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap:24px; align-items:start;">
    
    <!-- Left Column: Meeting Details & AI Prep -->
    <div style="display:flex; flex-direction:column; gap:24px;">
        
        <!-- Details Card -->
        <div class="card p-6">
            <h3 style="font-size:14px; font-weight:700; color:#1e293b; border-bottom:1px solid #f1f5f9; padding-bottom:12px; margin-bottom:16px;">
                Schedule Details
            </h3>
            <div style="display:flex; flex-direction:column; gap:16px; font-size:12px;">
                <div>
                    <span style="display:block; font-size:10px; color:#64748b; uppercase; font-weight:700; text-transform:uppercase;">Meeting Title</span>
                    <span style="font-size:14px; font-weight:700; color:#1e293b; margin-top:4px; display:block;">{{ $meeting->title }}</span>
                </div>
                <div>
                    <span style="display:block; font-size:10px; color:#64748b; uppercase; font-weight:700; text-transform:uppercase;">Customer / Lead Name</span>
                    <span style="color:#1e293b; margin-top:4px; display:block; font-weight:600;">{{ $meeting->customer_name }}</span>
                    @if($meeting->lead)
                        <a href="{{ route('leads.show', $meeting->lead_id) }}" style="font-size:10px; color:#4f46e5; text-decoration:underline; display:block; margin-top:4px;">View Lead Console</a>
                    @endif
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div>
                        <span style="display:block; font-size:10px; color:#64748b; uppercase; font-weight:700; text-transform:uppercase;">Date</span>
                        <span style="color:#334155; margin-top:4px; display:block; font-weight:600;">{{ date('d M, Y', strtotime($meeting->date)) }}</span>
                    </div>
                    <div>
                        <span style="display:block; font-size:10px; color:#64748b; uppercase; font-weight:700; text-transform:uppercase;">Time</span>
                        <span style="color:#334155; margin-top:4px; display:block; font-weight:600;">{{ date('h:i A', strtotime($meeting->time)) }}</span>
                    </div>
                </div>
                <div>
                    <span style="display:block; font-size:10px; color:#64748b; uppercase; font-weight:700; text-transform:uppercase;">Location</span>
                    @if($meeting->meeting_link)
                        <a href="{{ $meeting->meeting_link }}" target="_blank" style="color:#4f46e5; text-decoration:none; margin-top:4px; display:block; font-weight:600; word-break:break-all;"><i class="fa-solid fa-video" style="margin-right:6px;"></i>{{ $meeting->meeting_link }}</a>
                    @else
                        <span style="color:#334155; margin-top:4px; display:block;"><i class="fa-solid fa-location-dot" style="margin-right:6px; color:#64748b;"></i>{{ $meeting->location ?: 'Office / Physical meeting' }}</span>
                    @endif
                </div>
                @if($meeting->notes)
                    <div>
                        <span style="display:block; font-size:10px; color:#64748b; uppercase; font-weight:700; text-transform:uppercase;">Agenda Notes</span>
                        <p style="color:#475569; margin-top:4px; font-style:italic; line-height:1.5;">{{ $meeting->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- AI Preparation Card -->
        <div class="card p-6" style="position:relative; overflow:hidden;">
            <div style="position:absolute; top:12px; right:12px; opacity:0.04; pointer-events:none;">
                <i class="fa-solid fa-brain" style="font-size:60px; color:#6366f1;"></i>
            </div>
            
            <h3 style="font-size:14px; font-weight:700; color:#1e293b; border-bottom:1px solid #f1f5f9; padding-bottom:12px; margin-bottom:16px; display:flex; align-items:center; justify-content:space-between;">
                <span>AI Meeting Prep Assistant</span>
                <form action="{{ route('meetings.prep-ai', $meeting->id) }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="btn btn-light btn-sm" style="font-size:10px !important; padding:4px 8px !important; color:#4f46e5;">
                        <i class="fa-solid fa-rotate"></i> Refresh
                    </button>
                </form>
            </h3>

            <div style="display:flex; flex-direction:column; gap:16px; font-size:12px;">
                <div>
                    <span style="display:block; font-size:10px; color:#64748b; uppercase; font-weight:700; text-transform:uppercase;">Client Context Summary</span>
                    <p style="color:#475569; margin-top:4px; line-height:1.6;">{{ $meeting->ai_customer_summary ?: 'Wait for AI setup.' }}</p>
                </div>
                <div>
                    <span style="display:block; font-size:10px; color:#64748b; uppercase; font-weight:700; text-transform:uppercase;">Previous Interactions</span>
                    <p style="color:#475569; margin-top:4px; line-height:1.6;">{{ $meeting->ai_previous_interactions ?: 'No previous communications registered.' }}</p>
                </div>
                <div>
                    <span style="display:block; font-size:10px; color:#64748b; uppercase; font-weight:700; text-transform:uppercase;">Suggested Discussion Topics</span>
                    <p style="color:#475569; margin-top:4px; line-height:1.6; white-space:pre-line;">{{ $meeting->ai_suggested_topics ?: 'No topics recommended.' }}</p>
                </div>
            </div>
        </div>

    </div>

    <!-- Right Column: Transcript Paste & AI Summarization -->
    <div style="grid-column: span 2; display:flex; flex-direction:column; gap:24px;">
        
        <!-- Paste transcript form (only if status is scheduled / pending notes) -->
        @if($meeting->status === 'Scheduled')
            <div class="card p-6" style="border-radius:20px;">
                <h3 style="font-size:16px; font-weight:800; color:#1e293b; padding-bottom:12px; margin-bottom:12px; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; gap:8px;">
                    <i class="fa-solid fa-microphone-lines" style="color:#7c3aed;"></i> Post-Meeting Notes AI Processor
                </h3>
                <p style="font-size:12px; color:#64748b; margin-bottom:20px;">Paste the conversation transcript or raw bullet points from the call. DevineSky AI will summarize it, extract decisions, and auto-schedule follow-up tasks.</p>

                <form action="{{ route('meetings.notes-ai', $meeting->id) }}" method="POST" style="display:flex; flex-direction:column; gap:16px; margin:0;">
                    @csrf
                    <div>
                        <label class="form-label">Conversation transcript / Notes *</label>
                        <textarea name="transcript" required rows="10" placeholder="John: Welcome Priya. Let's discuss your organic store...&#10;Priya: Thanks John. I need to make sure we sync inventory...&#10;John: Yes, we can integrate a warehouse CSV scheduler..." class="form-input" style="font-family:monospace; resize:vertical; background:#fff;"></textarea>
                    </div>

                    <div style="display:flex; justify-content:flex-end;">
                        <button type="submit" class="btn btn-violet">
                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                            <span>Process Conversation with AI</span>
                        </button>
                    </div>
                </form>
            </div>
        @endif

        <!-- AI Meeting Outcomes (if Completed) -->
        @if($meeting->status === 'Completed')
            <div class="card p-6" style="border-radius:20px;">
                <h3 style="font-size:16px; font-weight:800; color:#1e293b; padding-bottom:12px; margin-bottom:20px; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; gap:8px;">
                    <i class="fa-solid fa-circle-check" style="color:#059669;"></i> AI Meeting Summarization
                </h3>

                <div style="display:flex; flex-direction:column; gap:20px; font-size:12px;">
                    <!-- Summary -->
                    <div style="background:#f8fafc; border:1px solid #e2e8f0; padding:16px; border-radius:12px;">
                        <span style="display:block; font-size:10px; color:#64748b; uppercase; font-weight:700; text-transform:uppercase; margin-bottom:6px;">Executive Conversation Summary</span>
                        <p style="color:#334155; line-height:1.6; margin:0;">{{ $meeting->ai_summary }}</p>
                    </div>

                    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:20px;">
                        <!-- Key Decisions -->
                        <div style="background:#f8fafc; border:1px solid #e2e8f0; padding:16px; border-radius:12px;">
                            <span style="display:block; font-size:10px; color:#64748b; uppercase; font-weight:700; text-transform:uppercase; margin-bottom:8px;"><i class="fa-solid fa-circle-check text-emerald-450" style="margin-right:6px;"></i>Key Decisions Approved</span>
                            <p style="color:#334155; line-height:1.6; margin:0; white-space:pre-line;">{{ $meeting->ai_action_items ?: 'No major decisions logged.' }}</p>
                        </div>

                        <!-- Action Items -->
                        <div style="background:#f8fafc; border:1px solid #e2e8f0; padding:16px; border-radius:12px;">
                            <span style="display:block; font-size:10px; color:#64748b; uppercase; font-weight:700; text-transform:uppercase; margin-bottom:8px;"><i class="fa-solid fa-list-check text-rose-400" style="margin-right:6px;"></i>Follow-up Tasks Scheduled</span>
                            <p style="color:#334155; line-height:1.6; margin:0; white-space:pre-line;">{{ $meeting->ai_followup_tasks ?: 'No followups created.' }}</p>
                            <span style="display:block; font-size:9px; color:#64748b; italic; font-style:italic; margin-top:10px;"><i class="fa-solid fa-robot" style="color:#6366f1; margin-right:4px;"></i> These action items were automatically created in your Task Console.</span>
                        </div>
                    </div>

                    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:20px;">
                        <!-- Next meeting date recommendation -->
                        <div style="background:#f8fafc; border:1px solid #e2e8f0; padding:16px; border-radius:12px;">
                            <span style="display:block; font-size:10px; color:#64748b; uppercase; font-weight:700; text-transform:uppercase; margin-bottom:6px;"><i class="fa-regular fa-calendar-check" style="color:#7c3aed; margin-right:6px;"></i>Next recommended meeting</span>
                            <span style="font-weight:700; color:#1e293b; font-size:13px; display:block;">
                                {{ $meeting->ai_next_meeting_suggestions ?: 'No date recommended.' }}
                            </span>
                        </div>

                        <!-- Location summary -->
                        <div style="background:#f8fafc; border:1px solid #e2e8f0; padding:16px; border-radius:12px;">
                            <span style="display:block; font-size:10px; color:#64748b; uppercase; font-weight:700; text-transform:uppercase; margin-bottom:6px;">Meeting Duration & Status</span>
                            <span style="color:#334155; display:block; font-weight:600;">Status: <b style="color:#059669;">Successfully Completed & Archived</b></span>
                        </div>
                    </div>

                    <!-- Transcript Box -->
                    <div style="padding-top:16px; border-top:1px solid #f1f5f9;">
                        <span style="display:block; font-size:10px; color:#64748b; uppercase; font-weight:700; text-transform:uppercase; margin-bottom:8px;">Submitted Conversation Transcript</span>
                        <div style="padding:12px; background:#fafbff; border:1px solid #e2e8f0; border-radius:12px; max-height:200px; overflow-y:auto; font-family:monospace; font-size:11px; color:#475569; white-space:pre-line;">
                            {{ $meeting->transcript }}
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
