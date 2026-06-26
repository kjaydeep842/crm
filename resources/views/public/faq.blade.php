@extends('layouts.frontend')
@section('title', 'Frequently Asked Questions | DevineSky')

@section('styles')
<style>
    .faq-container {
        max-width: 800px;
        margin: 0 auto;
    }
    .faq-item {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 16px;
        margin-bottom: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .faq-item:hover {
        box-shadow: 0 10px 25px rgba(79, 70, 229, 0.05);
        border-color: #c7d2fe;
    }
    .faq-question {
        padding: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        font-weight: 700;
        font-size: 16px;
        color: var(--dark);
        user-select: none;
    }
    .faq-answer {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s cubic-bezier(0, 1, 0, 1);
        padding: 0 24px;
        color: var(--text);
        font-size: 15px;
        line-height: 1.7;
    }
    .faq-item.active .faq-answer {
        max-height: 1000px;
        padding-bottom: 24px;
        transition: max-height 0.3s cubic-bezier(1, 0, 1, 0);
    }
    .faq-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 12px;
        transition: all 0.3s;
    }
    .faq-item.active .faq-icon {
        transform: rotate(180deg);
        background: var(--primary);
        color: #ffffff;
    }
    .support-cta {
        text-align: center;
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
        border-radius: 24px;
        padding: 60px 40px;
        color: #ffffff;
        margin-top: 80px;
        position: relative;
        overflow: hidden;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <h1 data-aos="fade-up">Frequently Asked Questions</h1>
    <p data-aos="fade-up" data-aos-delay="100">Have questions about DevineSky AI CRM? We have answers. Find resources and learn how to configure your team.</p>
</div>

<section class="section-padding" style="background:var(--bg-light);">
    <div class="faq-container">
        
        <div class="faq-item" data-aos="fade-up" data-aos-delay="100">
            <div class="faq-question">
                <span>How does the AI lead qualification and scoring work?</span>
                <div class="faq-icon"><i class="fa-solid fa-chevron-down"></i></div>
            </div>
            <div class="faq-answer">
                <p>When an inquiry is submitted via your website forms, WhatsApp API, or emails, our integrated AI analyze the message content. It extracts the customer's intent, estimates their budget, sets urgency levels, recommends the best sales department, and scores the lead from 1 to 100. Hot leads are immediately assigned to agents and a response is drafted automatically.</p>
            </div>
        </div>

        <div class="faq-item" data-aos="fade-up" data-aos-delay="150">
            <div class="faq-question">
                <span>Can we toggle between One-Time upgrades and monthly subscriptions?</span>
                <div class="faq-icon"><i class="fa-solid fa-chevron-down"></i></div>
            </div>
            <div class="faq-answer">
                <p>Yes, absolutely! On your Organization settings under the "Plan & Billing" tab, you can choose between a Lifetime One-Time Upgrade or a Monthly Subscription. Both payment flows are secure and powered by Razorpay integration.</p>
            </div>
        </div>

        <div class="faq-item" data-aos="fade-up" data-aos-delay="200">
            <div class="faq-question">
                <span>Is Google OAuth login available for staff and employees?</span>
                <div class="faq-icon"><i class="fa-solid fa-chevron-down"></i></div>
            </div>
            <div class="faq-answer">
                <p>Yes, we support native Google OAuth. Users can sign in with their Google accounts. If the email matches an active user in any organization, they are automatically logged in and redirected directly to their team dashboard with their respective role permissions (SuperAdmin, Admin, or Staff).</p>
            </div>
        </div>

        <div class="faq-item" data-aos="fade-up" data-aos-delay="250">
            <div class="faq-question">
                <span>How do we download invoices for tax or accounting purposes?</span>
                <div class="faq-icon"><i class="fa-solid fa-chevron-down"></i></div>
            </div>
            <div class="faq-answer">
                <p>You can access your complete payment history under the "Billing History" page. For every successful transaction, a dedicated "Invoice PDF" button is available. The PDF is generated with a clean layout including Subtotal, GST, and Razorpay references, ready for download.</p>
            </div>
        </div>

        <div class="faq-item" data-aos="fade-up" data-aos-delay="300">
            <div class="faq-question">
                <span>Do you offer custom email and notification templates?</span>
                <div class="faq-icon"><i class="fa-solid fa-chevron-down"></i></div>
            </div>
            <div class="faq-answer">
                <p>Yes! DevineSky includes a dynamic template builder. Admins can create and customize email notification templates with dynamic variables like <code>&#123;&#123;lead_name&#125;&#125;</code> and <code>&#123;&#123;agent_name&#125;&#125;</code> to personalize automated response emails.</p>
            </div>
        </div>

        <div class="faq-item" data-aos="fade-up" data-aos-delay="350">
            <div class="faq-question">
                <span>Is the WhatsApp integration utilizing the official Cloud API?</span>
                <div class="faq-icon"><i class="fa-solid fa-chevron-down"></i></div>
            </div>
            <div class="faq-answer">
                <p>Yes. DevineSky directly interfaces with the Meta WhatsApp Cloud API. This ensures fast delivery, compliance with WhatsApp policies, template message support, and real-time webhook parsing for inbound replies.</p>
            </div>
        </div>

        <div class="support-cta" data-aos="zoom-in">
            <h2 style="font-family:'Plus Jakarta Sans', sans-serif; font-size:32px; font-weight:800; margin-bottom:16px;">Still have questions?</h2>
            <p style="color:#cbd5e1; font-size:16px; margin-bottom:32px; max-width:600px; margin-left:auto; margin-right:auto;">Our support team is available 24/7. Get in touch to learn how DevineSky can automate your sales.</p>
            <a href="{{ route('public.contact') }}" class="btn-primary" style="background:#ffffff; color:var(--primary); box-shadow:none;">
                <span>Contact Support</span>
                <i class="fa-solid fa-envelope"></i>
            </a>
        </div>

    </div>
</section>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.faq-question').forEach(q => {
        q.addEventListener('click', () => {
            const item = q.parentElement;
            item.classList.toggle('active');
        });
    });
</script>
@endsection
