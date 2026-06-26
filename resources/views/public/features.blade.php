@extends('layouts.frontend')
@section('title', 'Features | AI WhatsApp CRM')
@section('content')
<div class="page-header">
    <h1 data-aos="fade-up">AI WhatsApp CRM & Sales Automation</h1>
    <p data-aos="fade-up" data-aos-delay="100">Everything you need to automate your sales, qualify leads, and close deals directly on WhatsApp.</p>
</div>
<section class="section-padding" style="background:var(--surface);">
    <div style="max-width:1200px; margin:0 auto;">
        
        <!-- Feature 1: CRM -->
        <div style="display:flex; flex-wrap:wrap; align-items:center; gap:40px; margin-bottom:80px;" data-aos="fade-up">
            <div style="flex:1; min-width:300px;">
                <div style="width:60px; height:60px; background:rgba(79,70,229,0.1); color:var(--primary); border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:24px; margin-bottom:20px;">
                    <i class="fa-solid fa-users"></i>
                </div>
                <h2 style="font-family:'Plus Jakarta Sans', sans-serif; font-size:32px; color:var(--dark); margin-bottom:16px;">Powerful CRM</h2>
                <p style="color:var(--text); font-size:16px; margin-bottom:24px;">Manage your entire sales pipeline in one place. Keep track of every lead, contact, and deal without dropping the ball.</p>
                <ul style="list-style:none;">
                    <li style="margin-bottom:12px; color:var(--text);"><i class="fa-solid fa-check" style="color:#059669; margin-right:8px;"></i> Lead & Contact Management</li>
                    <li style="margin-bottom:12px; color:var(--text);"><i class="fa-solid fa-check" style="color:#059669; margin-right:8px;"></i> Visual Deal Pipeline</li>
                    <li style="margin-bottom:12px; color:var(--text);"><i class="fa-solid fa-check" style="color:#059669; margin-right:8px;"></i> Tasks, Reminders & Notes</li>
                    <li style="margin-bottom:12px; color:var(--text);"><i class="fa-solid fa-check" style="color:#059669; margin-right:8px;"></i> Team Management</li>
                </ul>
            </div>
            <div style="flex:1; min-width:300px; background:linear-gradient(135deg, #f8fafc, #eef2ff); border:1px solid var(--border); border-radius:24px; padding:40px; box-shadow:0 10px 30px rgba(0,0,0,0.02);">
                 <div style="background:white; padding:20px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.05); border-left:4px solid var(--primary);">
                     <div style="display:flex; justify-content: space-between; align-items: center; margin-bottom:15px;">
                        <span style="font-weight:600; color:var(--dark);">Deal: Website Redesign</span>
                        <span style="background:#dcf8c6; color:#059669; padding:4px 8px; border-radius:12px; font-size:12px; font-weight:700;">Negotiation</span>
                     </div>
                     <p style="font-size:14px; color:var(--text); margin-bottom:10px;"><strong>Value:</strong> $15,000</p>
                     <div style="height:8px; background:#e2e8f0; border-radius:4px; overflow:hidden;">
                         <div style="width:75%; height:100%; background:var(--primary);"></div>
                     </div>
                 </div>
            </div>
        </div>

        <!-- Feature 2: WhatsApp -->
        <div style="display:flex; flex-wrap:wrap; align-items:center; flex-direction:row-reverse; gap:40px; margin-bottom:80px;" data-aos="fade-up">
            <div style="flex:1; min-width:300px;">
                <div style="width:60px; height:60px; background:rgba(6,182,212,0.1); color:var(--secondary); border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:24px; margin-bottom:20px;">
                    <i class="fa-brands fa-whatsapp"></i>
                </div>
                <h2 style="font-family:'Plus Jakarta Sans', sans-serif; font-size:32px; color:var(--dark); margin-bottom:16px;">WhatsApp Integration</h2>
                <p style="color:var(--text); font-size:16px; margin-bottom:24px;">Connect your business directly to the official WhatsApp Cloud API. Manage conversations with a shared team inbox and launch targeted broadcast campaigns.</p>
                <ul style="list-style:none;">
                    <li style="margin-bottom:12px; color:var(--text);"><i class="fa-solid fa-check" style="color:#059669; margin-right:8px;"></i> Official WhatsApp Cloud API</li>
                    <li style="margin-bottom:12px; color:var(--text);"><i class="fa-solid fa-check" style="color:#059669; margin-right:8px;"></i> Shared Team Inbox & Chat Assignment</li>
                    <li style="margin-bottom:12px; color:var(--text);"><i class="fa-solid fa-check" style="color:#059669; margin-right:8px;"></i> Broadcast Campaigns & Template Messages</li>
                    <li style="margin-bottom:12px; color:var(--text);"><i class="fa-solid fa-check" style="color:#059669; margin-right:8px;"></i> Auto-replies & Conversation History</li>
                </ul>
            </div>
            <div style="flex:1; min-width:300px; background:linear-gradient(135deg, #f8fafc, #ecfeff); border:1px solid var(--border); border-radius:24px; padding:40px; box-shadow:0 10px 30px rgba(0,0,0,0.02);">
                 <div style="background:white; padding:20px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.05);">
                     <p style="font-size:14px; background:#dcf8c6; padding:10px 15px; border-radius:15px 15px 0 15px; display:inline-block; margin-bottom:10px; margin-left:auto;">Hi! We received your inquiry. Let's schedule a call this week.</p>
                     <p style="font-size:12px; color:#94a3b8; text-align:right;">Sent via WhatsApp API</p>
                 </div>
            </div>
        </div>

        <!-- Feature 3: AI Features -->
        <div style="display:flex; flex-wrap:wrap; align-items:center; gap:40px; margin-bottom:80px;" data-aos="fade-up">
            <div style="flex:1; min-width:300px;">
                <div style="width:60px; height:60px; background:rgba(236,72,153,0.1); color:#ec4899; border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:24px; margin-bottom:20px;">
                    <i class="fa-solid fa-robot"></i>
                </div>
                <h2 style="font-family:'Plus Jakarta Sans', sans-serif; font-size:32px; color:var(--dark); margin-bottom:16px;">Intelligent AI Features</h2>
                <p style="color:var(--text); font-size:16px; margin-bottom:24px;">Supercharge your sales with an autonomous AI copilot that qualifies leads, drafts responses, and suggests follow-ups instantly.</p>
                <ul style="list-style:none;">
                    <li style="margin-bottom:12px; color:var(--text);"><i class="fa-solid fa-check" style="color:#059669; margin-right:8px;"></i> AI Chatbot & Generated Replies</li>
                    <li style="margin-bottom:12px; color:var(--text);"><i class="fa-solid fa-check" style="color:#059669; margin-right:8px;"></i> AI Lead Qualification</li>
                    <li style="margin-bottom:12px; color:var(--text);"><i class="fa-solid fa-check" style="color:#059669; margin-right:8px;"></i> AI Call/Transcript Summaries</li>
                    <li style="margin-bottom:12px; color:var(--text);"><i class="fa-solid fa-check" style="color:#059669; margin-right:8px;"></i> AI Quotation & Email Drafting</li>
                </ul>
            </div>
            <div style="flex:1; min-width:300px; background:linear-gradient(135deg, #f8fafc, #fdf2f8); border:1px solid var(--border); border-radius:24px; padding:40px; box-shadow:0 10px 30px rgba(0,0,0,0.02);">
                 <div style="background:white; padding:20px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.05); border-left:4px solid #ec4899;">
                     <p style="font-size:14px; color:var(--text); margin-bottom:10px;"><strong>AI Insight:</strong> Prospect shows high intent. Suggested reply drafted below.</p>
                     <div style="background:#f1f5f9; padding:10px; border-radius:8px; font-size:13px; color:var(--text);">
                         "I can definitely help with that. Would you be available for a quick 10-minute demo tomorrow at 2 PM?"
                     </div>
                 </div>
            </div>
        </div>
        
        <!-- Feature 4: Sales Automation -->
        <div style="display:flex; flex-wrap:wrap; align-items:center; flex-direction:row-reverse; gap:40px; margin-bottom:80px;" data-aos="fade-up">
            <div style="flex:1; min-width:300px;">
                <div style="width:60px; height:60px; background:rgba(245,158,11,0.1); color:#f59e0b; border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:24px; margin-bottom:20px;">
                    <i class="fa-solid fa-bolt"></i>
                </div>
                <h2 style="font-family:'Plus Jakarta Sans', sans-serif; font-size:32px; color:var(--dark); margin-bottom:16px;">Sales Automation</h2>
                <p style="color:var(--text); font-size:16px; margin-bottom:24px;">Put your sales process on autopilot. Automate repetitive tasks so your team can focus on closing deals.</p>
                <ul style="list-style:none;">
                    <li style="margin-bottom:12px; color:var(--text);"><i class="fa-solid fa-check" style="color:#059669; margin-right:8px;"></i> Automatic Follow-up Sequences</li>
                    <li style="margin-bottom:12px; color:var(--text);"><i class="fa-solid fa-check" style="color:#059669; margin-right:8px;"></i> Lead Scoring & Routing</li>
                    <li style="margin-bottom:12px; color:var(--text);"><i class="fa-solid fa-check" style="color:#059669; margin-right:8px;"></i> Sales Pipeline Automation</li>
                    <li style="margin-bottom:12px; color:var(--text);"><i class="fa-solid fa-check" style="color:#059669; margin-right:8px;"></i> Automated Quotation & Invoice Gen</li>
                </ul>
            </div>
            <div style="flex:1; min-width:300px; background:linear-gradient(135deg, #f8fafc, #fffbeb); border:1px solid var(--border); border-radius:24px; padding:40px; box-shadow:0 10px 30px rgba(0,0,0,0.02);">
                 <div style="background:white; padding:20px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.05); text-align:center;">
                     <div style="display:inline-block; padding:10px 20px; background:#f59e0b; color:white; border-radius:20px; font-weight:600; font-size:14px; margin-bottom:15px;">
                         Trigger: Lead Added
                     </div>
                     <div style="font-size:20px; color:#cbd5e1; margin-bottom:15px;"><i class="fa-solid fa-arrow-down"></i></div>
                     <div style="display:inline-block; padding:10px 20px; border:1px solid var(--border); color:var(--dark); border-radius:20px; font-weight:600; font-size:14px;">
                         Action: Send WhatsApp Welcome Message
                     </div>
                 </div>
            </div>
        </div>

        <!-- Feature 5: Reports -->
        <div style="display:flex; flex-wrap:wrap; align-items:center; gap:40px;" data-aos="fade-up">
            <div style="flex:1; min-width:300px;">
                <div style="width:60px; height:60px; background:rgba(16,185,129,0.1); color:#10b981; border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:24px; margin-bottom:20px;">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
                <h2 style="font-family:'Plus Jakarta Sans', sans-serif; font-size:32px; color:var(--dark); margin-bottom:16px;">Advanced Reports</h2>
                <p style="color:var(--text); font-size:16px; margin-bottom:24px;">Gain deep insights into your sales performance with comprehensive analytics and customizable dashboards.</p>
                <ul style="list-style:none;">
                    <li style="margin-bottom:12px; color:var(--text);"><i class="fa-solid fa-check" style="color:#059669; margin-right:8px;"></i> Real-time Sales Dashboard</li>
                    <li style="margin-bottom:12px; color:var(--text);"><i class="fa-solid fa-check" style="color:#059669; margin-right:8px;"></i> Conversion & Revenue Analytics</li>
                    <li style="margin-bottom:12px; color:var(--text);"><i class="fa-solid fa-check" style="color:#059669; margin-right:8px;"></i> Employee Performance Metrics</li>
                    <li style="margin-bottom:12px; color:var(--text);"><i class="fa-solid fa-check" style="color:#059669; margin-right:8px;"></i> Broadcast Campaign Performance</li>
                </ul>
            </div>
            <div style="flex:1; min-width:300px; background:linear-gradient(135deg, #f8fafc, #ecfdf5); border:1px solid var(--border); border-radius:24px; padding:40px; box-shadow:0 10px 30px rgba(0,0,0,0.02);">
                 <div style="background:white; padding:20px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.05);">
                     <div style="display:flex; align-items:flex-end; gap:10px; height:120px; border-bottom:1px solid var(--border); padding-bottom:10px;">
                         <div style="flex:1; background:#10b981; height:40%; border-radius:4px 4px 0 0;"></div>
                         <div style="flex:1; background:#10b981; height:70%; border-radius:4px 4px 0 0;"></div>
                         <div style="flex:1; background:#10b981; height:50%; border-radius:4px 4px 0 0;"></div>
                         <div style="flex:1; background:#10b981; height:90%; border-radius:4px 4px 0 0;"></div>
                     </div>
                     <p style="text-align:center; font-size:12px; color:var(--text); margin-top:10px; font-weight:600;">Monthly Revenue Growth</p>
                 </div>
            </div>
        </div>

    </div>
</section>
@endsection
