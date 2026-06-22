@extends('layouts.app')

@section('header_title')
    View Document: {{ $document->document_number }}
@endsection

@section('content')
<!-- Back link and Print Actions -->
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px;" class="print:hidden">
    <a href="{{ route('documents.index') }}" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i>
        <span>Back to Registry</span>
    </a>
    
    <button onclick="window.print()" class="btn btn-primary">
        <i class="fa-solid fa-print"></i>
        <span>Print or Save PDF</span>
    </button>
</div>

<!-- Premium Print-ready Document Container -->
<div class="card" style="padding:48px; max-width:850px; margin:0 auto; background:#ffffff; box-shadow: 0 10px 30px rgba(0,0,0,0.04) !important; border:1px solid #e2e8f0; border-radius:16px; color:#1e293b;">
    
    <!-- Header Block -->
    <div style="display:flex; flex-wrap:wrap; justify-content:space-between; items-start: flex-start; gap:24px; border-bottom:1px solid #f1f5f9; padding-bottom:32px; margin-bottom:32px;">
        <div>
            <!-- Branding -->
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:16px;">
                <div style="width:32px; height:32px; border-radius:8px; background:linear-gradient(135deg, #6366f1, #06b6d4); display:flex; align-items:center; justify-content:center; box-shadow:0 4px 10px rgba(99, 102, 241, 0.2);">
                    <i class="fa-solid fa-cube" style="color:#ffffff; font-size:14px;"></i>
                </div>
                <span style="font-family:'Plus Jakarta Sans',sans-serif; font-size:16px; font-weight:800; color:#1e293b;">Aura AI Enterprise</span>
            </div>
            <span style="font-size:12px; color:#64748b; display:block; line-height:1.5;">Unit 4B, Cyber Towers, Phase II</span>
            <span style="font-size:12px; color:#64748b; display:block; line-height:1.5;">HITEC City, Hyderabad, 500081</span>
            <span style="font-size:12px; color:#64748b; display:block; line-height:1.5;">finance@auracrm.com | +91 40 44882200</span>
        </div>

        <div style="text-align:right; min-width:200px;">
            <h1 style="font-family:'Plus Jakarta Sans',sans-serif; font-size:24px; font-weight:800; color:#1e293b; letter-spacing:-0.02em; margin-bottom:4px; text-transform:uppercase;">{{ $document->type }}</h1>
            <span style="font-size:12px; color:#64748b; font-weight:700; display:block;">NO: <span style="font-family:monospace; color:#1e293b;">{{ $document->document_number }}</span></span>
            <span style="font-size:12px; color:#64748b; display:block; margin-top:4px;">Date: {{ $document->created_at->format('d M, Y') }}</span>
        </div>
    </div>

    <!-- Client Address Blocks -->
    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:32px; margin-bottom:32px; font-size:12px; line-height:1.6;">
        <div>
            <span style="font-size:10px; color:#94a3b8; uppercase; font-weight:700; text-transform:uppercase; display:block; margin-bottom:6px; letter-spacing:0.05em;">Prepared For:</span>
            <span style="font-weight:800; color:#1e293b; font-size:14px; display:block;">{{ $document->lead->full_name ?? 'N/A' }}</span>
            <span style="color:#475569; display:block; font-weight:600; margin-top:2px;">{{ $document->lead->company_name ?? 'Individual Client' }}</span>
            <span style="color:#64748b; display:block; margin-top:2px;">{{ $document->lead->email ?? '' }}</span>
            <span style="color:#64748b; display:block;">{{ $document->lead->mobile ?? '' }}</span>
        </div>

        <div style="text-align:right;">
            <span style="font-size:10px; color:#94a3b8; uppercase; font-weight:700; text-transform:uppercase; display:block; margin-bottom:6px; letter-spacing:0.05em;">Commercial Terms:</span>
            <span style="color:#475569; display:block;">Payment Method: Bank Transfer / UPI</span>
            <span style="color:#475569; display:block; margin-top:2px;">Due Date: Net 15 Days</span>
            <span style="color:#4f46e5; font-weight:800; font-size:14px; display:block; margin-top:8px;">Valued At: ₹{{ number_format($document->amount, 2) }}</span>
        </div>
    </div>

    <!-- Executive Summary -->
    <div style="margin-bottom:32px; font-size:12px; line-height:1.6;">
        <h3 style="font-size:11px; font-weight:800; color:#1e293b; text-transform:uppercase; tracking-wider: 0.05em; margin-bottom:12px; padding-bottom:6px; border-bottom:1px solid #f1f5f9;">1. Executive Summary</h3>
        <p style="color:#475569; text-align:justify; margin:0;">{{ $document->content['executive_summary'] ?? 'This document sets forth the business and technical scope for custom software services.' }}</p>
    </div>

    <!-- Scope list -->
    <div style="margin-bottom:32px; font-size:12px; line-height:1.6;">
        <h3 style="font-size:11px; font-weight:800; color:#1e293b; text-transform:uppercase; tracking-wider: 0.05em; margin-bottom:12px; padding-bottom:6px; border-bottom:1px solid #f1f5f9;">2. Scope of Services</h3>
        <ul style="list-style-type:disc; padding-left:20px; margin:0; display:flex; flex-direction:column; gap:8px; color:#475569;">
            @if(isset($document->content['scope']) && is_array($document->content['scope']))
                @foreach($document->content['scope'] as $scopeItem)
                    <li>{!! e($scopeItem) !!}</li>
                @endforeach
            @else
                <li>Integration of core software requirements as analyzed by AI.</li>
                <li>Design implementation and custom dashboards.</li>
            @endif
        </ul>
    </div>

    <!-- Milestones and Deliverables table -->
    <div style="margin-bottom:32px; font-size:12px;">
        <h3 style="font-size:11px; font-weight:800; color:#1e293b; text-transform:uppercase; tracking-wider: 0.05em; margin-bottom:12px; padding-bottom:6px; border-bottom:1px solid #f1f5f9;">3. Project Phases & Pricing</h3>
        <div style="border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; background:#fafbff;">
            <table class="data-table" style="margin:0; width:100%; border-collapse:collapse;">
                <thead>
                    <tr>
                        <th style="padding:12px 16px; background:#fafbff; color:#64748b; font-size:10px; font-weight:700; border-bottom:1px solid #e2e8f0; text-transform:uppercase;">Phase</th>
                        <th style="padding:12px 16px; background:#fafbff; color:#64748b; font-size:10px; font-weight:700; border-bottom:1px solid #e2e8f0; text-transform:uppercase;">Deliverables & Scope</th>
                        <th style="padding:12px 16px; background:#fafbff; color:#64748b; font-size:10px; font-weight:700; border-bottom:1px solid #e2e8f0; text-transform:uppercase;">Timeline</th>
                        <th style="padding:12px 16px; background:#fafbff; color:#64748b; font-size:10px; font-weight:700; border-bottom:1px solid #e2e8f0; text-transform:uppercase; text-align:right;">Cost</th>
                    </tr>
                </thead>
                <tbody style="background:#ffffff;">
                    @if(isset($document->content['milestones']) && is_array($document->content['milestones']))
                        @foreach($document->content['milestones'] as $milestone)
                            <tr>
                                <td style="padding:12px 16px; border-bottom:1px solid #f1f5f9; font-weight:700; color:#1e293b;">{{ $milestone['phase'] ?? '' }}</td>
                                <td style="padding:12px 16px; border-bottom:1px solid #f1f5f9; color:#475569;">{!! e($milestone['deliverables'] ?? '') !!}</td>
                                <td style="padding:12px 16px; border-bottom:1px solid #f1f5f9; color:#475569;">{{ $milestone['timeline'] ?? '' }}</td>
                                <td style="padding:12px 16px; border-bottom:1px solid #f1f5f9; text-align:right; font-weight:700; color:#1e293b;">{{ $milestone['cost'] ?? '' }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td style="padding:12px 16px; border-bottom:1px solid #f1f5f9; font-weight:700; color:#1e293b;">Phase 1</td>
                            <td style="padding:12px 16px; border-bottom:1px solid #f1f5f9; color:#475569;">Initial Analysis and Mockup Layouts</td>
                            <td style="padding:12px 16px; border-bottom:1px solid #f1f5f9; color:#475569;">2 Weeks</td>
                            <td style="padding:12px 16px; border-bottom:1px solid #f1f5f9; text-align:right; font-weight:700; color:#1e293b;">₹35,000</td>
                        </tr>
                        <tr>
                            <td style="padding:12px 16px; font-weight:700; color:#1e293b;">Phase 2</td>
                            <td style="padding:12px 16px; color:#475569;">Functional Integration and API builds</td>
                            <td style="padding:12px 16px; color:#475569;">4 Weeks</td>
                            <td style="padding:12px 16px; text-align:right; font-weight:700; color:#1e293b;">₹65,000</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Terms -->
    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:32px; font-size:11px; line-height:1.6; padding-top:24px; border-top:1px solid #f1f5f9;">
        <div>
            <h4 style="font-weight:800; color:#1e293b; margin-bottom:8px; text-transform:uppercase; letter-spacing:0.02em;">Payment Terms</h4>
            <ul style="list-style-type:disc; padding-left:16px; margin:0; display:flex; flex-direction:column; gap:6px; color:#475569;">
                @if(isset($document->content['payment_terms']) && is_array($document->content['payment_terms']))
                    @foreach($document->content['payment_terms'] as $term)
                        <li>{{ $term }}</li>
                    @endforeach
                @else
                    <li>50% Advance payout prior to project kick-off.</li>
                    <li>50% Milestones delivery payout.</li>
                @endif
            </ul>
        </div>

        <div>
            <h4 style="font-weight:800; color:#1e293b; margin-bottom:8px; text-transform:uppercase; letter-spacing:0.02em;">Terms & Conditions</h4>
            <ul style="list-style-type:disc; padding-left:16px; margin:0; display:flex; flex-direction:column; gap:6px; color:#475569;">
                @if(isset($document->content['terms_conditions']) && is_array($document->content['terms_conditions']))
                    @foreach($document->content['terms_conditions'] as $tc)
                        <li>{{ $tc }}</li>
                    @endforeach
                @else
                    <li>Delivery schedule depends on receiving requirements in timely manner.</li>
                    <li>Any add-on feature requests will be billed at standard hourly rates.</li>
                @endif
            </ul>
        </div>
    </div>

    <!-- Signatures block -->
    <div style="display:flex; flex-wrap:wrap; justify-content:space-between; align-items:flex-end; gap:24px; padding-top:64px; font-size:12px; text-align:center;">
        <div style="width:180px;">
            <div style="height:40px; display:flex; align-items:center; justify-content:center;">
                <span style="font-family:monospace; color:#94a3b8; font-style:italic; font-size:10px;">Digitally signed by Aura AI</span>
            </div>
            <div style="border-top:1px solid #cbd5e1; padding-top:6px; font-weight:700; color:#475569;">Authorized Signatory</div>
            <div style="font-size:10px; color:#94a3b8; margin-top:2px;">Aura AI Enterprise Ltd</div>
        </div>

        <div style="width:180px;">
            <div style="height:40px;"></div>
            <div style="border-top:1px solid #cbd5e1; padding-top:6px; font-weight:700; color:#475569;">Client Signature</div>
            <div style="font-size:10px; color:#94a3b8; margin-top:2px;">{{ $document->lead->company_name ?? 'Individual Customer' }}</div>
        </div>
    </div>

</div>

<!-- CSS `@media print` layout overrides -->
<style>
    @media print {
        body {
            background-color: white !important;
            color: black !important;
        }
        main {
            background: white !important;
            padding: 0 !important;
            overflow: visible !important;
        }
        aside, header, footer, .print\:hidden, nav, button, .btn {
            display: none !important;
        }
        .card {
            background: white !important;
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            margin: 0 !important;
        }
    }
</style>
@endsection
