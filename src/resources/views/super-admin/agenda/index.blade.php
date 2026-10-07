@extends('layouts.super-admin')

@section('title', 'Kelola Agenda & Kegiatan')
@section('breadcrumb')
    <a href="{{ route('super-admin.dashboard') }}">Dashboard</a> / <span>Agenda & Kegiatan</span>
@endsection

@section('content')
<div class="admin-page-header flex-between">
    <div>
        <h1 class="admin-page-title">Agenda & Kegiatan (Global)</h1>
        <p class="admin-page-desc">Kelola agenda kegiatan kemasyarakatan tingkat Desa maupun Dusun.</p>
    </div>
    <div>
        <a href="{{ route('super-admin.agenda.create') }}" class="btn btn-primary">
            + Tambah Agenda Baru
        </a>
    </div>
</div>

<!-- Filters Bar -->
<div class="admin-card mb-3">
    <div class="admin-card-body" style="padding: 1rem 1.25rem;">
        <form method="GET" action="{{ route('super-admin.agenda.index') }}" class="filter-form-bar">
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
                <a href="{{ route('super-admin.agenda.index') }}" class="btn btn-sm btn-secondary">Reset Filter</a>
            @endif
        </form>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-body p-0">
        @if($agendaList->isEmpty())
            <div class="admin-empty-state">
                <div class="empty-icon">📅</div>
                <h3 class="empty-title">Belum ada agenda kegiatan</h3>
                <p class="empty-desc">Tidak ada data kegiatan yang sesuai dengan kriteria filter saat ini.</p>
                <a href="{{ route('super-admin.agenda.create') }}" class="btn btn-primary" style="margin-top: 1rem;">
                    + Tambah Agenda Pertama
                </a>
            </div>
        @else
            <div class="admin-table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="min-width: 280px;">Judul & Deskripsi Kegiatan</th>
                            <th>Cakupan & Wilayah</th>
                            <th>Tanggal & Waktu</th>
                            <th>Status Pelaksanaan</th>
                            <th>Status Rekod</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($agendaList as $agenda)
                            @php
                                $lifecycle = $agenda->effectiveStatusFor(now('Asia/Jakarta'));
                                $lifecycleBadgeClass = match($lifecycle) {
                                    'AKAN_DATANG' => 'badge-info',
                                    'BERLANGSUNG' => 'badge-success',
                                    'SELESAI' => 'badge-neutral',
                                    default => 'badge-neutral',
                                };
                                $lifecycleLabel = match($lifecycle) {
                                    'AKAN_DATANG' => 'Akan Datang',
                                    'BERLANGSUNG' => 'Sedang Berlangsung',
                                    'SELESAI' => 'Selesai',
                                    default => $lifecycle,
                                };
                            @endphp
                            <tr class="{{ $agenda->trashed() ? 'row-trashed' : '' }}">
                                <td class="table-lead-col" data-label="Informasi">
                                    <div class="entity-info-block">
                                        <div class="entity-row entity-title-row">
                                            <span class="entity-tag entity-tag-agenda">KEGIATAN</span>
                                            <strong class="item-title entity-title-text">{{ $agenda->judul }}</strong>
                                        </div>
                                        <div class="entity-row entity-content-row">
                                            <span class="entity-tag entity-tag-isi">DESKRIPSI</span>
                                            <div class="entity-content-box agenda-box">
                                                <span class="entity-content-text">{{ Str::limit($agenda->deskripsi_singkat, 85) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td data-label="Cakupan">
                                    @if($agenda->scope_level === 'DESA')
                                        <span class="badge badge-primary">🌐 Tingkat Desa</span>
                                    @else
                                        <span class="badge badge-neutral">🏘️ {{ $agenda->dusun->nama_dusun ?? 'Dusun' }}</span>
                                    @endif
                                </td>
                                <td data-label="Jadwal">
                                    <div>{{ $agenda->tanggal_mulai->translatedFormat('d M Y') }}</div>
                                    @if($agenda->tanggal_selesai && $agenda->tanggal_selesai->ne($agenda->tanggal_mulai))
                                        <div class="text-muted text-xs">s/d {{ $agenda->tanggal_selesai->translatedFormat('d M Y') }}</div>
                                    @endif
                                </td>
                                <td data-label="Pelaksanaan">
                                    <span class="badge {{ $lifecycleBadgeClass }}">{{ $lifecycleLabel }}</span>
                                    @if($agenda->manual_status_override)
                                        <div class="text-muted text-xs">(Manual Override)</div>
                                    @endif
                                </td>
                                <td data-label="Status Rekod">
                                    @if($agenda->trashed())
                                        <span class="badge badge-danger">🗑️ Soft Deleted</span>
                                    @else
                                        <span class="badge badge-success">✓ Aktif</span>
                                    @endif
                                </td>
                                <td class="text-right" data-label="Aksi">
                                    <div class="action-buttons">
                                        @if($agenda->trashed())
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                onclick="openRestoreModal('{{ route('super-admin.agenda.restore', $agenda->id) }}', '{{ addslashes($agenda->judul) }}')"
                                            >
                                                Pulihkan
                                            </button>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-danger"
                                                onclick="openForceDeleteModal('{{ route('super-admin.agenda.force-delete', $agenda->id) }}', '{{ addslashes($agenda->judul) }}')"
                                            >
                                                Hapus Permanen
                                            </button>
                                        @else
                                            <a href="{{ route('super-admin.agenda.edit', $agenda->id) }}" class="btn btn-sm btn-outline-primary">
                                                Edit
                                            </a>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="openDeactivateModal('{{ route('super-admin.agenda.destroy', $agenda->id) }}', '{{ addslashes($agenda->judul) }}')"
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
                {{ $agendaList->links('partials.pagination') }}
            </div>
        @endif
    </div>
</div>
@endsection
