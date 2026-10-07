@extends('layouts.super-admin')

@section('title', 'Kelola Fasilitas')
@section('breadcrumb')
    <a href="{{ route('super-admin.dashboard') }}">Dashboard</a> / <span>Kelola Fasilitas</span>
@endsection

@section('content')
<div class="admin-page-header flex-between">
    <div>
        <h1 class="admin-page-title">Kelola Fasilitas Umum (Global)</h1>
        <p class="admin-page-desc">Daftar seluruh sarana, prasarana, dan fasilitas umum di wilayah Desa Bendung.</p>
    </div>
    <div>
        <a href="{{ route('super-admin.fasilitas.create') }}" class="btn btn-primary">
            + Tambah Fasilitas Baru
        </a>
    </div>
</div>

<!-- Filters Bar -->
<div class="admin-card mb-3">
    <div class="admin-card-body" style="padding: 1rem 1.25rem;">
        <form method="GET" action="{{ route('super-admin.fasilitas.index') }}" class="filter-form-bar">
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

            <div class="filter-group">
                <label for="kategori_id" class="filter-label">Filter Kategori:</label>
                <select name="kategori_id" id="kategori_id" class="form-select filter-select" onchange="this.form.submit()">
                    <option value="">-- Seluruh Kategori --</option>
                    @foreach($kategoriList as $kategori)
                        <option value="{{ $kategori->id }}" {{ (string)$kategoriFilter === (string)$kategori->id ? 'selected' : '' }}>
                            {{ $kategori->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            @if(!empty($dusunFilter) || !empty($kategoriFilter) || $statusFilter !== 'active')
                <a href="{{ route('super-admin.fasilitas.index') }}" class="btn btn-sm btn-secondary">Reset Filter</a>
            @endif
        </form>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-body p-0">
        @if($fasilitasList->isEmpty())
            <div class="admin-empty-state">
                <div class="empty-icon">📍</div>
                <h3 class="empty-title">Belum ada fasilitas yang terdaftar</h3>
                <p class="empty-desc">Tidak ada data fasilitas yang sesuai dengan kriteria filter saat ini.</p>
                <a href="{{ route('super-admin.fasilitas.create') }}" class="btn btn-primary" style="margin-top: 1rem;">
                    + Tambah Fasilitas Pertama
                </a>
            </div>
        @else
            <div class="admin-table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nama Fasilitas</th>
                            <th>Wilayah Dusun</th>
                            <th>Kategori</th>
                            <th>Koordinat</th>
                            <th>Status Rekod</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fasilitasList as $fasilitas)
                            <tr class="{{ $fasilitas->trashed() ? 'row-trashed' : '' }}">
                                <td class="table-thumb-col">
                                    @if($fasilitas->foto_path)
                                        <img src="{{ asset('storage/' . $fasilitas->foto_path) }}" alt="{{ $fasilitas->nama }}" class="table-thumb">
                                    @else
                                        <div class="table-thumb-placeholder">📍</div>
                                    @endif
                                </td>
                                <td>
                                    <strong class="item-title">{{ $fasilitas->nama }}</strong>
                                    <div class="item-subtitle">{{ Str::limit($fasilitas->alamat, 45) }}</div>
                                </td>
                                <td>
                                    <span class="badge badge-primary">{{ $fasilitas->dusun->nama_dusun ?? 'Dusun' }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-neutral">{{ $fasilitas->kategoriFasilitas->nama_kategori ?? 'Kategori' }}</span>
                                </td>
                                <td>
                                    <span class="coord-badge">📍 {{ $fasilitas->latitude }}, {{ $fasilitas->longitude }}</span>
                                </td>
                                <td>
                                    @if($fasilitas->trashed())
                                        <span class="badge badge-danger">🗑️ Soft Deleted</span>
                                    @else
                                        <span class="badge badge-success">✓ Aktif</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <div class="action-buttons">
                                        @if($fasilitas->trashed())
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                onclick="openRestoreModal('{{ route('super-admin.fasilitas.restore', $fasilitas->id) }}', '{{ addslashes($fasilitas->nama) }}')"
                                            >
                                                Pulihkan
                                            </button>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-danger"
                                                onclick="openForceDeleteModal('{{ route('super-admin.fasilitas.force-delete', $fasilitas->id) }}', '{{ addslashes($fasilitas->nama) }}')"
                                            >
                                                Hapus Permanen
                                            </button>
                                        @else
                                            <a href="{{ route('super-admin.fasilitas.edit', $fasilitas->id) }}" class="btn btn-sm btn-outline-primary">
                                                Edit
                                            </a>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="openDeactivateModal('{{ route('super-admin.fasilitas.destroy', $fasilitas->id) }}', '{{ addslashes($fasilitas->nama) }}')"
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
                {{ $fasilitasList->links('partials.pagination') }}
            </div>
        @endif
    </div>
</div>
@endsection
