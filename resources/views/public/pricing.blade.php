@extends('layouts.frontend')
@section('title', 'Pricing | DevineSky')

@section('styles')
<style>
    .pricing-header {
        text-align: center;
        margin-bottom: 60px;
    }
    .pricing-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
        max-width: 1200px;
        margin: 0 auto;
    }
    .pricing-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 24px;
        padding: 48px 40px;
        text-align: center;
        transition: all 0.3s ease;
        position: relative;
    }
    .pricing-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.05);
    }
    .pricing-card.featured {
        border: 2px solid var(--primary);
        transform: scale(1.03);
        box-shadow: 0 20px 40px rgba(79, 70, 229, 0.12);
    }
    .pricing-card.featured:hover {
        transform: scale(1.03) translateY(-4px);
    }
    .featured-label {
        position: absolute;
        top: -16px;
        left: 50%;
        transform: translateX(-50%);
        background: var(--primary);
        color: #ffffff;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }
    .plan-name {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 24px;
        font-weight: 800;
        color: var(--dark);
        margin-bottom: 12px;
    }
    .plan-price {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 48px;
        font-weight: 800;
        color: var(--dark);
        margin-bottom: 8px;
    }
    .plan-price span {
        font-size: 15px;
        color: var(--text);
        font-weight: 500;
    }
    .plan-sub {
        font-size: 12px;
        color: #64748b;
        margin-bottom: 32px;
    }
    .features-list {
        list-style: none;
        text-align: left;
        margin-bottom: 40px;
    }
    .features-list li {
        margin-bottom: 16px;
        font-size: 14px;
        color: var(--text);
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .features-list li i {
        color: #10b981;
        font-size: 16px;
    }
    .pricing-cta {
        width: 100%;
        padding: 14px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 15px;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }
    .cta-outline {
        background: transparent;
        border: 1px solid var(--primary);
        color: var(--primary);
    }
    .cta-outline:hover {
        background: var(--primary);
        color: #ffffff;
    }
    .cta-solid {
        background: var(--primary);
        color: #ffffff;
    }
    .cta-solid:hover {
        background: var(--primary-hover);
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <h1 data-aos="fade-up">Simple, Predictable Billing</h1>
    <p data-aos="fade-up" data-aos-delay="100">Upgrade your workspace securely via Razorpay. Choose between flexible Monthly Subscriptions or One-Time upgrades.</p>
</div>

<section class="section-padding" style="background:var(--bg-light);">
    <div class="pricing-grid">
        
        <!-- Plan 1 -->
        <div class="pricing-card" data-aos="fade-up" data-aos-delay="100">
            <h3 class="plan-name">Starter</h3>
            <div class="plan-price">₹2,499<span>/mo</span></div>
            <div class="plan-sub">or ₹23,999 one-time payment</div>
            <ul class="features-list">
                <li><i class="fa-solid fa-check"></i> Up to 5 Active Users</li>
                <li><i class="fa-solid fa-check"></i> 1,000 AI Credits / month</li>
                <li><i class="fa-solid fa-check"></i> Google OAuth Integration</li>
                <li><i class="fa-solid fa-check"></i> Email Template Builder</li>
                <li><i class="fa-solid fa-check"></i> Standard Security & Logging</li>
            </ul>
            <a href="{{ route('login') }}" style="text-decoration:none;"><button class="pricing-cta cta-outline">Get Started</button></a>
        </div>

        <!-- Plan 2 -->
        <div class="pricing-card featured" data-aos="fade-up" data-aos-delay="200">
            <div class="featured-label">Most Popular</div>
            <h3 class="plan-name">Professional</h3>
            <div class="plan-price">₹7,499<span>/mo</span></div>
            <div class="plan-sub">or ₹71,999 one-time payment</div>
            <ul class="features-list">
                <li><i class="fa-solid fa-check"></i> Up to 15 Active Users</li>
                <li><i class="fa-solid fa-check"></i> 10,000 AI Credits / month</li>
                <li><i class="fa-solid fa-check"></i> WhatsApp Cloud API Webhooks</li>
                <li><i class="fa-solid fa-check"></i> Dynamic PDF Invoice Downloads</li>
                <li><i class="fa-solid fa-check"></i> Continuous Audit Tracking</li>
                <li><i class="fa-solid fa-check"></i> Priority Email Support</li>
            </ul>
            <a href="{{ route('login') }}" style="text-decoration:none;"><button class="pricing-cta cta-solid">Start Free Trial</button></a>
        </div>

        <!-- Plan 3 -->
        <div class="pricing-card" data-aos="fade-up" data-aos-delay="300">
            <h3 class="plan-name">Business</h3>
            <div class="plan-price">₹19,999<span>/mo</span></div>
            <div class="plan-sub">or ₹1,91,999 one-time payment</div>
            <ul class="features-list">
                <li><i class="fa-solid fa-check"></i> Up to 50 Active Users</li>
                <li><i class="fa-solid fa-check"></i> 50,000 AI Credits / month</li>
                <li><i class="fa-solid fa-check"></i> Dedicated OpenAI Agent Tuning</li>
                <li><i class="fa-solid fa-check"></i> Custom Integrations & APIs</li>
                <li><i class="fa-solid fa-check"></i> Complete Team Activity Auditing</li>
                <li><i class="fa-solid fa-check"></i> 24/7 Dedicated Support manager</li>
            </ul>
            <a href="{{ route('login') }}" style="text-decoration:none;"><button class="pricing-cta cta-outline">Contact Sales</button></a>
        </div>

    </div>
</section>
@endsection
