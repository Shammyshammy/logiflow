@extends('layouts.public')

@section('title', 'Page Not Found — LogiFlow')

@section('content')
    <section class="lf-section" style="padding-top: 160px; padding-bottom: 100px; text-align: center;">
        <div class="lf-container" style="max-width: 560px;">
            <p style="font-size: 100px; font-weight: 800; color: #CBCD30; margin: 0; letter-spacing: -4px;">404</p>
            <h1 style="font-size: 32px; font-weight: 800; letter-spacing: -1px; margin: 8px 0 12px;">Page not found</h1>
            <p style="font-size: 16px; color: #6B7273; margin-bottom: 32px;">
                The page you're looking for doesn't exist or was moved.
            </p>
            <a href="{{ route('home') }}" class="lf-btn lf-btn-primary">Back to home</a>
        </div>
    </section>
@endsection