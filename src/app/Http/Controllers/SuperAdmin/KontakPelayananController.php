<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\KontakPelayananRequest;
use App\Models\Dusun;
use App\Models\KontakPelayanan;
use App\Services\MediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KontakPelayananController extends Controller
{
    public function index(Request $request): View
    {
        $statusFilter = $request->query('status', 'active');
        $dusunFilter = $request->query('dusun_id');

        $query = KontakPelayanan::with('dusun');

        if ($statusFilter === 'trashed') {
            $query->onlyTrashed();
        } elseif ($statusFilter === 'all') {
            $query->withTrashed();
        }

        if (! empty($dusunFilter)) {
            $query->where('dusun_id', $dusunFilter);
        }

        $kontakList = $query->orderBy('nama')->paginate(15)->withQueryString();
        $dusuns = Dusun::orderBy('nama_dusun')->get();

        return view('super-admin.kontak.index', compact('kontakList', 'dusuns', 'statusFilter', 'dusunFilter'));
    }

    public function create(Request $request): View
    {
        $this->authorize('create', KontakPelayanan::class);
        $dusuns = Dusun::orderBy('nama_dusun')->get();

        return view('super-admin.kontak.create', compact('dusuns'));
    }

    public function store(KontakPelayananRequest $request, MediaService $mediaService): RedirectResponse
    {
        $this->authorize('create', KontakPelayanan::class);

        $validated = $request->validated();
        $fotoPath = null;

        if ($request->hasFile('foto')) {
            $fotoPath = $mediaService->storeImage($request->file('foto'), 'kontak');
        }

        KontakPelayanan::forceCreate([
            'dusun_id' => $validated['dusun_id'],
            'nama' => $validated['nama'],
            'jabatan' => $validated['jabatan'],
            'nomor_whatsapp' => $validated['nomor_whatsapp'],
            'alamat_pelayanan' => $validated['alamat_pelayanan'] ?? null,
            'foto_path' => $fotoPath,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('super-admin.kontak.index')
            ->with('success', 'Kontak pelayanan berhasil ditambahkan.');
    }

    public function edit(Request $request, int $id): View
    {
        $kontak = KontakPelayanan::withTrashed()->findOrFail($id);
        $this->authorize('view', $kontak);

        $dusuns = Dusun::orderBy('nama_dusun')->get();

        return view('super-admin.kontak.edit', compact('kontak', 'dusuns'));
    }

    public function update(KontakPelayananRequest $request, int $id, MediaService $mediaService): RedirectResponse
    {
        $kontak = KontakPelayanan::withTrashed()->findOrFail($id);
        $this->authorize('update', $kontak);

        $validated = $request->validated();
        $oldFoto = null;

        if ($request->hasFile('foto')) {
            $newFoto = $mediaService->storeImage($request->file('foto'), 'kontak');
            $oldFoto = $kontak->foto_path;
            $kontak->foto_path = $newFoto;
        }

        $kontak->dusun_id = $validated['dusun_id'];
        $kontak->nama = $validated['nama'];
        $kontak->jabatan = $validated['jabatan'];
        $kontak->nomor_whatsapp = $validated['nomor_whatsapp'];
        $kontak->alamat_pelayanan = $validated['alamat_pelayanan'] ?? null;
        $kontak->latitude = $validated['latitude'] ?? null;
        $kontak->longitude = $validated['longitude'] ?? null;
        $kontak->save();

        if ($oldFoto) {
            $mediaService->deleteImage($oldFoto);
        }

        return redirect()->route('super-admin.kontak.index')
            ->with('success', 'Data kontak pelayanan berhasil diperbarui.');
    }

    public function destroy(Request $request, int $id): RedirectResponse
    {
        $kontak = KontakPelayanan::findOrFail($id);
        $this->authorize('delete', $kontak);

        $kontak->delete(); // Soft delete. Media retained.

        return redirect()->route('super-admin.kontak.index')
            ->with('success', 'Kontak pelayanan berhasil dinonaktifkan (Soft Delete).');
    }

    public function restore(Request $request, int $id): RedirectResponse
    {
        $kontak = KontakPelayanan::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $kontak);

        $kontak->restore();

        return redirect()->route('super-admin.kontak.index', ['status' => 'trashed'])
            ->with('success', 'Kontak pelayanan berhasil dipulihkan (Restore).');
    }

    public function forceDelete(Request $request, int $id, MediaService $mediaService): RedirectResponse
    {
        $kontak = KontakPelayanan::onlyTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $kontak);

        $fotoPath = $kontak->foto_path;

        $kontak->forceDelete();

        if ($fotoPath) {
            $mediaService->deleteImage($fotoPath);
        }

        return redirect()->route('super-admin.kontak.index', ['status' => 'trashed'])
            ->with('success', 'Kontak pelayanan berhasil dihapus permanen.');
    }
}
