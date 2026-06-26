@extends('layouts.app')
@section('header_title', 'Payment History')

@section('styles')
<style>
    .billing-summary-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 16px;
        margin-bottom: 24px;
    }
    .history-row {
        padding: 16px 20px;
        border-bottom: 1px solid #f8fafc;
        display: flex;
        align-items: center;
        gap: 16px;
    }
    @media (max-width: 768px) {
        .billing-summary-grid {
            grid-template-columns: 1fr;
        }
    }
    @media (max-width: 576px) {
        .history-row {
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
        }
        .history-row > div:last-child {
            text-align: left !important;
            border-top: 1px dashed #e2e8f0;
            padding-top: 8px;
        }
    }
</style>
@endsection

@section('content')
<div style="max-width:900px; margin:0 auto;">

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <div>
            <h1 style="font-size:22px; font-weight:800; color:#1e293b; margin-bottom:4px;">💳 Payment History</h1>
            <p style="color:#64748b; font-size:13px;">All transactions for your organization.</p>
        </div>
        <a href="{{ route('settings.organization') }}?tab=billing" style="padding:10px 18px; background:#f1f5f9; color:#475569; border:1px solid #e2e8f0; border-radius:8px; font-size:13px; font-weight:600; text-decoration:none;">
            ← Back to Billing
        </a>
    </div>

    {{-- Summary Cards --}}
    @php
        $successful = $payments->where('status', 'success');
        $totalPaid  = $successful->sum('amount');
    @endphp
    <div class="billing-summary-grid">
        <div style="background:#fff; border:1px solid #e2e8f0; border-radius:14px; padding:20px;">
            <p style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#94a3b8; margin-bottom:8px;">Total Paid</p>
            <p style="font-size:24px; font-weight:800; color:#1e293b;">₹{{ number_format($totalPaid, 2) }}</p>
        </div>
        <div style="background:#fff; border:1px solid #e2e8f0; border-radius:14px; padding:20px;">
            <p style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#94a3b8; margin-bottom:8px;">Successful Payments</p>
            <p style="font-size:24px; font-weight:800; color:#059669;">{{ $payments->where('status', 'success')->count() }}</p>
        </div>
        <div style="background:#fff; border:1px solid #e2e8f0; border-radius:14px; padding:20px;">
            <p style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#94a3b8; margin-bottom:8px;">Total Transactions</p>
            <p style="font-size:24px; font-weight:800; color:#6366f1;">{{ $payments->total() }}</p>
        </div>
    </div>

    {{-- Transactions Table --}}
    <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden;">
        <div style="padding:16px 20px; border-bottom:1px solid #f1f5f9; background:#fafbff;">
            <h3 style="font-size:14px; font-weight:700; color:#1e293b;">All Transactions</h3>
        </div>

        @forelse($payments as $payment)
            @php
                $statusColors = [
                    'success' => ['bg'=>'#f0fdf4','color'=>'#15803d','text'=>'✅ Success'],
                    'pending' => ['bg'=>'#fefce8','color'=>'#a16207','text'=>'⏳ Pending'],
                    'failed'  => ['bg'=>'#fff1f2','color'=>'#be123c','text'=>'❌ Failed'],
                    'refunded'=> ['bg'=>'#f1f5f9','color'=>'#475569','text'=>'↩️ Refunded'],
                ];
                $s = $statusColors[$payment->status] ?? $statusColors['pending'];
            @endphp
            <div class="history-row">
                <div style="width:40px; height:40px; border-radius:10px; background:{{ $s['bg'] }}; display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0;">
                    {{ $payment->type === 'subscription' ? '🔄' : '💳' }}
                </div>
                <div style="flex:1;">
                    <div style="font-size:13px; font-weight:700; color:#1e293b; margin-bottom:3px;">
                        {{ ucfirst($payment->plan) }} Plan — {{ ucfirst(str_replace('_', '-', $payment->type)) }}
                    </div>
                    <div style="font-size:11px; color:#94a3b8;">
                        {{ $payment->created_at->format('d M Y, h:i A') }}
                        @if($payment->razorpay_payment_id)
                            &bull; ID: {{ $payment->razorpay_payment_id }}
                        @endif
                        @if($payment->razorpay_order_id)
                            &bull; Order: {{ $payment->razorpay_order_id }}
                        @endif
                    </div>
                </div>
                <div style="text-align:right;">
                    <div style="font-size:16px; font-weight:800; color:#1e293b; margin-bottom:6px;">
                        ₹{{ number_format($payment->amount, 2) }}
                    </div>
                    <span style="background:{{ $s['bg'] }}; color:{{ $s['color'] }}; padding:3px 8px; border-radius:6px; font-size:10px; font-weight:700; display:block; margin-bottom:8px;">
                        {{ $s['text'] }}
                    </span>
                    @if($payment->status === 'success')
                    <a href="{{ route('billing.invoice', $payment->id) }}"
                       style="display:inline-flex; align-items:center; gap:5px; padding:6px 12px; background:linear-gradient(135deg,#6366f1,#06b6d4); color:#fff; border-radius:7px; font-size:11px; font-weight:700; text-decoration:none; transition:opacity 0.2s;"
                       onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
                        ⬇ Invoice PDF
                    </a>
                    @endif
                </div>
            </div>
        @empty
            <div style="padding:60px 20px; text-align:center; color:#94a3b8;">
                <div style="font-size:40px; margin-bottom:12px;">💳</div>
                <p style="font-size:14px; font-weight:600; margin-bottom:6px;">No transactions yet</p>
                <p style="font-size:13px;">Upgrade your plan to see payment history here.</p>
                <a href="{{ route('settings.organization') }}" style="display:inline-block; margin-top:16px; padding:10px 20px; background:#6366f1; color:#fff; border-radius:8px; font-size:13px; font-weight:600; text-decoration:none;">
                    View Plans →
                </a>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($payments->hasPages())
        <div style="margin-top:16px;">
            {{ $payments->links() }}
        </div>
    @endif

</div>
@endsection
