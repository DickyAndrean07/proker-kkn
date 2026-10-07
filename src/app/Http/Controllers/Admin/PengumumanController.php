<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PengumumanRequest;
use App\Models\Pengumuman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PengumumanController extends Controller
{
    public function index(Request $request): View
    {
        $dusun = $request->user()->dusun;
        $pengumumanList = Pengumuman::where('dusun_id', $dusun->id)
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.pengumuman.index', compact('pengumumanList', 'dusun'));
    }

    public function create(Request $request): View
    {
        $dusun = $request->user()->dusun;
        $this->authorize('create', [Pengumuman::class, 'DUSUN', $dusun->id]);

        return view('admin.pengumuman.create', compact('dusun'));
    }

    public function store(PengumumanRequest $request): RedirectResponse
    {
        $dusun = $request->user()->dusun;
        $this->authorize('create', [Pengumuman::class, 'DUSUN', $dusun->id]);

        $validated = $request->validated();

        Pengumuman::forceCreate([
            'desa_id' => $dusun->desa_id,
            'dusun_id' => $dusun->id,
            'scope_level' => 'DUSUN',
            'judul' => $validated['judul'],
            'isi' => $validated['isi'],
            'tanggal_kedaluwarsa' => $validated['tanggal_kedaluwarsa'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin-dusun.pengumuman.index')
            ->with('success', 'Data pengumuman berhasil disimpan.');
    }

    public function edit(Request $request, int $id): View
    {
        $dusun = $request->user()->dusun;
        $pengumuman = Pengumuman::where('dusun_id', $dusun->id)->findOrFail($id);
        $this->authorize('view', $pengumuman);

        return view('admin.pengumuman.edit', compact('pengumuman', 'dusun'));
    }

    public function update(PengumumanRequest $request, int $id): RedirectResponse
    {
        $dusun = $request->user()->dusun;
        $pengumuman = Pengumuman::where('dusun_id', $dusun->id)->findOrFail($id);
        $this->authorize('update', $pengumuman);

        $validated = $request->validated();

        $pengumuman->judul = $validated['judul'];
        $pengumuman->isi = $validated['isi'];
        $pengumuman->tanggal_kedaluwarsa = $validated['tanggal_kedaluwarsa'];
        $pengumuman->save();

        return redirect()->route('admin-dusun.pengumuman.index')
            ->with('success', 'Data pengumuman berhasil disimpan.');
    }

    public function destroy(Request $request, int $id): RedirectResponse
    {
        $dusun = $request->user()->dusun;
        $pengumuman = Pengumuman::where('dusun_id', $dusun->id)->findOrFail($id);
        $this->authorize('delete', $pengumuman);

        $pengumuman->delete(); // Soft delete.

        return redirect()->route('admin-dusun.pengumuman.index')
            ->with('success', 'Data berhasil dinonaktifkan.');
    }
}
