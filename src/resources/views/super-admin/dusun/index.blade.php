@extends('layouts.super-admin')

@section('title', 'Kelola Dusun')
@section('breadcrumb')
    <a href="{{ route('super-admin.dashboard') }}">Dashboard</a> / <span>Kelola Dusun</span>
@endsection

@section('content')
<div class="admin-page-header flex-between">
    <div>
        <h1 class="admin-page-title">Kelola 6 Wilayah Dusun</h1>
        <p class="admin-page-desc">Daftar seluruh dusun di Desa Bendung beserta status publikasi dan ringkasan data.</p>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-body p-0">
        <div class="admin-table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Nama Dusun</th>
                        <th>Kepala Dusun</th>
                        <th>Wilayah (RT / RW)</th>
                        <th>Status Publik</th>
                        <th>Statistik Data</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dusuns as $dusun)
                        <tr>
                            <td>
                                <strong class="item-title">{{ $dusun->nama_dusun }}</strong>
                                <div class="item-subtitle">{{ Str::limit($dusun->deskripsi_singkat, 45) }}</div>
                            </td>
                            <td>{{ $dusun->nama_kepala_dusun }}</td>
                            <td>{{ $dusun->jumlah_rt }} RT / {{ $dusun->jumlah_rw }} RW</td>
                            <td>
                                @if($dusun->status_dusun === 'ACTIVE')
                                    <span class="badge badge-success">● AKTIF PUBLIK</span>
                                @else
                                    <span class="badge badge-danger">○ NONAKTIF</span>
                                @endif
                            </td>
                            <td>
                                <div class="text-xs text-muted">
                                    <span>📞 {{ $dusun->kontak_pelayanans_count }} Kontak</span> •
                                    <span>🛍️ {{ $dusun->umkms_count }} UMKM</span> •
                                    <span>📍 {{ $dusun->fasilitas_count }} Fasilitas</span>
                                </div>
                                <div class="text-xs text-muted" style="margin-top: 2px;">
                                    <span>📅 {{ $dusun->agenda_kegiatans_count }} Agenda</span> •
                                    <span>📢 {{ $dusun->pengumumans_count }} Pengumuman</span> •
                                    <span>👥 {{ $dusun->admin_accounts_count }} Admin</span>
                                </div>
                            </td>
                            <td class="text-right">
                                <div class="action-buttons">
                                    <a href="{{ route('super-admin.dusun.edit', $dusun->id) }}" class="btn btn-sm btn-outline-primary">
                                        Edit Profil
                                    </a>

                                    @if($dusun->status_dusun === 'ACTIVE')
                                        <form method="POST" action="{{ route('super-admin.dusun.deactivate', $dusun->id) }}" class="inline-form" onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan status publik Dusun {{ addslashes($dusun->nama_dusun) }}? Seluruh halaman dan konten publik dusun ini akan disembunyikan.');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                Deaktivasi
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('super-admin.dusun.activate', $dusun->id) }}" class="inline-form">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary">
                                                Aktivasi Publik
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
