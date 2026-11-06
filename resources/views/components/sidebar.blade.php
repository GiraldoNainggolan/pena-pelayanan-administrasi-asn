@php
    $role = auth()->user()->role;
    $prefix = $role === 'viewer' ? 'viewer' : 'admin';
@endphp

<!-- Sidebar Component -->
<aside class="w-full md:w-64 bg-white text-slate-600 flex flex-col justify-between flex-shrink-0 border-r border-slate-200/60 no-print">
    <div>
        <!-- Logo & Identity -->
        <div class="border-b border-slate-100 px-6 py-5 flex items-center gap-3 bg-slate-50/50">
            <div class="h-9 w-9 flex items-center justify-center shrink-0 overflow-hidden">
                <img src="{{ asset('logo.webp') }}" alt="Logo Tuban" class="h-full w-auto object-contain">
            </div>
            <div>
                <a href="{{ route($prefix . '.dashboard') }}" class="text-sm font-black tracking-wide text-sky-900 block leading-none">PENA</a>
                <span class="text-[9px] text-slate-500 font-bold uppercase tracking-widest block mt-1">ASN Administrasi</span>
            </div>
        </div>

        <!-- Menu -->
        <nav class="space-y-6 px-4 py-6">
            <div>
                <p class="px-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Menu Utama</p>
                <div class="mt-2 space-y-1">
                    <!-- Dashboard -->
                    <a href="{{ route($prefix . '.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold rounded-xl transition-all duration-150 {{ request()->routeIs($prefix . '.dashboard') ? 'bg-sky-50 text-sky-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 shrink-0 {{ request()->routeIs($prefix . '.dashboard') ? 'text-sky-600' : 'text-slate-400' }}">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                    
                    <!-- Input Layanan (Admin/Operator only) -->
                    @if($role === 'admin' || $role === 'operator')
                        <a href="{{ route('admin.layanan.create') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold rounded-xl transition-all duration-150 {{ request()->routeIs('admin.layanan.create') ? 'bg-sky-50 text-sky-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.layanan.create') ? 'text-sky-600' : 'text-slate-400' }}">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            <span>Input Layanan</span>
                        </a>
                    @endif

                    <!-- Data Layanan -->
                    <a href="{{ route($prefix . '.layanan.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold rounded-xl transition-all duration-150 {{ request()->routeIs($prefix . '.layanan.index') || request()->routeIs($prefix . '.layanan.show') || request()->routeIs($prefix . '.layanan.edit') ? 'bg-sky-50 text-sky-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 shrink-0 {{ request()->routeIs($prefix . '.layanan.index') || request()->routeIs($prefix . '.layanan.show') || request()->routeIs($prefix . '.layanan.edit') ? 'text-sky-600' : 'text-slate-400' }}">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0v3.75" />
                        </svg>
                        <span>Data Layanan</span>
                    </a>

                    <!-- Laporan -->
                    <a href="{{ route($prefix . '.laporan.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold rounded-xl transition-all duration-150 {{ request()->routeIs($prefix . '.laporan.index') ? 'bg-sky-50 text-sky-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 shrink-0 {{ request()->routeIs($prefix . '.laporan.index') ? 'text-sky-600' : 'text-slate-400' }}">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                        </svg>
                        <span>Laporan</span>
                    </a>
                </div>
            </div>

            <!-- Master Data & Users (Admin only) -->
            @if($role === 'admin')
                <div>
                    <p class="px-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Administrator</p>
                    <div class="mt-2 space-y-1">
                        <a href="{{ route('admin.master.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold rounded-xl transition-all duration-150 {{ request()->routeIs('admin.master.index') ? 'bg-sky-50 text-sky-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.master.index') ? 'text-sky-600' : 'text-slate-400' }}">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.008 1.24l.885 1.77a2.25 2.25 0 0 0 2.007 1.24h1.98a2.25 2.25 0 0 0 2.007-1.24l.885-1.77a2.25 2.25 0 0 1 2.007-1.24h3.86m-18 0h18m-18 0v-2.25A2.25 2.25 0 0 1 4.5 9h15A2.25 2.25 0 0 1 21.75 11.25v2.25m-18 0v3A2.25 2.25 0 0 0 4.5 21h15a2.25 2.25 0 0 0 2.25-2.25v-3m-9 0h.008v.008H12v-.008Z" />
                            </svg>
                            <span>Master Data</span>
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold rounded-xl transition-all duration-150 {{ request()->routeIs('admin.users.index') ? 'bg-sky-50 text-sky-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.users.index') ? 'text-sky-600' : 'text-slate-400' }}">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            <span>Manajemen User</span>
                        </a>
                    </div>
                </div>
            @endif
        </nav>
    </div>

    <!-- Profil & Logout -->
    <div class="border-t border-slate-100 p-4 bg-slate-50/50">
        <a href="{{ route($prefix . '.profile') }}" class="block p-3 hover:bg-white mb-3 border border-transparent hover:border-slate-200 bg-transparent rounded-xl transition duration-150 hover:shadow-xs">
            <div class="flex items-center gap-3">
                <div class="h-9 w-9 rounded-full bg-sky-100 text-sky-700 flex items-center justify-center font-bold text-sm shrink-0 border border-sky-200/50">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <span class="block text-sm font-bold text-slate-800 truncate leading-tight">{{ auth()->user()->name }}</span>
                    <span class="block truncate text-[10px] text-slate-500 mt-0.5">{{ auth()->user()->email }}</span>
                </div>
            </div>
            <div class="mt-3 flex items-center justify-between">
                <span class="inline-block px-2 py-0.5 text-[9px] uppercase font-bold tracking-wider bg-slate-200 text-slate-600 rounded">
                    {{ $role }}
                </span>
                <span class="text-[10px] text-sky-600 font-semibold group-hover:underline">Profil &rarr;</span>
            </div>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="w-full bg-white border border-slate-200 text-slate-700 hover:bg-rose-50 hover:text-rose-700 hover:border-rose-200 px-4 py-2.5 rounded-xl text-sm font-bold transition duration-150 flex items-center justify-center gap-2 cursor-pointer shadow-xs hover:shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                </svg>
                Log Out
            </button>
        </form>
    </div>
</aside>
