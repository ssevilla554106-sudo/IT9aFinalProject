{{-- FILE: resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'EventHub') }} - @yield('title', 'Discover Events')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary: #1a1a2e;
            --accent: #e94560;
            --accent-light: #ff6b8a;
            --secondary: #16213e;
            --surface: #0f3460;
            --gold: #f5a623;
            --text-light: #94a3b8;
            --white: #ffffff;
            --bg: #f8fafc;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            background-color: var(--bg);
            color: #1e293b;
        }

        h1, h2, h3, h4, h5, .font-display {
            font-family: 'Syne', sans-serif;
        }

        /* NAVBAR */
        .navbar {
            background: var(--primary);
            padding: 0 2rem;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 20px rgba(0,0,0,0.3);
        }

        .navbar-brand {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 1.6rem;
            color: var(--white);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar-brand span { color: var(--accent); }

        .nav-links { display: flex; align-items: center; gap: 0.5rem; }

        .nav-link {
            color: #94a3b8;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .nav-link:hover { color: var(--white); background: rgba(255,255,255,0.08); }
        .nav-link.active { color: var(--white); }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.6rem 1.4rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            text-decoration: none;
            border: none;
            transition: all 0.2s;
            font-family: 'DM Sans', sans-serif;
        }

        .btn-primary {
            background: var(--accent);
            color: white;
        }
        .btn-primary:hover { background: var(--accent-light); transform: translateY(-1px); box-shadow: 0 4px 15px rgba(233,69,96,0.4); }

        .btn-outline {
            background: transparent;
            color: var(--white);
            border: 1.5px solid rgba(255,255,255,0.2);
        }
        .btn-outline:hover { border-color: var(--accent); color: var(--accent); }

        .btn-secondary {
            background: #e2e8f0;
            color: #475569;
        }
        .btn-secondary:hover { background: #cbd5e1; }

        .btn-danger {
            background: #ef4444;
            color: white;
        }
        .btn-danger:hover { background: #dc2626; }

        .btn-success {
            background: #10b981;
            color: white;
        }
        .btn-success:hover { background: #059669; }

        .btn-sm { padding: 0.4rem 0.9rem; font-size: 0.82rem; }
        .btn-lg { padding: 0.8rem 2rem; font-size: 1rem; }

        /* ALERTS */
        .alert {
            padding: 1rem 1.25rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 500;
        }
        .alert-success { background: #d1fae5; color: #065f46; border-left: 4px solid #10b981; }
        .alert-error { background: #fee2e2; color: #991b1b; border-left: 4px solid #ef4444; }
        .alert-warning { background: #fef3c7; color: #92400e; border-left: 4px solid #f59e0b; }

        /* CARDS */
        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 20px rgba(0,0,0,0.06);
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card:hover { transform: translateY(-3px); box-shadow: 0 8px 30px rgba(0,0,0,0.1); }

        /* BADGE */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 100px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .badge-published { background: #d1fae5; color: #065f46; }
        .badge-draft { background: #f1f5f9; color: #475569; }
        .badge-cancelled { background: #fee2e2; color: #991b1b; }
        .badge-completed { background: #dbeafe; color: #1e40af; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-rejected { background: #fee2e2; color: #991b1b; }
        .badge-free { background: #d1fae5; color: #065f46; }
        .badge-paid { background: #fef3c7; color: #92400e; }

        /* FORM */
        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-weight: 600; font-size: 0.875rem; color: #374151; margin-bottom: 0.5rem; }
        .form-control {
            width: 100%;
            padding: 0.65rem 1rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.95rem;
            font-family: 'DM Sans', sans-serif;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: white;
            color: #1e293b;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(233,69,96,0.1);
        }
        .form-error { color: #ef4444; font-size: 0.8rem; margin-top: 0.3rem; }

        /* CONTAINER */
        .container { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; }

        /* FOOTER */
        footer {
            background: var(--primary);
            color: #94a3b8;
            padding: 2.5rem 0;
            margin-top: 5rem;
        }
        footer a { color: #94a3b8; text-decoration: none; }
        footer a:hover { color: white; }

        /* DROPDOWN */
        .dropdown { position: relative; }
        .dropdown-menu {
            position: absolute;
            right: 0;
            top: 110%;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            min-width: 200px;
            z-index: 100;
            display: none;
            overflow: hidden;
            border: 1px solid #f1f5f9;
        }
        .dropdown:hover .dropdown-menu,
        .dropdown.open .dropdown-menu { display: block; }
        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.75rem 1.25rem;
            color: #374151;
            font-size: 0.875rem;
            cursor: pointer;
            transition: background 0.15s;
            text-decoration: none;
        }
        .dropdown-item:hover { background: #f8fafc; color: var(--accent); }
        .dropdown-divider { border: none; border-top: 1px solid #f1f5f9; margin: 0.25rem 0; }

        /* SECTION */
        .section { padding: 4rem 0; }
        .section-title { font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem; }
        .section-subtitle { color: var(--text-light); margin-bottom: 2.5rem; }

        /* PAGE HEADER */
        .page-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 3rem 0;
        }
        .page-header h1 { font-size: 2.2rem; font-weight: 800; margin: 0 0 0.5rem; }
        .page-header p { color: #94a3b8; margin: 0; }

        /* STAT CARD */
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.75rem;
            box-shadow: 0 2px 20px rgba(0,0,0,0.06);
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }
        .stat-icon {
            width: 56px; height: 56px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
        }
        .stat-value { font-size: 1.8rem; font-weight: 800; font-family: 'Syne', sans-serif; }
        .stat-label { font-size: 0.85rem; color: var(--text-light); font-weight: 500; }

        /* GRID */
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
        .grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; }
        .grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; }

        @media (max-width: 900px) {
            .grid-3, .grid-4 { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 600px) {
            .grid-3, .grid-4, .grid-2 { grid-template-columns: 1fr; }
            .navbar { padding: 0 1rem; }
        }
    </style>
    @stack('styles')
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <a href="{{ route('events.index') }}" class="navbar-brand">
        <i class="fas fa-calendar-star"></i>
        Event<span>Hub</span>
    </a>

    <div class="nav-links">
        <a href="{{ route('events.archive') }}" class="nav-link {{ request()->routeIs('events.archive') ? 'active' : '' }}">
                <i class="fas fa-archive"></i> Archive
            </a>
            <a href="{{ route('events.index') }}" class="nav-link {{ request()->routeIs('events.index') ? 'active' : '' }}">
            <i class="fas fa-compass"></i> Explore
        </a>

        @auth
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
            <a href="{{ route('events.my-events') }}" class="nav-link {{ request()->routeIs('events.my-events') ? 'active' : '' }}">
                <i class="fas fa-ticket-alt"></i> My Events
            </a>
            <a href="{{ route('events.create') }}" class="btn btn-primary" style="margin-left: 0.5rem;">
                <i class="fas fa-plus"></i> Create Event
            </a>

            <div class="dropdown" style="margin-left: 0.5rem;">
                <button class="btn btn-outline" onclick="this.closest('.dropdown').classList.toggle('open')">
                    <i class="fas fa-user-circle"></i>
                    {{ Str::words(Auth::user()->name, 1, '') }}
                    <i class="fas fa-chevron-down" style="font-size:0.75rem"></i>
                </button>
                <div class="dropdown-menu">
                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <i class="fas fa-user-edit"></i> Edit Profile
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item" style="width:100%; background:none; border:none; cursor:pointer; font-family: inherit;">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        @else
            <a href="{{ route('login') }}" class="btn btn-outline">Login</a>
            <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
        @endauth
    </div>
</nav>

<!-- FLASH MESSAGES -->
@if (session('success') || session('error') || session('warning'))
<div class="container" style="padding-top: 1rem;">
    @if (session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif
    @if (session('warning'))
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            {{ session('warning') }}
        </div>
    @endif
</div>
@endif

<!-- CONTENT -->
<main>
    @yield('content')
</main>

<!-- FOOTER -->
<footer>
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div>
                <div style="font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1.3rem; color: white; margin-bottom: 0.3rem;">
                    Event<span style="color: #e94560;">Hub</span>
                </div>
                <p style="font-size: 0.875rem;">Discover and manage amazing events.</p>
            </div>
            <div style="display: flex; gap: 1.5rem; font-size: 0.875rem;">
                <a href="{{ route('events.index') }}">Browse Events</a>
                @auth
                    <a href="{{ route('events.create') }}">Create Event</a>
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                @else
                    <a href="{{ route('login') }}">Login</a>
                    <a href="{{ route('register') }}">Register</a>
                @endauth
            </div>
        </div>
        <div style="border-top: 1px solid rgba(255,255,255,0.08); margin-top: 1.5rem; padding-top: 1.5rem; font-size: 0.8rem; text-align: center;">
            &copy; {{ date('Y') }} EventHub. All rights reserved.
        </div>
    </div>
</footer>

<script>
document.addEventListener('click', function(e) {
    const dropdowns = document.querySelectorAll('.dropdown.open');
    dropdowns.forEach(d => {
        if (!d.contains(e.target)) d.classList.remove('open');
    });
});
</script>

@stack('scripts')
</body>
</html>
