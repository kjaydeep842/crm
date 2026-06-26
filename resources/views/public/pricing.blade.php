@extends('layouts.frontend')
@section('title', 'Pricing | AI WhatsApp CRM')
@section('content')
<div class="page-header">
    <h1 data-aos="fade-up">Simple, Transparent Pricing</h1>
    <p data-aos="fade-up" data-aos-delay="100">Scale your AI WhatsApp CRM as your business grows. High demand, recurring value.</p>
</div>
<section class="section-padding" style="background:var(--surface);">
    <div style="max-width:1200px; margin:0 auto; display:grid; grid-template-columns:repeat(auto-fit, minmax(250px, 1fr)); gap:30px;">
        
        <!-- Starter -->
        <div style="border:1px solid var(--border); border-radius:20px; padding:40px; text-align:center; transition:transform 0.3s; background:var(--bg-light);" data-aos="fade-up" data-aos-delay="100">
            <h3 style="font-size:24px; color:var(--dark); margin-bottom:15px;">Starter</h3>
            <div style="font-size:42px; font-weight:800; color:var(--dark); margin-bottom:20px; font-family:'Plus Jakarta Sans', sans-serif;">₹999<span style="font-size:16px; color:var(--text); font-weight:500;">/mo</span></div>
            <p style="color:var(--text); margin-bottom:30px;">Perfect for small teams getting started.</p>
            <ul style="list-style:none; text-align:left; margin-bottom:30px;">
                <li style="margin-bottom:15px; color:var(--text); display:flex; align-items:center; gap:10px;"><i class="fa-solid fa-check" style="color:#059669;"></i> Basic CRM Features</li>
                <li style="margin-bottom:15px; color:var(--text); display:flex; align-items:center; gap:10px;"><i class="fa-solid fa-check" style="color:#059669;"></i> Single WhatsApp Number</li>
                <li style="margin-bottom:15px; color:var(--text); display:flex; align-items:center; gap:10px;"><i class="fa-solid fa-check" style="color:#059669;"></i> 1,000 AI Credits/mo</li>
            </ul>
            <button style="width:100%; padding:14px; border-radius:10px; font-weight:700; border:1px solid var(--primary); color:var(--primary); background:transparent; cursor:pointer;">Get Started</button>
        </div>

        <!-- Professional -->
        <div style="border:1px solid var(--border); border-radius:20px; padding:40px; text-align:center; transition:transform 0.3s; background:var(--bg-light);" data-aos="fade-up" data-aos-delay="200">
            <h3 style="font-size:24px; color:var(--dark); margin-bottom:15px;">Professional</h3>
            <div style="font-size:42px; font-weight:800; color:var(--dark); margin-bottom:20px; font-family:'Plus Jakarta Sans', sans-serif;">₹2,999<span style="font-size:16px; color:var(--text); font-weight:500;">/mo</span></div>
            <p style="color:var(--text); margin-bottom:30px;">Advanced features for growing sales teams.</p>
            <ul style="list-style:none; text-align:left; margin-bottom:30px;">
                <li style="margin-bottom:15px; color:var(--text); display:flex; align-items:center; gap:10px;"><i class="fa-solid fa-check" style="color:#059669;"></i> Up to 5 Users</li>
                <li style="margin-bottom:15px; color:var(--text); display:flex; align-items:center; gap:10px;"><i class="fa-solid fa-check" style="color:#059669;"></i> Shared Team Inbox</li>
                <li style="margin-bottom:15px; color:var(--text); display:flex; align-items:center; gap:10px;"><i class="fa-solid fa-check" style="color:#059669;"></i> 10,000 AI Credits/mo</li>
                <li style="margin-bottom:15px; color:var(--text); display:flex; align-items:center; gap:10px;"><i class="fa-solid fa-check" style="color:#059669;"></i> Automated Follow-ups</li>
            </ul>
            <button style="width:100%; padding:14px; border-radius:10px; font-weight:700; border:1px solid var(--primary); color:var(--primary); background:transparent; cursor:pointer;">Start Free Trial</button>
        </div>

        <!-- Business -->
        <div style="border:2px solid var(--primary); border-radius:20px; padding:40px; text-align:center; transform:scale(1.05); box-shadow:0 20px 40px rgba(79, 70, 229, 0.15); background:var(--surface); position:relative; z-index:1;" data-aos="fade-up" data-aos-delay="300">
            <div style="position:absolute; top:-15px; left:50%; transform:translateX(-50%); background:var(--primary); color:white; padding:6px 16px; border-radius:20px; font-size:12px; font-weight:700; text-transform:uppercase;">Most Popular</div>
            <h3 style="font-size:24px; color:var(--dark); margin-bottom:15px;">Business</h3>
            <div style="font-size:42px; font-weight:800; color:var(--dark); margin-bottom:20px; font-family:'Plus Jakarta Sans', sans-serif;">₹7,999<span style="font-size:16px; color:var(--text); font-weight:500;">/mo</span></div>
            <p style="color:var(--text); margin-bottom:30px;">For established businesses scaling operations.</p>
            <ul style="list-style:none; text-align:left; margin-bottom:30px;">
                <li style="margin-bottom:15px; color:var(--text); display:flex; align-items:center; gap:10px;"><i class="fa-solid fa-check" style="color:#059669;"></i> Up to 15 Users</li>
                <li style="margin-bottom:15px; color:var(--text); display:flex; align-items:center; gap:10px;"><i class="fa-solid fa-check" style="color:#059669;"></i> Advanced API Integrations</li>
                <li style="margin-bottom:15px; color:var(--text); display:flex; align-items:center; gap:10px;"><i class="fa-solid fa-check" style="color:#059669;"></i> 50,000 AI Credits/mo</li>
                <li style="margin-bottom:15px; color:var(--text); display:flex; align-items:center; gap:10px;"><i class="fa-solid fa-check" style="color:#059669;"></i> Custom Sales Pipelines</li>
            </ul>
            <button style="width:100%; padding:14px; border-radius:10px; font-weight:700; background:var(--primary); color:white; border:none; cursor:pointer;">Start Free Trial</button>
        </div>

        <!-- Enterprise -->
        <div style="border:1px solid var(--border); border-radius:20px; padding:40px; text-align:center; transition:transform 0.3s; background:var(--bg-light);" data-aos="fade-up" data-aos-delay="400">
            <h3 style="font-size:24px; color:var(--dark); margin-bottom:15px;">Enterprise</h3>
            <div style="font-size:42px; font-weight:800; color:var(--dark); margin-bottom:20px; font-family:'Plus Jakarta Sans', sans-serif;">₹15,000+<span style="font-size:16px; color:var(--text); font-weight:500;">/mo</span></div>
            <p style="color:var(--text); margin-bottom:30px;">Dedicated solutions for large organizations.</p>
            <ul style="list-style:none; text-align:left; margin-bottom:30px;">
                <li style="margin-bottom:15px; color:var(--text); display:flex; align-items:center; gap:10px;"><i class="fa-solid fa-check" style="color:#059669;"></i> Unlimited Users</li>
                <li style="margin-bottom:15px; color:var(--text); display:flex; align-items:center; gap:10px;"><i class="fa-solid fa-check" style="color:#059669;"></i> Unlimited AI Credits</li>
                <li style="margin-bottom:15px; color:var(--text); display:flex; align-items:center; gap:10px;"><i class="fa-solid fa-check" style="color:#059669;"></i> Custom AI Models</li>
                <li style="margin-bottom:15px; color:var(--text); display:flex; align-items:center; gap:10px;"><i class="fa-solid fa-check" style="color:#059669;"></i> Dedicated Account Manager</li>
            </ul>
            <button style="width:100%; padding:14px; border-radius:10px; font-weight:700; border:1px solid var(--primary); color:var(--primary); background:transparent; cursor:pointer;">Contact Sales</button>
        </div>
    </div>
</section>
@endsection
