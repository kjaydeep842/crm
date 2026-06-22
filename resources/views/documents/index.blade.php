@extends('layouts.app')

@section('header_title', 'Documents & Proposals')

@section('content')
<div x-data="{ openDocModal: false }">

    {{-- ── Header ── --}}
    <div class="glass-card rounded-2xl p-5 mb-6" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px;">
        <div>
            <h2 style="font-size:15px;font-weight:700;color:#1e293b;margin:0 0 3px;">Contract Documents Registry</h2>
            <p style="font-size:12px;color:#64748b;margin:0;">Create and manage AI-generated proposals, commercial quotations, and invoices.</p>
        </div>
        <button @click="openDocModal = true" class="btn-primary">
            <i class="fa-solid fa-plus"></i> Generate Document
        </button>
    </div>

    {{-- ── Documents Grid ── --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:18px;">
        @forelse($documents as $doc)
            <div class="glass-card rounded-2xl" style="padding:20px;display:flex;flex-direction:column;justify-content:space-between;min-height:190px;transition:all 0.2s;">
                {{-- Top --}}
                <div>
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
                        @php
                            $docBadge = match($doc->type) {
                                'Proposal'  => ['bg'=>'#eef2ff','color'=>'#4f46e5','border'=>'#c7d2fe'],
                                'Quotation' => ['bg'=>'#ecfeff','color'=>'#0891b2','border'=>'#a5f3fc'],
                                default     => ['bg'=>'#ecfdf5','color'=>'#047857','border'=>'#a7f3d0'],
                            };
                        @endphp
                        <span style="padding:3px 9px;border-radius:6px;font-size:9px;font-weight:800;text-transform:uppercase;letter-spacing:0.08em;background:{{ $docBadge['bg'] }};color:{{ $docBadge['color'] }};border:1px solid {{ $docBadge['border'] }};">
                            {{ $doc->type }}
                        </span>
                        <span style="font-size:10px;color:#94a3b8;font-weight:600;font-family:monospace;">{{ $doc->document_number }}</span>
                    </div>
                    <h3 style="font-size:13px;font-weight:700;color:#1e293b;margin:0 0 4px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $doc->title }}</h3>
                    <span style="font-size:11px;color:#64748b;display:block;margin-bottom:3px;">Client: <b style="color:#1e293b;">{{ $doc->lead->full_name ?? 'N/A' }}</b></span>
                    <span style="font-size:10px;color:#94a3b8;display:block;">Created: {{ $doc->created_at->format('d M, Y') }}</span>
                </div>

                {{-- Footer --}}
                <div style="display:flex;align-items:center;justify-content:space-between;padding-top:12px;border-top:1px solid #f1f5f9;margin-top:12px;">
                    <div>
                        <span style="font-size:9px;color:#94a3b8;text-transform:uppercase;font-weight:700;display:block;letter-spacing:0.06em;">Grand Total</span>
                        <span style="font-size:15px;font-weight:800;color:#1e293b;">₹{{ number_format($doc->amount, 2) }}</span>
                    </div>
                    <a href="{{ route('documents.show', $doc->id) }}" class="btn-secondary" style="font-size:11px;padding:7px 14px;">
                        <i class="fa-regular fa-folder-open" style="font-size:11px;"></i> Open Details
                    </a>
                </div>
            </div>
        @empty
            <div class="glass-card rounded-2xl" style="grid-column:1/-1;padding:48px;text-align:center;color:#94a3b8;">
                <i class="fa-solid fa-file-invoice" style="font-size:32px;display:block;margin-bottom:12px;opacity:0.4;"></i>
                No documents found.
            </div>
        @endforelse
    </div>

    @if($documents->hasPages())
        <div style="margin-top:20px;">{{ $documents->links() }}</div>
    @endif

    {{-- ══ Create Document Slide-over ══ --}}
    <div x-show="openDocModal" class="fixed inset-0 z-50" style="overflow:hidden;display:none;">
        <div style="position:absolute;inset:0;overflow:hidden;">
            <div x-show="openDocModal"
                 x-transition:enter="ease-in-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in-out duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 style="position:absolute;inset:0;background:rgba(15,23,42,0.4);backdrop-filter:blur(4px);"
                 @click="openDocModal = false"></div>

            <div style="pointer-events:none;position:fixed;inset-y:0;right:0;display:flex;max-width:100%;padding-left:40px;">
                <div x-show="openDocModal"
                     x-transition:enter="transform transition ease-in-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                     x-transition:leave="transform transition ease-in-out duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                     style="pointer-events:auto;width:100%;max-width:440px;">
                    <div class="drawer-panel" style="display:flex;flex-direction:column;height:100%;overflow:hidden;">

                        <div class="drawer-header">
                            <h2><i class="fa-solid fa-file-invoice" style="color:#6366f1;"></i> Generate Document</h2>
                            <button @click="openDocModal = false" style="background:none;border:none;color:#64748b;cursor:pointer;font-size:18px;padding:4px;">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>

                        <div class="drawer-body">
                            <form action="{{ route('documents.store') }}" method="POST">
                                @csrf
                                <div style="display:flex;flex-direction:column;gap:14px;">

                                    <div>
                                        <label class="form-label">Link to Lead *</label>
                                        <select name="lead_id" required class="form-input">
                                            <option value="" disabled selected>Select a Lead…</option>
                                            @foreach($leads as $l)
                                                <option value="{{ $l->id }}">{{ $l->full_name }} ({{ $l->company_name ?: 'No Company' }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="form-label">Document Type *</label>
                                        <select name="type" required class="form-input">
                                            <option value="Proposal">AI Business Proposal</option>
                                            <option value="Quotation">Commercial Quotation</option>
                                            <option value="Invoice">Client Invoice</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="form-label">Document Title *</label>
                                        <input type="text" name="title" required placeholder="Custom Software Services Agreement" class="form-input">
                                    </div>
                                    <div>
                                        <label class="form-label">Total Value / Amount (INR) *</label>
                                        <input type="number" name="amount" required placeholder="125000" class="form-input">
                                    </div>

                                    <p style="font-size:10px;color:#94a3b8;background:#fafbff;border:1px solid #e2e8f0;border-radius:8px;padding:10px;line-height:1.5;">
                                        <i class="fa-solid fa-robot" style="color:#6366f1;margin-right:4px;"></i>
                                        Aura AI will automatically write complete business terms, project milestones, payment schedules, and scope details.
                                    </p>

                                    <div style="display:flex;justify-content:flex-end;gap:10px;padding-top:10px;border-top:1px solid #f1f5f9;margin-top:4px;">
                                        <button type="button" @click="openDocModal = false" class="btn-secondary">Cancel</button>
                                        <button type="submit" class="btn-primary">
                                            <i class="fa-solid fa-wand-magic-sparkles"></i> Generate with AI
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
