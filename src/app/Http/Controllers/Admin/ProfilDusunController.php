<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProfilDusunRequest;
use App\Services\MediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfilDusunController extends Controller
{
    public function edit(Request $request): View
    {
        $dusun = $request->user()->dusun;
        $this->authorize('update', $dusun);

        return view('admin.profil.edit', compact('dusun'));
    }

    public function update(ProfilDusunRequest $request, MediaService $mediaService): RedirectResponse
    {
        $dusun = $request->user()->dusun;
        $this->authorize('update', $dusun);

        $validated = $request->validated();
        $oldBanner = null;
        $oldFoto = null;

        if ($request->hasFile('banner')) {
            $newBanner = $mediaService->storeImage($request->file('banner'), 'dusuns');
            $oldBanner = $dusun->banner_path;
            $dusun->banner_path = $newBanner;
        }

        if ($request->hasFile('foto_kepala_dusun')) {
            $newFoto = $mediaService->storeImage($request->file('foto_kepala_dusun'), 'dusuns/kadus');
            $oldFoto = $dusun->foto_kepala_dusun_path;
            $dusun->foto_kepala_dusun_path = $newFoto;
        }

        $dusun->nama_dusun = $validated['nama_dusun'];
        $dusun->deskripsi_singkat = $validated['deskripsi_singkat'];
        $dusun->nama_kepala_dusun = $validated['nama_kepala_dusun'];
        $dusun->jumlah_rt = $validated['jumlah_rt'];
        $dusun->jumlah_rw = $validated['jumlah_rw'];
        $dusun->save();

        if ($oldBanner) {
            $mediaService->deleteImage($oldBanner);
        }

        if ($oldFoto) {
            $mediaService->deleteImage($oldFoto);
        }

        return redirect()->route('admin-dusun.profil.edit')
            ->with('success', 'Profil Dusun berhasil diperbarui.');
    }
}
