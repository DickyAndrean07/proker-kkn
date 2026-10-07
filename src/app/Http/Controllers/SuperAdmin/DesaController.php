<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\DesaRequest;
use App\Models\Desa;
use App\Services\MediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DesaController extends Controller
{
    public function edit(Request $request): View
    {
        $desa = Desa::firstOrFail();
        $this->authorize('update', $desa);

        return view('super-admin.desa.edit', compact('desa'));
    }

    public function update(DesaRequest $request, MediaService $mediaService): RedirectResponse
    {
        $desa = Desa::firstOrFail();
        $this->authorize('update', $desa);

        $validated = $request->validated();
        $oldBanner = null;

        if ($request->hasFile('banner')) {
            $newBanner = $mediaService->storeImage($request->file('banner'), 'desa');
            $oldBanner = $desa->banner_path;
            $desa->banner_path = $newBanner;
        }

        $desa->nama_desa = $validated['nama_desa'];
        $desa->deskripsi_singkat = $validated['deskripsi_singkat'];
        $desa->alamat_kantor = $validated['alamat_kantor'];
        $desa->nomor_kontak = $validated['nomor_kontak'];
        $desa->nama_kepala_desa = $validated['nama_kepala_desa'];
        $desa->jam_pelayanan = $validated['jam_pelayanan'];
        $desa->save();

        if ($oldBanner) {
            $mediaService->deleteImage($oldBanner);
        }

        return redirect()->route('super-admin.desa.edit')
            ->with('success', 'Identitas Desa berhasil diperbarui.');
    }
}
