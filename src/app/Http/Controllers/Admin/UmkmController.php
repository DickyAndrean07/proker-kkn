<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UmkmRequest;
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
        $dusun = $request->user()->dusun;
        $umkmList = Umkm::where('dusun_id', $dusun->id)
            ->with('produkUmkms')
            ->orderBy('nama_umkm')
            ->paginate(15);

        return view('admin.umkm.index', compact('umkmList', 'dusun'));
    }

    public function create(Request $request): View
    {
        $dusun = $request->user()->dusun;
        $this->authorize('create', [Umkm::class, $dusun->id]);

        return view('admin.umkm.create', compact('dusun'));
    }

    public function store(UmkmRequest $request, MediaService $mediaService): RedirectResponse
    {
        $dusun = $request->user()->dusun;
        $this->authorize('create', [Umkm::class, $dusun->id]);

        $validated = $request->validated();

        DB::transaction(function () use ($dusun, $validated, $request, $mediaService): void {
            $fotoPath = null;
            if ($request->hasFile('foto_utama')) {
                $fotoPath = $mediaService->storeImage($request->file('foto_utama'), 'umkm');
            }

            $umkm = Umkm::forceCreate([
                'dusun_id' => $dusun->id,
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

        return redirect()->route('admin-dusun.umkm.index')
            ->with('success', 'Data UMKM berhasil disimpan.');
    }

    public function edit(Request $request, int $id): View
    {
        $dusun = $request->user()->dusun;
        $umkm = Umkm::where('dusun_id', $dusun->id)
            ->with('produkUmkms')
            ->findOrFail($id);
        $this->authorize('view', $umkm);

        return view('admin.umkm.edit', compact('umkm', 'dusun'));
    }

    public function update(UmkmRequest $request, int $id, MediaService $mediaService): RedirectResponse
    {
        $dusun = $request->user()->dusun;
        $umkm = Umkm::where('dusun_id', $dusun->id)
            ->with('produkUmkms')
            ->findOrFail($id);
        $this->authorize('update', $umkm);

        $validated = $request->validated();
        $oldFoto = null;

        DB::transaction(function () use ($umkm, $validated, $request, $mediaService, &$oldFoto): void {
            if ($request->hasFile('foto_utama')) {
                $newFoto = $mediaService->storeImage($request->file('foto_utama'), 'umkm');
                $oldFoto = $umkm->foto_utama_path;
                $umkm->foto_utama_path = $newFoto;
            }

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

            // Reconcile Produk UMKM
            $submittedProducts = $validated['produk'] ?? [];
            $keptProductIds = [];

            foreach ($submittedProducts as $p) {
                $namaProduk = trim($p['nama_produk'] ?? '');
                if ($namaProduk === '') {
                    continue;
                }

                $productId = isset($p['id']) ? (int) $p['id'] : null;

                if ($productId) {
                    $existingProduct = ProdukUmkm::where('umkm_id', $umkm->id)->find($productId);
                    if ($existingProduct) {
                        $existingProduct->nama_produk = $namaProduk;
                        $existingProduct->save();
                        $keptProductIds[] = $existingProduct->id;

                        continue;
                    }
                }

                $newProduct = ProdukUmkm::forceCreate([
                    'umkm_id' => $umkm->id,
                    'nama_produk' => $namaProduk,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $keptProductIds[] = $newProduct->id;
            }

            // Remove non-kept products
            ProdukUmkm::where('umkm_id', $umkm->id)
                ->whereNotIn('id', $keptProductIds)
                ->delete();
        });

        if ($oldFoto) {
            $mediaService->deleteImage($oldFoto);
        }

        return redirect()->route('admin-dusun.umkm.index')
            ->with('success', 'Data UMKM berhasil disimpan.');
    }

    public function destroy(Request $request, int $id): RedirectResponse
    {
        $dusun = $request->user()->dusun;
        $umkm = Umkm::where('dusun_id', $dusun->id)->findOrFail($id);
        $this->authorize('delete', $umkm);

        $umkm->delete(); // Soft delete. Media retained.

        return redirect()->route('admin-dusun.umkm.index')
            ->with('success', 'Data berhasil dinonaktifkan.');
    }
}
