@extends('layouts.frontend')
@section('title', 'How it Works | Aura CRM')
@section('content')
<div class="page-header">
    <h1 data-aos="fade-up">How Aura Automates Your Sales</h1>
    <p data-aos="fade-up" data-aos-delay="100">Aura AI operates as a seamless pipeline, from lead capture to deal closure.</p>
</div>
<section class="section-padding" style="background:var(--bg-light);">
    <div style="max-width:800px; margin:0 auto;">
        
        <div style="display:flex; gap:30px; margin-bottom:60px;" data-aos="fade-up">
            <div style="width:60px; height:60px; border-radius:50%; background:var(--primary); color:white; display:flex; align-items:center; justify-content:center; font-size:24px; font-weight:800; flex-shrink:0;">1</div>
            <div>
                <h3 style="font-family:'Plus Jakarta Sans', sans-serif; font-size:24px; color:var(--dark); margin-bottom:12px;">Omnichannel Capture</h3>
                <p style="color:var(--text); font-size:16px; line-height:1.7;">Aura ingests leads from everywhere: your website forms, direct emails, and incoming WhatsApp messages. Everything lands in a unified, organized dashboard instantly.</p>
            </div>
        </div>

        <div style="display:flex; gap:30px; margin-bottom:60px;" data-aos="fade-up">
            <div style="width:60px; height:60px; border-radius:50%; background:var(--secondary); color:white; display:flex; align-items:center; justify-content:center; font-size:24px; font-weight:800; flex-shrink:0;">2</div>
            <div>
                <h3 style="font-family:'Plus Jakarta Sans', sans-serif; font-size:24px; color:var(--dark); margin-bottom:12px;">AI Processing & Scoring</h3>
                <p style="color:var(--text); font-size:16px; line-height:1.7;">The AI reads the raw text, understands the context, extracts entities (like desired software features, budget ranges, timelines), and assigns a lead score from 1-100 based on your criteria.</p>
            </div>
        </div>

        <div style="display:flex; gap:30px; margin-bottom:60px;" data-aos="fade-up">
            <div style="width:60px; height:60px; border-radius:50%; background:#ec4899; color:white; display:flex; align-items:center; justify-content:center; font-size:24px; font-weight:800; flex-shrink:0;">3</div>
            <div>
                <h3 style="font-family:'Plus Jakarta Sans', sans-serif; font-size:24px; color:var(--dark); margin-bottom:12px;">Smart Routing</h3>
                <p style="color:var(--text); font-size:16px; line-height:1.7;">High-priority leads are immediately routed to senior account executives. Low-priority inquiries get automated responses or are assigned to the SDR team for nurturing.</p>
            </div>
        </div>

        <div style="display:flex; gap:30px;" data-aos="fade-up">
            <div style="width:60px; height:60px; border-radius:50%; background:#059669; color:white; display:flex; align-items:center; justify-content:center; font-size:24px; font-weight:800; flex-shrink:0;">4</div>
            <div>
                <h3 style="font-family:'Plus Jakarta Sans', sans-serif; font-size:24px; color:var(--dark); margin-bottom:12px;">Generative Follow-up</h3>
                <p style="color:var(--text); font-size:16px; line-height:1.7;">Aura drafts highly personalized email and WhatsApp responses that address the exact pain points the lead mentioned. You just click "Approve and Send".</p>
            </div>
        </div>

    </div>
</section>
@endsection
