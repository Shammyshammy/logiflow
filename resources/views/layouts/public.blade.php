<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LogiFlow — Logistics & Shipment Tracking')</title>
    <meta name="description" content="@yield('meta_description', 'Track your shipments in real time. LogiFlow is a modern logistics management platform.')">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>📦</text></svg>">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Scroll reveal */
        .reveal-up { opacity: 0; transform: translateY(30px); transition: opacity 0.8s ease, transform 0.8s ease; }
        .reveal-up.visible { opacity: 1; transform: translateY(0); }
        .reveal-fade { opacity: 0; transition: opacity 1s ease; }
        .reveal-fade.visible { opacity: 1; }
        .reveal-scale { opacity: 0; transform: scale(0.95); transition: opacity 0.8s ease, transform 0.8s ease; }
        .reveal-scale.visible { opacity: 1; transform: scale(1); }
    </style>
</head>
<body class="lf-body">

    {{-- Header --}}
    <header class="lf-header" id="lfHeader">
        <div class="lf-container">
            <nav class="lf-nav">
                <a href="{{ route('home') }}" class="lf-logo">Logi<span>Flow</span></a>

                <ul class="lf-nav-links">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('tracking.index') }}">Track</a></li>
                    <li><a href="{{ route('booking.show') }}">Book</a></li>
                    <li><a href="#features">Features</a></li>
                    <li><a href="#about">About</a></li>
                </ul>

                <div style="display: flex; gap: 12px; align-items: center;">
                    @auth
                        <a href="{{ route('dashboard') }}" class="lf-btn lf-btn-dark">
                            {{ auth()->user()->hasRole(['admin', 'staff']) ? 'Admin Panel' : 'Dashboard' }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="lf-btn lf-btn-outline">Sign In</a>
                        <a href="{{ route('register') }}" class="lf-btn lf-btn-primary">Get Started</a>
                    @endauth
                </div>
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="lf-footer">
        <div class="lf-container">
            <div class="lf-footer-grid">
                <div>
                    <a href="{{ route('home') }}" class="lf-logo" style="color: white; display: block; margin-bottom: 16px;">Logi<span>Flow</span></a>
                    <p style="color: rgba(255,255,255,0.6); font-size: 14px; line-height: 1.6;">
                        Modern logistics management and shipment tracking. Built for teams that move things.
                    </p>
                </div>

                <div>
                    <h4>Product</h4>
                    <ul>
                        <li><a href="#features">Features</a></li>
                        <li><a href="{{ route('tracking.index') }}">Track Shipment</a></li>
                    </ul>
                </div>

                <div>
                    <h4>Company</h4>
                    <ul>
                        <li><a href="#about">About</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>

                <div>
                    <h4>Legal</h4>
                    <ul>
                        <li><a href="#">Privacy</a></li>
                        <li><a href="#">Terms</a></li>
                    </ul>
                </div>
            </div>

            <div class="lf-footer-bottom">
                © {{ date('Y') }} LogiFlow. All rights reserved.
            </div>
        </div>
    </footer>

    <script>
        // Header scroll
        const header = document.getElementById('lfHeader');
        window.addEventListener('scroll', () => {
            header.classList.toggle('scrolled', window.scrollY > 20);
        });

        // Reveal on scroll
        const io = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });

        document.querySelectorAll('.reveal-up, .reveal-fade, .reveal-scale').forEach(el => io.observe(el));
    </script>

    @stack('scripts')
</body>
</html>