@extends('layouts.guest')

@section('title', 'Sign Up')

@section('form')
    <h1 class="auth-animate">Create account</h1>
    <p class="auth-animate auth-delay-1">Join LogiFlow and start shipping smarter.</p>

    <form method="POST" action="{{ route('register') }}" class="auth-animate auth-delay-2">
        @csrf

        <div class="auth-group">
            <label for="name" class="auth-label">Full Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" class="auth-input" required autofocus autocomplete="name">
            @error('name') <p class="auth-error">{{ $message }}</p> @enderror
        </div>

        <div class="auth-group">
            <label for="email" class="auth-label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="auth-input" required autocomplete="username">
            @error('email') <p class="auth-error">{{ $message }}</p> @enderror
        </div>

        <div class="auth-group">
            <label for="password" class="auth-label">Password</label>
            <input id="password" type="password" name="password" class="auth-input" required autocomplete="new-password">
            @error('password') <p class="auth-error">{{ $message }}</p> @enderror
        </div>

        <div class="auth-group">
            <label for="password_confirmation" class="auth-label">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="auth-input" required autocomplete="new-password">
        </div>

        <button type="submit" class="auth-btn">Create Account</button>
    </form>

    <div class="auth-footer auth-animate auth-delay-3">
        Already have an account? <a href="{{ route('login') }}">Sign in</a>
    </div>
@endsection