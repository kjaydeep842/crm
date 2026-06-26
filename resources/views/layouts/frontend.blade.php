<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DevineSky - AI WhatsApp CRM & Sales Automation Platform')</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --secondary: #06b6d4;
            --dark: #0f172a;
            --text: #475569;
            --bg-light: #f8fafc;
            --surface: #ffffff;
            --border: #e2e8f0;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        
        html { scroll-behavior: smooth; }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-light);
            color: var(--dark);
            line-height: 1.6;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        main { flex-grow: 1; }

        /* ── Header / Navigation ── */
        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 80px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 5%;
            transition: all 0.3s ease;
        }
        header.scrolled {
            height: 70px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            group: hover;
        }
        .logo-box {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
            transition: transform 0.3s ease;
        }
        .brand:hover .logo-box {
            transform: rotate(10deg) scale(1.05);
        }
        .brand-name {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 20px;
            font-weight: 800;
            background: linear-gradient(135deg, var(--dark), var(--primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        /* Mobile menu toggle button */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            color: var(--dark);
            cursor: pointer;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .nav-link {
            text-decoration: none;
            color: var(--text);
            font-weight: 600;
            font-size: 15px;
            transition: color 0.2s, transform 0.2s;
        }
        .nav-link:hover {
            color: var(--primary);
            transform: translateY(-1px);
        }
        .btn-login {
            background: var(--surface);
            border: 1px solid var(--border);
            color: var(--primary);
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }
        .btn-login:hover {
            background: #f1f5f9;
            border-color: var(--primary);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.1);
        }
        
        /* ── Animations ── */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
        @keyframes pulse-glow {
            0% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.4); }
            70% { box-shadow: 0 0 0 15px rgba(79, 70, 229, 0); }
            100% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0); }
        }
        @keyframes slideGradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* ── Footer ── */
        footer {
            padding: 60px 5% 40px;
            background: var(--dark);
            color: #94a3b8;
            text-align: center;
        }
        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding-bottom: 40px;
            margin-bottom: 40px;
            flex-wrap: wrap;
            gap: 20px;
        }
        .footer-brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .footer-brand i {
            color: white;
            font-size: 24px;
        }
        .footer-brand span {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 24px;
            font-weight: 800;
            color: white;
        }
        .social-links {
            display: flex;
            gap: 16px;
        }
        .social-links a {
            color: #94a3b8;
            font-size: 20px;
            transition: color 0.3s;
        }
        .social-links a:hover {
            color: white;
        }
        .copyright {
            font-size: 14px;
        }
        
        .page-header {
            padding: 160px 5% 80px;
            text-align: center;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.05), rgba(6, 182, 212, 0.05));
            border-bottom: 1px solid var(--border);
        }
        .page-header h1 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 48px;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 16px;
        }
        .page-header p {
            font-size: 18px;
            color: var(--text);
            max-width: 600px;
            margin: 0 auto;
        }
        
        .section-padding {
            padding: 100px 5%;
        }

        @media (max-width: 1250px) {
            header { padding: 0 5%; }
            .mobile-menu-btn { display: block; }
            .nav-links { 
                display: none; 
                position: absolute;
                top: 100%; left: 0; right: 0;
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(16px);
                flex-direction: column;
                padding: 24px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.08);
                border-top: 1px solid var(--border);
                gap: 18px;
                align-items: stretch;
            }
            .nav-links.active { display: flex; }
            .nav-link {
                padding: 8px 0;
                border-bottom: 1px solid #f1f5f9;
                width: 100%;
            }
            .btn-login {
                text-align: center;
                margin-top: 10px;
            }
            .footer-content { flex-direction: column; text-align: center; }
            .page-header h1 { font-size: 36px; }
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Header Navigation -->
    <header id="header">
        <a href="/" class="brand">
            <div class="logo-box">
                <i class="fa-solid fa-cube" style="color:#ffffff; font-size:18px;"></i>
            </div>
            <span class="brand-name">DEVINESKY</span>
        </a>
        <button class="mobile-menu-btn" id="mobileMenuBtn">
            <i class="fa-solid fa-bars"></i>
        </button>
        <div class="nav-links" id="navLinks">
            <a href="{{ route('public.features') }}" class="nav-link">Features</a>
            <a href="{{ route('public.how-it-works') }}" class="nav-link">How it Works</a>
            <a href="{{ route('public.integrations') }}" class="nav-link">Integrations</a>
            <a href="{{ route('public.security') }}" class="nav-link">Security</a>
            <a href="{{ route('public.pricing') }}" class="nav-link">Pricing</a>
            <a href="{{ route('public.faq') }}" class="nav-link">FAQ</a>
            <a href="{{ route('public.contact') }}" class="nav-link">Contact</a>
            <a href="{{ route('login') }}" class="btn-login">Client Portal</a>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="footer-content" data-aos="fade-up">
            <div class="footer-brand">
                <i class="fa-solid fa-cube"></i>
                <span>DEVINESKY</span>
            </div>
            <div class="footer-links" style="display:flex; gap:20px; flex-wrap:wrap; justify-content:center;">
                <a href="{{ route('public.features') }}" style="color:var(--text);text-decoration:none;">Features</a>
                <a href="{{ route('public.how-it-works') }}" style="color:var(--text);text-decoration:none;">How it Works</a>
                <a href="{{ route('public.integrations') }}" style="color:var(--text);text-decoration:none;">Integrations</a>
                <a href="{{ route('public.security') }}" style="color:var(--text);text-decoration:none;">Security</a>
                <a href="{{ route('public.pricing') }}" style="color:var(--text);text-decoration:none;">Pricing</a>
                <a href="{{ route('public.faq') }}" style="color:var(--text);text-decoration:none;">FAQ</a>
                <a href="{{ route('public.about') }}" style="color:var(--text);text-decoration:none;">About Us</a>
                <a href="{{ route('public.contact') }}" style="color:var(--text);text-decoration:none;">Contact</a>
            </div>
            <div class="social-links">
                <a href="#"><i class="fa-brands fa-twitter"></i></a>
                <a href="#"><i class="fa-brands fa-linkedin"></i></a>
                <a href="#"><i class="fa-brands fa-github"></i></a>
            </div>
        </div>
        <p class="copyright" data-aos="fade-up" data-aos-delay="100">&copy; 2026 DevineSky AI Enterprise Ltd. All rights reserved.</p>
        <p style="color:#475569; font-size:12px; margin-top: 10px;" data-aos="fade-up" data-aos-delay="200">The ultimate AI WhatsApp CRM & Sales Automation Platform.</p>
    </footer>

    <!-- AOS Animation JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <!-- Scripts -->
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true,
            offset: 50,
        });

        // Header Scroll Effect
        window.addEventListener('scroll', () => {
            const header = document.getElementById('header');
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
        
        // Mobile menu toggle
        document.getElementById('mobileMenuBtn').addEventListener('click', function() {
            document.getElementById('navLinks').classList.toggle('active');
        });
    </script>
    @yield('scripts')
</body>
</html>
