<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevineSkyCRM — Sign In</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 50%, #eef2ff 100%);
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .login-container {
            width: 100%;
            max-width: 900px;
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(99, 102, 241, 0.08);
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        @media (max-width: 768px) {
            .login-container {
                grid-template-columns: 1fr;
            }
            .info-side {
                border-right: none;
                border-bottom: 1px solid #e2e8f0;
            }
        }

        /* Left Side: Brand info & credentials quick selector */
        .info-side {
            background: #fafbff;
            border-right: 1px solid #e2e8f0;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .brand-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 24px;
        }
        .logo-box {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, #7c3aed, #6366f1, #06b6d4);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25);
        }
        .brand-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 18px;
            font-weight: 800;
            background: linear-gradient(135deg, #1e293b, #4f46e5);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .quick-login-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #94a3b8;
            margin-bottom: 12px;
        }
        .quick-user-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 14px;
            border-radius: 12px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            margin-bottom: 8px;
            text-decoration: none;
            color: inherit;
            transition: all 0.15s ease;
        }
        .quick-user-card:hover {
            border-color: #6366f1;
            background: #f5f3ff;
            transform: translateX(4px);
        }
        .badge {
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 2px 7px;
            border-radius: 999px;
            border: 1px solid transparent;
        }
        .badge-superadmin { background: #fff1f2; color: #be123c; border-color: #fecdd3; }
        .badge-admin      { background: #eff6ff; color: #1d4ed8; border-color: #bfdbfe; }
        .badge-staff      { background: #ecfdf5; color: #047857; border-color: #a7f3d0; }

        /* Right Side: Form */
        .form-side {
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .welcome-text {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 22px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 6px;
        }
        .sub-text {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 24px;
        }
        .form-group {
            margin-bottom: 16px;
        }
        .form-label {
            display: block;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748b;
            margin-bottom: 6px;
        }
        .form-input {
            width: 100%;
            padding: 10px 14px;
            border-radius: 10px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #1e293b;
            font-size: 13px;
            outline: none;
            transition: all 0.15s ease;
        }
        .form-input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            background: #ffffff;
        }
        .btn-submit {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, #6366f1, #06b6d4);
            color: #ffffff;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25);
            transition: all 0.15s ease;
            margin-top: 10px;
        }
        .btn-submit:hover {
            background: linear-gradient(135deg, #4f46e5, #0891b2);
            box-shadow: 0 6px 16px rgba(99, 102, 241, 0.35);
        }

        .alert-error {
            background: #fff1f2;
            border: 1px solid #fecdd3;
            color: #be123c;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 18px;
            list-style: none;
        }
    </style>
</head>
<body>

<div class="login-container">
    <!-- Left Side: Brand and Quick credentials -->
    <div class="info-side">
        <div>
            <div class="brand-header">
                <div class="logo-box">
                    <i class="fa-solid fa-cube" style="color:#ffffff;font-size:16px;"></i>
                </div>
                <span class="brand-title">DevineSkyCRM</span>
            </div>
            <p style="font-size:12px;color:#64748b;line-height:1.5;margin-bottom:32px;">
                Intelligent Sales Automation with Multi-Tenancy Organization Structure.
            </p>
        </div>

        <div>
            <h3 class="quick-login-title"><i class="fa-solid fa-bolt" style="color:#eab308;margin-right:4px;"></i> Quick Sandbox Access</h3>
            <div style="max-height: 350px; overflow-y: auto; padding-right: 4px;">
                @foreach($users as $u)
                    <a href="{{ route('quick-login', $u->id) }}" class="quick-user-card">
                        <div>
                            <span style="font-size:12px;font-weight:700;display:block;">{{ $u->name }}</span>
                            <span style="font-size:10px;color:#94a3b8;">
                                @if($u->organization)
                                    Org: {{ $u->organization->name }}
                                @else
                                    Global SuperAdmin
                                @endif
                            </span>
                        </div>
                        <div>
                            @if($u->role === 'superadmin')
                                <span class="badge badge-superadmin">SuperAdmin</span>
                            @elseif($u->role === 'admin')
                                <span class="badge badge-admin">Admin</span>
                            @else
                                <span class="badge badge-staff">Staff ({{ $u->staff_role ?: 'Sales' }})</span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Right Side: Standard Login Form -->
    <div class="form-side">
        <h2 class="welcome-text">Account Sign In</h2>
        <p class="sub-text">Enter credentials or use the sandbox panel for instant login.</p>

        @if($errors->any())
            <ul class="alert-error">
                @foreach($errors->all() as $err)
                    <li><i class="fa-solid fa-triangle-exclamation" style="margin-right:6px;"></i> {{ $err }}</li>
                @endforeach
            </ul>
        @endif

        @if(session('error'))
            <div class="alert-error" style="list-style:none;">
                <i class="fa-solid fa-triangle-exclamation" style="margin-right:6px;"></i> {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="email@crm.com" class="form-input">
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" required placeholder="••••••••" class="form-input">
            </div>

            <button type="submit" class="btn-submit">
                Sign In to Platform
            </button>
        </form>

        <div style="display:flex; align-items:center; margin:24px 0;">
            <div style="flex:1; height:1px; background:#e2e8f0;"></div>
            <div style="padding:0 12px; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em;">Or continue with</div>
            <div style="flex:1; height:1px; background:#e2e8f0;"></div>
        </div>

        <a href="{{ route('auth.google') }}" style="display:flex; align-items:center; justify-content:center; width:100%; padding:12px; border-radius:10px; background:#ffffff; border:1px solid #cbd5e1; color:#1e293b; font-size:13px; font-weight:600; text-decoration:none; transition:all 0.15s ease; box-shadow:0 1px 2px rgba(0,0,0,0.05);">
            <svg style="width:18px; height:18px; margin-right:8px;" viewBox="0 0 24 24">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
            </svg>
            Sign in with Google
        </a>
    </div>
</div>

</body>
</html>
