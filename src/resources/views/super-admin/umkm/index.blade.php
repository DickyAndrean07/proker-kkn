@extends('layouts.super-admin')

@section('title', 'Kelola UMKM')
@section('breadcrumb')
    <a href="{{ route('super-admin.dashboard') }}">Dashboard</a> / <span>Kelola UMKM</span>
@endsection

@section('content')
<div class="admin-page-header flex-between">
    <div>
        <h1 class="admin-page-title">Kelola UMKM (Global)</h1>
        <p class="admin-page-desc">Daftar usaha mikro, kecil, dan menengah di seluruh wilayah Desa Bendung.</p>
    </div>
    <div>
        <a href="{{ route('super-admin.umkm.create') }}" class="btn btn-primary">
            + Tambah UMKM Baru
        </a>
    </div>
</div>

<!-- Filters Bar -->
<div class="admin-card mb-3">
    <div class="admin-card-body" style="padding: 1rem 1.25rem;">
        <form method="GET" action="{{ route('super-admin.umkm.index') }}" class="filter-form-bar">
            <div class="filter-group">
                <label for="status" class="filter-label">Status Data:</label>
                <select name="status" id="status" class="form-select filter-select" onchange="this.form.submit()">
                    <option value="active" {{ $statusFilter === 'active' ? 'selected' : '' }}>Aktif (Non-Deleted)</option>
                    <option value="trashed" {{ $statusFilter === 'trashed' ? 'selected' : '' }}>Soft Deleted (Nonaktif)</option>
                    <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>Semua Data</option>
                </select>
            </div>

            <div class="filter-group">
                <label for="dusun_id" class="filter-label">Filter Dusun:</label>
                <select name="dusun_id" id="dusun_id" class="form-select filter-select" onchange="this.form.submit()">
                    <option value="">-- Seluruh Dusun --</option>
                    @foreach($dusuns as $dusun)
                        <option value="{{ $dusun->id }}" {{ (string)$dusunFilter === (string)$dusun->id ? 'selected' : '' }}>
                            {{ $dusun->nama_dusun }}
                        </option>
                    @endforeach
                </select>
            </div>

            @if(!empty($dusunFilter) || $statusFilter !== 'active')
                <a href="{{ route('super-admin.umkm.index') }}" class="btn btn-sm btn-secondary">Reset Filter</a>
            @endif
        </form>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-body p-0">
        @if($umkmList->isEmpty())
            <div class="admin-empty-state">
                <div class="empty-icon">🛍️</div>
                <h3 class="empty-title">Belum ada usaha UMKM</h3>
                <p class="empty-desc">Tidak ada data UMKM yang sesuai dengan kriteria filter saat ini.</p>
                <a href="{{ route('super-admin.umkm.create') }}" class="btn btn-primary" style="margin-top: 1rem;">
                    + Tambah UMKM Pertama
                </a>
            </div>
        @else
            <div class="admin-table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nama Usaha & Pemilik</th>
                            <th>Wilayah Dusun</th>
                            <th>Jenis Usaha</th>
                            <th>Produk</th>
                            <th>Status Rekod</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($umkmList as $umkm)
                            <tr class="{{ $umkm->trashed() ? 'row-trashed' : '' }}">
                                <td class="table-thumb-col">
                                    @if($umkm->foto_utama_path)
                                        <img src="{{ asset('storage/' . $umkm->foto_utama_path) }}" alt="{{ $umkm->nama_umkm }}" class="table-thumb">
                                    @else
                                        <div class="table-thumb-placeholder">🛍️</div>
                                    @endif
                                </td>
                                <td>
                                    <strong class="item-title">{{ $umkm->nama_umkm }}</strong>
                                    <div class="item-subtitle">Pemilik: {{ $umkm->nama_pemilik }}</div>
                                </td>
                                <td>
                                    <span class="badge badge-primary">{{ $umkm->dusun->nama_dusun ?? 'Dusun' }}</span>
                                </td>
                                <td>{{ $umkm->jenis_usaha }}</td>
                                <td>
                                    @if($umkm->produkUmkms->isNotEmpty())
                                        <span class="badge badge-neutral">{{ $umkm->produkUmkms->count() }} Produk</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($umkm->trashed())
                                        <span class="badge badge-danger">🗑️ Soft Deleted</span>
                                    @else
                                        <span class="badge badge-success">✓ Aktif</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <div class="action-buttons">
                                        @if($umkm->trashed())
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                onclick="openRestoreModal('{{ route('super-admin.umkm.restore', $umkm->id) }}', '{{ addslashes($umkm->nama_umkm) }}')"
                                            >
                                                Pulihkan
                                            </button>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-danger"
                                                onclick="openForceDeleteModal('{{ route('super-admin.umkm.force-delete', $umkm->id) }}', '{{ addslashes($umkm->nama_umkm) }}')"
                                            >
                                                Hapus Permanen
                                            </button>
                                        @else
                                            <a href="{{ route('super-admin.umkm.edit', $umkm->id) }}" class="btn btn-sm btn-outline-primary">
                                                Edit
                                            </a>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="openDeactivateModal('{{ route('super-admin.umkm.destroy', $umkm->id) }}', '{{ addslashes($umkm->nama_umkm) }}')"
                                            >
                                                Nonaktifkan
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="admin-pagination-wrapper">
                {{ $umkmList->links('partials.pagination') }}
            </div>
        @endif
    </div>
</div>
@endsection
