<?php

namespace Tests\Feature\Auth;

use App\Models\AdminAccount;
use App\Models\Desa;
use App\Models\Dusun;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    private Desa $desa;

    private Dusun $activeDusun;

    private Dusun $inactiveDusun;

    private AdminAccount $adminDusun;

    private AdminAccount $inactiveDusunAdmin;

    private AdminAccount $superAdmin;

    private AdminAccount $removedAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->desa = Desa::query()->forceCreate([
            'nama_desa' => 'Desa Bendung',
            'deskripsi_singkat' => 'Data sintetis untuk pengujian autentikasi.',
            'alamat_kantor' => 'Kantor Desa Bendung',
            'nomor_kontak' => '081200000001',
            'nama_kepala_desa' => 'Kepala Desa Bendung',
            'jam_pelayanan' => '08.00 - 15.00 WIB',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $this->activeDusun = Dusun::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'nama_dusun' => 'Dusun Bendung Satu',
            'status_dusun' => 'ACTIVE',
            'deskripsi_singkat' => 'Dusun satu aktif.',
            'nama_kepala_dusun' => 'Kadus 1',
            'jumlah_rt' => 2,
            'jumlah_rw' => 1,
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $this->inactiveDusun = Dusun::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'nama_dusun' => 'Dusun Bendung Dua',
            'status_dusun' => 'INACTIVE',
            'deskripsi_singkat' => 'Dusun dua nonaktif.',
            'nama_kepala_dusun' => 'Kadus 2',
            'jumlah_rt' => 2,
            'jumlah_rw' => 1,
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $this->adminDusun = AdminAccount::query()->forceCreate([
            'dusun_id' => $this->activeDusun->id,
            'username' => 'admindusun1',
            'password_hash' => Hash::make('Secret123!'),
            'role' => 'ADMIN_DUSUN',
            'removed_at' => null,
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $this->inactiveDusunAdmin = AdminAccount::query()->forceCreate([
            'dusun_id' => $this->inactiveDusun->id,
            'username' => 'admininactivedusun',
            'password_hash' => Hash::make('Secret123!'),
            'role' => 'ADMIN_DUSUN',
            'removed_at' => null,
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $this->superAdmin = AdminAccount::query()->forceCreate([
            'dusun_id' => null,
            'username' => 'superadmin',
            'password_hash' => Hash::make('SuperSecret123!'),
            'role' => 'SUPER_ADMIN',
            'removed_at' => null,
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $this->removedAdmin = AdminAccount::query()->forceCreate([
            'dusun_id' => $this->activeDusun->id,
            'username' => 'removedadmin',
            'password_hash' => Hash::make('Secret123!'),
            'role' => 'ADMIN_DUSUN',
            'removed_at' => CarbonImmutable::now(),
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);
    }

    public function test_login_page_renders_successfully_with_username_and_password_inputs(): void
    {
        $response = $this->get(route('admin.login'));

        $response->assertStatus(200);
        $response->assertSee('Login Admin');
        $response->assertSee('name="username"', false);
        $response->assertSee('name="password"', false);
        $response->assertSee('name="remember" value="1"', false);
        $response->assertSee('Ingat saya');
        $response->assertDontSee('Remember Me');
        $response->assertDontSee('Daftar');
    }

    public function test_valid_admin_dusun_login_succeeds_and_redirects_to_dusun_dashboard(): void
    {
        $response = $this->post(route('admin.login.submit'), [
            'username' => 'admindusun1',
            'password' => 'Secret123!',
        ]);

        $response->assertRedirect(route('admin-dusun.dashboard'));
        $this->assertAuthenticatedAs($this->adminDusun);
    }

    public function test_valid_admin_dusun_login_with_remember_me_stores_token_and_sets_cookie(): void
    {
        $response = $this->post(route('admin.login.submit'), [
            'username' => 'admindusun1',
            'password' => 'Secret123!',
            'remember' => '1',
        ]);

        $response->assertRedirect(route('admin-dusun.dashboard'));
        $this->assertAuthenticatedAs($this->adminDusun);
        $response->assertCookie(Auth::guard('web')->getRecallerName());

        $this->adminDusun->refresh();
        $this->assertNotNull($this->adminDusun->remember_token);
        $this->assertNotEmpty($this->adminDusun->remember_token);
    }

    public function test_valid_super_admin_login_succeeds_and_redirects_to_super_admin_dashboard(): void
    {
        $response = $this->post(route('admin.login.submit'), [
            'username' => 'superadmin',
            'password' => 'SuperSecret123!',
        ]);

        $response->assertRedirect(route('super-admin.dashboard'));
        $this->assertAuthenticatedAs($this->superAdmin);
    }

    public function test_valid_super_admin_login_with_remember_me_stores_token_and_sets_cookie(): void
    {
        $response = $this->post(route('admin.login.submit'), [
            'username' => 'superadmin',
            'password' => 'SuperSecret123!',
            'remember' => '1',
        ]);

        $response->assertRedirect(route('super-admin.dashboard'));
        $this->assertAuthenticatedAs($this->superAdmin);
        $response->assertCookie(Auth::guard('web')->getRecallerName());

        $this->superAdmin->refresh();
        $this->assertNotNull($this->superAdmin->remember_token);
        $this->assertNotEmpty($this->superAdmin->remember_token);
    }

    public function test_admin_dusun_of_inactive_dusun_can_login_successfully(): void
    {
        $response = $this->post(route('admin.login.submit'), [
            'username' => 'admininactivedusun',
            'password' => 'Secret123!',
        ]);

        $response->assertRedirect(route('admin-dusun.dashboard'));
        $this->assertAuthenticatedAs($this->inactiveDusunAdmin);
    }

    public function test_wrong_password_fails_with_generic_error(): void
    {
        $response = $this->from(route('admin.login'))->post(route('admin.login.submit'), [
            'username' => 'admindusun1',
            'password' => 'WrongPassword!',
        ]);

        $response->assertRedirect(route('admin.login'));
        $response->assertSessionHasErrors(['username' => 'Kredensial yang diberikan tidak cocok dengan data kami.']);
        $this->assertGuest();
    }

    public function test_unknown_username_fails_with_same_generic_error(): void
    {
        $response = $this->from(route('admin.login'))->post(route('admin.login.submit'), [
            'username' => 'nonexistentuser',
            'password' => 'AnyPassword123!',
        ]);

        $response->assertRedirect(route('admin.login'));
        $response->assertSessionHasErrors(['username' => 'Kredensial yang diberikan tidak cocok dengan data kami.']);
        $this->assertGuest();
    }

    public function test_logically_removed_account_fails_login_with_generic_error(): void
    {
        $response = $this->from(route('admin.login'))->post(route('admin.login.submit'), [
            'username' => 'removedadmin',
            'password' => 'Secret123!',
        ]);

        $response->assertRedirect(route('admin.login'));
        $response->assertSessionHasErrors(['username' => 'Kredensial yang diberikan tidak cocok dengan data kami.']);
        $this->assertGuest();
    }

    public function test_already_authenticated_admin_visiting_login_redirects_to_own_dashboard(): void
    {
        $response = $this->actingAs($this->adminDusun)->get(route('admin.login'));
        $response->assertRedirect(route('admin-dusun.dashboard'));

        $responseSuper = $this->actingAs($this->superAdmin)->get(route('admin.login'));
        $responseSuper->assertRedirect(route('super-admin.dashboard'));
    }

    public function test_logout_terminates_session_and_redirects_to_login(): void
    {
        $this->actingAs($this->adminDusun);

        $response = $this->post(route('admin.logout'));

        $response->assertRedirect(route('admin.login'));
        $this->assertGuest();
    }

    public function test_unauthenticated_request_to_protected_routes_redirects_to_canonical_login(): void
    {
        $response1 = $this->get(route('admin-dusun.dashboard'));
        $response1->assertRedirect(route('admin.login'));

        $response2 = $this->get(route('super-admin.dashboard'));
        $response2->assertRedirect(route('admin.login'));
    }

    public function test_role_mismatch_returns_http_403(): void
    {
        // Admin Dusun trying to access Super Admin dashboard
        $response1 = $this->actingAs($this->adminDusun)->get(route('super-admin.dashboard'));
        $response1->assertStatus(403);

        // Super Admin trying to access Admin Dusun dashboard
        $response2 = $this->actingAs($this->superAdmin)->get(route('admin-dusun.dashboard'));
        $response2->assertStatus(403);
    }

    public function test_active_session_of_newly_removed_account_is_denied_on_subsequent_request(): void
    {
        $this->actingAs($this->adminDusun);

        // Now logically remove the account in persistence
        $this->adminDusun->removed_at = CarbonImmutable::now();
        $this->adminDusun->save();

        $response = $this->get(route('admin-dusun.dashboard'));

        $response->assertRedirect(route('admin.login'));
        $this->assertGuest();
    }

    public function test_login_rate_limiting_triggers_after_five_failed_attempts(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post(route('admin.login.submit'), [
                'username' => 'admindusun1',
                'password' => 'WrongPassword!',
            ]);
        }

        // 6th attempt should be throttled
        $response = $this->post(route('admin.login.submit'), [
            'username' => 'admindusun1',
            'password' => 'WrongPassword!',
        ]);

        $response->assertSessionHasErrors('username');
        $error = session('errors')->first('username');
        $this->assertStringContainsString('Terlalu banyak percobaan login', $error);
    }

    public function test_logout_rotates_remember_token_and_invalidates_session(): void
    {
        $this->post(route('admin.login.submit'), [
            'username' => 'admindusun1',
            'password' => 'Secret123!',
            'remember' => '1',
        ]);

        $this->adminDusun->refresh();
        $initialToken = $this->adminDusun->remember_token;
        $this->assertNotNull($initialToken);

        $response = $this->post(route('admin.logout'));

        $response->assertRedirect(route('admin.login'));
        $this->assertGuest();

        $this->adminDusun->refresh();
        $this->assertNotEquals($initialToken, $this->adminDusun->remember_token);
    }

    public function test_remember_token_is_hidden_from_model_serialization(): void
    {
        $this->adminDusun->remember_token = 'sample-secret-remember-token';
        $array = $this->adminDusun->toArray();

        $this->assertArrayNotHasKey('remember_token', $array);
        $this->assertArrayNotHasKey('password_hash', $array);
    }

    public function test_unsupported_auth_routes_do_not_exist(): void
    {
        $this->assertNull(Route::getRoutes()->getByName('register'));
        $this->assertNull(Route::getRoutes()->getByName('password.request'));
        $this->assertNull(Route::getRoutes()->getByName('password.reset'));
        $this->assertNull(Route::getRoutes()->getByName('password.email'));
        $this->assertNull(Route::getRoutes()->getByName('verification.notice'));
    }
}
