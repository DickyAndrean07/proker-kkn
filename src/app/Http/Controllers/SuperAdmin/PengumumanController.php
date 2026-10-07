<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\PengumumanRequest;
use App\Models\Desa;
use App\Models\Dusun;
use App\Models\Pengumuman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PengumumanController extends Controller
{
    public function index(Request $request): View
    {
        $statusFilter = $request->query('status', 'active');
        $scopeFilter = $request->query('scope_level');
        $dusunFilter = $request->query('dusun_id');

        $query = Pengumuman::with('dusun');

        if ($statusFilter === 'trashed') {
            $query->onlyTrashed();
        } elseif ($statusFilter === 'all') {
            $query->withTrashed();
        }

        if (! empty($scopeFilter)) {
            $query->where('scope_level', $scopeFilter);
        }

        if (! empty($dusunFilter)) {
            $query->where('dusun_id', $dusunFilter);
        }

        $pengumumanList = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        $dusuns = Dusun::orderBy('nama_dusun')->get();

        return view('super-admin.pengumuman.index', compact(
            'pengumumanList',
            'dusuns',
            'statusFilter',
            'scopeFilter',
            'dusunFilter'
        ));
    }

    public function create(Request $request): View
    {
        $this->authorize('create', [Pengumuman::class, 'DESA']);
        $dusuns = Dusun::orderBy('nama_dusun')->get();

        return view('super-admin.pengumuman.create', compact('dusuns'));
    }

    public function store(PengumumanRequest $request): RedirectResponse
    {
        $this->authorize('create', [Pengumuman::class, $request->input('scope_level'), $request->input('dusun_id')]);

        $desa = Desa::firstOrFail();
        $validated = $request->validated();

        Pengumuman::forceCreate([
            'desa_id' => $desa->id,
            'dusun_id' => $validated['scope_level'] === 'DUSUN' ? $validated['dusun_id'] : null,
            'scope_level' => $validated['scope_level'],
            'judul' => $validated['judul'],
            'isi' => $validated['isi'],
            'tanggal_kedaluwarsa' => $validated['tanggal_kedaluwarsa'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('super-admin.pengumuman.index')
            ->with('success', 'Pengumuman berhasil diterbitkan.');
    }

    public function edit(Request $request, int $id): View
    {
        $pengumuman = Pengumuman::withTrashed()->findOrFail($id);
        $this->authorize('view', $pengumuman);

        $dusuns = Dusun::orderBy('nama_dusun')->get();

        return view('super-admin.pengumuman.edit', compact('pengumuman', 'dusuns'));
    }

    public function update(PengumumanRequest $request, int $id): RedirectResponse
    {
        $pengumuman = Pengumuman::withTrashed()->findOrFail($id);
        $this->authorize('update', $pengumuman);

        $validated = $request->validated();

        $pengumuman->scope_level = $validated['scope_level'];
        $pengumuman->dusun_id = $validated['scope_level'] === 'DUSUN' ? $validated['dusun_id'] : null;
        $pengumuman->judul = $validated['judul'];
        $pengumuman->isi = $validated['isi'];
        $pengumuman->tanggal_kedaluwarsa = $validated['tanggal_kedaluwarsa'];
        $pengumuman->save();

        return redirect()->route('super-admin.pengumuman.index')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Request $request, int $id): RedirectResponse
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $this->authorize('delete', $pengumuman);

        $pengumuman->delete(); // Soft delete

        return redirect()->route('super-admin.pengumuman.index')
            ->with('success', 'Pengumuman berhasil dinonaktifkan (Soft Delete).');
    }

    public function restore(Request $request, int $id): RedirectResponse
    {
        $pengumuman = Pengumuman::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $pengumuman);

        $pengumuman->restore();

        return redirect()->route('super-admin.pengumuman.index', ['status' => 'trashed'])
            ->with('success', 'Pengumuman berhasil dipulihkan (Restore).');
    }

    public function forceDelete(Request $request, int $id): RedirectResponse
    {
        $pengumuman = Pengumuman::onlyTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $pengumuman);

        $pengumuman->forceDelete();

        return redirect()->route('super-admin.pengumuman.index', ['status' => 'trashed'])
            ->with('success', 'Pengumuman berhasil dihapus permanen.');
    }
}
