<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FasilitasRequest;
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
        $dusun = $request->user()->dusun;
        $fasilitasList = Fasilitas::where('dusun_id', $dusun->id)
            ->with('kategoriFasilitas')
            ->orderBy('nama')
            ->paginate(15);

        return view('admin.fasilitas.index', compact('fasilitasList', 'dusun'));
    }

    public function create(Request $request): View
    {
        $dusun = $request->user()->dusun;
        $this->authorize('create', [Fasilitas::class, $dusun->id]);

        $kategoriList = KategoriFasilitas::where('desa_id', $dusun->desa_id)
            ->orderBy('nama_kategori')
            ->get();

        return view('admin.fasilitas.create', compact('dusun', 'kategoriList'));
    }

    public function store(FasilitasRequest $request, MediaService $mediaService): RedirectResponse
    {
        $dusun = $request->user()->dusun;
        $this->authorize('create', [Fasilitas::class, $dusun->id]);

        $validated = $request->validated();
        $fotoPath = null;

        if ($request->hasFile('foto')) {
            $fotoPath = $mediaService->storeImage($request->file('foto'), 'fasilitas');
        }

        Fasilitas::forceCreate([
            'dusun_id' => $dusun->id,
            'kategori_fasilitas_id' => $validated['kategori_fasilitas_id'],
            'nama' => $validated['nama'],
            'deskripsi' => $validated['deskripsi'],
            'alamat' => $validated['alamat'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'foto_path' => $fotoPath,
            'nomor_whatsapp' => $validated['nomor_whatsapp'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin-dusun.fasilitas.index')
            ->with('success', 'Data fasilitas berhasil disimpan.');
    }

    public function edit(Request $request, int $id): View
    {
        $dusun = $request->user()->dusun;
        $fasilitas = Fasilitas::where('dusun_id', $dusun->id)->findOrFail($id);
        $this->authorize('view', $fasilitas);

        $kategoriList = KategoriFasilitas::where('desa_id', $dusun->desa_id)
            ->orderBy('nama_kategori')
            ->get();

        return view('admin.fasilitas.edit', compact('fasilitas', 'dusun', 'kategoriList'));
    }

    public function update(FasilitasRequest $request, int $id, MediaService $mediaService): RedirectResponse
    {
        $dusun = $request->user()->dusun;
        $fasilitas = Fasilitas::where('dusun_id', $dusun->id)->findOrFail($id);
        $this->authorize('update', $fasilitas);

        $validated = $request->validated();
        $oldFoto = null;

        if ($request->hasFile('foto')) {
            $newFoto = $mediaService->storeImage($request->file('foto'), 'fasilitas');
            $oldFoto = $fasilitas->foto_path;
            $fasilitas->foto_path = $newFoto;
        }

        $fasilitas->kategori_fasilitas_id = $validated['kategori_fasilitas_id'];
        $fasilitas->nama = $validated['nama'];
        $fasilitas->deskripsi = $validated['deskripsi'];
        $fasilitas->alamat = $validated['alamat'];
        $fasilitas->latitude = $validated['latitude'];
        $fasilitas->longitude = $validated['longitude'];
        $fasilitas->nomor_whatsapp = $validated['nomor_whatsapp'] ?? null;
        $fasilitas->save();

        if ($oldFoto) {
            $mediaService->deleteImage($oldFoto);
        }

        return redirect()->route('admin-dusun.fasilitas.index')
            ->with('success', 'Data fasilitas berhasil disimpan.');
    }

    public function destroy(Request $request, int $id): RedirectResponse
    {
        $dusun = $request->user()->dusun;
        $fasilitas = Fasilitas::where('dusun_id', $dusun->id)->findOrFail($id);
        $this->authorize('delete', $fasilitas);

        $fasilitas->delete(); // Soft delete. Media retained.

        return redirect()->route('admin-dusun.fasilitas.index')
            ->with('success', 'Data berhasil dinonaktifkan.');
    }
}
