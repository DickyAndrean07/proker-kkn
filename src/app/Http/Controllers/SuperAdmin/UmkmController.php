<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\UmkmRequest;
use App\Models\Dusun;
use App\Models\ProdukUmkm;
use App\Models\Umkm;
use App\Services\MediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UmkmController extends Controller
{
    public function index(Request $request): View
    {
        $statusFilter = $request->query('status', 'active');
        $dusunFilter = $request->query('dusun_id');

        $query = Umkm::with(['dusun', 'produkUmkms']);

        if ($statusFilter === 'trashed') {
            $query->onlyTrashed();
        } elseif ($statusFilter === 'all') {
            $query->withTrashed();
        }

        if (! empty($dusunFilter)) {
            $query->where('dusun_id', $dusunFilter);
        }

        $umkmList = $query->orderBy('nama_umkm')->paginate(15)->withQueryString();
        $dusuns = Dusun::orderBy('nama_dusun')->get();

        return view('super-admin.umkm.index', compact('umkmList', 'dusuns', 'statusFilter', 'dusunFilter'));
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Umkm::class);
        $dusuns = Dusun::orderBy('nama_dusun')->get();

        return view('super-admin.umkm.create', compact('dusuns'));
    }

    public function store(UmkmRequest $request, MediaService $mediaService): RedirectResponse
    {
        $this->authorize('create', Umkm::class);

        $validated = $request->validated();

        DB::transaction(function () use ($validated, $request, $mediaService): void {
            $fotoPath = null;
            if ($request->hasFile('foto_utama')) {
                $fotoPath = $mediaService->storeImage($request->file('foto_utama'), 'umkm');
            }

            $umkm = Umkm::forceCreate([
                'dusun_id' => $validated['dusun_id'],
                'nama_umkm' => $validated['nama_umkm'],
                'nama_pemilik' => $validated['nama_pemilik'],
                'jenis_usaha' => $validated['jenis_usaha'],
                'deskripsi' => $validated['deskripsi'],
                'alamat' => $validated['alamat'],
                'nomor_whatsapp' => $validated['nomor_whatsapp'],
                'jam_operasional' => $validated['jam_operasional'],
                'foto_utama_path' => $fotoPath,
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if (! empty($validated['produk'])) {
                foreach ($validated['produk'] as $p) {
                    $namaProduk = trim($p['nama_produk'] ?? '');
                    if ($namaProduk !== '') {
                        ProdukUmkm::forceCreate([
                            'umkm_id' => $umkm->id,
                            'nama_produk' => $namaProduk,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        });

        return redirect()->route('super-admin.umkm.index')
            ->with('success', 'Data UMKM berhasil disimpan.');
    }

    public function edit(Request $request, int $id): View
    {
        $umkm = Umkm::withTrashed()->with('produkUmkms')->findOrFail($id);
        $this->authorize('view', $umkm);

        $dusuns = Dusun::orderBy('nama_dusun')->get();

        return view('super-admin.umkm.edit', compact('umkm', 'dusuns'));
    }

    public function update(UmkmRequest $request, int $id, MediaService $mediaService): RedirectResponse
    {
        $umkm = Umkm::withTrashed()->with('produkUmkms')->findOrFail($id);
        $this->authorize('update', $umkm);

        $validated = $request->validated();
        $oldFoto = null;

        DB::transaction(function () use ($umkm, $validated, $request, $mediaService, &$oldFoto): void {
            if ($request->hasFile('foto_utama')) {
                $newFoto = $mediaService->storeImage($request->file('foto_utama'), 'umkm');
                $oldFoto = $umkm->foto_utama_path;
                $umkm->foto_utama_path = $newFoto;
            }

            $umkm->dusun_id = $validated['dusun_id'];
            $umkm->nama_umkm = $validated['nama_umkm'];
            $umkm->nama_pemilik = $validated['nama_pemilik'];
            $umkm->jenis_usaha = $validated['jenis_usaha'];
            $umkm->deskripsi = $validated['deskripsi'];
            $umkm->alamat = $validated['alamat'];
            $umkm->nomor_whatsapp = $validated['nomor_whatsapp'];
            $umkm->jam_operasional = $validated['jam_operasional'];
            $umkm->latitude = $validated['latitude'] ?? null;
            $umkm->longitude = $validated['longitude'] ?? null;
            $umkm->save();

            // Product rows reconciliation
            $submittedProducts = $validated['produk'] ?? [];
            $submittedIds = [];

            foreach ($submittedProducts as $p) {
                $namaProduk = trim($p['nama_produk'] ?? '');
                if ($namaProduk === '') {
                    continue;
                }

                if (! empty($p['id'])) {
                    $existing = ProdukUmkm::where('umkm_id', $umkm->id)->find($p['id']);
                    if ($existing) {
                        $existing->nama_produk = $namaProduk;
                        $existing->save();
                        $submittedIds[] = $existing->id;
                    }
                } else {
                    $created = ProdukUmkm::forceCreate([
                        'umkm_id' => $umkm->id,
                        'nama_produk' => $namaProduk,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $submittedIds[] = $created->id;
                }
            }

            ProdukUmkm::where('umkm_id', $umkm->id)
                ->whereNotIn('id', $submittedIds)
                ->delete();
        });

        if ($oldFoto) {
            $mediaService->deleteImage($oldFoto);
        }

        return redirect()->route('super-admin.umkm.index')
            ->with('success', 'Data UMKM berhasil diperbarui.');
    }

    public function destroy(Request $request, int $id): RedirectResponse
    {
        $umkm = Umkm::findOrFail($id);
        $this->authorize('delete', $umkm);

        $umkm->delete(); // Soft delete. Media retained.

        return redirect()->route('super-admin.umkm.index')
            ->with('success', 'Data UMKM berhasil dinonaktifkan (Soft Delete).');
    }

    public function restore(Request $request, int $id): RedirectResponse
    {
        $umkm = Umkm::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $umkm);

        $umkm->restore();

        return redirect()->route('super-admin.umkm.index', ['status' => 'trashed'])
            ->with('success', 'Data UMKM berhasil dipulihkan (Restore).');
    }

    public function forceDelete(Request $request, int $id, MediaService $mediaService): RedirectResponse
    {
        $umkm = Umkm::onlyTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $umkm);

        $fotoPath = $umkm->foto_utama_path;

        $umkm->forceDelete(); // DB CASCADE removes produk_umkms

        if ($fotoPath) {
            $mediaService->deleteImage($fotoPath);
        }

        return redirect()->route('super-admin.umkm.index', ['status' => 'trashed'])
            ->with('success', 'Data UMKM berhasil dihapus permanen.');
    }
}
