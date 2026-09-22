<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — LogiFlow</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>📦</text></svg>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="adm-body">

<div class="adm-shell">

    {{-- Sidebar --}}
    <aside class="adm-sidebar" id="admSidebar">
        <a href="{{ route('dashboard') }}" class="adm-sidebar-logo">Logi<span>Flow</span></a>

        @php $user = auth()->user(); @endphp

        {{-- Main --}}
        <div class="adm-nav-group">
            <div class="adm-nav-label">Main</div>
            <ul class="adm-nav">
                <li>
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9"/><rect x="14" y="3" width="7" height="5"/><rect x="14" y="12" width="7" height="9"/><rect x="3" y="16" width="7" height="5"/></svg>
                        Dashboard
                    </a>
                </li>
            </ul>
        </div>

        {{-- Operations + Fleet --}}
        @if($user->hasRole(['admin', 'staff']))
            <div class="adm-nav-group">
                <div class="adm-nav-label">Operations</div>
                <ul class="adm-nav">
                    <li>
                        <a href="{{ route('shipments.index') }}" class="{{ request()->routeIs('shipments.*') ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 3h1a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h1"/><path d="M9 3h6v4H9z"/><path d="M8 12h8M8 16h5"/></svg>
                            Shipments
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('customers.index') }}" class="{{ request()->routeIs('customers.*') ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            Customers
                        </a>
                    </li>
                </ul>
            </div>

            <div class="adm-nav-group">
                <div class="adm-nav-label">Fleet</div>
                <ul class="adm-nav">
                    <li>
                        <a href="{{ route('drivers.index') }}" class="{{ request()->routeIs('drivers.*') ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21v-2a6 6 0 0 1 6-6h4a6 6 0 0 1 6 6v2"/></svg>
                            Drivers
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('vehicles.index') }}" class="{{ request()->routeIs('vehicles.*') ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 17h14M5 17a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM23 17a2 2 0 1 1-4 0 2 2 0 0 1 4 0z"/><path d="M5 17V9l2-5h10l2 5v8"/></svg>
                            Vehicles
                        </a>
                    </li>
                </ul>
            </div>

            <div class="adm-nav-group">
                <div class="adm-nav-label">Reporting</div>
                <ul class="adm-nav">
                    <li>
                        <a href="{{ route('activity.index') }}" class="{{ request()->routeIs('activity.*') ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8v4l3 3"/><circle cx="12" cy="12" r="10"/></svg>
                            Activity Log
                        </a>
                    </li>
                </ul>
            </div>
        @endif

        {{-- Driver quick links --}}
        @if($user->isDriver())
            <div class="adm-nav-group">
                <div class="adm-nav-label">My Work</div>
                <ul class="adm-nav">
                    <li>
                        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 3h1a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h1"/><path d="M9 3h6v4H9z"/></svg>
                            My Deliveries
                        </a>
                    </li>
                </ul>
            </div>
        @endif

        {{-- Customer quick links --}}
        @if($user->isCustomer())
            <div class="adm-nav-group">
                <div class="adm-nav-label">My Account</div>
                <ul class="adm-nav">
                    <li>
                        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 3h1a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h1"/><path d="M9 3h6v4H9z"/></svg>
                            My Shipments
                        </a>
                    </li>
                </ul>
            </div>
        @endif

        {{-- Account --}}
        <div class="adm-nav-group">
            <div class="adm-nav-label">Account</div>
            <ul class="adm-nav">
                <li>
                    <a href="{{ route('notifications.index') }}" class="{{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                        Notifications
                    </a>
                </li>
                <li>
                    <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                        Settings
                    </a>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5M21 12H9"/></svg>
                            Log Out
                        </a>
                    </form>
                </li>
            </ul>
        </div>
    </aside>

    {{-- Main content --}}
    <div class="adm-main">

        {{-- Topbar --}}
        <header class="adm-topbar">
            <div style="display: flex; align-items: center; gap: 16px;">
                <button class="adm-mobile-toggle" onclick="document.getElementById('admSidebar').classList.toggle('open')">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
                </button>
                <h1 class="adm-page-title">@yield('page_title', 'Dashboard')</h1>
            </div>

            <div style="display: flex; align-items: center; gap: 8px;">
                <button id="dmToggle" type="button" style="background: #F5F6F7; border: none; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; color: #073B3A;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                </button>

                @php
                    $unreadCount = auth()->user()->unreadNotifications->count();
                @endphp

                <a href="{{ route('notifications.index') }}" style="position: relative; display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 50%; background: #F5F6F7; color: #073B3A; text-decoration: none;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    @if($unreadCount > 0)
                        <span style="position: absolute; top: 2px; right: 2px; background: #FF6B00; color: white; font-size: 10px; font-weight: 700; padding: 2px 6px; border-radius: 999px; min-width: 18px; text-align: center;">
                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                        </span>
                    @endif
                </a>

                <div class="adm-user">
                    <div class="adm-user-info">
                        <p>{{ $user->name }}</p>
                        <span>{{ $user->role }}</span>
                    </div>
                    <div class="adm-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                </div>
            </div>
        </header>

        {{-- Content --}}
        <main class="adm-content">
            <div class="adm-container">
                @if(session('success'))
                    <div class="adm-alert adm-alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="adm-alert adm-alert-error">{{ session('error') }}</div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

</div>

<script>
    document.addEventListener('click', function(e) {
        const sidebar = document.getElementById('admSidebar');
        const toggle = document.querySelector('.adm-mobile-toggle');
        if (window.innerWidth <= 900 && sidebar.classList.contains('open')) {
            if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
                sidebar.classList.remove('open');
            }
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', () => {
            const btn = form.querySelector('button[type="submit"]');
            if (btn && !btn.disabled) {
                btn.disabled = true;
                btn.style.opacity = '0.6';
                btn.style.cursor = 'wait';
            }
        });
    });
</script>

<script>
    (function() {
        const html = document.documentElement;
        const toggle = document.getElementById('dmToggle');
        if (!toggle) return;

        if (localStorage.getItem('darkMode') === '1') {
            html.classList.add('dark');
        }

        toggle.addEventListener('click', () => {
            html.classList.toggle('dark');
            localStorage.setItem('darkMode', html.classList.contains('dark') ? '1' : '0');
        });
    })();
</script>

@stack('scripts')

</body>
</html>