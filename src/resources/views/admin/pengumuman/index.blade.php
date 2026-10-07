@extends('layouts.admin')

@section('title', 'Kelola Pengumuman')
@section('breadcrumb')
    <a href="{{ route('admin-dusun.dashboard') }}">Dashboard</a> / <span>Kelola Pengumuman</span>
@endsection

@section('content')
<div class="admin-page-header flex-between">
    <div>
        <h1 class="admin-page-title">Pengumuman Dusun</h1>
        <p class="admin-page-desc">Kelola pengumuman dan informasi penting warga di Dusun {{ $dusun->nama_dusun }}.</p>
    </div>
    <div>
        <a href="{{ route('admin-dusun.pengumuman.create') }}" class="btn btn-primary">
            + Tambah Pengumuman
        </a>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-body p-0">
        @if($pengumumanList->isEmpty())
            <div class="admin-empty-state">
                <div class="empty-icon">📢</div>
                <h3 class="empty-title">Belum ada pengumuman</h3>
                <p class="empty-desc">Terbitkan pengumuman resmi atau pemberitahuan penting untuk warga dusun.</p>
                <a href="{{ route('admin-dusun.pengumuman.create') }}" class="btn btn-primary" style="margin-top: 1rem;">
                    + Tambah Pengumuman Pertama
                </a>
            </div>
        @else
            <div class="admin-table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="min-width: 280px;">Judul & Isi Pengumuman</th>
                            <th>Tanggal Terbit</th>
                            <th>Masa Berlaku (Kedaluwarsa)</th>
                            <th>Status Masa Aktif</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pengumumanList as $pengumuman)
                            @php
                                $isArchived = $pengumuman->isArchivedFor(now('Asia/Jakarta'));
                            @endphp
                            <tr>
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
                                <td data-label="Tgl Terbit">{{ $pengumuman->created_at->translatedFormat('d M Y') }}</td>
                                <td data-label="Masa Berlaku">
                                    {{ $pengumuman->tanggal_kedaluwarsa->translatedFormat('d M Y') }}
                                </td>
                                <td data-label="Status">
                                    @if($isArchived)
                                        <span class="badge badge-neutral">Kedaluwarsa (Arsip)</span>
                                    @else
                                        <span class="badge badge-success">Aktif Publik</span>
                                    @endif
                                </td>
                                <td class="text-right" data-label="Aksi">
                                    <div class="action-buttons">
                                        <a href="{{ route('admin-dusun.pengumuman.edit', $pengumuman->id) }}" class="btn btn-sm btn-outline-primary">
                                            Edit
                                        </a>
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="openDeactivateModal('{{ route('admin-dusun.pengumuman.destroy', $pengumuman->id) }}', '{{ addslashes($pengumuman->judul) }}')"
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
                {{ $pengumumanList->links('partials.pagination') }}
            </div>
        @endif
    </div>
</div>
@endsection
