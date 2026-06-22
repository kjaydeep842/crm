<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aura CRM - Enterprise AI Customer Relationship Management</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            line-height: 1.5;
            overflow-x: hidden;
        }

        /* ── Header / Navigation ── */
        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 72px;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 40px;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .logo-box {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(99, 102, 241, 0.2);
        }
        .brand-name {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 18px;
            font-weight: 800;
            background: linear-gradient(135deg, #1e293b, #4f46e5);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 32px;
        }
        .nav-link {
            text-decoration: none;
            color: #475569;
            font-weight: 600;
            font-size: 14px;
            transition: color 0.15s;
        }
        .nav-link:hover {
            color: #4f46e5;
        }
        .btn-login {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            color: #4f46e5;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.15s;
        }
        .btn-login:hover {
            background: #f1f5f9;
            border-color: #6366f1;
        }

        /* ── Hero Section ── */
        .hero {
            padding: 160px 40px 100px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            background: radial-gradient(circle at top right, rgba(99, 102, 241, 0.05), transparent 600px),
                        radial-gradient(circle at bottom left, rgba(6, 182, 212, 0.03), transparent 600px);
            position: relative;
        }
        .ai-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: #eef2ff;
            border: 1px solid #c7d2fe;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            color: #4f46e5;
            margin-bottom: 24px;
        }
        .hero h1 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 54px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.15;
            letter-spacing: -0.03em;
            max-width: 900px;
            margin-bottom: 24px;
        }
        .hero h1 span {
            background: linear-gradient(135deg, #4f46e5, #06b6d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero p {
            font-size: 18px;
            color: #475569;
            max-width: 650px;
            margin-bottom: 40px;
        }
        .hero-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: #ffffff;
            border: none;
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.3);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4);
        }
        .btn-secondary {
            background: #ffffff;
            color: #334155;
            border: 1px solid #cbd5e1;
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.15s;
        }
        .btn-secondary:hover {
            background: #f8fafc;
            border-color: #94a3b8;
        }

        /* ── Features Section ── */
        .features {
            padding: 80px 40px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .section-header {
            text-align: center;
            margin-bottom: 60px;
        }
        .section-header h2 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 32px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.02em;
            margin-bottom: 12px;
        }
        .section-header p {
            font-size: 16px;
            color: #64748b;
        }
        .grid-features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 32px;
        }
        .feature-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
            transition: all 0.2s;
        }
        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border-color: #cbd5e1;
        }
        .icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: #eef2ff;
            color: #4f46e5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 24px;
        }
        .feature-card h3 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 12px;
        }
        .feature-card p {
            font-size: 14px;
            color: #64748b;
            line-height: 1.6;
        }

        /* ── Qualification / Lead Form Section ── */
        .demo-section {
            padding: 80px 40px;
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
        }
        .demo-container {
            max-width: 1000px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            gap: 60px;
            align-items: center;
        }
        .demo-text h2 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 36px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.02em;
            line-height: 1.2;
            margin-bottom: 16px;
        }
        .demo-text p {
            font-size: 15px;
            color: #475569;
            line-height: 1.6;
            margin-bottom: 24px;
        }
        .bullet-point {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 16px;
            font-size: 14px;
            color: #475569;
        }
        .bullet-icon {
            color: #059669;
            font-size: 16px;
            margin-top: 2px;
        }
        .form-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            border-radius: 20px;
            padding: 32px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #475569;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .form-input {
            width: 100%;
            border-radius: 10px;
            border: 1px solid #cbd5e1;
            padding: 12px;
            font-size: 14px;
            color: #1e293b;
            outline: none;
            transition: all 0.15s;
        }
        .form-input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
        .submit-btn {
            width: 100%;
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: #ffffff;
            border: none;
            padding: 14px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.15s;
        }
        .submit-btn:hover {
            box-shadow: 0 6px 18px rgba(79, 70, 229, 0.3);
        }

        /* ── Modal / Notification ── */
        .modal {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2000;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease-in-out;
        }
        .modal.open {
            opacity: 1;
            pointer-events: auto;
        }
        .modal-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 32px;
            max-width: 480px;
            width: 90%;
            text-align: center;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
            transform: scale(0.9);
            transition: transform 0.2s ease-in-out;
        }
        .modal.open .modal-card {
            transform: scale(1);
        }
        .modal-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: #d1fae5;
            color: #059669;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin: 0 auto 20px;
        }
        .modal h3 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 12px;
        }
        .modal p {
            font-size: 14px;
            color: #475569;
            line-height: 1.6;
            margin-bottom: 24px;
        }
        .btn-close {
            background: #f1f5f9;
            color: #475569;
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            transition: background 0.15s;
        }
        .btn-close:hover {
            background: #e2e8f0;
        }

        /* ── Footer ── */
        footer {
            padding: 48px 40px;
            background: #0f172a;
            color: #94a3b8;
            text-align: center;
            font-size: 13px;
        }
        footer p {
            margin-bottom: 8px;
        }
        
        @media (max-width: 768px) {
            header { padding: 0 20px; }
            .nav-links { display: none; }
            .hero h1 { font-size: 36px; }
            .demo-container { grid-template-columns: 1fr; gap: 40px; }
        }
    </style>
</head>
<body>

    <!-- Header Navigation -->
    <header>
        <a href="/" class="brand">
            <div class="logo-box">
                <i class="fa-solid fa-cube" style="color:#ffffff; font-size:14px;"></i>
            </div>
            <span class="brand-name">AURA</span>
        </a>
        <div class="nav-links">
            <a href="#features" class="nav-link">Features</a>
            <a href="#pricing" class="nav-link">Pricing</a>
            <a href="#demo" class="nav-link">Request Demo</a>
            <a href="{{ route('login') }}" class="btn-login">Client Portal</a>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="ai-badge">
            <i class="fa-solid fa-wand-magic-sparkles"></i>
            <span>Powered by Aura AI 2.0</span>
        </div>
        <h1>Enterprise CRM Redefined with <span>Autonomous AI Sales Copilots</span></h1>
        <p>Aura captures, scores, qualifies, and generates instant WhatsApp and email follow-up drafts for every inbound lead. Maximize your conversions automatically.</p>
        <div class="hero-actions">
            <a href="#demo" class="btn-primary">
                <span>Start Free Trial</span>
                <i class="fa-solid fa-arrow-right"></i>
            </a>
            <a href="{{ route('login') }}" class="btn-secondary">
                <span>Access Dashboard</span>
            </a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="section-header">
            <h2>Core Platform Features</h2>
            <p>Everything you need to automate your sales funnel and empower your team.</p>
        </div>
        <div class="grid-features">
            <!-- Feature 1 -->
            <div class="feature-card">
                <div class="icon-box">
                    <i class="fa-solid fa-brain"></i>
                </div>
                <h3>AI Qualification & Scoring</h3>
                <p>Automated buyer intent parsing, budget estimation, urgency classification, and custom scoring to prioritize hot leads.</p>
            </div>
            <!-- Feature 2 -->
            <div class="feature-card">
                <div class="icon-box">
                    <i class="fa-brands fa-whatsapp"></i>
                </div>
                <h3>Omnichannel Routing</h3>
                <p>Seamlessly integrate email and WhatsApp API webhooks. Qualify and reply to prospects instantly without lifting a finger.</p>
            </div>
            <!-- Feature 3 -->
            <div class="feature-card">
                <div class="icon-box">
                    <i class="fa-solid fa-building-user"></i>
                </div>
                <h3>Multi-Tenant Architecture</h3>
                <p>Designed for organizations of all sizes. Separate and scale workspaces with strict SuperAdmin, Admin, and Staff RBAC permissions.</p>
            </div>
        </div>
    </section>

    <!-- Demo / Form Section -->
    <section class="demo-section" id="demo">
        <div class="demo-container">
            <div class="demo-text">
                <h2>Test Our Real-Time AI Qualification</h2>
                <p>Submit your software requirements below. Our integrated AI Engine will analyze your intent, estimate your budget, and qualify you instantly.</p>
                
                <div class="bullet-point">
                    <i class="fa-solid fa-circle-check bullet-icon"></i>
                    <span><b>Automated Routing:</b> Instantly qualifying inputs and assigning to correct sales departments.</span>
                </div>
                <div class="bullet-point">
                    <i class="fa-solid fa-circle-check bullet-icon"></i>
                    <span><b>Tailored Response:</b> Auto-drafting custom WhatsApp/Email followups.</span>
                </div>
                <div class="bullet-point">
                    <i class="fa-solid fa-circle-check bullet-icon"></i>
                    <span><b>Secure Workspace:</b> Fully integrated multi-org data protection.</span>
                </div>
            </div>

            <!-- Public Inquiry Form -->
            <div class="form-card">
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
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <h3 id="modalTitle">Inquiry Qualified!</h3>
            <p id="modalBody">Thank you for submitting your requirements. Our AI agent has qualifications and saved the inquiry to our CRM desk.</p>
            <button onclick="closeModal()" class="btn-close">Close</button>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2026 Aura AI Enterprise Ltd. All rights reserved.</p>
        <p style="color:#475569; font-size:11px;">Designed for high-performance and automated sales workflows.</p>
    </footer>

    <!-- Form Submit Handler Script -->
    <script>
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
</body>
</html>
