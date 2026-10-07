<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\FasilitasRequest;
use App\Models\Dusun;
use App\Models\Fasilitas;
use App\Models\KategoriFasilitas;
use App\Services\MediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FasilitasController extends Controller
{
    public function index(Request $request): View
    {
        $statusFilter = $request->query('status', 'active');
        $dusunFilter = $request->query('dusun_id');
        $kategoriFilter = $request->query('kategori_id');

        $query = Fasilitas::with(['dusun', 'kategoriFasilitas']);

        if ($statusFilter === 'trashed') {
            $query->onlyTrashed();
        } elseif ($statusFilter === 'all') {
            $query->withTrashed();
        }

        if (! empty($dusunFilter)) {
            $query->where('dusun_id', $dusunFilter);
        }

        if (! empty($kategoriFilter)) {
            $query->where('kategori_fasilitas_id', $kategoriFilter);
        }

        $fasilitasList = $query->orderBy('nama')->paginate(15)->withQueryString();
        $dusuns = Dusun::orderBy('nama_dusun')->get();
        $kategoriList = KategoriFasilitas::orderBy('nama_kategori')->get();

        return view('super-admin.fasilitas.index', compact(
            'fasilitasList',
            'dusuns',
            'kategoriList',
            'statusFilter',
            'dusunFilter',
            'kategoriFilter'
        ));
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Fasilitas::class);

        $dusuns = Dusun::orderBy('nama_dusun')->get();
        $kategoriList = KategoriFasilitas::orderBy('nama_kategori')->get();

        return view('super-admin.fasilitas.create', compact('dusuns', 'kategoriList'));
    }

    public function store(FasilitasRequest $request, MediaService $mediaService): RedirectResponse
    {
        $this->authorize('create', Fasilitas::class);

        $validated = $request->validated();
        $fotoPath = null;

        if ($request->hasFile('foto')) {
            $fotoPath = $mediaService->storeImage($request->file('foto'), 'fasilitas');
        }

        Fasilitas::forceCreate([
            'dusun_id' => $validated['dusun_id'],
            'kategori_fasilitas_id' => $validated['kategori_fasilitas_id'],
            'nama' => $validated['nama'],
            'deskripsi' => $validated['deskripsi'],
            'alamat' => $validated['alamat'],
            'nomor_whatsapp' => $validated['nomor_whatsapp'] ?? null,
            'foto_path' => $fotoPath,
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('super-admin.fasilitas.index')
            ->with('success', 'Data fasilitas berhasil disimpan.');
    }

    public function edit(Request $request, int $id): View
    {
        $fasilitas = Fasilitas::withTrashed()->findOrFail($id);
        $this->authorize('view', $fasilitas);

        $dusuns = Dusun::orderBy('nama_dusun')->get();
        $kategoriList = KategoriFasilitas::orderBy('nama_kategori')->get();

        return view('super-admin.fasilitas.edit', compact('fasilitas', 'dusuns', 'kategoriList'));
    }

    public function update(FasilitasRequest $request, int $id, MediaService $mediaService): RedirectResponse
    {
        $fasilitas = Fasilitas::withTrashed()->findOrFail($id);
        $this->authorize('update', $fasilitas);

        $validated = $request->validated();
        $oldFoto = null;

        if ($request->hasFile('foto')) {
            $newFoto = $mediaService->storeImage($request->file('foto'), 'fasilitas');
            $oldFoto = $fasilitas->foto_path;
            $fasilitas->foto_path = $newFoto;
        }

        $fasilitas->dusun_id = $validated['dusun_id'];
        $fasilitas->kategori_fasilitas_id = $validated['kategori_fasilitas_id'];
        $fasilitas->nama = $validated['nama'];
        $fasilitas->deskripsi = $validated['deskripsi'];
        $fasilitas->alamat = $validated['alamat'];
        $fasilitas->nomor_whatsapp = $validated['nomor_whatsapp'] ?? null;
        $fasilitas->latitude = $validated['latitude'];
        $fasilitas->longitude = $validated['longitude'];
        $fasilitas->save();

        if ($oldFoto) {
            $mediaService->deleteImage($oldFoto);
        }

        return redirect()->route('super-admin.fasilitas.index')
            ->with('success', 'Data fasilitas berhasil diperbarui.');
    }

    public function destroy(Request $request, int $id): RedirectResponse
    {
        $fasilitas = Fasilitas::findOrFail($id);
        $this->authorize('delete', $fasilitas);

        $fasilitas->delete(); // Soft delete. Media retained.

        return redirect()->route('super-admin.fasilitas.index')
            ->with('success', 'Data fasilitas berhasil dinonaktifkan (Soft Delete).');
    }

    public function restore(Request $request, int $id): RedirectResponse
    {
        $fasilitas = Fasilitas::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $fasilitas);

        $fasilitas->restore();

        return redirect()->route('super-admin.fasilitas.index', ['status' => 'trashed'])
            ->with('success', 'Data fasilitas berhasil dipulihkan (Restore).');
    }

    public function forceDelete(Request $request, int $id, MediaService $mediaService): RedirectResponse
    {
        $fasilitas = Fasilitas::onlyTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $fasilitas);

        $fotoPath = $fasilitas->foto_path;

        $fasilitas->forceDelete();

        if ($fotoPath) {
            $mediaService->deleteImage($fotoPath);
        }

        return redirect()->route('super-admin.fasilitas.index', ['status' => 'trashed'])
            ->with('success', 'Data fasilitas berhasil dihapus permanen.');
    }
}
