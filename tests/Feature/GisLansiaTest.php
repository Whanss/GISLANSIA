<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Lansia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class GisLansiaTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $petugas;

    // ── Setup: jalankan sebelum setiap test ───────────────────────────────────
    protected function setUp(): void
    {
        parent::setUp();

        // Buat semua permission
        $permissions = [
            'lansia.view', 'lansia.create', 'lansia.edit',
            'lansia.delete', 'lansia.set_status', 'lansia.auto_confirm',
            'user.view', 'user.create', 'user.edit', 'user.delete',
            'role.view', 'role.create', 'role.edit', 'role.delete',
        ];
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Buat role super admin (semua permission) dan petugas (terbatas)
        $adminRole   = Role::firstOrCreate(['name' => 'super admin']);
        $petugasRole = Role::firstOrCreate(['name' => 'petugas']);
        $adminRole->syncPermissions(Permission::all());
        $petugasRole->syncPermissions(['lansia.view', 'lansia.create', 'lansia.edit']);

        // Buat user admin dan petugas
        $this->admin   = User::factory()->create();
        $this->petugas = User::factory()->create();
        $this->admin->assignRole('super admin');
        $this->petugas->assignRole('petugas');
    }

    // =========================================================================
    // FITUR 1 — LOGIN
    // =========================================================================

    #[Test]
    public function halaman_login_dapat_diakses()
    {
        $this->get('/login')->assertStatus(200);
    }

    #[Test]
    public function user_dapat_login_dengan_kredensial_valid()
    {
        $user = User::factory()->create(['password' => bcrypt('password123')]);

        $this->post('/login', ['email' => $user->email, 'password' => 'password123'])
             ->assertRedirect('/dashboard');

        $this->assertAuthenticatedAs($user);
    }

    #[Test]
    public function login_gagal_dengan_password_salah()
    {
        $user = User::factory()->create(['password' => bcrypt('password123')]);

        $this->post('/login', ['email' => $user->email, 'password' => 'salah'])
             ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    #[Test]
    public function login_gagal_jika_email_tidak_terdaftar()
    {
        $this->post('/login', ['email' => 'tidakada@test.com', 'password' => 'password123'])
             ->assertSessionHasErrors('email');
    }

    #[Test]
    public function login_gagal_jika_field_kosong()
    {
        $this->post('/login', ['email' => '', 'password' => ''])
             ->assertSessionHasErrors(['email', 'password']);
    }

    // =========================================================================
    // FITUR 2 — LOGOUT
    // =========================================================================

    #[Test]
    public function user_dapat_logout()
    {
        $this->actingAs($this->admin)
             ->post('/logout')
             ->assertRedirect('/login');

        $this->assertGuest();
    }

    #[Test]
    public function guest_tidak_bisa_akses_dashboard()
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    // =========================================================================
    // FITUR 3 — DASHBOARD STATS
    // =========================================================================

    #[Test]
    public function dashboard_dapat_diakses_setelah_login()
    {
        $this->actingAs($this->admin)->get('/dashboard')->assertStatus(200);
    }

    #[Test]
    public function dashboard_menampilkan_statistik_yang_benar()
    {
        Lansia::factory()->create(['status' => 'dikonfirmasi', 'user_id' => $this->admin->id]);
        Lansia::factory()->create(['status' => 'pending',      'user_id' => $this->admin->id]);
        Lansia::factory()->create(['status' => 'ditolak',      'user_id' => $this->admin->id]);
        Lansia::factory()->create(['status' => 'meninggal',    'user_id' => $this->admin->id]);

        $response = $this->actingAs($this->admin)->get('/dashboard');
        $stats    = $response->viewData('stats');

        $this->assertEquals(1, $stats['total_dikonfirmasi']);
        $this->assertEquals(1, $stats['total_pending']);
        $this->assertEquals(1, $stats['total_ditolak']);
        $this->assertEquals(1, $stats['total_meninggal']);
    }

    // =========================================================================
    // FITUR 4 — PETA GIS (hanya lansia dikonfirmasi yang muncul di peta)
    // =========================================================================

    #[Test]
    public function peta_dashboard_hanya_tampilkan_lansia_dikonfirmasi()
    {
        Lansia::factory()->create([
            'status' => 'dikonfirmasi', 'latitude' => '-8.58',
            'longitude' => '116.3', 'user_id' => $this->admin->id,
        ]);
        Lansia::factory()->create(['status' => 'pending', 'user_id' => $this->admin->id]);

        $mapData = $this->actingAs($this->admin)
                        ->get('/dashboard')
                        ->viewData('allLansiaForMap');

        $this->assertEquals(1, $mapData->count());
        $this->assertEquals('dikonfirmasi', $mapData->first()->status);
    }

    // =========================================================================
    // FITUR 5 — DATA LANSIA (INDEX + SEARCH)
    // =========================================================================

    #[Test]
    public function halaman_index_lansia_dapat_diakses()
    {
        $this->actingAs($this->admin)->get('/lansia')->assertStatus(200);
    }

    #[Test]
    public function index_lansia_bisa_search_berdasarkan_nama()
    {
        Lansia::factory()->create(['nama' => 'Budi Santoso', 'user_id' => $this->admin->id]);
        Lansia::factory()->create(['nama' => 'Siti Rahayu',  'user_id' => $this->admin->id]);

        $response = $this->actingAs($this->admin)->get('/lansia?search=Budi');

        $response->assertSee('Budi Santoso');
        $response->assertDontSee('Siti Rahayu');
    }

    // =========================================================================
    // FITUR 6 — TAMBAH DATA LANSIA
    // =========================================================================

    #[Test]
    public function halaman_tambah_lansia_dapat_diakses()
    {
        $this->actingAs($this->admin)->get('/lansia/create')->assertStatus(200);
    }

    #[Test]
    public function admin_tambah_lansia_langsung_dikonfirmasi()
    {
        $this->actingAs($this->admin)->post('/lansia', $this->lansiaData())
             ->assertRedirect('/lansia');

        $this->assertDatabaseHas('lansias', ['nik' => '1234567890123456', 'status' => 'dikonfirmasi']);
    }

    #[Test]
    public function petugas_tambah_lansia_statusnya_pending()
    {
        $data = $this->lansiaData();
        unset($data['status']); // petugas tidak bisa set status manual

        $this->actingAs($this->petugas)->post('/lansia', $data)
             ->assertRedirect('/lansia');

        $this->assertDatabaseHas('lansias', ['nik' => '1234567890123456', 'status' => 'pending']);
    }

    #[Test]
    public function tambah_lansia_gagal_jika_nik_duplikat()
    {
        Lansia::factory()->create(['nik' => '1234567890123456', 'user_id' => $this->admin->id]);

        $this->actingAs($this->admin)->post('/lansia', $this->lansiaData())
             ->assertSessionHasErrors('nik');
    }

    #[Test]
    public function tambah_lansia_gagal_jika_field_wajib_kosong()
    {
        $this->actingAs($this->admin)->post('/lansia', [])
             ->assertSessionHasErrors(['nama', 'nik', 'tanggal_lahir', 'umur', 'alamat']);
    }

    // =========================================================================
    // FITUR 7 — DETAIL DATA LANSIA
    // =========================================================================

    #[Test]
    public function halaman_detail_lansia_menampilkan_data_yang_benar()
    {
        $lansia = Lansia::factory()->create(['nama' => 'Ahmad Fauzi', 'user_id' => $this->admin->id]);

        $this->actingAs($this->admin)->get("/lansia/{$lansia->id}")
             ->assertStatus(200)
             ->assertSee('Ahmad Fauzi');
    }

    // =========================================================================
    // FITUR 8 — EDIT DATA LANSIA
    // =========================================================================

    #[Test]
    public function admin_dapat_update_data_lansia()
    {
        $lansia       = Lansia::factory()->create(['user_id' => $this->admin->id]);
        $data         = $this->lansiaData();
        $data['nama'] = 'Nama Diubah';
        $data['nik']  = $lansia->nik;

        $this->actingAs($this->admin)->put("/lansia/{$lansia->id}", $data)
             ->assertRedirect('/lansia');

        $this->assertDatabaseHas('lansias', ['id' => $lansia->id, 'nama' => 'Nama Diubah']);
    }

    // =========================================================================
    // FITUR 9 — HAPUS DATA LANSIA
    // =========================================================================

    #[Test]
    public function admin_dapat_hapus_data_lansia()
    {
        $lansia = Lansia::factory()->create(['user_id' => $this->admin->id]);

        $this->actingAs($this->admin)->delete("/lansia/{$lansia->id}")
             ->assertRedirect('/lansia');

        $this->assertDatabaseMissing('lansias', ['id' => $lansia->id]);
    }

    #[Test]
    public function petugas_tidak_bisa_hapus_data_lansia()
    {
        $lansia = Lansia::factory()->create(['user_id' => $this->admin->id]);

        $this->actingAs($this->petugas)->delete("/lansia/{$lansia->id}")
             ->assertStatus(403);

        $this->assertDatabaseHas('lansias', ['id' => $lansia->id]);
    }

    // =========================================================================
    // FITUR 10 — TOLAK DATA LANSIA (koordinat dihapus dari peta)
    // =========================================================================

    #[Test]
    public function admin_dapat_tolak_lansia_dan_koordinat_dihapus()
    {
        $lansia = Lansia::factory()->create([
            'status' => 'pending', 'latitude' => '-8.58',
            'longitude' => '116.3', 'user_id' => $this->admin->id,
        ]);

        $this->actingAs($this->admin)->post("/lansia/{$lansia->id}/tolak")
             ->assertRedirect();

        $this->assertDatabaseHas('lansias', [
            'id' => $lansia->id, 'status' => 'ditolak',
            'latitude' => null, 'longitude' => null,
        ]);
    }

    #[Test]
    public function petugas_tidak_bisa_tolak_data_lansia()
    {
        $lansia = Lansia::factory()->create(['status' => 'pending', 'user_id' => $this->admin->id]);

        $this->actingAs($this->petugas)->post("/lansia/{$lansia->id}/tolak")
             ->assertStatus(403);
    }

    // =========================================================================
    // FITUR 13 — MANAJEMEN PENGGUNA
    // =========================================================================

    #[Test]
    public function admin_dapat_akses_halaman_pengguna()
    {
        $this->actingAs($this->admin)->get('/users')->assertStatus(200);
    }

    #[Test]
    public function petugas_tidak_bisa_akses_halaman_pengguna()
    {
        $this->actingAs($this->petugas)->get('/users')->assertStatus(403);
    }

    #[Test]
    public function admin_dapat_tambah_pengguna_baru()
    {
        $this->actingAs($this->admin)->post('/users', [
            'name'                  => 'Pengguna Baru',
            'email'                 => 'baru@gislansia.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect('/users');

        $this->assertDatabaseHas('users', ['email' => 'baru@gislansia.com']);
    }

    #[Test]
    public function tambah_pengguna_gagal_jika_email_duplikat()
    {
        User::factory()->create(['email' => 'duplikat@gislansia.com']);

        $this->actingAs($this->admin)->post('/users', [
            'name'                  => 'User Lain',
            'email'                 => 'duplikat@gislansia.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ])->assertSessionHasErrors('email');
    }

    #[Test]
    public function admin_tidak_bisa_hapus_akun_sendiri()
    {
        $this->actingAs($this->admin)->delete("/users/{$this->admin->id}")
             ->assertRedirect('/users');

        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }

    // =========================================================================
    // FITUR 14 — MANAJEMEN ROLE & PERMISSION
    // =========================================================================

    #[Test]
    public function admin_dapat_akses_halaman_role()
    {
        $this->actingAs($this->admin)->get('/roles')->assertStatus(200);
    }

    #[Test]
    public function petugas_tidak_bisa_akses_halaman_role()
    {
        $this->actingAs($this->petugas)->get('/roles')->assertStatus(403);
    }

    #[Test]
    public function admin_dapat_buat_role_baru()
    {
        $this->actingAs($this->admin)->post('/roles', [
            'name' => 'supervisor', 'permissions' => [],
        ])->assertRedirect('/roles');

        $this->assertDatabaseHas('roles', ['name' => 'supervisor']);
    }

    #[Test]
    public function admin_dapat_hapus_role()
    {
        $role = Role::create(['name' => 'role-test-hapus']);

        $this->actingAs($this->admin)->delete("/roles/{$role->id}")
             ->assertRedirect('/roles');

        $this->assertDatabaseMissing('roles', ['name' => 'role-test-hapus']);
    }

    // =========================================================================
    // HELPER
    // =========================================================================

    private function lansiaData(): array
    {
        return [
            'nama'          => 'Ahmad Fauzi',
            'nik'           => '1234567890123456',
            'tanggal_lahir' => '1950-01-15',
            'umur'          => '74',
            'alamat'        => 'Jl. Mawar No. 1',
            'desa'          => 'Desa Maju',
            'kecamatan'     => 'Praya',
            'kabupaten'     => 'Lombok Tengah',
            'provinsi'      => 'Nusa Tenggara Barat',
            'rt'            => '001',
            'rw'            => '002',
            'status'        => 'dikonfirmasi',
            'note'          => null,
            'latitude'      => null,
            'longitude'     => null,
        ];
    }
}
