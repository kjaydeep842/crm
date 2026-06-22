@extends('layouts.app')

@section('header_title', 'Leads Management')

@section('content')
<div x-data="{ openCreateModal: false }">

    {{-- ── Filters & Search Toolbar ── --}}
    <div class="glass-card rounded-2xl p-5 mb-6" style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:14px;">
        <form action="{{ route('leads.index') }}" method="GET" style="display:flex;flex-wrap:wrap;gap:10px;flex:1;">
            {{-- Search --}}
            <div style="position:relative;min-width:200px;flex:1;">
                <i class="fa-solid fa-magnifying-glass" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:11px;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, company, email…"
                    class="form-input" style="padding-left:32px;">
            </div>

            {{-- Status --}}
            <select name="status" class="form-input" style="width:150px;">
                <option value="">All Statuses</option>
                @foreach(['New','Contacted','Qualified','Proposal Sent','Negotiation','Won','Lost'] as $st)
                    <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>{{ $st }}</option>
                @endforeach
            </select>

            {{-- Source --}}
            <select name="source" class="form-input" style="width:140px;">
                <option value="">All Sources</option>
                @foreach(['Website','WhatsApp','Facebook','Instagram','Email','Manual'] as $src)
                    <option value="{{ $src }}" {{ request('source') === $src ? 'selected' : '' }}>{{ $src }}</option>
                @endforeach
            </select>

            <button type="submit" class="btn-filter">
                <i class="fa-solid fa-filter"></i> Apply
            </button>
            @if(request()->anyFilled(['search','status','source']))
                <a href="{{ route('leads.index') }}" class="btn-secondary" style="padding:8px 12px;">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            @endif
        </form>

        <button @click="openCreateModal = true" class="btn-primary">
            <i class="fa-solid fa-plus"></i>
            <span>Add New Lead</span>
        </button>
    </div>

    {{-- ── Leads Table ── --}}
    <div class="glass-card rounded-2xl" style="overflow:hidden;">
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Lead ID</th>
                        <th>Full Name & Company</th>
                        <th>Contact & Industry</th>
                        <th>Source</th>
                        <th style="text-align:center;">AI Rating</th>
                        <th style="text-align:right;">Budget</th>
                        <th>Assigned To</th>
                        <th>Status</th>
                        <th style="text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leads as $lead)
                        <tr>
                            <td style="font-weight:700;color:#94a3b8;font-family:monospace;font-size:11px;">
                                #L-{{ str_pad($lead->id, 4, '0', STR_PAD_LEFT) }}
                            </td>
                            <td>
                                <span style="font-weight:700;color:#1e293b;display:block;">{{ $lead->full_name }}</span>
                                <span style="font-size:10px;color:#94a3b8;display:block;margin-top:2px;">{{ $lead->company_name ?: 'No Company' }}</span>
                            </td>
                            <td>
                                <span style="display:block;font-size:11px;"><i class="fa-regular fa-envelope" style="color:#94a3b8;margin-right:4px;"></i>{{ $lead->email ?: 'N/A' }}</span>
                                <span style="display:block;font-size:11px;margin-top:2px;"><i class="fa-solid fa-mobile-screen-button" style="color:#94a3b8;margin-right:4px;"></i>{{ $lead->mobile ?: 'N/A' }}</span>
                            </td>
                            <td>
                                <span style="display:inline-flex;align-items:center;gap:6px;font-size:11px;">
                                    @if($lead->lead_source === 'WhatsApp') <i class="fa-brands fa-whatsapp" style="color:#16a34a;"></i>
                                    @elseif($lead->lead_source === 'Email') <i class="fa-regular fa-envelope" style="color:#6366f1;"></i>
                                    @elseif($lead->lead_source === 'Website') <i class="fa-solid fa-globe" style="color:#0891b2;"></i>
                                    @elseif($lead->lead_source === 'Facebook') <i class="fa-brands fa-facebook" style="color:#1d4ed8;"></i>
                                    @elseif($lead->lead_source === 'Instagram') <i class="fa-brands fa-instagram" style="color:#db2777;"></i>
                                    @else <i class="fa-solid fa-user-pen" style="color:#94a3b8;"></i>
                                    @endif
                                    {{ $lead->lead_source }}
                                </span>
                            </td>
                            <td style="text-align:center;">
                                <span class="badge @if($lead->ai_qualification === 'Hot Lead') badge-hot @elseif($lead->ai_qualification === 'Warm Lead') badge-warm @else badge-cold @endif">
                                    {{ $lead->ai_qualification ?: 'Unrated' }}
                                </span>
                                <span style="display:block;font-size:9px;color:#94a3b8;margin-top:3px;">Score: <b style="color:#475569;">{{ $lead->ai_score }}</b></span>
                            </td>
                            <td style="text-align:right;font-weight:700;color:#1e293b;">
                                ₹{{ number_format($lead->budget, 2) }}
                            </td>
                            <td style="color:#64748b;font-size:12px;">
                                {{ $lead->assignedAgent->name ?? 'Unassigned' }}
                            </td>
                            <td>
                                @php
                                    $badgeClass = match($lead->status) {
                                        'New'           => 'badge-new',
                                        'Contacted'     => 'badge-contacted',
                                        'Qualified'     => 'badge-qualified',
                                        'Proposal Sent' => 'badge-proposal',
                                        'Won'           => 'badge-won',
                                        'Lost'          => 'badge-lost',
                                        default         => 'badge-proposal'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $lead->status }}</span>
                            </td>
                            <td style="text-align:center;">
                                <div style="display:flex;align-items:center;justify-content:center;gap:6px;">
                                    <a href="{{ route('leads.show', $lead->id) }}" class="btn-icon" title="View Lead Console">
                                        <i class="fa-solid fa-chart-simple"></i>
                                    </a>
                                    <form action="{{ route('leads.destroy', $lead->id) }}" method="POST"
                                          onsubmit="return confirm('Delete this lead?');" style="margin:0;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-icon btn-icon-danger" title="Delete Lead">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align:center;padding:48px 20px;color:#94a3b8;">
                                <i class="fa-solid fa-users-slash" style="font-size:28px;display:block;margin-bottom:10px;opacity:0.5;"></i>
                                No leads match the selected filters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($leads->hasPages())
            <div style="padding:14px 20px;border-top:1px solid #f1f5f9;">
                {{ $leads->links() }}
            </div>
        @endif
    </div>

    {{-- ══ Create Lead Slide-over ══ --}}
    <div x-show="openCreateModal" class="fixed inset-0 z-50" style="overflow:hidden;display:none;">
        <div style="position:absolute;inset:0;overflow:hidden;">
            {{-- Backdrop --}}
            <div x-show="openCreateModal"
                 x-transition:enter="ease-in-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in-out duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 style="position:absolute;inset:0;background:rgba(15,23,42,0.4);backdrop-filter:blur(4px);"
                 @click="openCreateModal = false"></div>

            <div style="pointer-events:none;position:fixed;top:0;bottom:0;right:0;display:flex;max-width:100%;padding-left:40px;">
                <div x-show="openCreateModal"
                     x-transition:enter="transform transition ease-in-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                     x-transition:leave="transform transition ease-in-out duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                     style="pointer-events:auto;width:100%;max-width:480px;">
                    <div class="drawer-panel" style="display:flex;flex-direction:column;height:100%;overflow:hidden;">

                        <div class="drawer-header">
                            <h2><i class="fa-solid fa-users" style="color:#6366f1;"></i> Log Inbound Lead</h2>
                            <button @click="openCreateModal = false" style="background:none;border:none;color:#64748b;cursor:pointer;font-size:18px;line-height:1;padding:4px;">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>

                        <div class="drawer-body">
                            <form action="{{ route('leads.store') }}" method="POST">
                                @csrf
                                <div style="display:flex;flex-direction:column;gap:14px;">

                                    <div>
                                        <label class="form-label">Full Name *</label>
                                        <input type="text" name="full_name" required placeholder="Rajesh Kumar" class="form-input">
                                    </div>
                                    <div>
                                        <label class="form-label">Company Name</label>
                                        <input type="text" name="company_name" placeholder="EduTech Solutions" class="form-input">
                                    </div>
                                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                                        <div>
                                            <label class="form-label">Mobile Number</label>
                                            <input type="text" name="mobile" placeholder="9876543210" class="form-input">
                                        </div>
                                        <div>
                                            <label class="form-label">Email Address</label>
                                            <input type="email" name="email" placeholder="rajesh@edutech.com" class="form-input">
                                        </div>
                                    </div>
                                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                                        <div>
                                            <label class="form-label">Website</label>
                                            <input type="text" name="website" placeholder="edutech.com" class="form-input">
                                        </div>
                                        <div>
                                            <label class="form-label">Industry</label>
                                            <input type="text" name="industry" placeholder="Education" class="form-input">
                                        </div>
                                    </div>
                                    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;">
                                        <div>
                                            <label class="form-label">Source</label>
                                            <select name="lead_source" class="form-input">
                                                @foreach(['Manual','Website','WhatsApp','Facebook','Instagram','Email'] as $s)
                                                    <option value="{{ $s }}">{{ $s }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="form-label">Budget (INR)</label>
                                            <input type="number" name="budget" placeholder="150000" class="form-input">
                                        </div>
                                        <div>
                                            <label class="form-label">Assign Agent</label>
                                            <select name="assigned_to" class="form-input">
                                                <option value="">Unassigned</option>
                                                @foreach($agents as $agent)
                                                    <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="form-label">Core Requirement *</label>
                                        <textarea name="requirement" required rows="3" placeholder="Need a school ERP with fee payments and app access." class="form-input"></textarea>
                                        <p style="font-size:10px;color:#94a3b8;margin-top:4px;"><i class="fa-solid fa-robot" style="color:#6366f1;margin-right:3px;"></i>AI will automatically score this lead.</p>
                                    </div>
                                    <div>
                                        <label class="form-label">Internal Notes</label>
                                        <textarea name="notes" rows="2" placeholder="Wants to deploy in 2 months." class="form-input"></textarea>
                                    </div>

                                    <div style="display:flex;justify-content:flex-end;gap:10px;padding-top:10px;border-top:1px solid #f1f5f9;margin-top:6px;">
                                        <button type="button" @click="openCreateModal = false" class="btn-secondary">Cancel</button>
                                        <button type="submit" class="btn-primary">
                                            <i class="fa-solid fa-robot"></i> Analyze & Save Lead
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
