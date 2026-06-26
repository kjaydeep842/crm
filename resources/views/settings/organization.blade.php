@extends('layouts.app')
@section('header_title', 'Organization Settings')

@section('styles')
<style>
    .settings-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }
    .package-info-container {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .template-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }
    .billing-kpi-card {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .plans-layout-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 16px;
        margin-bottom: 20px;
    }
    .enterprise-card {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fff7ed;
        border: 1px solid #fed7aa;
        border-radius: 12px;
        padding: 20px;
    }

    @media (max-width: 900px) {
        .plans-layout-grid {
            grid-template-columns: 1fr;
        }
    }
    @media (max-width: 600px) {
        .settings-grid-2 {
            grid-template-columns: 1fr;
        }
        .package-info-container {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }
        .package-info-container > a {
            margin-left: 0 !important;
            width: 100%;
            text-align: center;
        }
        .billing-kpi-card {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }
        .billing-kpi-card > a {
            width: 100%;
            text-align: center;
        }
    }
    @media (max-width: 576px) {
        .template-row {
            flex-direction: column;
            gap: 12px;
        }
        .template-row > form {
            width: 100%;
        }
        .template-row button {
            width: 100%;
            text-align: center;
        }
        .enterprise-card {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }
        .enterprise-card > a {
            width: 100%;
            text-align: center;
        }
    }
</style>
@endsection

@section('content')
<div style="max-width:900px; margin:0 auto;">

    {{-- Header --}}
    <div style="margin-bottom:24px;">
        <h1 style="font-size:22px; font-weight:800; color:#1e293b; margin-bottom:4px;">⚙️ Organization Settings</h1>
        <p style="color:#64748b; font-size:13px;">Manage your company profile, email templates, and view activity logs.</p>
    </div>

    @if(session('success'))
        <div style="background:#f0fdf4; border:1px solid #bbf7d0; color:#15803d; padding:12px 16px; border-radius:10px; margin-bottom:20px; font-size:13px; font-weight:600;">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- Tabs --}}
    <div style="display:flex; flex-wrap:wrap; gap:4px; margin-bottom:24px; background:#f1f5f9; padding:4px; border-radius:12px; width:fit-content;">
        <button onclick="switchTab('profile')" id="tab-profile" class="settings-tab active-tab" style="padding:8px 16px; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer;">🏢 Company Profile</button>
        <button onclick="switchTab('templates')" id="tab-templates" class="settings-tab" style="padding:8px 16px; border:none; background:transparent; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; color:#64748b;">📧 Email Templates</button>
        <button onclick="switchTab('activity')" id="tab-activity" class="settings-tab" style="padding:8px 16px; border:none; background:transparent; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; color:#64748b;">📋 Activity Log</button>
        <button onclick="switchTab('billing')" id="tab-billing" class="settings-tab" style="padding:8px 16px; border:none; background:transparent; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; color:#64748b;">💳 Plan & Billing</button>
    </div>

    {{-- PROFILE TAB --}}
    <div id="panel-profile">
        <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:28px;">
            <h3 style="font-size:15px; font-weight:700; margin-bottom:20px; color:#1e293b;">Company Information</h3>
            <form action="{{ route('settings.organization.update') }}" method="POST">
                @csrf
                <div class="settings-grid-2">
                    <div>
                        <label style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#64748b; display:block; margin-bottom:6px;">Company Name *</label>
                        <input name="name" value="{{ $org->name }}" required style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:8px; font-size:13px; outline:none;" onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    <div>
                        <label style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#64748b; display:block; margin-bottom:6px;">Phone</label>
                        <input name="phone" value="{{ $org->phone }}" style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:8px; font-size:13px; outline:none;" onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    <div>
                        <label style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#64748b; display:block; margin-bottom:6px;">Website</label>
                        <input name="website" value="{{ $org->website }}" placeholder="https://yourcompany.com" style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:8px; font-size:13px; outline:none;" onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    <div>
                        <label style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#64748b; display:block; margin-bottom:6px;">Currency</label>
                        <select name="currency" style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:8px; font-size:13px; outline:none;">
                            @foreach(['INR'=>'₹ INR (Indian Rupee)','USD'=>'$ USD (US Dollar)','EUR'=>'€ EUR (Euro)','GBP'=>'£ GBP (British Pound)','AED'=>'AED (UAE Dirham)'] as $code=>$label)
                                <option value="{{ $code }}" {{ $org->currency == $code ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#64748b; display:block; margin-bottom:6px;">Timezone</label>
                        <select name="timezone" style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:8px; font-size:13px; outline:none;">
                            @foreach(['Asia/Kolkata'=>'Asia/Kolkata (IST)','Asia/Dubai'=>'Asia/Dubai (GST)','America/New_York'=>'America/New_York (EST)','America/Los_Angeles'=>'America/LA (PST)','Europe/London'=>'Europe/London (GMT)'] as $tz=>$label)
                                <option value="{{ $tz }}" {{ $org->timezone == $tz ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="grid-column:1/-1;">
                        <label style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#64748b; display:block; margin-bottom:6px;">Business Address</label>
                        <textarea name="address" rows="2" style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:8px; font-size:13px; outline:none; resize:none;" onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#e2e8f0'">{{ $org->address }}</textarea>
                    </div>
                </div>
                <div style="margin-top:20px; display:flex; justify-content:flex-end;">
                    <button type="submit" style="padding:10px 24px; background:linear-gradient(135deg,#6366f1,#06b6d4); color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:700; cursor:pointer;">
                        💾 Save Changes
                    </button>
                </div>
            </form>
        </div>

        {{-- Package Info --}}
        <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:28px; margin-top:20px;">
            <h3 style="font-size:15px; font-weight:700; margin-bottom:16px; color:#1e293b;">📦 Current Package</h3>
            <div class="package-info-container">
                <div style="padding:10px 20px; background:linear-gradient(135deg,#6366f1,#06b6d4); color:#fff; border-radius:10px; font-size:14px; font-weight:800; text-transform:uppercase;">
                    {{ $org->package ?? 'starter' }}
                </div>
                <div>
                    <p style="font-size:13px; color:#1e293b; font-weight:600;">AI Credits: {{ number_format($org->ai_credits_used) }} / {{ number_format($org->ai_credit_limit) }} used</p>
                    <p style="font-size:12px; color:#64748b;">Team Members: {{ $org->users()->count() }} / {{ $org->max_users }}</p>
                </div>
                <a href="{{ route('public.pricing') }}" style="margin-left:auto; padding:10px 20px; background:#f0fdf4; color:#059669; border:1px solid #bbf7d0; border-radius:8px; font-size:13px; font-weight:600; text-decoration:none;">
                    ⚡ Upgrade Plan
                </a>
            </div>
        </div>
    </div>

    {{-- EMAIL TEMPLATES TAB --}}
    <div id="panel-templates" style="display:none;">
        <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:28px; margin-bottom:20px;">
            <h3 style="font-size:15px; font-weight:700; margin-bottom:20px; color:#1e293b;">➕ Create Email Template</h3>
            <form action="{{ route('settings.email-templates.store') }}" method="POST">
                @csrf
                <div class="settings-grid-2" style="margin-bottom:16px;">
                    <div>
                        <label style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#64748b; display:block; margin-bottom:6px;">Template Name</label>
                        <input name="name" required placeholder="e.g. Welcome Email" style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:8px; font-size:13px; outline:none;" onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    <div>
                        <label style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#64748b; display:block; margin-bottom:6px;">Category</label>
                        <select name="category" style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:8px; font-size:13px; outline:none;">
                            <option value="general">General</option>
                            <option value="follow_up">Follow Up</option>
                            <option value="proposal">Proposal</option>
                            <option value="welcome">Welcome</option>
                            <option value="reminder">Reminder</option>
                        </select>
                    </div>
                    <div style="grid-column:1/-1;">
                        <label style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#64748b; display:block; margin-bottom:6px;">Email Subject</label>
                        <input name="subject" required placeholder="e.g. Thank you for your inquiry!" style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:8px; font-size:13px; outline:none;" onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    <div style="grid-column:1/-1;">
                        <label style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#64748b; display:block; margin-bottom:6px;">Email Body</label>
                        <textarea name="body" rows="5" required placeholder="Hi @{{lead_name}}, Thank you for your interest..." style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:8px; font-size:13px; outline:none; resize:vertical; font-family:inherit;" onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#e2e8f0'"></textarea>
                        <p style="font-size:10px; color:#94a3b8; margin-top:4px;">Variables: &#123;&#123;lead_name&#125;&#125;, &#123;&#123;company_name&#125;&#125;, &#123;&#123;phone&#125;&#125;, &#123;&#123;email&#125;&#125;, &#123;&#123;your_company&#125;&#125;</p>
                    </div>
                </div>
                <div style="display:flex; justify-content:flex-end;">
                    <button type="submit" style="padding:10px 24px; background:linear-gradient(135deg,#6366f1,#06b6d4); color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:700; cursor:pointer;">
                        💾 Save Template
                    </button>
                </div>
            </form>
        </div>

        <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:28px;">
            <h3 style="font-size:15px; font-weight:700; margin-bottom:16px; color:#1e293b;">📋 Saved Templates ({{ $emailTemplates->count() }})</h3>
            @forelse($emailTemplates as $tpl)
                <div class="template-row" style="padding:16px; border:1px solid #e2e8f0; border-radius:10px; margin-bottom:10px;">
                    <div>
                        <div style="font-size:13px; font-weight:700; color:#1e293b; margin-bottom:4px;">{{ $tpl->name }}</div>
                        <div style="font-size:11px; color:#6366f1; font-weight:600; text-transform:uppercase; margin-bottom:4px;">{{ str_replace('_', ' ', $tpl->category) }}</div>
                        <div style="font-size:12px; color:#64748b;">Subject: {{ $tpl->subject }}</div>
                    </div>
                    <form action="{{ route('settings.email-templates.destroy', $tpl->id) }}" method="POST" onsubmit="return confirm('Delete this template?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background:#fff1f2; color:#be123c; border:1px solid #fecdd3; padding:6px 12px; border-radius:6px; font-size:11px; font-weight:600; cursor:pointer;">🗑 Delete</button>
                    </form>
                </div>
            @empty
                <p style="color:#94a3b8; font-size:13px; text-align:center; padding:20px;">No templates yet. Create one above!</p>
            @endforelse
        </div>
    </div>

    {{-- ACTIVITY LOG TAB --}}
    <div id="panel-activity" style="display:none;">
        <div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:28px;">
            <h3 style="font-size:15px; font-weight:700; margin-bottom:16px; color:#1e293b;">📋 Recent Activity (Last 50 Actions)</h3>
            @forelse($activityLogs as $log)
                <div style="padding:12px 0; border-bottom:1px solid #f1f5f9; display:flex; align-items:flex-start; gap:12px;">
                    <div style="width:32px; height:32px; border-radius:50%; background:{{ $log->action == 'created' ? '#dcfce7' : ($log->action == 'deleted' ? '#fff1f2' : '#eef2ff') }}; display:flex; align-items:center; justify-content:center; font-size:14px; flex-shrink:0;">
                        {{ $log->action == 'created' ? '✅' : ($log->action == 'deleted' ? '🗑' : ($log->action == 'ai_triggered' ? '🤖' : '✏️')) }}
                    </div>
                    <div style="flex:1;">
                        <p style="font-size:13px; color:#1e293b; margin-bottom:2px;">{{ $log->description }}</p>
                        <p style="font-size:11px; color:#94a3b8;">
                            by {{ $log->user?->name ?? 'System' }} &bull;
                            {{ $log->created_at->diffForHumans() }}
                            @if($log->ip_address) &bull; IP: {{ $log->ip_address }} @endif
                        </p>
                    </div>
                    @if($log->entity_type)
                        <span style="background:#f1f5f9; color:#64748b; padding:2px 8px; border-radius:6px; font-size:10px; font-weight:600; flex-shrink:0;">{{ $log->entity_type }}</span>
                    @endif
                </div>
            @empty
                <p style="color:#94a3b8; font-size:13px; text-align:center; padding:20px;">No activity recorded yet.</p>
            @endforelse
        </div>
    </div>

    {{-- BILLING TAB --}}
    <div id="panel-billing" style="display:none;">
        {{-- Current Plan --}}
        <div style="background:linear-gradient(135deg,#1e293b,#334155); border-radius:16px; padding:24px; margin-bottom:20px; color:#fff;">
            <div class="billing-kpi-card">
                <div>
                    <p style="font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; color:#94a3b8; margin-bottom:4px;">Current Plan</p>
                    <h2 style="font-size:24px; font-weight:800; margin-bottom:4px;">{{ ucfirst($org->package ?? 'starter') }}</h2>
                    <p style="font-size:13px; color:#94a3b8;">
                        AI Credits: {{ number_format($org->ai_credits_used) }} / {{ number_format($org->ai_credit_limit) }} used &bull;
                        Team: {{ $org->users()->count() }} / {{ $org->max_users }} users
                        @if($org->subscription_ends_at)
                            &bull; Renews {{ \Carbon\Carbon::parse($org->subscription_ends_at)->format('d M Y') }}
                        @endif
                    </p>
                </div>
                <a href="{{ route('billing.history') }}" style="padding:10px 18px; background:rgba(255,255,255,0.1); color:#fff; border:1px solid rgba(255,255,255,0.2); border-radius:8px; font-size:12px; font-weight:600; text-decoration:none;">
                    📄 Payment History
                </a>
            </div>
        </div>

        {{-- Payment Method Toggle --}}
        <div style="display:flex; flex-wrap:wrap; gap:8px; margin-bottom:20px; align-items:center;">
            <span style="font-size:13px; font-weight:600; color:#475569;">Payment Type:</span>
            <button onclick="setPaymentType('one_time')" id="btn-one-time" style="padding:8px 16px; border-radius:8px; border:none; background:#6366f1; color:#fff; font-size:12px; font-weight:600; cursor:pointer;">💳 One-Time</button>
            <button onclick="setPaymentType('subscription')" id="btn-subscription" style="padding:8px 16px; border-radius:8px; border:1px solid #e2e8f0; background:#fff; color:#64748b; font-size:12px; font-weight:600; cursor:pointer;">🔄 Monthly Subscription</button>
            <span id="subscription-note" style="display:none; font-size:11px; color:#6366f1; background:#eef2ff; padding:6px 10px; border-radius:6px;">Subscription auto-renews monthly. Cancel anytime.</span>
        </div>

        {{-- Plans Grid --}}
        <div class="plans-layout-grid" id="plans-grid">
            @php
            $plans = [
                ['slug'=>'starter','name'=>'Starter','price'=>2499,'color'=>'#64748b','bg'=>'#f8fafc','users'=>'5 Users','credits'=>'1,000 AI Credits','features'=>['Lead Management','WhatsApp Inbox','Basic AI','Email Support']],
                ['slug'=>'professional','name'=>'Professional','price'=>7499,'color'=>'#6366f1','bg'=>'#eef2ff','users'=>'15 Users','credits'=>'10,000 AI Credits','features'=>['Everything in Starter','Advanced AI Analysis','Document Generator','Priority Support']],
                ['slug'=>'business','name'=>'Business','price'=>19999,'color'=>'#059669','bg'=>'#f0fdf4','users'=>'50 Users','credits'=>'50,000 AI Credits','features'=>['Everything in Professional','Team Productivity','Custom Reports','Dedicated Support']],
            ];
            @endphp

            @foreach($plans as $p)
            <div style="background:{{ $p['bg'] }}; border:2px solid {{ $org->package == $p['slug'] ? $p['color'] : '#e2e8f0' }}; border-radius:14px; padding:24px; position:relative; transition:all 0.2s;">
                @if($org->package == $p['slug'])
                    <div style="position:absolute; top:-10px; left:50%; transform:translateX(-50%); background:{{ $p['color'] }}; color:#fff; padding:2px 12px; border-radius:20px; font-size:10px; font-weight:700;">✓ CURRENT PLAN</div>
                @endif
                <div style="font-size:15px; font-weight:800; color:{{ $p['color'] }}; margin-bottom:8px;">{{ $p['name'] }}</div>
                <div style="font-size:26px; font-weight:800; color:#1e293b; margin-bottom:4px;">₹{{ number_format($p['price']) }}<span style="font-size:12px; color:#94a3b8;" id="period-label-{{ $p['slug'] }}">/mo</span></div>
                <div style="font-size:11px; color:#64748b; margin-bottom:4px;">{{ $p['users'] }}</div>
                <div style="font-size:11px; color:#64748b; margin-bottom:16px;">{{ $p['credits'] }}</div>
                <ul style="list-style:none; padding:0; margin:0 0 16px 0;">
                    @foreach($p['features'] as $f)
                        <li style="font-size:11px; color:#475569; margin-bottom:6px; display:flex; align-items:center; gap:6px;">
                            <span style="color:{{ $p['color'] }}; font-size:12px;">✓</span> {{ $f }}
                        </li>
                    @endforeach
                </ul>
                @if($org->package != $p['slug'])
                    <button onclick="startPayment('{{ $p['slug'] }}', {{ $p['price'] * 100 }}, '{{ $p['name'] }}')"
                        style="width:100%; padding:10px; background:{{ $p['color'] }}; color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:700; cursor:pointer; transition:opacity 0.2s;"
                        onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
                        Upgrade to {{ $p['name'] }}
                    </button>
                @else
                    <button disabled style="width:100%; padding:10px; background:#e2e8f0; color:#94a3b8; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:not-allowed;">
                        Active Plan
                    </button>
                @endif
            </div>
            @endforeach
        </div>

        <div class="enterprise-card">
            <div>
                <div style="font-size:14px; font-weight:700; color:#ea580c;">🏢 Enterprise Plan</div>
                <div style="font-size:12px; color:#9a3412; margin-top:4px;">Unlimited users, unlimited AI credits, custom integrations, SLA, dedicated support.</div>
            </div>
            <a href="mailto:support@devinesky.com?subject=Enterprise Plan Inquiry" style="padding:10px 20px; background:#ea580c; color:#fff; border-radius:8px; font-size:13px; font-weight:700; text-decoration:none;">Contact Sales</a>
        </div>
    </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
let currentPaymentType = 'one_time';

function setPaymentType(type) {
    currentPaymentType = type;
    const isSubscription = type === 'subscription';
    document.getElementById('btn-one-time').style.background = isSubscription ? '#fff' : '#6366f1';
    document.getElementById('btn-one-time').style.color = isSubscription ? '#64748b' : '#fff';
    document.getElementById('btn-one-time').style.border = isSubscription ? '1px solid #e2e8f0' : 'none';
    document.getElementById('btn-subscription').style.background = isSubscription ? '#6366f1' : '#fff';
    document.getElementById('btn-subscription').style.color = isSubscription ? '#fff' : '#64748b';
    document.getElementById('btn-subscription').style.border = isSubscription ? 'none' : '1px solid #e2e8f0';
    document.getElementById('subscription-note').style.display = isSubscription ? 'inline-block' : 'none';
    ['starter','professional','business'].forEach(p => {
        const el = document.getElementById('period-label-' + p);
        if (el) el.textContent = isSubscription ? '/mo (Auto-renews)' : ' (One-time)';
    });
}

async function startPayment(plan, amountPaise, planName) {
    if (currentPaymentType === 'subscription') {
        alert('For subscription payments, please contact support@devinesky.com or enter your Razorpay Plan ID from the dashboard.\n\nFor now, please use One-Time payment to upgrade instantly.');
        return;
    }

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Create Razorpay order
        const res = await fetch('{{ route("billing.order.one-time") }}', {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json'},
            body: JSON.stringify({plan: plan, period: 'monthly'})
        });

        const order = await res.json();
        if (order.error) { alert('Error: ' + order.error); return; }

        const options = {
            key: order.key,
            amount: order.amount,
            currency: order.currency,
            name: 'DevineSkyCRM',
            description: planName + ' Plan Upgrade',
            image: '',
            order_id: order.order_id,
            theme: { color: '#6366f1' },
            prefill: {
                name: '{{ Auth::user()->name }}',
                email: '{{ Auth::user()->email }}',
            },
            handler: async function(response) {
                const verifyRes = await fetch('{{ route("billing.verify.one-time") }}', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json'},
                    body: JSON.stringify({
                        razorpay_order_id: response.razorpay_order_id,
                        razorpay_payment_id: response.razorpay_payment_id,
                        razorpay_signature: response.razorpay_signature,
                        plan: plan,
                        payment_db_id: order.payment_id,
                    })
                });
                const result = await verifyRes.json();
                if (result.success) {
                    alert('🎉 Payment successful! Your plan has been upgraded to ' + planName + '.\n\nPage will reload.');
                    window.location.reload();
                } else {
                    alert('Payment verification failed: ' + (result.error || 'Unknown error. Contact support.'));
                }
            },
            modal: {
                ondismiss: function() { console.log('Payment window closed.'); }
            }
        };

        const rzp = new Razorpay(options);
        rzp.open();
    } catch (e) {
        alert('Payment initialization failed. Please check your Razorpay API keys in .env and try again.\n\nError: ' + e.message);
    }
}

function switchTab(tab) {
    ['profile','templates','activity','billing'].forEach(t => {
        document.getElementById('panel-' + t).style.display = t === tab ? 'block' : 'none';
        const btn = document.getElementById('tab-' + t);
        btn.style.background = t === tab ? '#fff' : 'transparent';
        btn.style.color = t === tab ? '#1e293b' : '#64748b';
        btn.style.boxShadow = t === tab ? '0 1px 3px rgba(0,0,0,0.1)' : 'none';
    });
}
switchTab('profile');
</script>
@endsection

