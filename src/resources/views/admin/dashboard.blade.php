@extends('layouts.admin')

@section('title', 'Dashboard Dusun ' . $dusun->nama_dusun)
@section('breadcrumb', 'Dashboard')

@php
    $dusunData = [
        'Kontak' => $kontakCount,
        'UMKM' => $umkmCount,
        'Fasilitas' => $fasilitasCount,
        'Agenda' => $agendaCount,
        'Pengumuman' => $pengumumanCount,
    ];
    $dusunDataTotal = array_sum($dusunData);
@endphp

@section('content')
<div class="dashboard-shell dashboard-dusun">
    <header class="dashboard-heading">
        <div class="dashboard-heading-copy">
            <p class="dashboard-eyebrow">ADMIN DUSUN</p>
            <h1 class="dashboard-title" aria-label="Dashboard Admin Dusun">Dashboard</h1>
            <p class="dashboard-description">Ringkasan data publik yang dikelola untuk {{ $dusun->nama_dusun }}.</p>
        </div>
        <a href="{{ route('admin-dusun.profil.edit') }}" class="dashboard-heading-action">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/>
            </svg>
            Kelola profil dusun
        </a>
    </header>

    <section class="dashboard-identity" aria-labelledby="dusun-identity-title">
        <div class="dashboard-identity-mark" aria-hidden="true">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="m3 10 9-7 9 7v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2Z"/><path d="M9 22V12h6v10"/>
            </svg>
        </div>
        <div class="dashboard-identity-copy">
            <p class="dashboard-identity-label">Wilayah kerja</p>
            <h2 id="dusun-identity-title">{{ $dusun->nama_dusun }}</h2>
            <p>{{ $dusun->jumlah_rt }} RT <span aria-hidden="true">·</span> {{ $dusun->jumlah_rw }} RW</p>
        </div>
        <span class="dashboard-status {{ $dusun->status_dusun === 'ACTIVE' ? 'is-active' : 'is-inactive' }}">
            <span class="dashboard-status-dot" aria-hidden="true"></span>
            {{ $dusun->status_dusun === 'ACTIVE' ? 'Aktif publik' : 'Nonaktif publik' }}
        </span>
    </section>

    @if($dusun->status_dusun === 'INACTIVE')
        <div class="alert-box alert-warning dashboard-notice" role="status">
            <div class="alert-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
            </div>
            <div>
                <div class="alert-title">Status Wilayah Nonaktif Publik</div>
                <p class="alert-desc">Status: Nonaktif Publik. Dusun Anda saat ini tidak ditampilkan di website publik. Anda tetap dapat mengelola data profil, kontak, UMKM, fasilitas, agenda, dan pengumuman dengan normal.</p>
            </div>
        </div>
    @endif

    <section class="dashboard-kpi-grid dashboard-kpi-grid-dusun" aria-label="Ringkasan data dusun">
        <article class="dashboard-kpi-card">
            <div class="dashboard-kpi-top"><span class="dashboard-kpi-icon" aria-hidden="true"><svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 .12 4.2 2 2 0 0 1 2.1 2h3a2 2 0 0 1 2 1.72 12.8 12.8 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L6.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.8 12.8 0 0 1 2.81.7A2 2 0 0 1 20 16.92Z"/></svg></span><span class="dashboard-kpi-label">Kontak</span></div>
            <strong class="dashboard-kpi-value">{{ $kontakCount }}</strong>
            <a href="{{ route('admin-dusun.kontak.index') }}" class="dashboard-kpi-link">Kelola kontak</a>
        </article>
        <article class="dashboard-kpi-card">
            <div class="dashboard-kpi-top"><span class="dashboard-kpi-icon" aria-hidden="true"><svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg></span><span class="dashboard-kpi-label">UMKM</span></div>
            <strong class="dashboard-kpi-value">{{ $umkmCount }}</strong>
            <a href="{{ route('admin-dusun.umkm.index') }}" class="dashboard-kpi-link">Kelola UMKM</a>
        </article>
        <article class="dashboard-kpi-card">
            <div class="dashboard-kpi-top"><span class="dashboard-kpi-icon" aria-hidden="true"><svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0Z"/><circle cx="12" cy="10" r="3"/></svg></span><span class="dashboard-kpi-label">Fasilitas</span></div>
            <strong class="dashboard-kpi-value">{{ $fasilitasCount }}</strong>
            <a href="{{ route('admin-dusun.fasilitas.index') }}" class="dashboard-kpi-link">Kelola fasilitas</a>
        </article>
        <article class="dashboard-kpi-card">
            <div class="dashboard-kpi-top"><span class="dashboard-kpi-icon" aria-hidden="true"><svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></span><span class="dashboard-kpi-label">Agenda</span></div>
            <strong class="dashboard-kpi-value">{{ $agendaCount }}</strong>
            <a href="{{ route('admin-dusun.agenda.index') }}" class="dashboard-kpi-link">Kelola agenda</a>
        </article>
        <article class="dashboard-kpi-card">
            <div class="dashboard-kpi-top"><span class="dashboard-kpi-icon" aria-hidden="true"><svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg></span><span class="dashboard-kpi-label">Pengumuman</span></div>
            <strong class="dashboard-kpi-value">{{ $pengumumanCount }}</strong>
            <a href="{{ route('admin-dusun.pengumuman.index') }}" class="dashboard-kpi-link">Kelola pengumuman</a>
        </article>
    </section>

    <div class="dashboard-analysis-grid dashboard-analysis-grid-dusun">
        <section class="dashboard-panel dashboard-chart-panel" aria-labelledby="dusun-data-chart-title">
            <div class="dashboard-panel-heading">
                <div>
                    <p class="dashboard-section-kicker">Isi data dusun</p>
                    <h2 id="dusun-data-chart-title">Data yang sudah dikelola</h2>
                    <p>Jumlah entri aktif pada setiap kelompok data.</p>
                </div>
                <span class="dashboard-panel-total"><strong>{{ $dusunDataTotal }}</strong><span>Total entri</span></span>
            </div>
            <div class="dashboard-chart-wrap {{ $dusunDataTotal === 0 ? 'is-empty' : '' }}">
                @if($dusunDataTotal > 0)
                    <canvas
                        data-dashboard-chart="bar"
                        data-chart-labels='@json(array_keys($dusunData))'
                        data-chart-values='@json(array_values($dusunData))'
                        aria-label="Diagram batang distribusi data dusun"
                    ></canvas>
                @else
                    <div class="dashboard-empty-state">
                        <span class="dashboard-empty-icon" aria-hidden="true"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19V5"/><path d="M4 19h16"/><path d="m8 15 3-3 3 2 4-5"/></svg></span>
                        <strong>Belum ada entri data</strong>
                        <p>Tambahkan data dusun melalui menu pengelolaan di bawah.</p>
                    </div>
                @endif
            </div>
            <ul class="dashboard-chart-data-list" aria-label="Jumlah data dusun per kelompok">
                @foreach($dusunData as $label => $value)
                    <li><span><i class="dashboard-data-dot" aria-hidden="true"></i>{{ $label }}</span><strong>{{ $value }}</strong></li>
                @endforeach
            </ul>
        </section>

        <section class="dashboard-panel dashboard-info-panel" aria-labelledby="dusun-info-title">
            <div class="dashboard-panel-heading">
                <div>
                    <p class="dashboard-section-kicker">Informasi publik</p>
                    <h2 id="dusun-info-title">Agenda dan pengumuman</h2>
                    <p>Kelola informasi yang muncul untuk warga.</p>
                </div>
            </div>
            <div class="dashboard-info-list">
                <a href="{{ route('admin-dusun.agenda.index') }}" class="dashboard-info-row">
                    <span class="dashboard-info-icon" aria-hidden="true"><svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></span>
                    <span><strong>Agenda</strong><small>Kegiatan dusun</small></span><b>{{ $agendaCount }}</b>
                </a>
                <a href="{{ route('admin-dusun.pengumuman.index') }}" class="dashboard-info-row">
                    <span class="dashboard-info-icon" aria-hidden="true"><svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg></span>
                    <span><strong>Pengumuman</strong><small>Informasi resmi</small></span><b>{{ $pengumumanCount }}</b>
                </a>
            </div>
        </section>
    </div>

    <section class="dashboard-panel dashboard-actions-panel" aria-labelledby="dusun-actions-title">
        <div class="dashboard-panel-heading dashboard-panel-heading-inline">
            <div>
                <p class="dashboard-section-kicker">Akses kerja</p>
                <h2 id="dusun-actions-title">Tambah data dusun</h2>
                <p>Masuk langsung ke formulir untuk memperbarui isi portal.</p>
            </div>
        </div>
        <div class="dashboard-action-grid">
            <a href="{{ route('admin-dusun.kontak.create') }}" class="dashboard-action-link"><span class="dashboard-action-symbol" aria-hidden="true">+</span><span>Tambah kontak</span></a>
            <a href="{{ route('admin-dusun.umkm.create') }}" class="dashboard-action-link"><span class="dashboard-action-symbol" aria-hidden="true">+</span><span>Tambah UMKM</span></a>
            <a href="{{ route('admin-dusun.fasilitas.create') }}" class="dashboard-action-link"><span class="dashboard-action-symbol" aria-hidden="true">+</span><span>Tambah fasilitas</span></a>
            <a href="{{ route('admin-dusun.agenda.create') }}" class="dashboard-action-link"><span class="dashboard-action-symbol" aria-hidden="true">+</span><span>Tambah agenda</span></a>
            <a href="{{ route('admin-dusun.pengumuman.create') }}" class="dashboard-action-link"><span class="dashboard-action-symbol" aria-hidden="true">+</span><span>Tambah pengumuman</span></a>
        </div>
    </section>

    <section class="dashboard-panel dashboard-profile-panel" aria-labelledby="dusun-profile-title">
        <div class="dashboard-panel-heading dashboard-panel-heading-inline">
            <div>
                <p class="dashboard-section-kicker">Profil wilayah</p>
                <h2 id="dusun-profile-title">Ringkasan profil dusun</h2>
            </div>
            <a href="{{ route('admin-dusun.profil.edit') }}" class="dashboard-text-link">Edit profil</a>
        </div>
        <div class="dashboard-profile-grid">
            <div><span>Nama dusun</span><strong>{{ $dusun->nama_dusun }}</strong></div>
            <div><span>Kepala dusun</span><strong>{{ $dusun->nama_kepala_dusun ?? 'Belum ditentukan' }}</strong></div>
            <div><span>Struktur wilayah</span><strong>{{ $dusun->jumlah_rt }} RT / {{ $dusun->jumlah_rw }} RW</strong></div>
            <div><span>Status wilayah</span><strong>{{ $dusun->status_dusun === 'ACTIVE' ? 'Aktif publik' : 'Nonaktif publik' }}</strong></div>
        </div>
        @if($dusun->deskripsi_singkat)
            <p class="dashboard-profile-description">{{ $dusun->deskripsi_singkat }}</p>
        @endif
    </section>
</div>
@endsection
