@extends('layouts.admin')

@section('title', 'Kelola Agenda & Kegiatan')
@section('breadcrumb')
    <a href="{{ route('admin-dusun.dashboard') }}">Dashboard</a> / <span>Agenda & Kegiatan</span>
@endsection

@section('content')
<div class="admin-page-header flex-between">
    <div>
        <h1 class="admin-page-title">Agenda & Kegiatan Dusun</h1>
        <p class="admin-page-desc">Kelola agenda, acara musyawarah, kerja bakti, dan kegiatan warga di Dusun {{ $dusun->nama_dusun }}.</p>
    </div>
    <div>
        <a href="{{ route('admin-dusun.agenda.create') }}" class="btn btn-primary">
            + Tambah Agenda
        </a>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-body p-0">
        @if($agendaList->isEmpty())
            <div class="admin-empty-state">
                <div class="empty-icon">📅</div>
                <h3 class="empty-title">Belum ada agenda kegiatan</h3>
                <p class="empty-desc">Tambahkan jadwal kegiatan atau acara kemasyarakatan di wilayah dusun.</p>
                <a href="{{ route('admin-dusun.agenda.create') }}" class="btn btn-primary" style="margin-top: 1rem;">
                    + Tambah Agenda Pertama
                </a>
            </div>
        @else
            <div class="admin-table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="min-width: 280px;">Judul & Deskripsi Kegiatan</th>
                            <th>Tanggal & Waktu</th>
                            <th>Lokasi</th>
                            <th>Status Pelaksanaan</th>
                            <th>Dokumentasi</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($agendaList as $agenda)
                            @php
                                $status = $agenda->effectiveStatusFor(now('Asia/Jakarta'));
                                $statusBadgeClass = match($status) {
                                    'AKAN_DATANG' => 'badge-info',
                                    'BERLANGSUNG' => 'badge-success',
                                    'SELESAI' => 'badge-neutral',
                                    default => 'badge-neutral',
                                };
                                $statusLabel = match($status) {
                                    'AKAN_DATANG' => 'Akan Datang',
                                    'BERLANGSUNG' => 'Sedang Berlangsung',
                                    'SELESAI' => 'Selesai',
                                    default => $status,
                                };
                            @endphp
                            <tr>
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
                                <td data-label="Jadwal">
                                    <div>{{ $agenda->tanggal_mulai->translatedFormat('d M Y') }}</div>
                                    @if($agenda->tanggal_selesai && $agenda->tanggal_selesai->ne($agenda->tanggal_mulai))
                                        <div class="text-muted text-sm">s/d {{ $agenda->tanggal_selesai->translatedFormat('d M Y') }}</div>
                                    @endif
                                    @if($agenda->jam)
                                        <div class="text-muted text-sm">⏰ {{ substr($agenda->jam, 0, 5) }} WIB</div>
                                    @endif
                                </td>
                                <td data-label="Lokasi">{{ $agenda->lokasi_text }}</td>
                                <td data-label="Pelaksanaan">
                                    <span class="badge {{ $statusBadgeClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                    @if($agenda->manual_status_override)
                                        <div class="text-muted text-xs">(Manual Override)</div>
                                    @endif
                                </td>
                                <td data-label="Dokumentasi">
                                    @if($agenda->agendaMedias->isNotEmpty())
                                        <span class="badge badge-neutral">🖼️ {{ $agenda->agendaMedias->count() }} Media</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-right" data-label="Aksi">
                                    <div class="action-buttons">
                                        <a href="{{ route('admin-dusun.agenda.edit', $agenda->id) }}" class="btn btn-sm btn-outline-primary">
                                            Edit
                                        </a>
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="openDeactivateModal('{{ route('admin-dusun.agenda.destroy', $agenda->id) }}', '{{ addslashes($agenda->judul) }}')"
                                        >
                                            Nonaktifkan
                                        </button>
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
