@extends('layouts.admin')

@section('title', 'Settings')
@section('page_title', 'Settings')

@section('content')

    <div class="adm-page-header">
        <div>
            <h1>Settings</h1>
            <p>Manage your account information and security</p>
        </div>
    </div>

    <div class="adm-grid" style="grid-template-columns: 1fr 1fr; gap: 20px;">

        <div class="adm-card">
            <h2 style="font-size: 16px; font-weight: 700; margin: 0 0 4px;">Profile Information</h2>
            <p style="font-size: 13px; color: #6B7273; margin: 0 0 20px;">
                Update your name, email, and phone number.
            </p>

            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="adm-card">
            <h2 style="font-size: 16px; font-weight: 700; margin: 0 0 4px;">Change Password</h2>
            <p style="font-size: 13px; color: #6B7273; margin: 0 0 20px;">
                Use a strong, unique password to keep your account secure.
            </p>

            @include('profile.partials.update-password-form')
        </div>
    </div>

    <div class="adm-card" style="margin-top: 20px; border-color: #FECACA;">
        <h2 style="font-size: 16px; font-weight: 700; margin: 0 0 4px; color: #991B1B;">Delete Account</h2>
        <p style="font-size: 13px; color: #6B7273; margin: 0 0 20px;">
            Once your account is deleted, all data will be permanently removed. This action cannot be undone.
        </p>

        @include('profile.partials.delete-user-form')
    </div>

@endsection