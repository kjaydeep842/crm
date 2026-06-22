@extends('layouts.app')

@section('header_title')
    View Document: {{ $document->document_number }}
@endsection

@section('content')
<!-- Back link and Print Actions -->
<div class="flex items-center justify-between mb-8 print:hidden">
    <a href="{{ route('documents.index') }}" class="text-xs font-bold text-slate-400 hover:text-slate-200 transition-colors flex items-center gap-1.5">
        <i class="fa-solid fa-arrow-left"></i>
        <span>Back to Registry</span>
    </a>
    
    <button onclick="window.print()" class="bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-500 hover:to-teal-400 text-white px-5 py-2.5 rounded-xl text-xs font-bold shadow-lg shadow-emerald-600/15 border border-emerald-450/20 flex items-center gap-2">
        <i class="fa-solid fa-print"></i>
        <span>Print or Save PDF</span>
    </button>
</div>

<!-- Premium Print-ready Document Container -->
<div class="glass-card rounded-3xl p-8 md:p-12 max-w-4xl mx-auto bg-white text-slate-800 shadow-2xl print:shadow-none print:bg-white print:p-0 print:border-none border border-slate-200/50 print:text-black">
    
    <!-- Header Block -->
    <div class="flex flex-col md:flex-row justify-between items-start gap-6 border-b border-slate-200 pb-8 mb-8">
        <div>
            <!-- Branding -->
            <div class="flex items-center gap-2.5 mb-4">
                <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center shadow shadow-indigo-600/30">
                    <i class="fa-solid fa-cube text-white text-sm"></i>
                </div>
                <span class="font-extrabold text-base tracking-tight text-slate-900">Aura AI Enterprise</span>
            </div>
            <span class="text-xs text-slate-500 block">Unit 4B, Cyber Towers, Phase II</span>
            <span class="text-xs text-slate-500 block">HITEC City, Hyderabad, 500081</span>
            <span class="text-xs text-slate-500 block">finance@auracrm.com | +91 40 44882200</span>
        </div>

        <div class="text-left md:text-right">
            <h1 class="text-2xl font-black text-slate-900 tracking-tight uppercase mb-1">{{ $document->type }}</h1>
            <span class="text-xs text-slate-500 font-bold block">NO: <span class="font-mono text-slate-800">{{ $document->document_number }}</span></span>
            <span class="text-xs text-slate-500 block mt-1">Date: {{ $document->created_at->format('d M, Y') }}</span>
        </div>
    </div>

    <!-- Client Address Blocks -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 text-xs">
        <div>
            <span class="text-[10px] text-slate-400 uppercase font-bold block mb-1.5">Prepared For:</span>
            <span class="font-extrabold text-slate-900 text-sm block">{{ $document->lead->full_name ?? 'N/A' }}</span>
            <span class="text-slate-700 block mt-1 font-semibold">{{ $document->lead->company_name ?? 'Individual Client' }}</span>
            <span class="text-slate-500 block mt-1">{{ $document->lead->email ?? '' }}</span>
            <span class="text-slate-500 block">{{ $document->lead->mobile ?? '' }}</span>
        </div>

        <div class="text-left md:text-right">
            <span class="text-[10px] text-slate-400 uppercase font-bold block mb-1.5">Commercial Terms:</span>
            <span class="text-slate-700 block">Payment Method: Bank Transfer / UPI</span>
            <span class="text-slate-700 block mt-1">Due Date: Net 15 Days</span>
            <span class="text-slate-900 font-extrabold text-sm block mt-2">Valued At: ₹{{ number_format($document->amount, 2) }}</span>
        </div>
    </div>

    <!-- Executive Summary -->
    <div class="mb-8 text-xs leading-relaxed">
        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-2 pb-1.5 border-b border-slate-100">1. Executive Summary</h3>
        <p class="text-slate-650">{{ $document->content['executive_summary'] ?? 'This document sets forth the business and technical scope for custom software services.' }}</p>
    </div>

    <!-- Scope list -->
    <div class="mb-8 text-xs leading-relaxed">
        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3 pb-1.5 border-b border-slate-100">2. Scope of Services</h3>
        <ul class="list-disc pl-5 space-y-2 text-slate-650">
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
    <div class="mb-8 text-xs">
        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3 pb-1.5 border-b border-slate-100">3. Project Phases & Pricing</h3>
        <table class="w-full text-left border-collapse border border-slate-200">
            <thead>
                <tr class="bg-slate-50 text-slate-800 font-bold border-b border-slate-200 text-[10px] uppercase">
                    <th class="p-3 border-r border-slate-200">Phase</th>
                    <th class="p-3 border-r border-slate-200">Deliverables & Scope</th>
                    <th class="p-3 border-r border-slate-200">Timeline</th>
                    <th class="p-3 text-right">Cost</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 text-slate-650">
                @if(isset($document->content['milestones']) && is_array($document->content['milestones']))
                    @foreach($document->content['milestones'] as $milestone)
                        <tr>
                            <td class="p-3 border-r border-slate-200 font-bold text-slate-800">{{ $milestone['phase'] ?? '' }}</td>
                            <td class="p-3 border-r border-slate-200">{!! e($milestone['deliverables'] ?? '') !!}</td>
                            <td class="p-3 border-r border-slate-200">{{ $milestone['timeline'] ?? '' }}</td>
                            <td class="p-3 text-right font-semibold text-slate-800">{{ $milestone['cost'] ?? '' }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="p-3 border-r border-slate-200 font-bold">Phase 1</td>
                        <td class="p-3 border-r border-slate-200">Initial Analysis and Mockup Layouts</td>
                        <td class="p-3 border-r border-slate-200">2 Weeks</td>
                        <td class="p-3 text-right font-semibold">₹35,000</td>
                    </tr>
                    <tr>
                        <td class="p-3 border-r border-slate-200 font-bold">Phase 2</td>
                        <td class="p-3 border-r border-slate-200">Functional Integration and API builds</td>
                        <td class="p-3 border-r border-slate-200">4 Weeks</td>
                        <td class="p-3 text-right font-semibold">₹65,000</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Terms -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-[11px] leading-relaxed pt-6 border-t border-slate-200">
        <div>
            <h4 class="font-bold text-slate-900 mb-1.5 uppercase tracking-wider">Payment Terms</h4>
            <ul class="list-disc pl-4 space-y-1 text-slate-600">
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
            <h4 class="font-bold text-slate-900 mb-1.5 uppercase tracking-wider">Terms & Conditions</h4>
            <ul class="list-disc pl-4 space-y-1 text-slate-600">
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
    <div class="flex justify-between items-end gap-12 pt-16 text-xs text-center">
        <div class="w-44">
            <div class="h-10 flex items-center justify-center">
                <span class="font-mono text-slate-400 italic text-[10px]">Digitally signed by Aura AI</span>
            </div>
            <div class="border-t border-slate-300 pt-1.5 font-bold text-slate-700">Authorized Signatory</div>
            <div class="text-[10px] text-slate-400 mt-0.5">Aura AI Enterprise Ltd</div>
        </div>

        <div class="w-44">
            <div class="h-10"></div>
            <div class="border-t border-slate-300 pt-1.5 font-bold text-slate-700">Client Signature</div>
            <div class="text-[10px] text-slate-400 mt-0.5">{{ $document->lead->company_name ?? 'Individual Customer' }}</div>
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
        aside, header, footer, .print\:hidden, nav, button {
            display: none !important;
        }
        .glass-card {
            background: white !important;
            border: none !important;
            backdrop-filter: none !important;
            padding: 0 !important;
            margin: 0 !important;
        }
    }
</style>
@endsection
