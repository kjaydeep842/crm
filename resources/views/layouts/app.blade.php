<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevineSkyCRM — @yield('header_title', 'Dashboard')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #f8fafc; color: #1e293b; height: 100vh; overflow: hidden; display: flex; }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: #f8fafc; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #6366f1; }

        /* ── Sidebar ── */
        #sidebar {
            width: 240px; min-width: 240px; height: 100vh;
            background: #ffffff; border-right: 1px solid #e2e8f0;
            display: flex; flex-direction: column; z-index: 50;
            box-shadow: 1px 0 10px rgba(99, 102, 241, 0.03);
        }
        .sb-brand {
            padding: 0 18px; height: 60px; min-height: 60px;
            display: flex; align-items: center; gap: 10px;
            border-bottom: 1px solid #f1f5f9; flex-shrink: 0;
        }
        .sb-logo {
            width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0;
            background: linear-gradient(135deg,#7c3aed,#6366f1,#06b6d4);
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 10px rgba(99,102,241,0.25);
        }
        .sb-nav {
            flex: 1; overflow-y: auto; padding: 12px 10px;
        }
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 8px 12px; border-radius: 9px; margin-bottom: 2px;
            font-size: 13px; font-weight: 500; color: #64748b;
            text-decoration: none; transition: all 0.15s ease; cursor: pointer;
        }
        .nav-item:hover { background: #f8fafc; color: #334155; }
        .nav-item.active {
            background: #eef2ff; color: #4f46e5; font-weight: 600;
            box-shadow: inset 0 0 0 1px rgba(99,102,241,0.12);
        }
        .nav-item i { width: 16px; text-align: center; font-size: 12px; flex-shrink: 0; }
        .nav-section {
            font-size: 9px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.08em; color: #94a3b8;
            padding: 14px 12px 6px; margin-top: 6px;
        }
        .sb-footer {
            border-top: 1px solid #f1f5f9; padding: 14px 12px;
            flex-shrink: 0; background: #fafbff;
        }
        .user-card {
            display: flex; align-items: center; gap: 9px; margin-bottom: 10px;
        }
        .user-avatar {
            width: 32px; height: 32px; border-radius: 50%; flex-shrink: 0;
            background: #eef2ff; display: flex; align-items: center; justify-content: center;
        }
        /* ── Role switcher dropdown ── */
        .role-switcher { position: relative; }
        .role-btn {
            width: 100%; display: flex; align-items: center; justify-content: space-between;
            padding: 8px 12px; border-radius: 8px; border: 1px solid #e2e8f0;
            background: #f8fafc; font-size: 11px; font-weight: 600; color: #475569;
            cursor: pointer; transition: background 0.15s; outline: none;
        }
        .role-btn:hover { background: #f1f5f9; }
        .role-dropdown {
            position: absolute; bottom: calc(100% + 6px); left: 0; right: 0;
            background: #fff; border: 1px solid #e2e8f0;
            border-radius: 10px; box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            padding: 5px; z-index: 200; overflow-y: auto; max-height: 250px;
        }
        .role-option {
            display: flex; align-items: center; justify-content: space-between;
            padding: 8px 10px; border-radius: 7px; font-size: 11px; font-weight: 500;
            color: #475569; text-decoration: none; transition: background 0.1s;
        }
        .role-option:hover { background: #f1f5f9; color: #1e293b; }
        .role-option.current { background: #eef2ff; color: #4f46e5; font-weight: 700; }

        /* ── Main Area ── */
        #main { flex: 1; display: flex; flex-direction: column; min-width: 0; overflow: hidden; }
        #topbar {
            height: 60px; min-height: 60px; padding: 0 28px;
            display: flex; align-items: center; justify-content: space-between;
            background: #ffffff; border-bottom: 1px solid #e2e8f0;
            z-index: 30; flex-shrink: 0;
        }
        #page-content {
            flex: 1; overflow-y: auto; padding: 28px;
            background: linear-gradient(150deg, #f8fafc 0%, #f1f5f9 60%, #eef2ff 100%);
            position: relative;
        }

        /* ── Cards ── */
        .card {
            background: #ffffff; border: 1px solid #e2e8f0;
            border-radius: 14px; box-shadow: 0 4px 12px rgba(99,102,241,0.02);
        }

        /* ── Buttons ── */
        .btn { display: inline-flex; align-items: center; gap: 6px; border: none; border-radius: 9px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.15s; text-decoration: none; outline: none; }
        .btn-sm { padding: 6px 12px; font-size: 11px; }
        .btn-md { padding: 8px 16px; }
        .btn-lg { padding: 10px 20px; }
        .btn-primary { background: linear-gradient(135deg,#6366f1,#06b6d4); color: #fff !important; box-shadow: 0 4px 12px rgba(99,102,241,0.2); }
        .btn-primary:hover { background: linear-gradient(135deg,#4f46e5,#0891b2); box-shadow: 0 6px 16px rgba(99,102,241,0.3); transform: translateY(-1px); }
        .btn-violet { background: linear-gradient(135deg,#7c3aed,#6366f1); color: #fff !important; box-shadow: 0 4px 12px rgba(124,58,237,0.2); }
        .btn-violet:hover { background: linear-gradient(135deg,#6d28d9,#4f46e5); }
        .btn-light { background: #f1f5f9; color: #475569 !important; border: 1px solid #e2e8f0; }
        .btn-light:hover { background: #e2e8f0; color: #1e293b !important; border-color: #cbd5e1; }
        .btn-icon { width: 30px; height: 30px; border-radius: 7px; padding: 0; display: inline-flex; align-items: center; justify-content: center; }

        /* ── Tables ── */
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table thead th {
            padding: 12px 18px; font-size: 10px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.07em; color: #64748b;
            background: #fafbff; border-bottom: 1px solid #e2e8f0;
        }
        .data-table tbody td { padding: 14px 18px; font-size: 12px; color: #334155; border-bottom: 1px solid #f1f5f9; }
        .data-table tbody tr { transition: background 0.1s; }
        .data-table tbody tr:hover { background: #fafbff; }
        .data-table tbody tr:last-child td { border-bottom: none; }

        /* ── Badges ── */
        .badge { display: inline-flex; align-items: center; gap: 3px; padding: 2px 8px; border-radius: 999px; font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; border: 1px solid transparent; }
        .b-new      { background:#eff6ff; color:#1d4ed8; border-color:#bfdbfe; }
        .b-contacted{ background:#fffbeb; color:#b45309; border-color:#fde68a; }
        .b-qualified{ background:#ecfdf5; color:#047857; border-color:#a7f3d0; }
        .b-proposal { background:#f5f3ff; color:#6d28d9; border-color:#ddd6fe; }
        .b-negotiation{ background:#fff7ed; color:#c2410c; border-color:#fed7aa; }
        .b-won      { background:#f0fdfa; color:#0f766e; border-color:#99f6e4; }
        .b-lost     { background:#fff1f2; color:#be123c; border-color:#fecdd3; }
        .b-scheduled{ background:#f5f3ff; color:#6d28d9; border-color:#ddd6fe; }
        .b-completed{ background:#ecfdf5; color:#047857; border-color:#a7f3d0; }
        .b-high     { background:#fff1f2; color:#be123c; border-color:#fecdd3; }
        .b-medium   { background:#fffbeb; color:#b45309; border-color:#fde68a; }
        .b-low      { background:#eff6ff; color:#1d4ed8; border-color:#bfdbfe; }

        /* ── Toast ── */
        .toast { border-radius:10px; padding:12px 16px; margin-bottom:18px; display:flex; align-items:center; justify-content:space-between; font-size:13px; font-weight:600; }
        .toast-success { background:#f0fdf4; border:1px solid #bbf7d0; color:#15803d; }
        .toast-error   { background:#fff1f2; border:1px solid #fecdd3; color:#be123c; }

        /* ── Status pill (AI) ── */
        .ai-status { display:inline-flex; align-items:center; gap:5px; padding:4px 11px; border-radius:999px; background:#f0fdf4; border:1px solid #bbf7d0; color:#15803d; font-size:11px; font-weight:600; }
        @keyframes dot-pulse { 0%,100%{opacity:1} 50%{opacity:.3} }
        .pulse { display:inline-block; width:6px; height:6px; border-radius:50%; background:#22c55e; animation:dot-pulse 2s infinite; }

        [x-cloak] { display:none !important; }

        @media (max-width: 768px) {
            body {
                flex-direction: column !important;
                overflow: auto !important;
                height: auto !important;
            }
            #sidebar {
                position: fixed !important;
                top: 0 !important;
                bottom: 0 !important;
                left: 0 !important;
                transform: translateX(-100%) !important;
                transition: transform 0.2s ease-in-out !important;
                z-index: 100 !important;
                height: 100vh !important;
            }
            #sidebar.open {
                transform: translateX(0) !important;
            }
            #main {
                height: auto !important;
                overflow: visible !important;
                display: flex !important;
                flex-direction: column !important;
            }
            #topbar {
                padding: 0 16px !important;
                height: 56px !important;
                min-height: 56px !important;
            }
            #page-content {
                padding: 16px !important;
                overflow: visible !important;
            }
            .mobile-menu-btn {
                display: inline-flex !important;
            }
            .sidebar-backdrop {
                position: fixed !important;
                inset: 0 !important;
                background: rgba(15, 23, 42, 0.4) !important;
                backdrop-filter: blur(4px) !important;
                z-index: 90 !important;
            }
        }
    </style>
</head>
<body x-data="{ sidebarOpen: false }">

<div class="sidebar-backdrop" x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak style="display:none;"></div>

<!-- ═══════════════════════════════ SIDEBAR ═══════════════════════════════ -->
<aside id="sidebar" :class="sidebarOpen ? 'open' : ''">

    <!-- Brand -->
    <div class="sb-brand">
        <div class="sb-logo">
            <i class="fa-solid fa-cube" style="color:#fff;font-size:14px;"></i>
        </div>
        <div>
            <div style="font-family:'Plus Jakarta Sans',sans-serif;font-size:16px;font-weight:800;background:linear-gradient(135deg,#1e293b,#4f46e5);-webkit-background-clip:text;-webkit-text-fill-color:transparent;line-height:1.1;">DEVINESKY</div>
            <div style="font-size:9px;font-weight:700;color:#6366f1;letter-spacing:.1em;text-transform:uppercase;">AI ENTERPRISE</div>
        </div>
    </div>

    <!-- Navigation (scrollable) -->
    <nav class="sb-nav">
        @php 
            $user = Auth::user();
            $isSuperAdmin = $user->isSuperAdmin();
            $isAdmin = $user->isAdmin();
            $isStaff = $user->isStaff();
        @endphp

        <!-- Core (all roles) -->
        <a href="{{ route('dashboard') }}" class="nav-item {{ Route::is('dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-line"></i><span>Dashboard</span>
        </a>
        <a href="{{ route('leads.index') }}" class="nav-item {{ Route::is('leads.*') ? 'active' : '' }}">
            <i class="fa-solid fa-users"></i><span>Leads Manager</span>
        </a>
        <a href="{{ route('meetings.index') }}" class="nav-item {{ Route::is('meetings.*') ? 'active' : '' }}">
            <i class="fa-solid fa-calendar-days"></i><span>Meetings Hub</span>
        </a>
        <a href="{{ route('tasks.index') }}" class="nav-item {{ Route::is('tasks.*') ? 'active' : '' }}">
            <i class="fa-solid fa-list-check"></i><span>Task Console</span>
        </a>
        <a href="{{ route('whatsapp.inbox') }}" class="nav-item {{ Route::is('whatsapp.*') ? 'active' : '' }}">
            <i class="fa-brands fa-whatsapp"></i><span>WhatsApp Inbox</span>
        </a>

        <!-- Admin & SuperAdmin management sections -->
        @if($isSuperAdmin || $isAdmin)
        <div class="nav-section">Management</div>
        
        <a href="{{ route('users.index') }}" class="nav-item {{ Route::is('users.*') ? 'active' : '' }}">
            <i class="fa-solid fa-users-gear"></i>
            <span>{{ $isSuperAdmin ? 'Users & Orgs' : 'Manage Staff' }}</span>
        </a>

        <a href="{{ route('inquiries.index') }}" class="nav-item {{ Route::is('inquiries.*') ? 'active' : '' }}">
            <i class="fa-solid fa-inbox"></i><span>Inquiry Capture</span>
        </a>
        <a href="{{ route('documents.index') }}" class="nav-item {{ Route::is('documents.*') ? 'active' : '' }}">
            <i class="fa-solid fa-file-invoice"></i><span>Docs & Quotations</span>
        </a>
        <a href="{{ route('agents.index') }}" class="nav-item {{ Route::is('agents.*') ? 'active' : '' }}">
            <i class="fa-solid fa-robot"></i><span>AI WhatsApp & Mail</span>
        </a>
        
        <div class="nav-section">Analytics</div>
        <a href="{{ route('productivity.index') }}" class="nav-item {{ Route::is('productivity.*') ? 'active' : '' }}">
            <i class="fa-solid fa-gauge-high"></i><span>Team Productivity</span>
        </a>
        <a href="{{ route('reports.index') }}" class="nav-item {{ Route::is('reports.*') ? 'active' : '' }}">
            <i class="fa-solid fa-file-contract"></i><span>Analytic Reports</span>
        </a>
        @endif
    </nav>

    <!-- Footer: User + Role Switcher -->
    <div class="sb-footer">
        <div class="user-card">
            <div class="user-avatar">
                <i class="fa-solid fa-user" style="color:#6366f1;font-size:11px;"></i>
            </div>
            <div style="overflow:hidden;flex:1;">
                <div style="font-size:12px;font-weight:700;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $user->name }}</div>
                <div style="font-size:9px;font-weight:700;color:#6366f1;text-transform:uppercase;letter-spacing:.07em;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                    @if($isSuperAdmin)
                        SuperAdmin
                    @elseif($isAdmin)
                        Admin ({{ $user->organization->name ?? 'Org' }})
                    @else
                        Staff ({{ $user->staff_role ?: 'Sales' }})
                    @endif
                </div>
            </div>
        </div>

        <!-- Logout -->
        <form action="{{ route('logout') }}" method="POST" style="margin-bottom:6px;">
            @csrf
            <button type="submit" style="width:100%;display:flex;align-items:center;gap:7px;padding:7px 10px;border-radius:8px;border:1px solid #fecdd3;background:#fff1f2;font-size:11px;font-weight:600;color:#be123c;cursor:pointer;transition:background .15s;outline:none;">
                <i class="fa-solid fa-right-from-bracket" style="font-size:10px;"></i> Logout
            </button>
        </form>

        <!-- Dropdown — Sandbox Role Switcher -->
        <div class="role-switcher" x-data="{ open: false }">
            <button type="button" class="role-btn" @click.stop="open = !open">
                <span>Switch Role</span>
                <i class="fa-solid fa-chevron-down" style="font-size:9px;transition:transform .2s;" :class="open ? 'rotate-180' : ''"></i>
            </button>

            <div class="role-dropdown" x-show="open" x-cloak @click.outside="open = false"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">
                @foreach(\App\Models\User::with('organization')->get() as $u)
                <a href="{{ route('switch-user', $u->id) }}" @click="open = false"
                   class="role-option {{ Auth::id() === $u->id ? 'current' : '' }}">
                    <span style="display:flex; flex-direction:column;">
                        <span style="font-weight:600;">{{ $u->name }}</span>
                        <span style="font-size:9px; opacity:.7;">
                            @if($u->isSuperAdmin())
                                SuperAdmin
                            @elseif($u->isAdmin())
                                Admin ({{ $u->organization->name ?? 'Org' }})
                            @else
                                Staff - {{ $u->organization->name ?? 'Org' }}
                            @endif
                        </span>
                    </span>
                    @if(Auth::id() === $u->id)
                        <i class="fa-solid fa-check" style="font-size:9px;color:#6366f1;"></i>
                    @endif
                </a>
                @endforeach
            </div>
        </div>
    </div>
</aside>

<!-- ═══════════════════════════════ MAIN ═══════════════════════════════ -->
<div id="main">

    <!-- Top Bar -->
    <header id="topbar">
        <div style="display:flex;align-items:center;gap:12px;">
            <button @click.stop="sidebarOpen = !sidebarOpen" class="btn-icon mobile-menu-btn" style="display:none;width:34px;height:34px;align-items:center;justify-content:center;border-radius:8px;border:1px solid #cbd5e1;background:#fff;cursor:pointer;">
                <i class="fa-solid fa-bars" style="color:#475569;font-size:14px;"></i>
            </button>
            <h1 style="font-family:'Plus Jakarta Sans',sans-serif;font-size:17px;font-weight:700;color:#1e293b;">
                @yield('header_title', 'Dashboard')
            </h1>
        </div>
        <div style="display:flex;align-items:center;gap:12px;">
            <div class="ai-status">
                <span class="pulse"></span>
                <span>AI Engine Online</span>
            </div>
            @if($isSuperAdmin || $isAdmin)
            <form action="{{ route('tasks.generate-priority') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="btn btn-md btn-violet">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                    <span>Generate AI Tasks</span>
                </button>
            </form>
            @endif
        </div>
    </header>

    <!-- Page Content -->
    <main id="page-content">
        <!-- Ambient glow blobs -->
        <div style="position:absolute;top:-60px;left:15%;width:400px;height:400px;border-radius:50%;background:radial-gradient(circle,rgba(99,102,241,.07) 0%,transparent 70%);pointer-events:none;"></div>
        <div style="position:absolute;bottom:-60px;right:5%;width:500px;height:500px;border-radius:50%;background:radial-gradient(circle,rgba(6,182,212,.06) 0%,transparent 70%);pointer-events:none;"></div>

        <div style="position:relative;z-index:1;">
            @if(session('success'))
                <div x-data="{s:true}" x-show="s" x-init="setTimeout(()=>s=false,5000)" class="toast toast-success">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <i class="fa-solid fa-circle-check"></i>
                        {{ session('success') }}
                    </div>
                    <button @click="s=false" style="background:none;border:none;color:inherit;cursor:pointer;font-size:14px;"><i class="fa-solid fa-xmark"></i></button>
                </div>
            @endif
            @if(session('error'))
                <div x-data="{s:true}" x-show="s" x-init="setTimeout(()=>s=false,5000)" class="toast toast-error">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        {{ session('error') }}
                    </div>
                    <button @click="s=false" style="background:none;border:none;color:inherit;cursor:pointer;font-size:14px;"><i class="fa-solid fa-xmark"></i></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</div>

</body>
</html>
