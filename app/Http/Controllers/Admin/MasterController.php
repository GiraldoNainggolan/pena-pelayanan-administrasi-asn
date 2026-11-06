<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kecamatan;
use App\Models\Sekolah;
use App\Models\User;
use App\Notifications\AccountCreatedNotification;
use App\Notifications\PasswordChangedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Throwable;

class MasterController extends Controller
{
    public function index(): View
    {
        $kecamatans = Kecamatan::withCount('sekolahs')->orderBy('nama')->get();
        $sekolahs = Sekolah::with('kecamatan')->orderBy('nama')->get();
        return view('admin.master', compact('kecamatans', 'sekolahs'));
    }

    public function storeKecamatan(Request $request): RedirectResponse
    {
        $request->validate(['nama' => 'required|string|max:255|unique:kecamatans,nama']);
        Kecamatan::create($request->only('nama'));
        return back()->with('status', 'Kecamatan berhasil ditambahkan.');
    }

    public function storeSekolah(Request $request): RedirectResponse
    {
        $request->validate([
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'nama' => 'required|string|max:255',
            'jenjang' => 'required|string|in:KB sederajat,TK sederajat,SD sederajat,SMP sederajat',
        ]);
        Sekolah::create($request->only(['kecamatan_id', 'nama', 'jenjang']));
        return back()->with('status', 'Sekolah berhasil ditambahkan.');
    }

    public function destroyKecamatan($id): RedirectResponse
    {
        Kecamatan::findOrFail($id)->delete();
        return back()->with('status', 'Kecamatan berhasil dihapus.');
    }

    public function destroySekolah($id): RedirectResponse
    {
        Sekolah::findOrFail($id)->delete();
        return back()->with('status', 'Sekolah berhasil dihapus.');
    }

    // User management
    public function usersIndex(): View
    {
        $users = User::orderBy('name')->get();
        return view('admin.users', compact('users'));
    }

    public function usersStore(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(12)->mixedCase()->numbers()->symbols()],
            'role' => 'required|string|in:admin,operator,viewer',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => mb_strtolower(trim((string) $request->email)),
            'password' => $request->password,
            'password_changed_at' => now(),
            'must_change_password' => true,
            'role' => $request->role,
        ]);

        try {
            $user->notify(new AccountCreatedNotification);
        } catch (Throwable $exception) {
            Log::warning('Notifikasi akun baru gagal dikirim.', [
                'user_id' => $user->id,
                'exception' => $exception->getMessage(),
            ]);

            return back()->with('status', 'Akun pengguna berhasil dibuat.')->with(
                'warning',
                'Email pemberitahuan belum terkirim. Periksa konfigurasi SMTP.'
            );
        }

        return back()->with('status', 'Akun pengguna berhasil dibuat dan email pemberitahuan telah dikirim.');
    }

    public function usersDestroy($id): RedirectResponse
    {
        DB::transaction(function () use ($id): void {
            $user = User::query()->lockForUpdate()->findOrFail($id);

            if ($user->id === auth()->id()) {
                abort(422, 'Anda tidak bisa menghapus akun Anda sendiri.');
            }

            if ($user->role === 'admin'
                && User::query()->where('role', 'admin')->lockForUpdate()->count() <= 1) {
                abort(422, 'Administrator terakhir tidak dapat dihapus.');
            }

            $user->delete();
        });

        return back()->with('status', 'Akun pengguna berhasil dihapus.');
    }

    public function usersPasswordUpdate(Request $request, $id): RedirectResponse
    {
        $validated = $request->validate([
            'admin_password' => ['required', 'current_password:web'],
            'password' => ['required', 'confirmed', Password::min(12)->mixedCase()->numbers()->symbols()],
        ]);

        $user = User::findOrFail($id);
        $user->forceFill([
            'password' => $validated['password'],
            'password_changed_at' => now(),
            'must_change_password' => true,
        ])->save();

        DB::table('sessions')->where('user_id', $user->id)->delete();

        try {
            $user->notify(new PasswordChangedNotification(true));
        } catch (Throwable $exception) {
            Log::warning('Notifikasi reset password oleh admin gagal dikirim.', [
                'user_id' => $user->id,
                'exception' => $exception->getMessage(),
            ]);

            return back()->with('status', 'Password pengguna berhasil diubah.')->with(
                'warning',
                'Email konfirmasi belum terkirim. Periksa konfigurasi SMTP.'
            );
        }

        return back()->with('status', 'Password pengguna berhasil diubah dan email konfirmasi telah dikirim.');
    }
}
