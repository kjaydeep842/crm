@extends('layouts.frontend')
@section('title', 'Pricing | Aura CRM')
@section('content')
<div class="page-header">
    <h1 data-aos="fade-up">Simple, Transparent Pricing</h1>
    <p data-aos="fade-up" data-aos-delay="100">Scale your AI CRM as your team grows. No hidden fees.</p>
</div>
<section class="section-padding" style="background:var(--surface);">
    <div style="max-width:1100px; margin:0 auto; display:grid; grid-template-columns:repeat(auto-fit, minmax(300px, 1fr)); gap:30px;">
        
        <div style="border:1px solid var(--border); border-radius:20px; padding:40px; text-align:center; transition:transform 0.3s; background:var(--bg-light);" data-aos="fade-up" data-aos-delay="100">
            <h3 style="font-size:24px; color:var(--dark); margin-bottom:15px;">Starter</h3>
            <div style="font-size:48px; font-weight:800; color:var(--dark); margin-bottom:20px; font-family:'Plus Jakarta Sans', sans-serif;">$49<span style="font-size:16px; color:var(--text); font-weight:500;">/mo</span></div>
            <p style="color:var(--text); margin-bottom:30px;">Perfect for small teams getting started with AI.</p>
            <ul style="list-style:none; text-align:left; margin-bottom:30px;">
                <li style="margin-bottom:15px; color:var(--text); display:flex; align-items:center; gap:10px;"><i class="fa-solid fa-check" style="color:#059669;"></i> Up to 5 Users</li>
                <li style="margin-bottom:15px; color:var(--text); display:flex; align-items:center; gap:10px;"><i class="fa-solid fa-check" style="color:#059669;"></i> 1,000 AI Credits/mo</li>
                <li style="margin-bottom:15px; color:var(--text); display:flex; align-items:center; gap:10px;"><i class="fa-solid fa-check" style="color:#059669;"></i> Basic Email Integration</li>
            </ul>
            <button style="width:100%; padding:14px; border-radius:10px; font-weight:700; border:1px solid var(--primary); color:var(--primary); background:transparent; cursor:pointer;">Get Started</button>
        </div>

        <div style="border:2px solid var(--primary); border-radius:20px; padding:40px; text-align:center; transform:scale(1.05); box-shadow:0 20px 40px rgba(79, 70, 229, 0.15); background:var(--surface); position:relative;" data-aos="fade-up" data-aos-delay="200">
            <div style="position:absolute; top:-15px; left:50%; transform:translateX(-50%); background:var(--primary); color:white; padding:6px 16px; border-radius:20px; font-size:12px; font-weight:700; text-transform:uppercase;">Most Popular</div>
            <h3 style="font-size:24px; color:var(--dark); margin-bottom:15px;">Professional</h3>
            <div style="font-size:48px; font-weight:800; color:var(--dark); margin-bottom:20px; font-family:'Plus Jakarta Sans', sans-serif;">$149<span style="font-size:16px; color:var(--text); font-weight:500;">/mo</span></div>
            <p style="color:var(--text); margin-bottom:30px;">Advanced features for growing sales teams.</p>
            <ul style="list-style:none; text-align:left; margin-bottom:30px;">
                <li style="margin-bottom:15px; color:var(--text); display:flex; align-items:center; gap:10px;"><i class="fa-solid fa-check" style="color:#059669;"></i> Up to 15 Users</li>
                <li style="margin-bottom:15px; color:var(--text); display:flex; align-items:center; gap:10px;"><i class="fa-solid fa-check" style="color:#059669;"></i> 10,000 AI Credits/mo</li>
                <li style="margin-bottom:15px; color:var(--text); display:flex; align-items:center; gap:10px;"><i class="fa-solid fa-check" style="color:#059669;"></i> WhatsApp Integration</li>
                <li style="margin-bottom:15px; color:var(--text); display:flex; align-items:center; gap:10px;"><i class="fa-solid fa-check" style="color:#059669;"></i> Advanced Routing</li>
            </ul>
            <button style="width:100%; padding:14px; border-radius:10px; font-weight:700; background:var(--primary); color:white; border:none; cursor:pointer;">Start Free Trial</button>
        </div>

        <div style="border:1px solid var(--border); border-radius:20px; padding:40px; text-align:center; transition:transform 0.3s; background:var(--bg-light);" data-aos="fade-up" data-aos-delay="300">
            <h3 style="font-size:24px; color:var(--dark); margin-bottom:15px;">Enterprise</h3>
            <div style="font-size:48px; font-weight:800; color:var(--dark); margin-bottom:20px; font-family:'Plus Jakarta Sans', sans-serif;">Custom</div>
            <p style="color:var(--text); margin-bottom:30px;">Dedicated solutions for large organizations.</p>
            <ul style="list-style:none; text-align:left; margin-bottom:30px;">
                <li style="margin-bottom:15px; color:var(--text); display:flex; align-items:center; gap:10px;"><i class="fa-solid fa-check" style="color:#059669;"></i> Unlimited Users</li>
                <li style="margin-bottom:15px; color:var(--text); display:flex; align-items:center; gap:10px;"><i class="fa-solid fa-check" style="color:#059669;"></i> Unlimited AI Credits</li>
                <li style="margin-bottom:15px; color:var(--text); display:flex; align-items:center; gap:10px;"><i class="fa-solid fa-check" style="color:#059669;"></i> Custom API Integrations</li>
                <li style="margin-bottom:15px; color:var(--text); display:flex; align-items:center; gap:10px;"><i class="fa-solid fa-check" style="color:#059669;"></i> Dedicated Support</li>
            </ul>
            <button style="width:100%; padding:14px; border-radius:10px; font-weight:700; border:1px solid var(--primary); color:var(--primary); background:transparent; cursor:pointer;">Contact Sales</button>
        </div>
    </div>
</section>
@endsection
