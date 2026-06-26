@extends('layouts.frontend')
@section('title', 'Security & Trust | DevineSky')

@section('styles')
<style>
    .security-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
        color: #ffffff;
        padding: 160px 5% 100px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .security-hero h1 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 48px;
        font-weight: 800;
        margin-bottom: 20px;
    }
    .security-hero p {
        font-size: 18px;
        color: #cbd5e1;
        max-width: 700px;
        margin: 0 auto;
    }
    .security-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 40px;
        max-width: 1200px;
        margin: 0 auto;
    }
    .security-item {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 24px;
        padding: 40px;
        transition: all 0.3s;
    }
    .security-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.03);
        border-color: #c7d2fe;
    }
    .sec-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        background: rgba(79, 70, 229, 0.08);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        margin-bottom: 24px;
    }
</style>
@endsection

@section('content')
<section class="security-hero">
    <h1 data-aos="fade-down">Enterprise-Grade Security</h1>
    <p data-aos="fade-up" data-aos-delay="100">Protecting your client relations, activity logs, and financial transactions with absolute isolation and encryption.</p>
</section>

<section class="section-padding" style="background:var(--bg-light);">
    <div class="security-grid">
        
        <!-- Sec 1 -->
        <div class="security-item" data-aos="fade-up" data-aos-delay="100">
            <div class="sec-icon">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <h3 style="font-family:'Plus Jakarta Sans', sans-serif; font-size:20px; font-weight:700; color:var(--dark); margin-bottom:12px;">Multi-Tenant Isolation</h3>
            <p style="color:var(--text); font-size:14px; line-height:1.6;">Our database structure enforces strict organizational isolation. A user associated with one client company can never access, modify, or view leads or activities belonging to another workspace.</p>
        </div>

        <!-- Sec 2 -->
        <div class="security-item" data-aos="fade-up" data-aos-delay="150">
            <div class="sec-icon">
                <i class="fa-solid fa-clipboard-list"></i>
            </div>
            <h3 style="font-family:'Plus Jakarta Sans', sans-serif; font-size:20px; font-weight:700; color:var(--dark); margin-bottom:12px;">Continuous Audit Logging</h3>
            <p style="color:var(--text); font-size:14px; line-height:1.6;">DevineSky features built-in activity tracking. Every sign-in (including Google OAuth), lead creation, and plan upgrade logs the user's ID, time, action, and IP address for compliance auditing.</p>
        </div>

        <!-- Sec 3 -->
        <div class="security-item" data-aos="fade-up" data-aos-delay="200">
            <div class="sec-icon">
                <i class="fa-solid fa-key"></i>
            </div>
            <h3 style="font-family:'Plus Jakarta Sans', sans-serif; font-size:20px; font-weight:700; color:var(--dark); margin-bottom:12px;">Secure Authentication</h3>
            <p style="color:var(--text); font-size:14px; line-height:1.6;">All endpoints utilize cryptographically signed session cookies, CSRF protection middleware, and SSL-encrypted connections. Standard guest and authenticated zones are fully verified.</p>
        </div>

        <!-- Sec 4 -->
        <div class="security-item" data-aos="fade-up" data-aos-delay="250">
            <div class="sec-icon">
                <i class="fa-solid fa-receipt"></i>
            </div>
            <h3 style="font-family:'Plus Jakarta Sans', sans-serif; font-size:20px; font-weight:700; color:var(--dark); margin-bottom:12px;">Payment Signature Verification</h3>
            <p style="color:var(--text); font-size:14px; line-height:1.6;">Every Razorpay checkout payment event (captured, subscriptions) validates its HMAC-SHA256 signature against webhook secrets before updating the tenant's plan subscription, protecting from fraud.</p>
        </div>

        <!-- Sec 5 -->
        <div class="security-item" data-aos="fade-up" data-aos-delay="300">
            <div class="sec-icon">
                <i class="fa-solid fa-user-lock"></i>
            </div>
            <h3 style="font-family:'Plus Jakarta Sans', sans-serif; font-size:20px; font-weight:700; color:var(--dark); margin-bottom:12px;">Role-Based Access (RBAC)</h3>
            <p style="color:var(--text); font-size:14px; line-height:1.6;">Granular authorization levels separate SuperAdmin (billing/history access), organization Admin (settings, custom notification templates), and Staff members (lead management and WhatsApp chat inbox).</p>
        </div>

        <!-- Sec 6 -->
        <div class="security-item" data-aos="fade-up" data-aos-delay="350">
            <div class="sec-icon">
                <i class="fa-solid fa-server"></i>
            </div>
            <h3 style="font-family:'Plus Jakarta Sans', sans-serif; font-size:20px; font-weight:700; color:var(--dark); margin-bottom:12px;">Data Encryption</h3>
            <p style="color:var(--text); font-size:14px; line-height:1.6;">Passwords and sensitive environment configuration variables are securely hashed and encrypted at rest in local databases, preventing unauthorized physical access leaks.</p>
        </div>

    </div>
</section>
@endsection
