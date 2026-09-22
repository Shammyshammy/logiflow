@extends('layouts.guest')

@section('title', 'Sign In')

@section('form')
    <h1 class="auth-animate">Welcome back</h1>
    <p class="auth-animate auth-delay-1">Sign in to your LogiFlow account to continue.</p>

    @if (session('status'))
        <div style="background: #D1FAE5; color: #065F46; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 14px;">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="auth-animate auth-delay-2">
        @csrf

        <div class="auth-group">
            <label for="email" class="auth-label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="auth-input" required autofocus autocomplete="username">
            @error('email') <p class="auth-error">{{ $message }}</p> @enderror
        </div>

        <div class="auth-group">
            <label for="password" class="auth-label">Password</label>
            <input id="password" type="password" name="password" class="auth-input" required autocomplete="current-password">
            @error('password') <p class="auth-error">{{ $message }}</p> @enderror
        </div>

        <div class="auth-row">
            <label class="auth-check">
                <input type="checkbox" name="remember">
                <span>Remember me</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="auth-link">Forgot password?</a>
            @endif
        </div>

        <button type="submit" class="auth-btn">Sign In</button>
    </form>

    <div class="auth-footer auth-animate auth-delay-3">
        Don't have an account? <a href="{{ route('register') }}">Sign up</a>
    </div>
@endsection