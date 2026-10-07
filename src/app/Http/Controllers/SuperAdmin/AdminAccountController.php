<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\AdminAccountCreateRequest;
use App\Http\Requests\SuperAdmin\AdminAccountResetPasswordRequest;
use App\Http\Requests\SuperAdmin\AdminAccountUpdateRequest;
use App\Models\AdminAccount;
use App\Models\Dusun;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminAccountController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', AdminAccount::class);

        $accounts = AdminAccount::where('role', 'ADMIN_DUSUN')
            ->with('dusun')
            ->orderByRaw('CASE WHEN removed_at IS NULL THEN 0 ELSE 1 END')
            ->orderBy('username')
            ->get();

        return view('super-admin.admin-dusun.index', compact('accounts'));
    }

    public function create(Request $request): View
    {
        $this->authorize('create', AdminAccount::class);

        $dusuns = Dusun::orderBy('nama_dusun')->get();

        return view('super-admin.admin-dusun.create', compact('dusuns'));
    }

    public function store(AdminAccountCreateRequest $request): RedirectResponse
    {
        $this->authorize('create', AdminAccount::class);

        $validated = $request->validated();

        AdminAccount::forceCreate([
            'dusun_id' => $validated['dusun_id'],
            'username' => strtolower(trim($validated['username'])),
            'password_hash' => Hash::make($validated['password']),
            'role' => 'ADMIN_DUSUN', // Server-forced role
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('super-admin.admin-dusun.index')
            ->with('success', "Akun Admin Dusun '{$validated['username']}' berhasil dibuat.");
    }

    public function edit(Request $request, int $id): View
    {
        $account = AdminAccount::where('role', 'ADMIN_DUSUN')->findOrFail($id);
        $this->authorize('update', $account);

        if ($account->isRemoved()) {
            return redirect()->route('super-admin.admin-dusun.index')
                ->with('error', 'Akun yang telah dinonaktifkan (removed) tidak dapat diedit kembali.');
        }

        $dusuns = Dusun::orderBy('nama_dusun')->get();

        return view('super-admin.admin-dusun.edit', compact('account', 'dusuns'));
    }

    public function update(AdminAccountUpdateRequest $request, int $id): RedirectResponse
    {
        $account = AdminAccount::where('role', 'ADMIN_DUSUN')->findOrFail($id);
        $this->authorize('update', $account);

        if ($account->isRemoved()) {
            return redirect()->route('super-admin.admin-dusun.index')
                ->with('error', 'Akun yang telah dinonaktifkan (removed) tidak dapat diubah.');
        }

        $validated = $request->validated();
        $account->dusun_id = $validated['dusun_id'];
        $account->save();

        return redirect()->route('super-admin.admin-dusun.index')
            ->with('success', "Penugasan wilayah dusun untuk akun '{$account->username}' berhasil diperbarui.");
    }

    public function showResetPasswordForm(Request $request, int $id): View
    {
        $account = AdminAccount::where('role', 'ADMIN_DUSUN')->findOrFail($id);
        $this->authorize('resetPassword', $account);

        if ($account->isRemoved()) {
            abort(404, 'Akun telah dinonaktifkan.');
        }

        return view('super-admin.admin-dusun.reset-password', compact('account'));
    }

    public function resetPassword(AdminAccountResetPasswordRequest $request, int $id): RedirectResponse
    {
        $account = AdminAccount::where('role', 'ADMIN_DUSUN')->findOrFail($id);
        $this->authorize('resetPassword', $account);

        if ($account->isRemoved()) {
            return redirect()->route('super-admin.admin-dusun.index')
                ->with('error', 'Kata sandi akun yang telah dinonaktifkan tidak dapat direset.');
        }

        $validated = $request->validated();
        $account->password_hash = Hash::make($validated['password']);
        $account->save();

        return redirect()->route('super-admin.admin-dusun.index')
            ->with('success', "Kata sandi akun '{$account->username}' berhasil direset.");
    }

    public function remove(Request $request, int $id): RedirectResponse
    {
        $account = AdminAccount::where('role', 'ADMIN_DUSUN')->findOrFail($id);
        $this->authorize('logicalRemove', $account);

        if ($account->isRemoved()) {
            return redirect()->route('super-admin.admin-dusun.index')
                ->with('error', 'Akun ini sudah dalam status nonaktif.');
        }

        $account->removed_at = now();
        $account->save();

        return redirect()->route('super-admin.admin-dusun.index')
            ->with('success', "Akun '{$account->username}' berhasil dinonaktifkan (Logical Removal). Identitas akun tetap disimpan untuk arsip.");
    }
}
