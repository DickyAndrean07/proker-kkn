@extends('layouts.super-admin')

@section('title', 'Kelola Admin Dusun')
@section('breadcrumb')
    <a href="{{ route('super-admin.dashboard') }}">Dashboard</a> / <span>Admin Dusun</span>
@endsection

@section('content')
<div class="admin-page-header flex-between">
    <div>
        <h1 class="admin-page-title">Kelola Akun Admin Dusun</h1>
        <p class="admin-page-desc">Manajemen akun pengelola wilayah dusun (ADMIN_DUSUN) beserta penugasan dan status akses.</p>
    </div>
    <div>
        <a href="{{ route('super-admin.admin-dusun.create') }}" class="btn btn-primary">
            + Tambah Admin Dusun Baru
        </a>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-body p-0">
        @if($accounts->isEmpty())
            <div class="admin-empty-state">
                <div class="empty-icon">👥</div>
                <h3 class="empty-title">Belum ada akun Admin Dusun</h3>
                <p class="empty-desc">Daftarkan akun admin baru untuk memberikan hak kelola konten pada perangkat dusun.</p>
                <a href="{{ route('super-admin.admin-dusun.create') }}" class="btn btn-primary" style="margin-top: 1rem;">
                    + Tambah Admin Dusun Pertama
                </a>
            </div>
        @else
            <div class="admin-table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Peran & Penugasan Wilayah</th>
                            <th>Status Akses Akun</th>
                            <th>Waktu Pembuatan</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($accounts as $account)
                            <tr class="{{ $account->isRemoved() ? 'row-trashed' : '' }}">
                                <td>
                                    <strong class="item-title">{{ $account->username }}</strong>
                                </td>
                                <td>
                                    <span class="badge badge-primary">ADMIN_DUSUN</span>
                                    <span class="badge badge-neutral">🏘️ {{ $account->dusun->nama_dusun ?? 'Dusun' }}</span>
                                </td>
                                <td>
                                    @if($account->isRemoved())
                                        <span class="badge badge-danger">🚫 Akses Dinonaktifkan (Arsip Audit)</span>
                                        <div class="text-muted text-xs">Sejak: {{ $account->removed_at->translatedFormat('d M Y H:i') }}</div>
                                    @else
                                        <span class="badge badge-success">● Aktif Bekerja</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $account->created_at->translatedFormat('d M Y') }}
                                </td>
                                <td class="text-right">
                                    <div class="action-buttons">
                                        @if($account->isRemoved())
                                            <span class="text-muted text-xs italic">Riwayat Permanen</span>
                                        @else
                                            <a href="{{ route('super-admin.admin-dusun.edit', $account->id) }}" class="btn btn-sm btn-outline-primary" title="Ubah Penugasan Dusun">
                                                Ubah Dusun
                                            </a>
                                            <a href="{{ route('super-admin.admin-dusun.reset-password', $account->id) }}" class="btn btn-sm btn-outline-primary" title="Reset Kata Sandi">
                                                Reset Password
                                            </a>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="openRemoveAccountModal('{{ route('super-admin.admin-dusun.remove', $account->id) }}', '{{ addslashes($account->username) }}')"
                                                title="Nonaktifkan Akses Akun (Logical Removal)"
                                            >
                                                Hapus Akses
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
