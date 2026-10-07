<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\DusunProfileRequest;
use App\Models\Dusun;
use App\Services\MediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DusunController extends Controller
{
    public function index(Request $request): View
    {
        $dusuns = Dusun::withCount([
            'kontakPelayanans',
            'umkms',
            'fasilitas',
            'agendaKegiatans',
            'pengumumans',
            'adminAccounts' => fn ($q) => $q->whereNull('removed_at'),
        ])->get();

        return view('super-admin.dusun.index', compact('dusuns'));
    }

    public function edit(Request $request, int $id): View
    {
        $dusun = Dusun::findOrFail($id);
        $this->authorize('update', $dusun);

        return view('super-admin.dusun.edit', compact('dusun'));
    }

    public function update(DusunProfileRequest $request, int $id, MediaService $mediaService): RedirectResponse
    {
        $dusun = Dusun::findOrFail($id);
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

        return redirect()->route('super-admin.dusun.index')
            ->with('success', "Profil {$dusun->nama_dusun} berhasil diperbarui.");
    }

    public function activate(Request $request, int $id): RedirectResponse
    {
        $dusun = Dusun::findOrFail($id);
        $this->authorize('activate', $dusun);

        $dusun->status_dusun = 'ACTIVE';
        $dusun->save();

        return redirect()->route('super-admin.dusun.index')
            ->with('success', "Status {$dusun->nama_dusun} berhasil diubah menjadi AKTIF publik.");
    }

    public function deactivate(Request $request, int $id): RedirectResponse
    {
        $dusun = Dusun::findOrFail($id);
        $this->authorize('deactivate', $dusun);

        $dusun->status_dusun = 'INACTIVE';
        $dusun->save();

        return redirect()->route('super-admin.dusun.index')
            ->with('success', "Status {$dusun->nama_dusun} berhasil diubah menjadi NONAKTIF publik.");
    }
}
