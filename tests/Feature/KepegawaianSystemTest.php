<?php

namespace Tests\Feature;

use App\Models\Kecamatan;
use App\Models\LayananKepegawaian;
use App\Models\Sekolah;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KepegawaianSystemTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test login view.
     */
    public function test_login_view_is_accessible_to_guests(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Pelayanan dan Administrasi ASN');
    }

    /**
     * Test admin role authorization access.
     */
    public function test_admin_can_access_dashboard_and_master_data(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@pena.test',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Statistik &amp; Rekapitulasi', false);

        $responseMaster = $this->actingAs($admin)->get('/admin/master');
        $responseMaster->assertStatus(200);
        $responseMaster->assertSee('Kelola Wilayah & Lembaga Sekolah', false);
    }

    /**
     * Test operator role authorization limits.
     */
    public function test_operator_cannot_access_master_data(): void
    {
        $operator = User::create([
            'name' => 'Operator User',
            'email' => 'operator@pena.test',
            'password' => bcrypt('password'),
            'role' => 'operator',
        ]);

        $response = $this->actingAs($operator)->get('/admin/dashboard');
        $response->assertStatus(200);

        $responseMaster = $this->actingAs($operator)->get('/admin/master');
        $responseMaster->assertStatus(403); // checkRole middleware should abort with 403
    }

    /**
     * Test master data CRUD.
     */
    public function test_admin_can_manage_kecamatan_and_sekolah(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@pena.test',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $responseKec = $this->actingAs($admin)->post('/admin/master/kecamatan', [
            'nama' => 'Semanding',
        ]);
        $responseKec->assertRedirect();
        $this->assertDatabaseHas('kecamatans', ['nama' => 'Semanding']);

        $kecamatan = Kecamatan::where('nama', 'Semanding')->firstOrFail();

        $responseSek = $this->actingAs($admin)->post('/admin/master/sekolah', [
            'kecamatan_id' => $kecamatan->id,
            'nama' => 'SDN Gedungombo 1',
            'jenjang' => 'SD sederajat',
        ]);
        $responseSek->assertRedirect();
        $this->assertDatabaseHas('sekolahs', ['nama' => 'SDN Gedungombo 1']);
    }

    /**
     * Test Layanan Kepegawaian creation by operator.
     */
    public function test_operator_can_create_layanan_kepegawaian(): void
    {
        $operator = User::create([
            'name' => 'Operator User',
            'email' => 'operator@pena.test',
            'password' => bcrypt('password'),
            'role' => 'operator',
        ]);

        $kec = Kecamatan::create(['nama' => 'Tuban']);
        $sek = Sekolah::create([
            'kecamatan_id' => $kec->id,
            'nama' => 'SMPN 1 Tuban',
            'jenjang' => 'SMP sederajat',
        ]);

        $response = $this->actingAs($operator)->post('/admin/layanan', [
            'nama_asn' => 'Imron Sadewo',
            'nip' => '199001012020121001',
            'nik' => '3523010101900001',
            'no_hp' => '081234567890',
            'email' => 'imron@example.com',
            'kecamatan_id' => $kec->id,
            'jenjang' => 'SMP sederajat',
            'sekolah_id' => $sek->id,
            'layanan' => 'Lainnya',
            'sub_layanan' => 'Pemutakhiran Data Mandiri (PDM)',
            'permasalahan' => 'Lupa password login MyASN.',
            'tindak_lanjut' => 'Melakukan reset password di portal SIASN BKN.',
            'hasil_layanan' => 'Selesai',
        ]);

        $response->assertRedirect('/admin/layanan');
        $this->assertDatabaseHas('layanan_kepegawaian', [
            'nama_asn' => 'Imron Sadewo',
            'nip' => '199001012020121001',
            'kecamatan_id' => $kec->id,
            'sekolah_id' => $sek->id,
        ]);
    }
}
