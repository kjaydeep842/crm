@extends('layouts.app')

@section('header_title', 'Meetings Hub')

@section('content')
<div x-data="{ openScheduleModal: false }">

    {{-- ── Filters & Actions ── --}}
    <div class="glass-card rounded-2xl p-5 mb-6" style="display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;">
        <form action="{{ route('meetings.index') }}" method="GET" style="display:flex;align-items:center;gap:10px;">
            <select name="status" onchange="this.form.submit()" class="form-input" style="width:160px;">
                <option value="">All Meetings</option>
                @foreach(['Scheduled','Completed','Cancelled'] as $st)
                    <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>{{ $st }}</option>
                @endforeach
            </select>
        </form>

        <button @click="openScheduleModal = true" class="btn-primary">
            <i class="fa-solid fa-plus"></i>
            <span>Schedule Meeting</span>
        </button>
    </div>

    {{-- ── Meetings Table ── --}}
    <div class="glass-card rounded-2xl" style="overflow:hidden;">
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Meeting Topic</th>
                        <th>Customer & Lead</th>
                        <th>Schedule Time</th>
                        <th>Location / Link</th>
                        <th>Status</th>
                        <th style="text-align:center;">AI Prep</th>
                        <th style="text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($meetings as $meeting)
                        <tr>
                            <td>
                                <span style="font-weight:700;color:#1e293b;display:block;">{{ $meeting->title }}</span>
                                <span style="font-size:10px;color:#94a3b8;display:block;margin-top:2px;max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                    {{ $meeting->notes ?: 'No description.' }}
                                </span>
                            </td>
                            <td>
                                <span style="font-weight:600;color:#1e293b;display:block;font-size:12px;">{{ $meeting->customer_name }}</span>
                                @if($meeting->lead)
                                    <a href="{{ route('leads.show', $meeting->lead_id) }}"
                                       style="font-size:10px;color:#6366f1;text-decoration:none;display:block;margin-top:2px;">
                                        View Lead #{{ $meeting->lead_id }}
                                    </a>
                                @endif
                            </td>
                            <td>
                                <span style="font-weight:600;color:#1e293b;display:block;font-size:12px;">{{ date('d M, Y', strtotime($meeting->date)) }}</span>
                                <span style="font-size:10px;color:#94a3b8;display:block;margin-top:2px;">{{ date('h:i A', strtotime($meeting->time)) }}</span>
                            </td>
                            <td>
                                @if($meeting->meeting_link)
                                    <a href="{{ $meeting->meeting_link }}" target="_blank"
                                       style="display:inline-flex;align-items:center;gap:5px;font-size:11px;color:#6366f1;text-decoration:none;font-weight:600;">
                                        <i class="fa-solid fa-video" style="font-size:10px;"></i> Join Meeting
                                    </a>
                                @else
                                    <span style="display:inline-flex;align-items:center;gap:5px;font-size:11px;color:#64748b;">
                                        <i class="fa-solid fa-location-dot" style="color:#94a3b8;font-size:10px;"></i>
                                        {{ $meeting->location ?: 'Physical office' }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $badgeClass = match($meeting->status) {
                                        'Scheduled'  => 'badge-scheduled',
                                        'Completed'  => 'badge-completed',
                                        'Cancelled'  => 'badge-cancelled',
                                        default      => 'badge-scheduled'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $meeting->status }}</span>
                            </td>
                            <td style="text-align:center;">
                                @if($meeting->ai_customer_summary)
                                    <span style="display:inline-flex;align-items:center;gap:4px;font-size:11px;color:#6366f1;font-weight:700;">
                                        <i class="fa-solid fa-wand-magic-sparkles" style="font-size:10px;"></i> Ready
                                    </span>
                                @else
                                    <span style="font-size:11px;color:#94a3b8;">Unanalyzed</span>
                                @endif
                            </td>
                            <td style="text-align:center;">
                                <a href="{{ route('meetings.show', $meeting->id) }}" class="btn-secondary" style="font-size:11px;padding:6px 14px;">
                                    <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:10px;"></i> Open
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center;padding:48px;color:#94a3b8;">
                                <i class="fa-solid fa-calendar-xmark" style="font-size:28px;display:block;margin-bottom:10px;opacity:0.5;"></i>
                                No meetings scheduled yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ══ Schedule Meeting Slide-over ══ --}}
    <div x-show="openScheduleModal" class="fixed inset-0 z-50" style="overflow:hidden;display:none;">
        <div style="position:absolute;inset:0;overflow:hidden;">
            <div x-show="openScheduleModal"
                 x-transition:enter="ease-in-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in-out duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 style="position:absolute;inset:0;background:rgba(15,23,42,0.4);backdrop-filter:blur(4px);"
                 @click="openScheduleModal = false"></div>

            <div style="pointer-events:none;position:fixed;inset-y:0;right:0;display:flex;max-width:100%;padding-left:40px;">
                <div x-show="openScheduleModal"
                     x-transition:enter="transform transition ease-in-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                     x-transition:leave="transform transition ease-in-out duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                     style="pointer-events:auto;width:100%;max-width:460px;">
                    <div class="drawer-panel" style="display:flex;flex-direction:column;height:100%;overflow:hidden;">

                        <div class="drawer-header">
                            <h2><i class="fa-solid fa-calendar-plus" style="color:#6366f1;"></i> Schedule Meeting</h2>
                            <button @click="openScheduleModal = false" style="background:none;border:none;color:#64748b;cursor:pointer;font-size:18px;padding:4px;">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>

                        <div class="drawer-body">
                            <form action="{{ route('meetings.store') }}" method="POST">
                                @csrf
                                <div style="display:flex;flex-direction:column;gap:14px;">

                                    <div>
                                        <label class="form-label">Select Pipeline Lead *</label>
                                        <select name="lead_id" required class="form-input">
                                            <option value="" disabled selected>Select a Lead…</option>
                                            @foreach($leads as $l)
                                                <option value="{{ $l->id }}">{{ $l->full_name }} ({{ $l->company_name ?: 'No Company' }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="form-label">Meeting Title *</label>
                                        <input type="text" name="title" required placeholder="Initial Scope Demonstration" class="form-input">
                                    </div>
                                    <div>
                                        <label class="form-label">Customer Name Override</label>
                                        <input type="text" name="customer_name" placeholder="Leave blank to use lead's name" class="form-input">
                                    </div>
                                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                                        <div>
                                            <label class="form-label">Meeting Date *</label>
                                            <input type="date" name="date" required value="{{ date('Y-m-d') }}" class="form-input">
                                        </div>
                                        <div>
                                            <label class="form-label">Meeting Time *</label>
                                            <input type="time" name="time" required value="10:00" class="form-input">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="form-label">Meeting Link (Zoom / Meet)</label>
                                        <input type="text" name="meeting_link" placeholder="https://zoom.us/j/123456" class="form-input">
                                    </div>
                                    <div>
                                        <label class="form-label">Physical Location</label>
                                        <input type="text" name="location" placeholder="e.g. Conference Room 1" class="form-input">
                                    </div>
                                    <div>
                                        <label class="form-label">Agenda & Notes</label>
                                        <textarea name="notes" rows="3" placeholder="Discussing core features, timeline and budget." class="form-input"></textarea>
                                    </div>

                                    <div style="display:flex;justify-content:flex-end;gap:10px;padding-top:10px;border-top:1px solid #f1f5f9;margin-top:4px;">
                                        <button type="button" @click="openScheduleModal = false" class="btn-secondary">Cancel</button>
                                        <button type="submit" class="btn-primary">
                                            <i class="fa-solid fa-calendar-check"></i> Schedule & Analyze
                                        </button>
                                    </div>
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
