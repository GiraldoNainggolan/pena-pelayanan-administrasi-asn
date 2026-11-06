@extends('layouts.app')

@section('title', 'Login - PENA')

@section('content')
    <div class="border border-sky-100 bg-white p-8 shadow-md" style="max-width: 420px; width: 100%; margin: 20px auto;">
        <div class="flex flex-col items-center mb-6">
            <img src="{{ asset('logo.webp') }}" alt="Logo" style="height: 100px; width: auto; max-height: 100px;" class="opacity-95 mb-4">
            <h1 class="text-lg font-bold text-sky-900 tracking-tight text-center">PENA</h1>
            <p class="text-xs text-slate-500 text-center mt-1">Pelayanan dan Administrasi ASN</p>
        </div>

        <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="text-xs font-semibold uppercase tracking-wider text-slate-600">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus class="mt-1 w-full border border-sky-200 bg-white px-3 py-2 text-sm outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500" placeholder="email@contoh.com">
            </div>
            <div>
                <label for="password" class="text-xs font-semibold uppercase tracking-wider text-slate-600">Password</label>
                <input id="password" name="password" type="password" required class="mt-1 w-full border border-sky-200 bg-white px-3 py-2 text-sm outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500" placeholder="••••••••">
            </div>
            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 font-medium text-slate-600">
                    <input type="checkbox" name="remember" value="1" class="h-4 w-4 border-sky-200 text-sky-600 focus:ring-sky-500">
                    Ingat saya
                </label>
                <a href="{{ route('password.request') }}" class="font-semibold text-sky-600 hover:text-sky-700">Lupa Password?</a>
            </div>
            <button class="w-full bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-sky-700 transition">
                Masuk ke Aplikasi
            </button>
        </form>
    </div>
@endsection
