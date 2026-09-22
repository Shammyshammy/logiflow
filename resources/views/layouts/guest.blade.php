<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sign In') — LogiFlow</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .auth-body { margin: 0; font-family: 'Inter', sans-serif; min-height: 100vh; display: grid; grid-template-columns: 1fr 1fr; background: #fff; }
        .auth-visual { background: #073B3A; color: #fff; padding: 60px; display: flex; flex-direction: column; justify-content: space-between; position: relative; overflow: hidden; }
        .auth-visual::before { content: ""; position: absolute; inset: 0; background: radial-gradient(circle at 20% 30%, rgba(203,205,48,0.15), transparent 50%), radial-gradient(circle at 80% 70%, rgba(18,137,133,0.4), transparent 50%); }
        .auth-visual-inner { position: relative; z-index: 2; }
        .auth-logo { font-size: 28px; font-weight: 800; color: #fff; text-decoration: none; letter-spacing: -0.5px; }
        .auth-logo span { color: #CBCD30; }
        .auth-visual h2 { font-size: 40px; font-weight: 800; letter-spacing: -1px; line-height: 1.15; margin: 60px 0 16px; }
        .auth-visual p { font-size: 16px; opacity: 0.85; line-height: 1.6; max-width: 420px; }
        .auth-visual .accent { color: #CBCD30; }
        .auth-visual-footer { position: relative; z-index: 2; font-size: 13px; opacity: 0.5; }

        .auth-form-side { padding: 60px; display: flex; align-items: center; justify-content: center; }
        .auth-form-inner { width: 100%; max-width: 400px; }
        .auth-form-inner h1 { font-size: 32px; font-weight: 800; letter-spacing: -1px; margin: 0 0 8px; color: #0F1B1C; }
        .auth-form-inner > p { color: #6B7273; font-size: 15px; margin: 0 0 32px; }

        .auth-group { margin-bottom: 20px; }
        .auth-label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; color: #0F1B1C; }
        .auth-input { width: 100%; padding: 12px 16px; border: 1.5px solid #CED1D1; border-radius: 10px; font-size: 15px; font-family: inherit; background: #fff; transition: all 0.2s; box-sizing: border-box; }
        .auth-input:focus { outline: none; border-color: #073B3A; box-shadow: 0 0 0 3px rgba(7,59,58,0.08); }
        .auth-error { color: #991B1B; font-size: 12px; margin-top: 6px; }

        .auth-row { display: flex; align-items: center; justify-content: space-between; margin: 20px 0; font-size: 14px; }
        .auth-check { display: flex; align-items: center; gap: 8px; color: #4A5253; }
        .auth-check input { accent-color: #073B3A; }
        .auth-link { color: #073B3A; font-weight: 600; text-decoration: none; }
        .auth-link:hover { text-decoration: underline; }

        .auth-btn { width: 100%; padding: 14px 20px; border-radius: 10px; background: #073B3A; color: #fff; font-size: 15px; font-weight: 600; font-family: inherit; border: none; cursor: pointer; transition: all 0.2s; }
        .auth-btn:hover { background: #0A504E; transform: translateY(-1px); box-shadow: 0 8px 24px rgba(7,59,58,0.15); }

        .auth-footer { text-align: center; margin-top: 24px; font-size: 14px; color: #6B7273; }
        .auth-footer a { color: #073B3A; font-weight: 600; text-decoration: none; }
        .auth-footer a:hover { text-decoration: underline; }

        @keyframes authFadeIn { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
        .auth-animate { animation: authFadeIn 0.6s ease-out forwards; opacity: 0; }
        .auth-delay-1 { animation-delay: 0.1s; }
        .auth-delay-2 { animation-delay: 0.2s; }
        .auth-delay-3 { animation-delay: 0.3s; }

        @media (max-width: 900px) {
            .auth-body { grid-template-columns: 1fr; }
            .auth-visual { display: none; }
            .auth-form-side { padding: 32px 24px; min-height: 100vh; }
        }
    </style>
</head>
<body class="auth-body">

    {{-- Left: Visual --}}
    <div class="auth-visual">
        <div class="auth-visual-inner">
            <a href="{{ route('home') }}" class="auth-logo">Logi<span>Flow</span></a>

            <h2>Move things <span class="accent">smarter</span>, not harder.</h2>
            <p>The modern logistics platform for teams that ship. Track shipments in real time, manage drivers, and keep customers in the loop.</p>
        </div>

        <div class="auth-visual-footer">
            © {{ date('Y') }} LogiFlow — All rights reserved
        </div>
    </div>

    {{-- Right: Form --}}
    <div class="auth-form-side">
        <div class="auth-form-inner">
            @yield('form')
        </div>
    </div>

</body>
</html>