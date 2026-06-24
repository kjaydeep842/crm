@extends('layouts.frontend')
@section('title', 'Features | Aura CRM')
@section('content')
<div class="page-header">
    <h1 data-aos="fade-up">Enterprise-Grade Features</h1>
    <p data-aos="fade-up" data-aos-delay="100">Discover all the tools and capabilities packed into Aura AI to supercharge your sales team.</p>
</div>
<section class="section-padding" style="background:var(--surface);">
    <div style="max-width:1200px; margin:0 auto;">
        <!-- Feature 1 Detailed -->
        <div style="display:flex; flex-wrap:wrap; align-items:center; gap:40px; margin-bottom:80px;" data-aos="fade-up">
            <div style="flex:1; min-width:300px;">
                <div style="width:60px; height:60px; background:rgba(79,70,229,0.1); color:var(--primary); border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:24px; margin-bottom:20px;">
                    <i class="fa-solid fa-robot"></i>
                </div>
                <h2 style="font-family:'Plus Jakarta Sans', sans-serif; font-size:32px; color:var(--dark); margin-bottom:16px;">Autonomous AI Copilot</h2>
                <p style="color:var(--text); font-size:16px; margin-bottom:24px;">Our AI copilot works 24/7 alongside your sales team. It automatically reads incoming inquiries, parses buyer intent, and highlights key data points such as budget and urgency before a human even touches the lead.</p>
                <ul style="list-style:none;">
                    <li style="margin-bottom:12px; color:var(--text);"><i class="fa-solid fa-check" style="color:#059669; margin-right:8px;"></i> Instant Data Extraction</li>
                    <li style="margin-bottom:12px; color:var(--text);"><i class="fa-solid fa-check" style="color:#059669; margin-right:8px;"></i> Custom Scoring Models</li>
                    <li style="margin-bottom:12px; color:var(--text);"><i class="fa-solid fa-check" style="color:#059669; margin-right:8px;"></i> Automated Routing to Sales Agents</li>
                </ul>
            </div>
            <div style="flex:1; min-width:300px; background:linear-gradient(135deg, #f8fafc, #eef2ff); border:1px solid var(--border); border-radius:24px; padding:40px; box-shadow:0 10px 30px rgba(0,0,0,0.02);">
                 <div style="background:white; padding:20px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.05); border-left:4px solid var(--primary);">
                     <p style="font-size:14px; color:var(--text); margin-bottom:10px;"><strong>AI Insight:</strong> This prospect has a high urgency and an estimated budget of $50k+.</p>
                     <div style="height:8px; background:#e2e8f0; border-radius:4px; overflow:hidden;">
                         <div style="width:85%; height:100%; background:var(--primary);"></div>
                     </div>
                 </div>
            </div>
        </div>

        <!-- Feature 2 Detailed -->
        <div style="display:flex; flex-wrap:wrap; align-items:center; flex-direction:row-reverse; gap:40px; margin-bottom:80px;" data-aos="fade-up">
            <div style="flex:1; min-width:300px;">
                <div style="width:60px; height:60px; background:rgba(6,182,212,0.1); color:var(--secondary); border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:24px; margin-bottom:20px;">
                    <i class="fa-brands fa-whatsapp"></i>
                </div>
                <h2 style="font-family:'Plus Jakarta Sans', sans-serif; font-size:32px; color:var(--dark); margin-bottom:16px;">Omnichannel Integration</h2>
                <p style="color:var(--text); font-size:16px; margin-bottom:24px;">Connect directly with WhatsApp Business API and Email. Aura doesn't just read messages; it drafts personalized, context-aware replies that sound like your best sales rep.</p>
                <ul style="list-style:none;">
                    <li style="margin-bottom:12px; color:var(--text);"><i class="fa-solid fa-check" style="color:#059669; margin-right:8px;"></i> WhatsApp Webhooks</li>
                    <li style="margin-bottom:12px; color:var(--text);"><i class="fa-solid fa-check" style="color:#059669; margin-right:8px;"></i> Email Thread Tracking</li>
                    <li style="margin-bottom:12px; color:var(--text);"><i class="fa-solid fa-check" style="color:#059669; margin-right:8px;"></i> One-Click Auto-Send</li>
                </ul>
            </div>
            <div style="flex:1; min-width:300px; background:linear-gradient(135deg, #f8fafc, #ecfeff); border:1px solid var(--border); border-radius:24px; padding:40px; box-shadow:0 10px 30px rgba(0,0,0,0.02);">
                 <div style="background:white; padding:20px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.05);">
                     <p style="font-size:14px; background:#dcf8c6; padding:10px 15px; border-radius:15px 15px 0 15px; display:inline-block; margin-bottom:10px; margin-left:auto;">Hi! I saw your inquiry. Let's schedule a call this week.</p>
                     <p style="font-size:12px; color:#94a3b8; text-align:right;">Sent via AI Agent</p>
                 </div>
            </div>
        </div>

        <!-- Feature 3 Detailed -->
        <div style="display:flex; flex-wrap:wrap; align-items:center; gap:40px;" data-aos="fade-up">
            <div style="flex:1; min-width:300px;">
                <div style="width:60px; height:60px; background:rgba(236,72,153,0.1); color:#ec4899; border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:24px; margin-bottom:20px;">
                    <i class="fa-solid fa-building-shield"></i>
                </div>
                <h2 style="font-family:'Plus Jakarta Sans', sans-serif; font-size:32px; color:var(--dark); margin-bottom:16px;">Multi-Tenant & Secure</h2>
                <p style="color:var(--text); font-size:16px; margin-bottom:24px;">Built for scale. Whether you're managing a single department or multiple global organizations, Aura's secure RBAC ensures data isolation and strict access control.</p>
                <ul style="list-style:none;">
                    <li style="margin-bottom:12px; color:var(--text);"><i class="fa-solid fa-check" style="color:#059669; margin-right:8px;"></i> Role-Based Access Control (RBAC)</li>
                    <li style="margin-bottom:12px; color:var(--text);"><i class="fa-solid fa-check" style="color:#059669; margin-right:8px;"></i> Workspace Isolation</li>
                    <li style="margin-bottom:12px; color:var(--text);"><i class="fa-solid fa-check" style="color:#059669; margin-right:8px;"></i> Enterprise Grade Encryption</li>
                </ul>
            </div>
            <div style="flex:1; min-width:300px; background:linear-gradient(135deg, #f8fafc, #fdf2f8); border:1px solid var(--border); border-radius:24px; padding:40px; box-shadow:0 10px 30px rgba(0,0,0,0.02);">
                 <div style="background:white; padding:20px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.05); border-left:4px solid #ec4899;">
                     <div style="display:flex; align-items:center; gap:15px; margin-bottom:15px;">
                         <div style="width:40px; height:40px; background:#f1f5f9; border-radius:50%; display:flex; align-items:center; justify-content:center;"><i class="fa-solid fa-user-shield"></i></div>
                         <div>
                             <h4 style="font-size:14px; margin:0;">Admin Workspace</h4>
                             <p style="font-size:12px; color:#94a3b8; margin:0;">Full Access Permissions</p>
                         </div>
                     </div>
                 </div>
            </div>
        </div>
    </div>
</section>
@endsection
