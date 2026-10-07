<?php

namespace Tests\Feature\Admin;

use App\Models\AdminAccount;
use App\Models\Desa;
use App\Models\Dusun;
use App\Models\KontakPelayanan;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class KontakPelayananTest extends TestCase
{
    use RefreshDatabase;

    private Desa $desa;

    private Dusun $dusunA;

    private Dusun $dusunB;

    private AdminAccount $adminA;

    private AdminAccount $adminB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->desa = Desa::query()->forceCreate([
            'nama_desa' => 'Desa Bendung',
            'deskripsi_singkat' => 'Deskripsi Desa.',
            'alamat_kantor' => 'Jl. Desa No. 1',
            'nomor_kontak' => '081234567890',
            'nama_kepala_desa' => 'Kepala Desa',
            'jam_pelayanan' => '08.00 - 15.00',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $this->dusunA = Dusun::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'nama_dusun' => 'Dusun Krajan',
            'status_dusun' => 'ACTIVE',
            'deskripsi_singkat' => 'Deskripsi Krajan.',
            'nama_kepala_dusun' => 'Kepala Krajan',
            'jumlah_rt' => 4,
            'jumlah_rw' => 2,
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $this->dusunB = Dusun::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'nama_dusun' => 'Dusun Seberang',
            'status_dusun' => 'ACTIVE',
            'deskripsi_singkat' => 'Deskripsi Seberang.',
            'nama_kepala_dusun' => 'Kepala Seberang',
            'jumlah_rt' => 3,
            'jumlah_rw' => 1,
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $this->adminA = AdminAccount::query()->forceCreate([
            'dusun_id' => $this->dusunA->id,
            'username' => 'admin_krajan',
            'password_hash' => Hash::make('password123'),
            'role' => 'ADMIN_DUSUN',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $this->adminB = AdminAccount::query()->forceCreate([
            'dusun_id' => $this->dusunB->id,
            'username' => 'admin_seberang',
            'password_hash' => Hash::make('password123'),
            'role' => 'ADMIN_DUSUN',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);
    }

    /**
     * 14. Create own Kontak.
     */
    public function test_admin_can_create_own_kontak(): void
    {
        $response = $this->actingAs($this->adminA)->post('/admin-dusun/kontak', [
            'nama' => 'Pak Budi Pelayanan',
            'jabatan' => 'Kasi Pelayanan Surat',
            'nomor_whatsapp' => '081234567891',
            'alamat_pelayanan' => 'Rumah RT 01',
            'latitude' => '-7.123456',
            'longitude' => '110.123456',
        ]);

        $response->assertRedirect('/admin-dusun/kontak');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('kontak_pelayanans', [
            'dusun_id' => $this->dusunA->id,
            'nama' => 'Pak Budi Pelayanan',
            'jabatan' => 'Kasi Pelayanan Surat',
            'nomor_whatsapp' => '081234567891',
            'deleted_at' => null,
        ]);
    }

    /**
     * 15. Update own Kontak.
     */
    public function test_admin_can_update_own_kontak(): void
    {
        $kontak = KontakPelayanan::query()->forceCreate([
            'dusun_id' => $this->dusunA->id,
            'nama' => 'Kontak Lama',
            'jabatan' => 'Jabatan Lama',
            'nomor_whatsapp' => '081111111111',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $response = $this->actingAs($this->adminA)->put('/admin-dusun/kontak/'.$kontak->id, [
            'nama' => 'Kontak Diperbarui',
            'jabatan' => 'Jabatan Baru',
            'nomor_whatsapp' => '082222222222',
        ]);

        $response->assertRedirect('/admin-dusun/kontak');
        $kontak->refresh();
        $this->assertSame('Kontak Diperbarui', $kontak->nama);
        $this->assertSame('Jabatan Baru', $kontak->jabatan);
        $this->assertSame('082222222222', $kontak->nomor_whatsapp);
    }

    /**
     * 16. Foreign Kontak read/update denied.
     */
    public function test_foreign_kontak_read_and_update_denied(): void
    {
        $kontakB = KontakPelayanan::query()->forceCreate([
            'dusun_id' => $this->dusunB->id,
            'nama' => 'Kontak Dusun B',
            'jabatan' => 'Kasi Seberang',
            'nomor_whatsapp' => '089999999999',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        // Admin A tries to edit Dusun B's Kontak -> 404
        $readResponse = $this->actingAs($this->adminA)->get('/admin-dusun/kontak/'.$kontakB->id.'/edit');
        $readResponse->assertNotFound();

        // Admin A tries to update Dusun B's Kontak -> 404
        $updateResponse = $this->actingAs($this->adminA)->put('/admin-dusun/kontak/'.$kontakB->id, [
            'nama' => 'Nama Diretas',
            'jabatan' => 'Jabatan Retas',
            'nomor_whatsapp' => '081234567890',
        ]);
        $updateResponse->assertNotFound();

        $kontakB->refresh();
        $this->assertSame('Kontak Dusun B', $kontakB->nama);
    }

    /**
     * 17. Required name/jabatan/WhatsApp validation.
     */
    public function test_required_name_jabatan_whatsapp_validation(): void
    {
        $response = $this->actingAs($this->adminA)->post('/admin-dusun/kontak', [
            'nama' => '',
            'jabatan' => '',
            'nomor_whatsapp' => '',
        ]);

        $response->assertSessionHasErrors(['nama', 'jabatan', 'nomor_whatsapp']);
    }

    /**
     * 18. Coordinate pair validation (half coordinate rejected).
     */
    public function test_half_coordinate_pair_rejected(): void
    {
        // Lat only
        $response1 = $this->actingAs($this->adminA)->post('/admin-dusun/kontak', [
            'nama' => 'Petugas A',
            'jabatan' => 'Jabatan A',
            'nomor_whatsapp' => '081234567890',
            'latitude' => '-7.123456',
            'longitude' => '',
        ]);
        $response1->assertSessionHasErrors(['longitude']);

        // Lng only
        $response2 = $this->actingAs($this->adminA)->post('/admin-dusun/kontak', [
            'nama' => 'Petugas A',
            'jabatan' => 'Jabatan A',
            'nomor_whatsapp' => '081234567890',
            'latitude' => '',
            'longitude' => '110.123456',
        ]);
        $response2->assertSessionHasErrors(['latitude']);
    }

    /**
     * 19. Coordinate range validation.
     */
    public function test_coordinate_range_validation(): void
    {
        $response = $this->actingAs($this->adminA)->post('/admin-dusun/kontak', [
            'nama' => 'Petugas A',
            'jabatan' => 'Jabatan A',
            'nomor_whatsapp' => '081234567890',
            'latitude' => '95.000000', // Invalid > 90
            'longitude' => '200.000000', // Invalid > 180
        ]);

        $response->assertSessionHasErrors(['latitude', 'longitude']);
    }

    /**
     * 20. Soft Delete own Kontak.
     */
    public function test_admin_can_soft_delete_own_kontak(): void
    {
        $kontak = KontakPelayanan::query()->forceCreate([
            'dusun_id' => $this->dusunA->id,
            'nama' => 'Kontak Akan Dinonaktifkan',
            'jabatan' => 'Staff',
            'nomor_whatsapp' => '081234567890',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $response = $this->actingAs($this->adminA)->delete('/admin-dusun/kontak/'.$kontak->id);

        $response->assertRedirect('/admin-dusun/kontak');
        $response->assertSessionHas('success');

        $kontak->refresh();
        $this->assertNotNull($kontak->deleted_at);
    }

    /**
     * 21. Soft Deleted row leaves normal Admin list.
     */
    public function test_soft_deleted_row_leaves_normal_admin_list(): void
    {
        $active = KontakPelayanan::query()->forceCreate([
            'dusun_id' => $this->dusunA->id,
            'nama' => 'Kontak Masih Aktif',
            'jabatan' => 'Staff Aktif',
            'nomor_whatsapp' => '081111111111',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $deleted = KontakPelayanan::query()->forceCreate([
            'dusun_id' => $this->dusunA->id,
            'nama' => 'Kontak Sudah Nonaktif',
            'jabatan' => 'Staff Nonaktif',
            'nomor_whatsapp' => '082222222222',
            'deleted_at' => CarbonImmutable::now(),
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $response = $this->actingAs($this->adminA)->get('/admin-dusun/kontak');

        $response->assertOk();
        $response->assertSee('Kontak Masih Aktif');
        $response->assertDontSee('Kontak Sudah Nonaktif');
    }

    /**
     * 22. Soft Deleted row leaves Public.
     */
    public function test_soft_deleted_row_leaves_public(): void
    {
        $deleted = KontakPelayanan::query()->forceCreate([
            'dusun_id' => $this->dusunA->id,
            'nama' => 'Kontak Rahasia Nonaktif',
            'jabatan' => 'Staff',
            'nomor_whatsapp' => '082222222222',
            'deleted_at' => CarbonImmutable::now(),
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $response = $this->get('/dusun/'.$this->dusunA->id);

        $response->assertOk();
        $response->assertDontSee('Kontak Rahasia Nonaktif');
    }

    /**
     * 23. Admin restore route/action absent.
     */
    public function test_admin_restore_route_absent(): void
    {
        $response = $this->actingAs($this->adminA)->post('/admin-dusun/kontak/1/restore');
        $response->assertNotFound();
    }

    /**
     * 24. Admin hard-delete route/action absent.
     */
    public function test_admin_hard_delete_route_absent(): void
    {
        $response = $this->actingAs($this->adminA)->delete('/admin-dusun/kontak/1/force');
        $response->assertNotFound();
    }
}
