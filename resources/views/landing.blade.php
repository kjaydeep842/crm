@extends('layouts.frontend')

@section('styles')
<style>
/* ── Hero Section ── */
.hero {
    padding: 180px 5% 120px;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    background: radial-gradient(circle at top right, rgba(79, 70, 229, 0.08), transparent 600px),
                radial-gradient(circle at bottom left, rgba(6, 182, 212, 0.05), transparent 600px);
    position: relative;
    overflow: hidden;
}
/* Floating shapes in hero */
.shape {
    position: absolute;
    z-index: -1;
    filter: blur(40px);
    opacity: 0.5;
    animation: float 8s ease-in-out infinite;
}
.shape-1 {
    width: 300px; height: 300px;
    background: rgba(79, 70, 229, 0.3);
    border-radius: 50%;
    top: -50px; left: -100px;
}
.shape-2 {
    width: 250px; height: 250px;
    background: rgba(6, 182, 212, 0.3);
    border-radius: 50%;
    bottom: 0; right: -50px;
    animation-delay: -4s;
}

.ai-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: rgba(238, 242, 255, 0.8);
    backdrop-filter: blur(8px);
    border: 1px solid #c7d2fe;
    border-radius: 999px;
    font-size: 13px;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 28px;
    box-shadow: 0 4px 10px rgba(79, 70, 229, 0.1);
    animation: pulse-glow 2.5s infinite;
}
.hero h1 {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 64px;
    font-weight: 800;
    color: var(--dark);
    line-height: 1.1;
    letter-spacing: -0.03em;
    max-width: 900px;
    margin-bottom: 24px;
}
.hero h1 span {
    background: linear-gradient(270deg, var(--primary), var(--secondary), #ec4899);
    background-size: 200% 200%;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: slideGradient 5s ease infinite;
}
.hero p {
    font-size: 20px;
    color: var(--text);
    max-width: 700px;
    margin-bottom: 48px;
}
.hero-actions {
    display: flex;
    align-items: center;
    gap: 20px;
}
.btn-primary {
    background: linear-gradient(135deg, var(--primary), #6366f1);
    color: #ffffff;
    border: none;
    padding: 16px 32px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 16px;
    cursor: pointer;
    box-shadow: 0 10px 25px rgba(79, 70, 229, 0.3);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s;
    position: relative;
    overflow: hidden;
}
.btn-primary::before {
    content: '';
    position: absolute;
    top: 0; left: -100%;
    width: 100%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: all 0.5s;
}
.btn-primary:hover::before {
    left: 100%;
}
.btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 30px rgba(79, 70, 229, 0.4);
    color: white;
}
.btn-secondary {
    background: var(--surface);
    color: var(--dark);
    border: 1px solid var(--border);
    padding: 16px 32px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 16px;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s;
    box-shadow: 0 4px 6px rgba(0,0,0,0.02);
}
.btn-secondary:hover {
    background: var(--bg-light);
    border-color: #cbd5e1;
    transform: translateY(-3px);
    box-shadow: 0 8px 15px rgba(0,0,0,0.05);
}

/* ── Logo Cloud ── */
.logo-cloud {
    padding: 40px 5%;
    background: var(--surface);
    border-top: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
    text-align: center;
}
.logo-cloud p {
    font-size: 14px;
    font-weight: 600;
    color: #94a3b8;
    margin-bottom: 24px;
    text-transform: uppercase;
    letter-spacing: 1px;
}
.logos {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 48px;
    opacity: 0.6;
}
.logos i {
    font-size: 32px;
    color: var(--text);
    transition: all 0.3s;
}
.logos i:hover {
    color: var(--primary);
    transform: scale(1.1);
}

/* ── Features Section ── */
.section-header {
    text-align: center;
    margin-bottom: 80px;
}
.section-header .tag {
    color: var(--primary);
    font-weight: 700;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 12px;
    display: block;
}
.section-header h2 {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 40px;
    font-weight: 800;
    color: var(--dark);
    letter-spacing: -0.02em;
    margin-bottom: 16px;
}
.section-header p {
    font-size: 18px;
    color: var(--text);
    max-width: 600px;
    margin: 0 auto;
}
.grid-features {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 40px;
    max-width: 1200px;
    margin: 0 auto;
}
.feature-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.02);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative;
    overflow: hidden;
    z-index: 1;
}
.feature-card::before {
    content: '';
    position: absolute;
    top: 0; right: 0; bottom: 0; left: 0;
    background: linear-gradient(135deg, rgba(79,70,229,0.05), rgba(6,182,212,0.05));
    z-index: -1;
    opacity: 0;
    transition: opacity 0.4s;
}
.feature-card:hover::before {
    opacity: 1;
}
.feature-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(79, 70, 229, 0.1);
    border-color: #c7d2fe;
}
.icon-box {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    background: linear-gradient(135deg, #eef2ff, #e0e7ff);
    color: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    margin-bottom: 24px;
    box-shadow: 0 4px 10px rgba(79,70,229,0.1);
    transition: transform 0.3s;
}
.feature-card:hover .icon-box {
    transform: scale(1.1) rotate(5deg);
}
.feature-card h3 {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 22px;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 16px;
}
.feature-card p {
    font-size: 16px;
    color: var(--text);
    line-height: 1.6;
}

/* ── How It Works ── */
.how-it-works {
    background: var(--dark);
    color: white;
    padding: 100px 5%;
    position: relative;
    overflow: hidden;
}
.how-it-works .section-header h2 {
    color: white;
}
.how-it-works .section-header p {
    color: #94a3b8;
}
.steps {
    display: flex;
    flex-direction: column;
    gap: 40px;
    max-width: 800px;
    margin: 0 auto;
    position: relative;
}
.steps::before {
    content: '';
    position: absolute;
    top: 20px; left: 24px;
    width: 2px; height: calc(100% - 40px);
    background: rgba(255,255,255,0.1);
}
.step {
    display: flex;
    gap: 30px;
    align-items: flex-start;
}
.step-number {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 20px;
    flex-shrink: 0;
    box-shadow: 0 0 20px rgba(79,70,229,0.5);
    position: relative;
    z-index: 2;
}
.step-content {
    background: rgba(255,255,255,0.05);
    padding: 30px;
    border-radius: 16px;
    border: 1px solid rgba(255,255,255,0.1);
    backdrop-filter: blur(10px);
    flex-grow: 1;
    transition: transform 0.3s, background 0.3s;
}
.step-content:hover {
    transform: translateX(10px);
    background: rgba(255,255,255,0.08);
}
.step-content h3 {
    font-size: 20px;
    margin-bottom: 10px;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.step-content p {
    color: #cbd5e1;
    font-size: 15px;
}

/* ── Pricing Section ── */
.pricing {
    background: var(--bg-light);
}
.pricing-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 30px;
    max-width: 1100px;
    margin: 0 auto;
}
.price-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 40px;
    text-align: center;
    position: relative;
    transition: all 0.3s;
}
.price-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.05);
}
.price-card.popular {
    border: 2px solid var(--primary);
    transform: scale(1.05);
    box-shadow: 0 20px 40px rgba(79, 70, 229, 0.15);
}
.price-card.popular:hover {
    transform: scale(1.05) translateY(-5px);
}
.popular-badge {
    position: absolute;
    top: -15px; left: 50%;
    transform: translateX(-50%);
    background: var(--primary);
    color: white;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
}
.price-card h3 {
    font-size: 24px;
    margin-bottom: 15px;
    color: var(--dark);
}
.price {
    font-size: 48px;
    font-weight: 800;
    color: var(--dark);
    margin-bottom: 20px;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.price span {
    font-size: 16px;
    color: var(--text);
    font-weight: 500;
}
.price-features {
    list-style: none;
    margin: 30px 0;
    text-align: left;
}
.price-features li {
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--text);
}
.price-features i {
    color: #059669;
}
.btn-price {
    width: 100%;
    padding: 14px;
    border-radius: 10px;
    font-weight: 700;
    text-decoration: none;
    display: block;
    transition: all 0.3s;
}
.btn-price-outline {
    border: 1px solid var(--primary);
    color: var(--primary);
}
.btn-price-outline:hover {
    background: var(--primary);
    color: white;
}
.btn-price-solid {
    background: var(--primary);
    color: white;
    border: 1px solid var(--primary);
}
.btn-price-solid:hover {
    background: var(--primary-hover);
}

/* ── Qualification / Lead Form Section ── */
.demo-section {
    background: linear-gradient(135deg, #ffffff, #f8fafc);
    position: relative;
}
.demo-container {
    max-width: 1100px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 1.1fr;
    gap: 80px;
    align-items: center;
}
.demo-text h2 {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 42px;
    font-weight: 800;
    color: var(--dark);
    letter-spacing: -0.02em;
    line-height: 1.2;
    margin-bottom: 20px;
}
.demo-text p {
    font-size: 18px;
    color: var(--text);
    line-height: 1.6;
    margin-bottom: 40px;
}
.bullet-point {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    margin-bottom: 24px;
    font-size: 16px;
    color: var(--text);
    background: var(--surface);
    padding: 16px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    transition: transform 0.3s;
}
.bullet-point:hover {
    transform: translateX(10px);
    border-left: 4px solid var(--primary);
}
.bullet-icon {
    color: #059669;
    font-size: 20px;
    margin-top: 2px;
}
.form-card {
    background: var(--surface);
    border: 1px solid var(--border);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.08);
    border-radius: 24px;
    padding: 40px;
    position: relative;
}
.form-card::after {
    content: '';
    position: absolute;
    top: -20px; right: -20px;
    width: 100px; height: 100px;
    background: radial-gradient(circle, rgba(79,70,229,0.2) 0%, transparent 70%);
    z-index: -1;
}
.form-group {
    margin-bottom: 24px;
}
.form-label {
    display: block;
    font-size: 13px;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
.form-input {
    width: 100%;
    border-radius: 12px;
    border: 1px solid #cbd5e1;
    padding: 14px 16px;
    font-size: 15px;
    color: var(--dark);
    outline: none;
    transition: all 0.2s;
    background: var(--bg-light);
    font-family: inherit;
}
.form-input:focus {
    border-color: var(--primary);
    background: var(--surface);
    box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
}
.submit-btn {
    width: 100%;
    background: linear-gradient(135deg, var(--primary), #6366f1);
    color: #ffffff;
    border: none;
    padding: 16px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 16px;
    cursor: pointer;
    box-shadow: 0 8px 20px rgba(79, 70, 229, 0.25);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: all 0.3s;
}
.submit-btn:hover {
    box-shadow: 0 12px 25px rgba(79, 70, 229, 0.35);
    transform: translateY(-2px);
}

/* ── Modal / Notification ── */
.modal {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(8px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2000;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease-in-out;
}
.modal.open {
    opacity: 1;
    pointer-events: auto;
}
.modal-card {
    background: #ffffff;
    border-radius: 24px;
    padding: 40px;
    max-width: 450px;
    width: 90%;
    text-align: center;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
    transform: scale(0.9) translateY(20px);
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
.modal.open .modal-card {
    transform: scale(1) translateY(0);
}
.modal-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: #d1fae5;
    color: #059669;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    margin: 0 auto 24px;
    box-shadow: 0 0 0 10px rgba(209, 250, 229, 0.5);
    animation: pulse-glow 2s infinite;
}
.modal h3 {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 24px;
    font-weight: 800;
    color: var(--dark);
    margin-bottom: 16px;
}
.modal p {
    font-size: 16px;
    color: var(--text);
    line-height: 1.6;
    margin-bottom: 32px;
}
.btn-close {
    background: #f1f5f9;
    color: var(--dark);
    border: none;
    padding: 14px 32px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 15px;
    cursor: pointer;
    transition: all 0.2s;
    width: 100%;
}
.btn-close:hover {
    background: #e2e8f0;
    transform: translateY(-2px);
}

@media (max-width: 992px) {
    .demo-container { grid-template-columns: 1fr; gap: 60px; }
    .hero h1 { font-size: 48px; }
    .price-card.popular { transform: none; }
    .price-card.popular:hover { transform: translateY(-5px); }
}
@media (max-width: 768px) {
    .hero { padding: 140px 5% 80px; }
    .hero h1 { font-size: 36px; }
    .hero p { font-size: 16px; }
    .hero-actions { flex-direction: column; width: 100%; }
    .btn-primary, .btn-secondary { width: 100%; justify-content: center; }
}
</style>
@endsection

@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        
        <div class="ai-badge" data-aos="fade-down" data-aos-delay="100">
            <i class="fa-solid fa-wand-magic-sparkles"></i>
            <span>Powered by DevineSky AI 2.0</span>
        </div>
        <h1 data-aos="fade-up" data-aos-delay="200">Enterprise CRM Redefined with <span>Autonomous AI Sales Copilots</span></h1>
        <p data-aos="fade-up" data-aos-delay="300">DevineSky captures, scores, qualifies, and generates instant WhatsApp and email follow-up drafts for every inbound lead. Maximize your conversions automatically.</p>
        <div class="hero-actions" data-aos="fade-up" data-aos-delay="400">
            <a href="#demo" class="btn-primary">
                <span>Start Free Trial</span>
                <i class="fa-solid fa-arrow-right"></i>
            </a>
            <a href="{{ route('login') }}" class="btn-secondary">
                <span>Access Dashboard</span>
            </a>
        </div>
    </section>

    <!-- Logo Cloud -->
    <div class="logo-cloud">
        <p data-aos="fade-up">Trusted by innovative teams worldwide</p>
        <div class="logos" data-aos="fade-up" data-aos-delay="100">
            <i class="fa-brands fa-aws"></i>
            <i class="fa-brands fa-slack"></i>
            <i class="fa-brands fa-stripe"></i>
            <i class="fa-brands fa-google"></i>
            <i class="fa-brands fa-microsoft"></i>
        </div>
    </div>

    <!-- Features Section -->
    <section class="section-padding features" id="features">
        <div class="section-header" data-aos="fade-up">
            <span class="tag">Features</span>
            <h2>Core Platform Features</h2>
            <p>Everything you need to automate your sales funnel and empower your team.</p>
        </div>
        <div class="grid-features">
            <!-- Feature 1 -->
            <div class="feature-card" data-aos="fade-up" data-aos-delay="100">
                <div class="icon-box">
                    <i class="fa-solid fa-brain"></i>
                </div>
                <h3>AI Qualification & Scoring</h3>
                <p>Automated buyer intent parsing, budget estimation, urgency classification, and custom scoring to prioritize hot leads in real-time.</p>
            </div>
            <!-- Feature 2 -->
            <div class="feature-card" data-aos="fade-up" data-aos-delay="200">
                <div class="icon-box">
                    <i class="fa-brands fa-whatsapp"></i>
                </div>
                <h3>Omnichannel Routing</h3>
                <p>Seamlessly integrate email and WhatsApp API webhooks. Qualify and reply to prospects instantly without lifting a finger.</p>
            </div>
            <!-- Feature 3 -->
            <div class="feature-card" data-aos="fade-up" data-aos-delay="300">
                <div class="icon-box">
                    <i class="fa-solid fa-chart-pie"></i>
                </div>
                <h3>Advanced Analytics</h3>
                <p>Gain deep insights into your sales pipeline, agent performance, and conversion metrics through interactive, real-time dashboards.</p>
            </div>
            <!-- Feature 4 -->
            <div class="feature-card" data-aos="fade-up" data-aos-delay="400">
                <div class="icon-box">
                    <i class="fa-solid fa-building-user"></i>
                </div>
                <h3>Multi-Tenant Architecture</h3>
                <p>Designed for organizations of all sizes. Separate and scale workspaces with strict SuperAdmin, Admin, and Staff RBAC permissions.</p>
            </div>
            <!-- Feature 5 -->
            <div class="feature-card" data-aos="fade-up" data-aos-delay="100">
                <div class="icon-box" style="background:linear-gradient(135deg, #eef2ff, #e0e7ff); color:#4285f4;">
                    <i class="fa-brands fa-google"></i>
                </div>
                <h3>Google SSO Login</h3>
                <p>Allow admins, staff, and employees to securely sign in using their Google Workspace accounts with automatic organization resolution.</p>
            </div>
            <!-- Feature 6 -->
            <div class="feature-card" data-aos="fade-up" data-aos-delay="200">
                <div class="icon-box" style="background:linear-gradient(135deg, #f0fdf4, #dcfce7); color:#15803d;">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <h3>Continuous Audit Trails</h3>
                <p>Complete compliance tracking. Every key action, authentication event, and invoice payment logs the user's role and their client IP.</p>
            </div>
            <!-- Feature 7 -->
            <div class="feature-card" data-aos="fade-up" data-aos-delay="300">
                <div class="icon-box" style="background:linear-gradient(135deg, #fffbeb, #fef3c7); color:#d97706;">
                    <i class="fa-solid fa-credit-card"></i>
                </div>
                <h3>Razorpay Subscriptions</h3>
                <p>Secure payment checkouts. Upgrade workspaces via lifetime one-time payments or monthly auto-renewals with automatic webhook sync.</p>
            </div>
            <!-- Feature 8 -->
            <div class="feature-card" data-aos="fade-up" data-aos-delay="400">
                <div class="icon-box" style="background:linear-gradient(135deg, #fdf2f8, #fce7f3); color:#db2777;">
                    <i class="fa-solid fa-file-pdf"></i>
                </div>
                <h3>Downloadable PDF Invoices</h3>
                <p>A4 tax invoices generated dynamically. Track total paid, subtotal, GST (18%), and download PDFs on demand from your billing history.</p>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="how-it-works" id="how-it-works">
        <div class="section-header" data-aos="fade-up">
            <span class="tag" style="color: #38bdf8;">Workflow</span>
            <h2>How DevineSky AI Works</h2>
            <p>Automate your entire sales process from lead capture to closing.</p>
        </div>
        <div class="steps">
            <div class="step" data-aos="fade-left" data-aos-delay="100">
                <div class="step-number">1</div>
                <div class="step-content">
                    <h3>Lead Capture</h3>
                    <p>Integrate your forms, email, and social channels. DevineSky instantly captures all incoming lead data into a unified dashboard.</p>
                </div>
            </div>
            <div class="step" data-aos="fade-right" data-aos-delay="200">
                <div class="step-number">2</div>
                <div class="step-content">
                    <h3>DevineSky Analysis & Qualification</h3>
                    <p>Our proprietary AI engine analyzes intent, validates contact info, and scores the lead based on budget and urgency.</p>
                </div>
            </div>
            <div class="step" data-aos="fade-left" data-aos-delay="300">
                <div class="step-number">3</div>
                <div class="step-content">
                    <h3>Automated Outreach</h3>
                    <p>DevineSky drafts and schedules personalized email and WhatsApp follow-ups tailored to the lead's specific requirements.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="section-padding pricing" id="pricing">
        <div class="section-header" data-aos="fade-up">
            <span class="tag">Pricing Plans</span>
            <h2>Simple, Secure Billing via Razorpay</h2>
            <p>Choose between flexible Monthly Subscriptions or One-Time upgrades for lifetime access.</p>
        </div>
        <div class="pricing-grid">
            <!-- Starter -->
            <div class="price-card" data-aos="fade-up" data-aos-delay="100">
                <h3>Starter</h3>
                <div class="price">₹2,499<span>/mo</span></div>
                <p style="font-size:12px; color:#64748b; margin-top:-10px; margin-bottom:15px;">or ₹23,999/year one-time</p>
                <p>Perfect for small teams getting started with AI.</p>
                <ul class="price-features">
                    <li><i class="fa-solid fa-check"></i> Up to 5 Active Users</li>
                    <li><i class="fa-solid fa-check"></i> 1,000 AI Credits / month</li>
                    <li><i class="fa-solid fa-check"></i> Standard Lead Scoring</li>
                    <li><i class="fa-solid fa-check"></i> Email Template Builder</li>
                    <li><i class="fa-solid fa-check"></i> Standard Support</li>
                </ul>
                <a href="#demo" class="btn-price btn-price-outline">Get Started Now</a>
            </div>
            <!-- Professional -->
            <div class="price-card popular" data-aos="fade-up" data-aos-delay="200">
                <div class="popular-badge">Most Popular</div>
                <h3>Professional</h3>
                <div class="price">₹7,499<span>/mo</span></div>
                <p style="font-size:12px; color:#c7d2fe; margin-top:-10px; margin-bottom:15px;">or ₹71,999/year one-time</p>
                <p>Advanced features for growing sales teams.</p>
                <ul class="price-features">
                    <li><i class="fa-solid fa-check"></i> Up to 15 Active Users</li>
                    <li><i class="fa-solid fa-check"></i> 10,000 AI Credits / month</li>
                    <li><i class="fa-solid fa-check"></i> WhatsApp Cloud API Webhooks</li>
                    <li><i class="fa-solid fa-check"></i> Automated Follow-up Emails</li>
                    <li><i class="fa-solid fa-check"></i> Dynamic PDF Invoice Downloads</li>
                    <li><i class="fa-solid fa-check"></i> Priority Support</li>
                </ul>
                <a href="#demo" class="btn-price btn-price-solid">Start Trial</a>
            </div>
            <!-- Business / Enterprise -->
            <div class="price-card" data-aos="fade-up" data-aos-delay="300">
                <h3>Business</h3>
                <div class="price">₹19,999<span>/mo</span></div>
                <p style="font-size:12px; color:#64748b; margin-top:-10px; margin-bottom:15px;">or ₹1,91,999/year one-time</p>
                <p>Dedicated solutions for large scale organizations.</p>
                <ul class="price-features">
                    <li><i class="fa-solid fa-check"></i> Up to 50 Active Users</li>
                    <li><i class="fa-solid fa-check"></i> 50,000 AI Credits / month</li>
                    <li><i class="fa-solid fa-check"></i> Dedicated OpenAI Agent Tuning</li>
                    <li><i class="fa-solid fa-check"></i> Continuous Activity Audit Trails</li>
                    <li><i class="fa-solid fa-check"></i> Custom Integrations & APIs</li>
                    <li><i class="fa-solid fa-check"></i> Dedicated Account Manager</li>
                </ul>
                <a href="#demo" class="btn-price btn-price-outline">Contact Sales</a>
            </div>
        </div>
    </section>

    <!-- Demo / Form Section -->
    <section class="section-padding demo-section" id="demo">
        <div class="demo-container">
            <div class="demo-text" data-aos="fade-right">
                <h2>Test Our Real-Time AI Qualification</h2>
                <p>Submit your software requirements below. Our integrated AI Engine will analyze your intent, estimate your budget, and qualify you instantly.</p>
                
                <div class="bullet-point">
                    <i class="fa-solid fa-circle-check bullet-icon"></i>
                    <div>
                        <strong>Automated Routing</strong>
                        <div style="font-size: 14px; margin-top: 4px;">Instantly qualifying inputs and assigning to correct sales departments.</div>
                    </div>
                </div>
                <div class="bullet-point">
                    <i class="fa-solid fa-circle-check bullet-icon"></i>
                    <div>
                        <strong>Tailored Response</strong>
                        <div style="font-size: 14px; margin-top: 4px;">Auto-drafting custom WhatsApp/Email followups.</div>
                    </div>
                </div>
                <div class="bullet-point">
                    <i class="fa-solid fa-circle-check bullet-icon"></i>
                    <div>
                        <strong>Secure Workspace</strong>
                        <div style="font-size: 14px; margin-top: 4px;">Fully integrated multi-org data protection.</div>
                    </div>
                </div>
            </div>

            <!-- Public Inquiry Form -->
            <div class="form-card" data-aos="fade-left">
                <form id="inquiryForm" onsubmit="submitInquiry(event)">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Full Name *</label>
                        <input type="text" id="name" required placeholder="Jane Doe" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email Address *</label>
                        <input type="email" id="email" required placeholder="jane@example.com" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Mobile Number</label>
                        <input type="text" id="mobile" placeholder="+1 555-0199" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Explain Your Requirements *</label>
                        <textarea id="requirement" required rows="4" placeholder="I need an AI-powered CRM with WhatsApp automation and custom reports for a team of 15 members." class="form-input" style="resize:none;"></textarea>
                    </div>
                    
                    <button type="submit" class="submit-btn" id="submitBtn">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                        <span>Submit & Qualify</span>
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Modal Success Notification -->
    <div class="modal" id="successModal">
        <div class="modal-card">
            <div class="modal-icon">
                <i class="fa-solid fa-check"></i>
            </div>
            <h3 id="modalTitle">Inquiry Qualified!</h3>
            <p id="modalBody">Thank you for submitting your requirements. Our AI agent has run qualifications and saved the inquiry to our CRM desk.</p>
            <button onclick="closeModal()" class="btn-close">Close</button>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Form Submit Handler
        function submitInquiry(event) {
            event.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> <span>Processing AI Insights...</span>';
            
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const mobile = document.getElementById('mobile').value;
            const requirement = document.getElementById('requirement').value;
            const token = document.querySelector('input[name="_token"]').value;
            
            fetch('/inquire', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({
                    name: name,
                    email: email,
                    mobile: mobile,
                    requirement: requirement
                })
            })
            .then(res => res.json())
            .then(data => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                
                if(data.success) {
                    document.getElementById('modalBody').innerText = data.message;
                    document.getElementById('successModal').classList.add('open');
                    document.getElementById('inquiryForm').reset();
                } else {
                    alert('Something went wrong. Please try again.');
                }
            })
            .catch(err => {
                console.error(err);
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                alert('Connection error. Please try again.');
            });
        }
        
        function closeModal() {
            document.getElementById('successModal').classList.remove('open');
        }
    </script>
@endsection
