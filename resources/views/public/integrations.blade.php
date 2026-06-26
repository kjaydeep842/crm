@extends('layouts.frontend')
@section('title', 'Integrations | DevineSky')

@section('styles')
<style>
    .integration-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
        max-width: 1200px;
        margin: 0 auto;
    }
    .integration-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 24px;
        padding: 40px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .integration-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(79, 70, 229, 0.08);
        border-color: #c7d2fe;
    }
    .integration-header {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 24px;
    }
    .integration-logo {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }
    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .status-active {
        background: #dcfce7;
        color: #15803d;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <h1 data-aos="fade-up">Powering Your Sales Ecosystem</h1>
    <p data-aos="fade-up" data-aos-delay="100">DevineSky integrates seamlessly with industry-leading applications to streamline lead flow, communication, and payments.</p>
</div>

<section class="section-padding" style="background:var(--bg-light);">
    <div class="integration-grid">
        
        <!-- Integration 1 -->
        <div class="integration-card" data-aos="fade-up" data-aos-delay="100">
            <div class="integration-header">
                <div class="integration-logo" style="background:#e8fdf0; color:#25d366;">
                    <i class="fa-brands fa-whatsapp"></i>
                </div>
                <div>
                    <h3 style="font-family:'Plus Jakarta Sans', sans-serif; font-size:18px; font-weight:700; color:var(--dark);">WhatsApp Cloud API</h3>
                    <span class="status-badge status-active">Built-in</span>
                </div>
            </div>
            <p style="color:var(--text); font-size:14px; line-height:1.6; margin-bottom:20px;">Receive, parse, qualify, and automate chat follow-ups directly over WhatsApp. Supports official templates and interactive buttons.</p>
        </div>

        <!-- Integration 2 -->
        <div class="integration-card" data-aos="fade-up" data-aos-delay="150">
            <div class="integration-header">
                <div class="integration-logo" style="background:#f1f5f9; color:#ea4335;">
                    <i class="fa-brands fa-google"></i>
                </div>
                <div>
                    <h3 style="font-family:'Plus Jakarta Sans', sans-serif; font-size:18px; font-weight:700; color:var(--dark);">Google Workspace</h3>
                    <span class="status-badge status-active">Native SSO</span>
                </div>
            </div>
            <p style="color:var(--text); font-size:14px; line-height:1.6; margin-bottom:20px;">One-click Google login for admins and sales staff. Restricts access to recognized organization contexts automatically.</p>
        </div>

        <!-- Integration 3 -->
        <div class="integration-card" data-aos="fade-up" data-aos-delay="200">
            <div class="integration-header">
                <div class="integration-logo" style="background:#eef2ff; color:#3395ff;">
                    <i class="fa-solid fa-credit-card"></i>
                </div>
                <div>
                    <h3 style="font-family:'Plus Jakarta Sans', sans-serif; font-size:18px; font-weight:700; color:var(--dark);">Razorpay Payments</h3>
                    <span class="status-badge status-active">Configured</span>
                </div>
            </div>
            <p style="color:var(--text); font-size:14px; line-height:1.6; margin-bottom:20px;">Secure one-time upgrades and monthly subscriptions. Implements automated webhook listener for immediate plan provisioning.</p>
        </div>

        <!-- Integration 4 -->
        <div class="integration-card" data-aos="fade-up" data-aos-delay="250">
            <div class="integration-header">
                <div class="integration-logo" style="background:#fef3c7; color:#d97706;">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <div>
                    <h3 style="font-family:'Plus Jakarta Sans', sans-serif; font-size:18px; font-weight:700; color:var(--dark);">Custom SMTP / Mail</h3>
                    <span class="status-badge status-active">Supported</span>
                </div>
            </div>
            <p style="color:var(--text); font-size:14px; line-height:1.6; margin-bottom:20px;">Connect your company's own mail servers (AWS SES, Mailgun, SMTP). Send automated emails utilizing custom layouts.</p>
        </div>

        <!-- Integration 5 -->
        <div class="integration-card" data-aos="fade-up" data-aos-delay="300">
            <div class="integration-header">
                <div class="integration-logo" style="background:#fce7f3; color:#db2777;">
                    <i class="fa-solid fa-code"></i>
                </div>
                <div>
                    <h3 style="font-family:'Plus Jakarta Sans', sans-serif; font-size:18px; font-weight:700; color:var(--dark);">Developer Webhooks</h3>
                    <span class="status-badge status-active">Rest API</span>
                </div>
            </div>
            <p style="color:var(--text); font-size:14px; line-height:1.6; margin-bottom:20px;">Forward newly qualified leads and sales logs directly to third-party endpoints. Strictly isolated per workspace.</p>
        </div>

        <!-- Integration 6 -->
        <div class="integration-card" data-aos="fade-up" data-aos-delay="350">
            <div class="integration-header">
                <div class="integration-logo" style="background:#ecfeff; color:#0891b2;">
                    <i class="fa-solid fa-brain"></i>
                </div>
                <div>
                    <h3 style="font-family:'Plus Jakarta Sans', sans-serif; font-size:18px; font-weight:700; color:var(--dark);">OpenAI Engine</h3>
                    <span class="status-badge status-active">Core AI</span>
                </div>
            </div>
            <p style="color:var(--text); font-size:14px; line-height:1.6; margin-bottom:20px;">Leverage advanced language models to perform sentiment analysis, score lead priority, and draft contextual replies.</p>
        </div>

    </div>
</section>
@endsection
