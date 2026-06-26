<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Invoice #{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 12px;
        color: #1e293b;
        background: #ffffff;
    }
    a { color: inherit; text-decoration: none; }

    /* ── Header ── */
    .header-table {
        width: 100%;
        background-color: #312e81;
        padding: 0;
    }
    .header-inner {
        width: 100%;
        padding: 32px 40px;
    }
    .brand-name {
        font-size: 22px;
        font-weight: 900;
        color: #ffffff;
        letter-spacing: -0.5px;
    }
    .brand-sub {
        font-size: 10px;
        color: #a5b4fc;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-top: 2px;
    }
    .brand-contact {
        font-size: 10px;
        color: #c7d2fe;
        margin-top: 6px;
        line-height: 1.6;
    }
    .invoice-label {
        font-size: 10px;
        color: #a5b4fc;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-bottom: 4px;
    }
    .invoice-number {
        font-size: 22px;
        font-weight: 900;
        color: #ffffff;
    }
    .invoice-meta {
        font-size: 10px;
        color: #c7d2fe;
        margin-top: 4px;
        line-height: 1.7;
    }

    /* ── Status Strip ── */
    .status-strip {
        width: 100%;
        background-color: #4ade80;
        text-align: center;
        padding: 7px 0;
        font-size: 11px;
        font-weight: 700;
        color: #14532d;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }
    .status-strip-pending {
        background-color: #fde68a;
        color: #78350f;
    }

    /* ── Body ── */
    .body-wrap {
        padding: 32px 40px;
    }

    /* ── Bill Info ── */
    .section-title {
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: #94a3b8;
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 6px;
        margin-bottom: 12px;
    }
    .bill-table {
        width: 100%;
        margin-bottom: 28px;
    }
    .bill-cell {
        width: 50%;
        vertical-align: top;
        padding-right: 16px;
    }
    .bill-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 16px;
    }
    .bill-company {
        font-size: 15px;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 8px;
    }
    .bill-field {
        margin-bottom: 5px;
    }
    .bill-key {
        font-size: 9px;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: block;
    }
    .bill-val {
        font-size: 12px;
        font-weight: 600;
        color: #334155;
    }
    .status-success {
        color: #059669;
        font-weight: 800;
        font-size: 12px;
    }
    .status-pending { color: #d97706; font-weight: 800; }
    .status-failed  { color: #dc2626; font-weight: 800; }

    /* ── Items Table ── */
    .items-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 24px;
    }
    .items-table thead tr {
        background-color: #312e81;
        color: #ffffff;
    }
    .items-table thead th {
        padding: 11px 14px;
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        text-align: left;
    }
    .items-table thead th.right { text-align: right; }
    .items-table tbody tr:nth-child(even) {
        background-color: #f8fafc;
    }
    .items-table tbody td {
        padding: 13px 14px;
        font-size: 12px;
        color: #1e293b;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: top;
    }
    .items-table tbody td.right {
        text-align: right;
        font-weight: 700;
    }
    .item-name { font-weight: 700; color: #1e293b; margin-bottom: 3px; }
    .item-desc { font-size: 10px; color: #64748b; line-height: 1.5; }
    .item-features { font-size: 10px; color: #94a3b8; margin-top: 3px; }

    /* ── Totals ── */
    .totals-table {
        width: 260px;
        margin-left: auto;
        margin-bottom: 24px;
    }
    .totals-row td {
        padding: 7px 0;
        font-size: 12px;
        border-bottom: 1px solid #f1f5f9;
    }
    .totals-label { color: #64748b; }
    .totals-value { text-align: right; font-weight: 600; color: #1e293b; }
    .totals-final {
        background-color: #312e81;
        border-radius: 8px;
        margin-top: 6px;
    }
    .totals-final td {
        padding: 12px 14px;
        color: #ffffff;
        border: none;
    }
    .totals-final .totals-label { color: #c7d2fe; font-weight: 700; font-size: 12px; }
    .totals-final .totals-value { color: #ffffff; font-size: 18px; font-weight: 900; }

    /* ── Payment Confirmation ── */
    .confirm-box {
        background-color: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 24px;
    }
    .confirm-title { font-size: 12px; font-weight: 700; color: #15803d; margin-bottom: 4px; }
    .confirm-sub { font-size: 10px; color: #166534; margin-bottom: 6px; }
    .id-pill {
        display: inline-block;
        background: #dcfce7;
        border: 1px solid #bbf7d0;
        color: #15803d;
        font-size: 10px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 4px;
        margin-right: 6px;
        font-family: DejaVu Sans Mono, monospace;
    }

    /* ── Notes ── */
    .notes-box {
        background: #fefce8;
        border: 1px solid #fde68a;
        border-radius: 8px;
        padding: 14px;
        font-size: 11px;
        color: #78350f;
        margin-bottom: 24px;
    }

    /* ── Footer ── */
    .footer-line {
        border-top: 1px solid #e2e8f0;
        margin: 0 40px;
    }
    .footer-table {
        width: 100%;
        padding: 16px 40px;
    }
    .footer-left {
        font-size: 10px;
        color: #94a3b8;
        line-height: 1.7;
    }
    .footer-right {
        text-align: right;
        font-size: 10px;
        color: #6366f1;
        font-weight: 700;
    }

    /* ── Page number ── */
    .page-num {
        text-align: center;
        font-size: 9px;
        color: #cbd5e1;
        padding-bottom: 8px;
    }
</style>
</head>
<body>

{{-- ════ HEADER ════ --}}
<table class="header-table" width="100%" cellpadding="0" cellspacing="0">
<tr>
<td class="header-inner">
    <table width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td width="60%" valign="top">
            <div class="brand-name">&#9670; DevineSkyCRM</div>
            <div class="brand-sub">Intelligent Sales Automation Platform</div>
            <div class="brand-contact">
                support@devinesky.com &nbsp;|&nbsp; www.devinesky.com
            </div>
        </td>
        <td width="40%" valign="top" align="right">
            <div class="invoice-label">Tax Invoice</div>
            <div class="invoice-number">#{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</div>
            <div class="invoice-meta">
                Issued: {{ $payment->created_at->format('d M Y') }}<br>
                @if($payment->paid_at)
                Paid On: {{ $payment->paid_at->format('d M Y, h:i A') }}<br>
                @endif
                @if($payment->current_period_end)
                Valid Until: {{ $payment->current_period_end->format('d M Y') }}
                @endif
            </div>
        </td>
    </tr>
    </table>
</td>
</tr>
</table>

{{-- ════ STATUS STRIP ════ --}}
@if($payment->status === 'success')
<div class="status-strip">&#10003; &nbsp; Payment Confirmed &nbsp; — &nbsp; This is an official invoice</div>
@elseif($payment->status === 'pending')
<div class="status-strip status-strip-pending">&#9679; &nbsp; Payment Pending &nbsp; — &nbsp; Pro-forma Invoice</div>
@endif

{{-- ════ BODY ════ --}}
<div class="body-wrap">

    {{-- Bill To + Payment Summary --}}
    <table class="bill-table" cellpadding="0" cellspacing="0">
    <tr>
        <td class="bill-cell" style="padding-right:12px;">
            <div class="bill-box">
                <div class="section-title">&#9632; Bill To</div>
                <div class="bill-company">{{ $org->name }}</div>
                @if($org->address)
                <div class="bill-field">
                    <span class="bill-key">Address</span>
                    <span class="bill-val">{{ $org->address }}</span>
                </div>
                @endif
                @if($org->phone)
                <div class="bill-field">
                    <span class="bill-key">Phone</span>
                    <span class="bill-val">{{ $org->phone }}</span>
                </div>
                @endif
                <div class="bill-field">
                    <span class="bill-key">Contact Person</span>
                    <span class="bill-val">{{ $payment->user->name }}</span>
                </div>
                <div class="bill-field">
                    <span class="bill-key">Email</span>
                    <span class="bill-val">{{ $payment->user->email }}</span>
                </div>
            </div>
        </td>
        <td class="bill-cell" style="padding-left:12px; padding-right:0;">
            <div class="bill-box">
                <div class="section-title">&#9632; Invoice Details</div>
                <div class="bill-field">
                    <span class="bill-key">Invoice No.</span>
                    <span class="bill-val">#{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="bill-field">
                    <span class="bill-key">Plan</span>
                    <span class="bill-val">{{ ucfirst($payment->plan) }} Plan</span>
                </div>
                <div class="bill-field">
                    <span class="bill-key">Payment Type</span>
                    <span class="bill-val">{{ ucfirst(str_replace('_', '-', $payment->type)) }}</span>
                </div>
                <div class="bill-field">
                    <span class="bill-key">Currency</span>
                    <span class="bill-val">{{ $payment->currency }} (Indian Rupee)</span>
                </div>
                <div class="bill-field">
                    <span class="bill-key">Status</span>
                    <span class="@if($payment->status==='success') status-success @elseif($payment->status==='failed') status-failed @else status-pending @endif">
                        @if($payment->status==='success') &#10003; PAID
                        @elseif($payment->status==='failed') &#10007; FAILED
                        @else &#9679; PENDING
                        @endif
                    </span>
                </div>
            </div>
        </td>
    </tr>
    </table>

    {{-- Line Items --}}
    <div class="section-title" style="margin-bottom:10px;">&#9632; Service Details</div>
    <table class="items-table" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="50%">Description</th>
                <th width="15%">Plan</th>
                <th width="15%">Period</th>
                <th class="right" width="15%">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>01</td>
                <td>
                    <div class="item-name">DevineSkyCRM &mdash; {{ ucfirst($payment->plan) }} Plan</div>
                    <div class="item-desc">
                        {{ $payment->type === 'subscription' ? 'Monthly Subscription — Auto-renewal enabled' : 'One-Time Payment — Full month access included' }}
                    </div>
                    <div class="item-features">
                        Includes: @php
                            $features = [
                                'starter'      => '5 Users · 1,000 AI Credits · WhatsApp Inbox · Lead Management · Email Support',
                                'professional' => '15 Users · 10,000 AI Credits · Advanced AI Analysis · Document Generator · Priority Support',
                                'business'     => '50 Users · 50,000 AI Credits · Custom Reports · Team Productivity · Dedicated Support',
                                'enterprise'   => 'Unlimited Users · Unlimited AI Credits · Custom Integrations · SLA · Dedicated Manager',
                            ];
                            echo $features[$payment->plan] ?? 'Full CRM Platform Access';
                        @endphp
                    </div>
                </td>
                <td>{{ ucfirst($payment->plan) }}</td>
                <td>1 Month</td>
                <td class="right">{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Totals --}}
    <table class="totals-table" cellpadding="0" cellspacing="0">
        <tr class="totals-row">
            <td class="totals-label">Subtotal</td>
            <td class="totals-value">{{ $payment->currency }} {{ number_format($payment->amount * 0.847, 2) }}</td>
        </tr>
        <tr class="totals-row">
            <td class="totals-label">GST @ 18%</td>
            <td class="totals-value">{{ $payment->currency }} {{ number_format($payment->amount * 0.153, 2) }}</td>
        </tr>
        <tr class="totals-row">
            <td class="totals-label">Discount</td>
            <td class="totals-value" style="color:#059669;">&#8212; {{ $payment->currency }} 0.00</td>
        </tr>
        <tr>
            <td colspan="2">
                <table class="totals-final" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="totals-label">Total Amount Paid</td>
                    <td class="totals-value">{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</td>
                </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- Payment Confirmation Box --}}
    @if($payment->status === 'success')
    <div class="confirm-box">
        <div class="confirm-title">&#10003; Payment Successfully Processed via Razorpay</div>
        <div class="confirm-sub">This is a computer-generated invoice. No signature required. For disputes, contact support@devinesky.com</div>
        @if($payment->razorpay_payment_id)
            <span class="id-pill">Payment ID: {{ $payment->razorpay_payment_id }}</span>
        @endif
        @if($payment->razorpay_order_id)
            <span class="id-pill">Order ID: {{ $payment->razorpay_order_id }}</span>
        @endif
    </div>
    @endif

    {{-- Terms --}}
    <div class="notes-box">
        <strong>Terms &amp; Notes:</strong> &nbsp;
        This invoice is valid for the stated billing period. Subscription plans auto-renew monthly unless cancelled before the renewal date.
        For refunds or cancellations, please contact support within 7 days of the billing date. All prices are inclusive of applicable taxes.
    </div>

</div>{{-- /body-wrap --}}

{{-- ════ FOOTER ════ --}}
<div class="footer-line"></div>
<table class="footer-table" width="100%" cellpadding="0" cellspacing="0">
<tr>
    <td class="footer-left">
        DevineSkyCRM &nbsp;|&nbsp; support@devinesky.com &nbsp;|&nbsp; www.devinesky.com<br>
        This is a system-generated invoice. &copy; {{ date('Y') }} DevineSkyCRM. All rights reserved.
    </td>
    <td class="footer-right">
        Invoice #{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}<br>
        Generated: {{ now()->format('d M Y') }}
    </td>
</tr>
</table>
<div class="page-num">Page 1 of 1</div>

</body>
</html>
