<?php

namespace Tests\Feature\SuperAdmin;

use App\Models\AdminAccount;
use App\Models\Desa;
use App\Models\Dusun;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AdminDusunAccountTest extends TestCase
{
    use RefreshDatabase;

    private AdminAccount $superAdmin;

    private Desa $desa;

    private Dusun $dusun1;

    private Dusun $dusun2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->desa = Desa::forceCreate([
            'nama_desa' => 'Desa Bendung',
            'deskripsi_singkat' => 'Profil Desa Bendung',
            'alamat_kantor' => 'Jl. Balai Desa No. 1',
            'nomor_kontak' => '08123456789',
            'nama_kepala_desa' => 'Lurah Sutrisno',
            'jam_pelayanan' => 'Senin - Jumat 08.00 - 15.00',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->dusun1 = Dusun::forceCreate([
            'desa_id' => $this->desa->id,
            'nama_dusun' => 'Dusun Krajan',
            'deskripsi_singkat' => 'Profil Dusun Krajan',
            'nama_kepala_dusun' => 'Kadus Krajan',
            'jumlah_rt' => 4,
            'jumlah_rw' => 2,
            'status_dusun' => 'ACTIVE',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->dusun2 = Dusun::forceCreate([
            'desa_id' => $this->desa->id,
            'nama_dusun' => 'Dusun Banyuripan',
            'deskripsi_singkat' => 'Profil Dusun Banyuripan',
            'nama_kepala_dusun' => 'Kadus Banyuripan',
            'jumlah_rt' => 3,
            'jumlah_rw' => 1,
            'status_dusun' => 'ACTIVE',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->superAdmin = AdminAccount::forceCreate([
            'dusun_id' => null,
            'username' => 'superadmin_accounts',
            'password_hash' => bcrypt('password123'),
            'role' => 'SUPER_ADMIN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_90_super_admin_can_list_admin_dusun_accounts(): void
    {
        AdminAccount::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'username' => 'admin_krajan_one',
            'password_hash' => bcrypt('password123'),
            'role' => 'ADMIN_DUSUN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->get(route('super-admin.admin-dusun.index'));

        $response->assertStatus(200);
        $response->assertSee('admin_krajan_one');
        $response->assertSee('Dusun Krajan');
    }

    public function test_91_super_admin_can_view_create_admin_dusun_form(): void
    {
        $response = $this->actingAs($this->superAdmin)->get(route('super-admin.admin-dusun.create'));

        $response->assertStatus(200);
        $response->assertSee('Tambah Akun Admin Dusun');
    }

    public function test_92_super_admin_can_create_admin_dusun_account_with_hashed_password(): void
    {
        $payload = [
            'username' => 'admin_banyuripan_new',
            'password' => 'secret123',
            'dusun_id' => $this->dusun2->id,
        ];

        $response = $this->actingAs($this->superAdmin)->post(route('super-admin.admin-dusun.store'), $payload);

        $response->assertRedirect(route('super-admin.admin-dusun.index'));
        $this->assertDatabaseHas('admin_accounts', [
            'username' => 'admin_banyuripan_new',
            'dusun_id' => $this->dusun2->id,
            'role' => 'ADMIN_DUSUN',
        ]);

        $account = AdminAccount::where('username', 'admin_banyuripan_new')->firstOrFail();
        $this->assertTrue(Hash::check('secret123', $account->password_hash));
    }

    public function test_93_role_is_server_forced_to_admin_dusun(): void
    {
        $payload = [
            'username' => 'admin_role_check',
            'password' => 'secret123',
            'dusun_id' => $this->dusun1->id,
            'role' => 'SUPER_ADMIN', // Attempt to inject SUPER_ADMIN
        ];

        $this->actingAs($this->superAdmin)->post(route('super-admin.admin-dusun.store'), $payload);

        $account = AdminAccount::where('username', 'admin_role_check')->firstOrFail();
        $this->assertEquals('ADMIN_DUSUN', $account->role);
    }

    public function test_94_username_uniqueness_across_active_accounts(): void
    {
        AdminAccount::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'username' => 'admin_existing',
            'password_hash' => bcrypt('password123'),
            'role' => 'ADMIN_DUSUN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->post(route('super-admin.admin-dusun.store'), [
            'username' => 'admin_existing',
            'password' => 'secret123',
            'dusun_id' => $this->dusun2->id,
        ]);

        $response->assertSessionHasErrors(['username']);
    }

    public function test_95_username_uniqueness_across_removed_accounts_blocks_recycling(): void
    {
        AdminAccount::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'username' => 'admin_removed_old',
            'password_hash' => bcrypt('password123'),
            'role' => 'ADMIN_DUSUN',
            'created_at' => now(),
            'updated_at' => now(),
            'removed_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->post(route('super-admin.admin-dusun.store'), [
            'username' => 'admin_removed_old',
            'password' => 'secret123',
            'dusun_id' => $this->dusun2->id,
        ]);

        $response->assertSessionHasErrors(['username']);
    }

    public function test_96_password_minimum_length_validation(): void
    {
        $response = $this->actingAs($this->superAdmin)->post(route('super-admin.admin-dusun.store'), [
            'username' => 'admin_short_pw',
            'password' => '12345',
            'dusun_id' => $this->dusun1->id,
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_97_invalid_username_characters_rejected(): void
    {
        $response = $this->actingAs($this->superAdmin)->post(route('super-admin.admin-dusun.store'), [
            'username' => 'admin with spaces!',
            'password' => 'secret123',
            'dusun_id' => $this->dusun1->id,
        ]);

        $response->assertSessionHasErrors(['username']);
    }

    public function test_98_super_admin_can_reassign_dusun_for_active_admin_account(): void
    {
        $account = AdminAccount::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'username' => 'admin_reassign_target',
            'password_hash' => bcrypt('password123'),
            'role' => 'ADMIN_DUSUN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->put(route('super-admin.admin-dusun.update', $account->id), [
            'dusun_id' => $this->dusun2->id,
        ]);

        $response->assertRedirect(route('super-admin.admin-dusun.index'));
        $account->refresh();
        $this->assertEquals($this->dusun2->id, $account->dusun_id);
    }

    public function test_99_super_admin_can_view_reset_password_form(): void
    {
        $account = AdminAccount::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'username' => 'admin_reset_form',
            'password_hash' => bcrypt('password123'),
            'role' => 'ADMIN_DUSUN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->get(route('super-admin.admin-dusun.reset-password', $account->id));

        $response->assertStatus(200);
        $response->assertSee('Reset Kata Sandi Akun');
        $response->assertSee('admin_reset_form');
    }

    public function test_100_super_admin_can_reset_password_for_active_admin_account(): void
    {
        $account = AdminAccount::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'username' => 'admin_reset_pw',
            'password_hash' => bcrypt('oldpassword'),
            'role' => 'ADMIN_DUSUN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->put(route('super-admin.admin-dusun.reset-password.submit', $account->id), [
            'password' => 'newsecretpass123',
            'password_confirmation' => 'newsecretpass123',
        ]);

        $response->assertRedirect(route('super-admin.admin-dusun.index'));
        $account->refresh();
        $this->assertTrue(Hash::check('newsecretpass123', $account->password_hash));
    }

    public function test_101_super_admin_can_logically_remove_admin_dusun_account(): void
    {
        $account = AdminAccount::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'username' => 'admin_to_remove',
            'password_hash' => bcrypt('password123'),
            'role' => 'ADMIN_DUSUN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->post(route('super-admin.admin-dusun.remove', $account->id));

        $response->assertRedirect(route('super-admin.admin-dusun.index'));
        $account->refresh();
        $this->assertNotNull($account->removed_at);
        $this->assertTrue($account->isRemoved());
    }

    public function test_102_logically_removed_account_cannot_login(): void
    {
        AdminAccount::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'username' => 'admin_blocked_login',
            'password_hash' => bcrypt('password123'),
            'role' => 'ADMIN_DUSUN',
            'created_at' => now(),
            'updated_at' => now(),
            'removed_at' => now(),
        ]);

        $response = $this->post(route('admin.login.submit'), [
            'username' => 'admin_blocked_login',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['username']);
        $this->assertGuest();
    }

    public function test_103_logically_removed_account_is_listed_with_inactive_badge(): void
    {
        AdminAccount::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'username' => 'admin_listed_removed',
            'password_hash' => bcrypt('password123'),
            'role' => 'ADMIN_DUSUN',
            'created_at' => now(),
            'updated_at' => now(),
            'removed_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->get(route('super-admin.admin-dusun.index'));

        $response->assertStatus(200);
        $response->assertSee('admin_listed_removed');
        $response->assertSee('Akses Dinonaktifkan (Arsip Audit)');
    }

    public function test_104_logically_removed_account_cannot_have_dusun_reassigned(): void
    {
        $account = AdminAccount::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'username' => 'admin_no_reassign',
            'password_hash' => bcrypt('password123'),
            'role' => 'ADMIN_DUSUN',
            'created_at' => now(),
            'updated_at' => now(),
            'removed_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->put(route('super-admin.admin-dusun.update', $account->id), [
            'dusun_id' => $this->dusun2->id,
        ]);

        $response->assertStatus(403);
    }

    public function test_105_logically_removed_account_cannot_have_password_reset(): void
    {
        $account = AdminAccount::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'username' => 'admin_no_reset',
            'password_hash' => bcrypt('password123'),
            'role' => 'ADMIN_DUSUN',
            'created_at' => now(),
            'updated_at' => now(),
            'removed_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->put(route('super-admin.admin-dusun.reset-password.submit', $account->id), [
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertStatus(403);
    }

    public function test_106_logically_removed_account_cannot_be_removed_again(): void
    {
        $account = AdminAccount::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'username' => 'admin_already_removed',
            'password_hash' => bcrypt('password123'),
            'role' => 'ADMIN_DUSUN',
            'created_at' => now(),
            'updated_at' => now(),
            'removed_at' => now(),
        ]);

        $response = $this->actingAs($this->superAdmin)->post(route('super-admin.admin-dusun.remove', $account->id));
        $response->assertStatus(403);
    }

    public function test_107_no_route_exists_for_creating_super_admin(): void
    {
        $this->assertFalse(Route::has('super-admin.create-super-admin'));
    }

    public function test_108_no_route_exists_for_hard_or_soft_deleting_admin_account(): void
    {
        $this->assertFalse(Route::has('super-admin.admin-dusun.destroy'));
        $this->assertFalse(Route::has('super-admin.admin-dusun.force-delete'));
    }

    public function test_109_no_route_exists_for_restoring_admin_account(): void
    {
        $this->assertFalse(Route::has('super-admin.admin-dusun.restore'));
    }

    public function test_110_admin_dusun_is_blocked_from_super_admin_account_management(): void
    {
        $adminDusun = AdminAccount::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'username' => 'admindusun_acc_sec',
            'password_hash' => bcrypt('password123'),
            'role' => 'ADMIN_DUSUN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($adminDusun)->get(route('super-admin.admin-dusun.index'));
        $response->assertStatus(403);
    }

    public function test_111_admin_dusun_cannot_reset_another_admin_dusun_password(): void
    {
        $adminDusun = AdminAccount::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'username' => 'admindusun_attacker',
            'password_hash' => bcrypt('password123'),
            'role' => 'ADMIN_DUSUN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $victim = AdminAccount::forceCreate([
            'dusun_id' => $this->dusun2->id,
            'username' => 'admindusun_victim',
            'password_hash' => bcrypt('password123'),
            'role' => 'ADMIN_DUSUN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($adminDusun)->put(route('super-admin.admin-dusun.reset-password.submit', $victim->id), [
            'password' => 'hacked123',
            'password_confirmation' => 'hacked123',
        ]);
        $response->assertStatus(403);
    }

    public function test_112_admin_dusun_cannot_remove_accounts(): void
    {
        $adminDusun = AdminAccount::forceCreate([
            'dusun_id' => $this->dusun1->id,
            'username' => 'admindusun_attacker2',
            'password_hash' => bcrypt('password123'),
            'role' => 'ADMIN_DUSUN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $victim = AdminAccount::forceCreate([
            'dusun_id' => $this->dusun2->id,
            'username' => 'admindusun_victim2',
            'password_hash' => bcrypt('password123'),
            'role' => 'ADMIN_DUSUN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($adminDusun)->post(route('super-admin.admin-dusun.remove', $victim->id));
        $response->assertStatus(403);
    }
}
