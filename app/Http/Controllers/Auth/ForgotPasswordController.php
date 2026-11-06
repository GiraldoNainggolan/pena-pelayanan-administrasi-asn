<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email:rfc', 'max:255'],
        ]);

        Password::sendResetLink([
            'email' => mb_strtolower(trim($validated['email'])),
        ]);

        return back()->with(
            'status',
            'Jika email terdaftar, tautan pengaturan ulang password telah dikirim. Periksa folder spam bila diperlukan.'
        );
    }
}