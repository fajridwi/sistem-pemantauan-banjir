@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-xl font-bold mb-4">Verifikasi OTP</h1>

    <form method="POST" action="/otp/verify">
        @csrf

        <input name="code"
               placeholder="Masukkan kode OTP"
               class="w-full border p-2 rounded mb-3">

        @error('code')
            <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror

        <button class="bg-blue-600 text-white px-4 py-2 rounded w-full">
            Verifikasi
        </button>
    </form>
</div>
@endsection
