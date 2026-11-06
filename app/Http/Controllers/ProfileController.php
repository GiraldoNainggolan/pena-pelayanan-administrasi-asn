<?php

namespace App\Http\Controllers;

use App\Notifications\PasswordChangedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Throwable;

class ProfileController extends Controller
{
    public function show(): View
    {
        return view('admin.profile');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password:web'],
            'password' => ['required', 'confirmed', Password::min(12)->mixedCase()->numbers()->symbols()],
        ], [
            'current_password.current_password' => 'Password saat ini tidak sesuai. Gunakan password yang dipakai saat login.',
            'password.confirmed' => 'Konfirmasi password baru tidak sama.',
        ]);

        $user = $request->user();
        $user->forceFill([
            'password' => $validated['password'],
            'password_changed_at' => now(),
            'must_change_password' => false,
        ])->save();

        DB::table('sessions')
            ->where('user_id', $user->id)
            ->where('id', '!=', $request->session()->getId())
            ->delete();

        try {
            $user->notify(new PasswordChangedNotification(false));
        } catch (Throwable $exception) {
            Log::warning('Notifikasi perubahan password gagal dikirim.', [
                'user_id' => $user->id,
                'exception' => $exception->getMessage(),
            ]);

            return redirect()
                ->to($request->user()->role === 'viewer' ? route('viewer.profile') : route('admin.profile'))
                ->with('status', 'Password berhasil diubah dan sudah aktif. Gunakan password baru pada login berikutnya.')
                ->with(
                'warning',
                'Email konfirmasi belum terkirim karena autentikasi Gmail ditolak. Perubahan password tetap berhasil.'
                );
        }

        return redirect()
            ->to($request->user()->role === 'viewer' ? route('viewer.profile') : route('admin.profile'))
            ->with('status', 'Password berhasil diubah dan sudah aktif. Email konfirmasi telah dikirim.');
    }
}