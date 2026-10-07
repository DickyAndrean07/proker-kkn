<?php

namespace Tests\Feature\Admin;

use App\Models\AdminAccount;
use App\Models\Desa;
use App\Models\Dusun;
use App\Models\Fasilitas;
use App\Models\KategoriFasilitas;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class FasilitasTest extends TestCase
{
    use RefreshDatabase;

    private Desa $desaA;

    private Desa $desaB;

    private Dusun $dusunA;

    private AdminAccount $adminA;

    private KategoriFasilitas $kategoriA;

    private KategoriFasilitas $kategoriB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->desaA = Desa::query()->forceCreate([
            'nama_desa' => 'Desa Bendung',
            'deskripsi_singkat' => 'Deskripsi Desa.',
            'alamat_kantor' => 'Jl. Desa No. 1',
            'nomor_kontak' => '081234567890',
            'nama_kepala_desa' => 'Kepala Desa',
            'jam_pelayanan' => '08.00 - 15.00',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $this->desaB = Desa::query()->forceCreate([
            'nama_desa' => 'Desa Lain',
            'deskripsi_singkat' => 'Deskripsi Desa Lain.',
            'alamat_kantor' => 'Jl. Lain No. 2',
            'nomor_kontak' => '081234567899',
            'nama_kepala_desa' => 'Kepala Lain',
            'jam_pelayanan' => '08.00 - 15.00',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $this->dusunA = Dusun::query()->forceCreate([
            'desa_id' => $this->desaA->id,
            'nama_dusun' => 'Dusun Krajan',
            'status_dusun' => 'ACTIVE',
            'deskripsi_singkat' => 'Deskripsi Krajan.',
            'nama_kepala_dusun' => 'Kepala Krajan',
            'jumlah_rt' => 4,
            'jumlah_rw' => 2,
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

        $this->kategoriA = KategoriFasilitas::query()->forceCreate([
            'desa_id' => $this->desaA->id,
            'nama_kategori' => 'Kesehatan',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $this->kategoriB = KategoriFasilitas::query()->forceCreate([
            'desa_id' => $this->desaB->id,
            'nama_kategori' => 'Pendidikan Desa Lain',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);
    }

    /**
     * 35. Create own Fasilitas.
     */
    public function test_admin_can_create_own_fasilitas(): void
    {
        $response = $this->actingAs($this->adminA)->post('/admin-dusun/fasilitas', [
            'kategori_fasilitas_id' => $this->kategoriA->id,
            'nama' => 'Posyandu Mawar Krajan',
            'deskripsi' => 'Pelayanan imunisasi dan kesehatan ibu anak.',
            'alamat' => 'RT 02 / RW 01 Dusun Krajan',
            'latitude' => '-7.323456',
            'longitude' => '110.323456',
            'nomor_whatsapp' => '081234567890',
        ]);

        $response->assertRedirect('/admin-dusun/fasilitas');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('fasilitas', [
            'dusun_id' => $this->dusunA->id,
            'kategori_fasilitas_id' => $this->kategoriA->id,
            'nama' => 'Posyandu Mawar Krajan',
            'latitude' => '-7.323456',
            'longitude' => '110.323456',
        ]);
    }

    /**
     * 36. Existing category may be selected.
     */
    public function test_existing_category_may_be_selected(): void
    {
        $response = $this->actingAs($this->adminA)->get('/admin-dusun/fasilitas/create');

        $response->assertOk();
        $response->assertSee('Kesehatan');
        $response->assertSee('value="'.$this->kategoriA->id.'"', false);
    }

    /**
     * 37. Category from wrong Desa rejected.
     */
    public function test_category_from_wrong_desa_rejected(): void
    {
        $response = $this->actingAs($this->adminA)->post('/admin-dusun/fasilitas', [
            'kategori_fasilitas_id' => $this->kategoriB->id, // Belongs to Desa B
            'nama' => 'Sekolah Ilegal',
            'deskripsi' => 'Deskripsi.',
            'alamat' => 'Alamat.',
            'latitude' => '-7.123456',
            'longitude' => '110.123456',
        ]);

        $response->assertSessionHasErrors(['kategori_fasilitas_id']);
    }

    /**
     * 38. Admin category management absent/denied.
     */
    public function test_admin_category_management_routes_absent(): void
    {
        $response1 = $this->actingAs($this->adminA)->post('/admin-dusun/kategori-fasilitas', [
            'nama_kategori' => 'Kategori Baru',
        ]);
        $response1->assertNotFound();

        $response2 = $this->actingAs($this->adminA)->delete('/admin-dusun/kategori-fasilitas/1');
        $response2->assertNotFound();
    }

    /**
     * 39. Required coordinate pair.
     */
    public function test_required_coordinate_pair_for_fasilitas(): void
    {
        $response = $this->actingAs($this->adminA)->post('/admin-dusun/fasilitas', [
            'kategori_fasilitas_id' => $this->kategoriA->id,
            'nama' => 'Balai Dusun',
            'deskripsi' => 'Deskripsi.',
            'alamat' => 'Alamat.',
            'latitude' => '',
            'longitude' => '',
        ]);

        $response->assertSessionHasErrors(['latitude', 'longitude']);
    }

    /**
     * 40. Invalid coordinate rejected.
     */
    public function test_invalid_coordinate_rejected_for_fasilitas(): void
    {
        $response = $this->actingAs($this->adminA)->post('/admin-dusun/fasilitas', [
            'kategori_fasilitas_id' => $this->kategoriA->id,
            'nama' => 'Balai Dusun',
            'deskripsi' => 'Deskripsi.',
            'alamat' => 'Alamat.',
            'latitude' => '100.000000', // Invalid > 90
            'longitude' => '200.000000', // Invalid > 180
        ]);

        $response->assertSessionHasErrors(['latitude', 'longitude']);
    }

    /**
     * 41. Optional WhatsApp accepted.
     */
    public function test_optional_whatsapp_accepted_for_fasilitas(): void
    {
        $response = $this->actingAs($this->adminA)->post('/admin-dusun/fasilitas', [
            'kategori_fasilitas_id' => $this->kategoriA->id,
            'nama' => 'Klinik Krajan',
            'deskripsi' => 'Deskripsi.',
            'alamat' => 'Alamat.',
            'latitude' => '-7.123456',
            'longitude' => '110.123456',
            'nomor_whatsapp' => '081234567890',
        ]);

        $response->assertRedirect('/admin-dusun/fasilitas');
        $this->assertDatabaseHas('fasilitas', [
            'nama' => 'Klinik Krajan',
            'nomor_whatsapp' => '081234567890',
        ]);
    }

    /**
     * 42. Soft Delete behavior correct.
     */
    public function test_soft_delete_fasilitas_correct(): void
    {
        $fasilitas = Fasilitas::query()->forceCreate([
            'dusun_id' => $this->dusunA->id,
            'kategori_fasilitas_id' => $this->kategoriA->id,
            'nama' => 'Fasilitas Nonaktif',
            'deskripsi' => 'Deskripsi.',
            'alamat' => 'Alamat.',
            'latitude' => '-7.123456',
            'longitude' => '110.123456',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $response = $this->actingAs($this->adminA)->delete('/admin-dusun/fasilitas/'.$fasilitas->id);
        $response->assertRedirect('/admin-dusun/fasilitas');

        $fasilitas->refresh();
        $this->assertNotNull($fasilitas->deleted_at);

        // Not in admin index
        $adminResponse = $this->actingAs($this->adminA)->get('/admin-dusun/fasilitas');
        $adminResponse->assertDontSee('Fasilitas Nonaktif');

        // Not in public detail
        $publicResponse = $this->get('/fasilitas/'.$fasilitas->id);
        $publicResponse->assertNotFound();
    }
}
