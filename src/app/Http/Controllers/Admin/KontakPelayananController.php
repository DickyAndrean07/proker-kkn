<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\KontakPelayananRequest;
use App\Models\KontakPelayanan;
use App\Services\MediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KontakPelayananController extends Controller
{
    public function index(Request $request): View
    {
        $dusun = $request->user()->dusun;
        $kontakList = KontakPelayanan::where('dusun_id', $dusun->id)
            ->orderBy('nama')
            ->paginate(15);

        return view('admin.kontak.index', compact('kontakList', 'dusun'));
    }

    public function create(Request $request): View
    {
        $dusun = $request->user()->dusun;
        $this->authorize('create', [KontakPelayanan::class, $dusun->id]);

        return view('admin.kontak.create', compact('dusun'));
    }

    public function store(KontakPelayananRequest $request, MediaService $mediaService): RedirectResponse
    {
        $dusun = $request->user()->dusun;
        $this->authorize('create', [KontakPelayanan::class, $dusun->id]);

        $validated = $request->validated();
        $fotoPath = null;

        if ($request->hasFile('foto')) {
            $fotoPath = $mediaService->storeImage($request->file('foto'), 'kontak');
        }

        KontakPelayanan::forceCreate([
            'dusun_id' => $dusun->id,
            'nama' => $validated['nama'],
            'jabatan' => $validated['jabatan'],
            'nomor_whatsapp' => $validated['nomor_whatsapp'],
            'foto_path' => $fotoPath,
            'alamat_pelayanan' => $validated['alamat_pelayanan'] ?? null,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin-dusun.kontak.index')
            ->with('success', 'Data kontak berhasil disimpan.');
    }

    public function edit(Request $request, int $id): View
    {
        $dusun = $request->user()->dusun;
        $kontak = KontakPelayanan::where('dusun_id', $dusun->id)->findOrFail($id);
        $this->authorize('view', $kontak);

        return view('admin.kontak.edit', compact('kontak', 'dusun'));
    }

    public function update(KontakPelayananRequest $request, int $id, MediaService $mediaService): RedirectResponse
    {
        $dusun = $request->user()->dusun;
        $kontak = KontakPelayanan::where('dusun_id', $dusun->id)->findOrFail($id);
        $this->authorize('update', $kontak);

        $validated = $request->validated();
        $oldFoto = null;

        if ($request->hasFile('foto')) {
            $newFoto = $mediaService->storeImage($request->file('foto'), 'kontak');
            $oldFoto = $kontak->foto_path;
            $kontak->foto_path = $newFoto;
        }

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

        return redirect()->route('admin-dusun.kontak.index')
            ->with('success', 'Data kontak berhasil disimpan.');
    }

    public function destroy(Request $request, int $id): RedirectResponse
    {
        $dusun = $request->user()->dusun;
        $kontak = KontakPelayanan::where('dusun_id', $dusun->id)->findOrFail($id);
        $this->authorize('delete', $kontak);

        $kontak->delete(); // Soft Delete. Media retained.

        return redirect()->route('admin-dusun.kontak.index')
            ->with('success', 'Data berhasil dinonaktifkan.');
    }
}
