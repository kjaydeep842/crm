@extends('layouts.frontend')
@section('title', 'About Us | Aura CRM')
@section('content')
<div class="page-header">
    <h1 data-aos="fade-up">Our Mission</h1>
    <p data-aos="fade-up" data-aos-delay="100">We are building the future of autonomous sales and CRM technology.</p>
</div>
<section class="section-padding" style="background:var(--surface);">
    <div style="max-width:800px; margin:0 auto; text-align:center;">
        <h2 style="font-family:'Plus Jakarta Sans', sans-serif; font-size:32px; color:var(--dark); margin-bottom:24px;" data-aos="fade-up">Why We Built Aura</h2>
        <p style="font-size:18px; color:var(--text); line-height:1.8; margin-bottom:24px;" data-aos="fade-up" data-aos-delay="100">For too long, sales teams have spent more time doing data entry, drafting emails, and qualifying bad leads than actually selling. CRM systems became glorified spreadsheets that demanded constant manual updating.</p>
        <p style="font-size:18px; color:var(--text); line-height:1.8; margin-bottom:40px;" data-aos="fade-up" data-aos-delay="200">Aura was founded in 2026 to flip this dynamic. We combined state-of-the-art Large Language Models with deep CRM architecture to create a system that works for you, not the other way around. Aura thinks, writes, and organizes so your team can focus on closing deals.</p>
        
        <div style="display:flex; justify-content:center; gap:40px; margin-top:60px; flex-wrap:wrap;" data-aos="fade-up" data-aos-delay="300">
            <div style="text-align:center;">
                <div style="font-size:40px; font-weight:800; color:var(--primary); font-family:'Plus Jakarta Sans', sans-serif;">10M+</div>
                <p style="color:var(--text); font-weight:600;">Leads Processed</p>
            </div>
            <div style="text-align:center;">
                <div style="font-size:40px; font-weight:800; color:var(--secondary); font-family:'Plus Jakarta Sans', sans-serif;">99%</div>
                <p style="color:var(--text); font-weight:600;">Accuracy in Parsing</p>
            </div>
            <div style="text-align:center;">
                <div style="font-size:40px; font-weight:800; color:#ec4899; font-family:'Plus Jakarta Sans', sans-serif;">24/7</div>
                <p style="color:var(--text); font-weight:600;">Agent Uptime</p>
            </div>
        </div>
    </div>
</section>
@endsection
