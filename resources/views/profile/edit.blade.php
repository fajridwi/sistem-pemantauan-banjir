@php
    // Pilih layout sesuai role user
    $layout = auth()->user()->role === 'pemerintah'
        ? 'layouts.pemerintah'
        : 'layouts.masyarakat';
@endphp

@extends($layout)

@section('title', 'Profile')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h2 class="text-2xl font-bold mb-6">Profile</h2>

    {{-- Update Profile Information --}}
    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg mb-6">
        <div class="max-w-xl">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    {{-- Update Password --}}
    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg mb-6">
        <div class="max-w-xl">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    {{-- Delete Account --}}
    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
        <div class="max-w-xl">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
@endsection
