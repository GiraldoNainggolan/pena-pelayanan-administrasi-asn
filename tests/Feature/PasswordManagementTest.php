<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\AccountCreatedNotification;
use App\Notifications\PasswordChangedNotification;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_change_password_from_profile(): void
    {
        Notification::fake();
        $user = User::create([
            'name' => 'Operator PENA',
            'email' => 'operator@example.com',
            'password' => 'CurrentPassword1!',
            'must_change_password' => true,
            'role' => 'operator',
        ]);

        $response = $this->actingAs($user)->put('/profil/password', [
            'current_password' => 'CurrentPassword1!',
            'password' => 'NewSecurePassword2@',
            'password_confirmation' => 'NewSecurePassword2@',
        ]);

        $response->assertRedirect();
        $user->refresh();
        $this->assertTrue(Hash::check('NewSecurePassword2@', $user->password));
        $this->assertFalse($user->must_change_password);
        Notification::assertSentTo($user, PasswordChangedNotification::class);
    }

    public function test_forgot_password_sends_custom_notification(): void
    {
        Notification::fake();
        $user = User::create([
            'name' => 'Viewer PENA',
            'email' => 'viewer@example.com',
            'password' => 'CurrentPassword1!',
            'role' => 'viewer',
        ]);

        $response = $this->post('/lupa-password', ['email' => 'VIEWER@example.com']);

        $response->assertRedirect();
        $response->assertSessionHas('status');
        Notification::assertSentTo($user, ResetPasswordNotification::class);
    }

    public function test_valid_token_can_reset_password(): void
    {
        $user = User::create([
            'name' => 'Viewer PENA',
            'email' => 'viewer@example.com',
            'password' => 'CurrentPassword1!',
            'must_change_password' => true,
            'role' => 'viewer',
        ]);
        $token = Password::createToken($user);

        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'ResetSecurePassword3#',
            'password_confirmation' => 'ResetSecurePassword3#',
        ]);

        $response->assertRedirect(route('login'));
        $user->refresh();
        $this->assertTrue(Hash::check('ResetSecurePassword3#', $user->password));
        $this->assertFalse($user->must_change_password);
    }

    public function test_admin_can_create_user_with_confirmed_temporary_password(): void
    {
        Notification::fake();
        $admin = User::create([
            'name' => 'Administrator PENA',
            'email' => 'admin@example.com',
            'password' => 'AdminPassword1!',
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->post('/admin/users', [
            'name' => 'Operator Baru',
            'email' => 'new.operator@example.com',
            'password' => 'TemporaryPassword4$',
            'password_confirmation' => 'TemporaryPassword4$',
            'role' => 'operator',
        ]);

        $response->assertRedirect();
        $user = User::where('email', 'new.operator@example.com')->firstOrFail();
        $this->assertTrue($user->must_change_password);
        Notification::assertSentTo($user, AccountCreatedNotification::class);
    }

    public function test_viewer_can_use_read_only_routes_but_not_admin_console(): void
    {
        $viewer = User::create([
            'name' => 'Viewer PENA',
            'email' => 'viewer@example.com',
            'password' => 'ViewerPassword1!',
            'role' => 'viewer',
        ]);

        $this->actingAs($viewer)->get('/viewer/dashboard')->assertOk();
        $this->actingAs($viewer)->get('/viewer/layanan')->assertOk();
        $this->actingAs($viewer)->get('/viewer/laporan')->assertOk();
        $this->actingAs($viewer)->get('/admin/dashboard')->assertForbidden();
    }
}