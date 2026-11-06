@extends('layouts.app')

@section('title', 'Reset Password - PENA')

@section('content')
    <div class="border border-sky-100 bg-white p-8 shadow-md" style="max-width: 420px; width: 100%; margin: 20px auto;">
        <div class="flex flex-col items-center mb-6">
            <img src="{{ asset('logo.webp') }}" alt="Logo" style="height: 80px; width: auto;" class="opacity-95 mb-4">
            <h1 class="text-lg font-bold text-sky-900 tracking-tight text-center">Atur Ulang Password</h1>
            <p class="text-xs text-slate-500 text-center mt-1">PENA – Pelayanan dan Administrasi ASN</p>
        </div>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div>
                <label for="email" class="text-xs font-semibold uppercase tracking-wider text-slate-600">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $email) }}" required class="mt-1 w-full border border-sky-200 bg-white px-3 py-2 text-sm outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500">
            </div>
            <div>
                <label for="password" class="text-xs font-semibold uppercase tracking-wider text-slate-600">Password Baru</label>
                <input id="password" name="password" type="password" required minlength="12" autocomplete="new-password" class="mt-1 w-full border border-sky-200 bg-white px-3 py-2 text-sm outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500" placeholder="Min 12 karakter, huruf besar/kecil, angka, simbol">
            </div>
            <div>
                <label for="password_confirmation" class="text-xs font-semibold uppercase tracking-wider text-slate-600">Konfirmasi Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required minlength="12" autocomplete="new-password" class="mt-1 w-full border border-sky-200 bg-white px-3 py-2 text-sm outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500">
            </div>
            <button class="w-full bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-sky-700 transition">
                Simpan Password Baru
            </button>
        </form>

        <div class="mt-4 text-center">
            <a href="{{ route('login') }}" class="text-xs font-semibold text-sky-600 hover:text-sky-700">← Kembali ke Login</a>
        </div>
    </div>
@endsection
