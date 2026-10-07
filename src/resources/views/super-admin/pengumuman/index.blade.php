@extends('layouts.super-admin')

@section('title', 'Kelola Pengumuman')
@section('breadcrumb')
    <a href="{{ route('super-admin.dashboard') }}">Dashboard</a> / <span>Pengumuman</span>
@endsection

@section('content')
<div class="admin-page-header flex-between">
    <div>
        <h1 class="admin-page-title">Pengumuman & Berita (Global)</h1>
        <p class="admin-page-desc">Kelola publikasi warta berita resmi dan pengumuman tingkat Desa maupun Dusun.</p>
    </div>
    <div>
        <a href="{{ route('super-admin.pengumuman.create') }}" class="btn btn-primary">
            + Terbitkan Pengumuman
        </a>
    </div>
</div>

<!-- Filters Bar -->
<div class="admin-card mb-3">
    <div class="admin-card-body" style="padding: 1rem 1.25rem;">
        <form method="GET" action="{{ route('super-admin.pengumuman.index') }}" class="filter-form-bar">
            <div class="filter-group">
                <label for="status" class="filter-label">Status Data:</label>
                <select name="status" id="status" class="form-select filter-select" onchange="this.form.submit()">
                    <option value="active" {{ $statusFilter === 'active' ? 'selected' : '' }}>Aktif (Non-Deleted)</option>
                    <option value="trashed" {{ $statusFilter === 'trashed' ? 'selected' : '' }}>Soft Deleted (Nonaktif)</option>
                    <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>Semua Data</option>
                </select>
            </div>

            <div class="filter-group">
                <label for="scope_level" class="filter-label">Cakupan Wilayah:</label>
                <select name="scope_level" id="scope_level" class="form-select filter-select" onchange="this.form.submit()">
                    <option value="">-- Semua Cakupan --</option>
                    <option value="DESA" {{ $scopeFilter === 'DESA' ? 'selected' : '' }}>Tingkat Desa</option>
                    <option value="DUSUN" {{ $scopeFilter === 'DUSUN' ? 'selected' : '' }}>Tingkat Dusun</option>
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

            @if(!empty($dusunFilter) || !empty($scopeFilter) || $statusFilter !== 'active')
                <a href="{{ route('super-admin.pengumuman.index') }}" class="btn btn-sm btn-secondary">Reset Filter</a>
            @endif
        </form>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-body p-0">
        @if($pengumumanList->isEmpty())
            <div class="admin-empty-state">
                <div class="empty-icon">📢</div>
                <h3 class="empty-title">Belum ada pengumuman</h3>
                <p class="empty-desc">Tidak ada data pengumuman yang sesuai dengan kriteria filter saat ini.</p>
                <a href="{{ route('super-admin.pengumuman.create') }}" class="btn btn-primary" style="margin-top: 1rem;">
                    + Buat Pengumuman Pertama
                </a>
            </div>
        @else
            <div class="admin-table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="min-width: 280px;">Judul & Isi Pengumuman</th>
                            <th>Cakupan Wilayah</th>
                            <th>Tanggal Terbit</th>
                            <th>Masa Aktif Publik</th>
                            <th>Status Rekod</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pengumumanList as $pengumuman)
                            @php
                                $isExpired = $pengumuman->tanggal_kedaluwarsa->isPast();
                            @endphp
                            <tr class="{{ $pengumuman->trashed() ? 'row-trashed' : '' }}">
                                <td class="table-lead-col" data-label="Informasi">
                                    <div class="entity-info-block">
                                        <div class="entity-row entity-title-row">
                                            <span class="entity-tag entity-tag-judul">JUDUL</span>
                                            <strong class="item-title entity-title-text">{{ $pengumuman->judul }}</strong>
                                        </div>
                                        <div class="entity-row entity-content-row">
                                            <span class="entity-tag entity-tag-isi">ISI</span>
                                            <div class="entity-content-box">
                                                <span class="entity-content-text">{{ Str::limit(strip_tags($pengumuman->isi), 85) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td data-label="Cakupan">
                                    @if($pengumuman->scope_level === 'DESA')
                                        <span class="badge badge-primary">🌐 Tingkat Desa</span>
                                    @else
                                        <span class="badge badge-neutral">🏘️ {{ $pengumuman->dusun->nama_dusun ?? 'Dusun' }}</span>
                                    @endif
                                </td>
                                <td data-label="Tgl Terbit">
                                    {{ $pengumuman->created_at->translatedFormat('d M Y') }}
                                </td>
                                <td data-label="Masa Aktif">
                                    @if($isExpired)
                                        <span class="badge badge-neutral">📁 Arsip (Kedaluwarsa)</span>
                                    @else
                                        <span class="badge badge-success">● Aktif Publik</span>
                                    @endif
                                    <div class="text-muted text-xs">s/d {{ $pengumuman->tanggal_kedaluwarsa->translatedFormat('d M Y') }}</div>
                                </td>
                                <td data-label="Status Rekod">
                                    @if($pengumuman->trashed())
                                        <span class="badge badge-danger">🗑️ Soft Deleted</span>
                                    @else
                                        <span class="badge badge-success">✓ Aktif</span>
                                    @endif
                                </td>
                                <td class="text-right" data-label="Aksi">
                                    <div class="action-buttons">
                                        @if($pengumuman->trashed())
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                onclick="openRestoreModal('{{ route('super-admin.pengumuman.restore', $pengumuman->id) }}', '{{ addslashes($pengumuman->judul) }}')"
                                            >
                                                Pulihkan
                                            </button>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-danger"
                                                onclick="openForceDeleteModal('{{ route('super-admin.pengumuman.force-delete', $pengumuman->id) }}', '{{ addslashes($pengumuman->judul) }}')"
                                            >
                                                Hapus Permanen
                                            </button>
                                        @else
                                            <a href="{{ route('super-admin.pengumuman.edit', $pengumuman->id) }}" class="btn btn-sm btn-outline-primary">
                                                Edit
                                            </a>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="openDeactivateModal('{{ route('super-admin.pengumuman.destroy', $pengumuman->id) }}', '{{ addslashes($pengumuman->judul) }}')"
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
                {{ $pengumumanList->links('partials.pagination') }}
            </div>
        @endif
    </div>
</div>
@endsection
