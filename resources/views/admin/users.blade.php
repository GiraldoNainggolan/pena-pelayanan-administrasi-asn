@extends('layouts.app')

@section('title', 'Manajemen User - PENA')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="border-b border-sky-100 pb-6">
        <p class="text-sm font-semibold uppercase tracking-wider text-sky-600">Pengaturan Sistem Kepegawaian</p>
        <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-900">Manajemen Akun Pengguna (User)</h1>
    </div>

    <div class="grid gap-8 lg:grid-cols-[1fr_2fr]">
        <!-- Form Buat Akun Baru -->
        <div class="space-y-6">
            <div class="border border-sky-100 bg-white p-6 shadow-2xs">
                <h3 class="text-sm font-bold text-sky-900 border-b border-sky-50 pb-2 mb-4 uppercase tracking-wider">Buat Pengguna Baru</h3>
                <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
                    @csrf
                    <!-- Nama Lengkap -->
                    <div>
                        <label for="user_name" class="block text-xs font-semibold text-slate-700 uppercase mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" id="user_name" required value="{{ old('name') }}" placeholder="Contoh: Budi Santoso" class="w-full border border-sky-100 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none">
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="user_email" class="block text-xs font-semibold text-slate-700 uppercase mb-1">Alamat Email <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" id="user_email" required value="{{ old('email') }}" placeholder="Contoh: budi@tubankab.go.id" class="w-full border border-sky-100 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none">
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="user_password" class="block text-xs font-semibold text-slate-700 uppercase mb-1">Kata Sandi (Password) <span class="text-rose-500">*</span></label>
                        <input type="password" name="password" id="user_password" required minlength="12" autocomplete="new-password" placeholder="Minimal 12 karakter" class="w-full border border-sky-100 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none">
                        <p class="text-3xs text-slate-400 mt-1">Gunakan huruf besar, huruf kecil, angka, dan simbol.</p>
                    </div>

                    <!-- Konfirmasi Password -->
                    <div>
                        <label for="user_password_confirmation" class="block text-xs font-semibold text-slate-700 uppercase mb-1">Konfirmasi Kata Sandi <span class="text-rose-500">*</span></label>
                        <input type="password" name="password_confirmation" id="user_password_confirmation" required minlength="12" autocomplete="new-password" class="w-full border border-sky-100 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none">
                    </div>

                    <!-- Role -->
                    <div>
                        <label for="user_role" class="block text-xs font-semibold text-slate-700 uppercase mb-1">Hak Akses (Role) <span class="text-rose-500">*</span></label>
                        <select name="role" id="user_role" required class="w-full border border-sky-100 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none">
                            <option value="operator" {{ old('role') === 'operator' ? 'selected' : '' }}>Operator / Pelaksana (Input & Edit)</option>
                            <option value="viewer" {{ old('role') === 'viewer' ? 'selected' : '' }}>Viewer / Kabid / Kadin (Hanya Baca)</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator (Akses Penuh)</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-sky-600 text-white py-2 text-center text-sm font-semibold hover:bg-sky-700 transition">
                        Buat Akun Baru
                    </button>
                </form>
            </div>
        </div>

        <!-- Daftar Akun Pengguna -->
        <div class="border border-sky-100 bg-white shadow-2xs">
            <div class="border-b border-sky-100 px-6 py-4">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Daftar Akun Pengguna Terdaftar</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-sky-100 bg-sky-50/50 text-xs font-semibold uppercase tracking-wider text-sky-900">
                            <th class="px-6 py-3.5">Nama</th>
                            <th class="px-6 py-3.5">Email</th>
                            <th class="px-6 py-3.5">Hak Akses</th>
                            <th class="px-6 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-sky-100 text-xs">
                        @foreach($users as $user)
                            <tr class="hover:bg-sky-50/20">
                                <td class="px-6 py-3.5 font-semibold text-slate-800">
                                    {{ $user->name }}
                                    @if($user->id === auth()->id())
                                        <span class="ml-1 text-3xs font-bold text-sky-600 bg-sky-50 px-1.5 py-0.5 uppercase tracking-wider">Saya</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3.5 text-slate-600">{{ $user->email }}</td>
                                <td class="px-6 py-3.5">
                                    <span class="inline-block px-2.5 py-0.5 text-4xs uppercase font-bold tracking-wider 
                                        @if($user->role === 'admin') bg-indigo-100 text-indigo-800
                                        @elseif($user->role === 'operator') bg-amber-100 text-amber-800
                                        @else bg-slate-100 text-slate-700 @endif">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="px-6 py-3.5 text-right whitespace-nowrap">
                                    @if($user->id !== auth()->id())
                                        <button type="button" onclick="document.getElementById('resetPwModal{{ $user->id }}').classList.remove('hidden')" class="text-xs font-bold text-amber-600 hover:text-amber-700 mr-2">
                                            Reset PW
                                        </button>
                                        <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs font-bold text-rose-600 hover:text-rose-700">
                                                Hapus
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Tidak dapat dihapus</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Reset Password Modals -->
@foreach($users as $user)
    @if($user->id !== auth()->id())
    <div id="resetPwModal{{ $user->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4">
        <div class="bg-white border border-sky-100 shadow-lg w-full max-w-sm p-6 relative">
            <button type="button" onclick="this.closest('[id^=resetPwModal]').classList.add('hidden')" class="absolute top-3 right-3 text-slate-400 hover:text-slate-600 text-lg font-bold">&times;</button>
            <h3 class="text-sm font-bold text-sky-900 uppercase tracking-wider mb-1">Reset Password</h3>
            <p class="text-xs text-slate-500 mb-4">Atur ulang password untuk <strong>{{ $user->name }}</strong></p>
            <form method="POST" action="{{ route('admin.users.password.update', $user->id) }}" class="space-y-3">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Password Baru <span class="text-rose-500">*</span></label>
                    <input type="password" name="password" required minlength="12" autocomplete="new-password" placeholder="Minimal 12 karakter" class="w-full border border-sky-100 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Konfirmasi Password <span class="text-rose-500">*</span></label>
                    <input type="password" name="password_confirmation" required minlength="12" autocomplete="new-password" class="w-full border border-sky-100 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Password Admin Anda <span class="text-rose-500">*</span></label>
                    <input type="password" name="admin_password" required placeholder="Masukkan password Anda" class="w-full border border-sky-100 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none">
                </div>
                <button type="submit" class="w-full bg-amber-600 text-white py-2 text-sm font-semibold hover:bg-amber-700 transition">
                    Reset Password User
                </button>
            </form>
        </div>
    </div>
    @endif
@endforeach
@endsection
