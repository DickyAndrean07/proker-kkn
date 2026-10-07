@extends('layouts.super-admin')

@section('title', 'Dashboard Super Administrator')
@section('breadcrumb')
    <span>Dashboard Super Administrator</span>
@endsection

@php
    $dataDesa = [
        'Kontak' => $stats['kontak_active'],
        'UMKM' => $stats['umkm_active'],
        'Fasilitas' => $stats['fasilitas_active'],
        'Kategori' => $stats['kategori_total'],
    ];
    $totalDataDesa = array_sum($dataDesa);
    $totalInformasi = $stats['agenda_active'] + $stats['pengumuman_active'];
    $totalAkun = $stats['admin_dusun_active'] + $stats['admin_dusun_removed'];
    $activeDusunPercent = $stats['dusun_total'] > 0 ? ($stats['dusun_active'] / $stats['dusun_total']) * 100 : 0;
    $inactiveDusunPercent = $stats['dusun_total'] > 0 ? ($stats['dusun_inactive'] / $stats['dusun_total']) * 100 : 0;
@endphp

@section('content')
<div class="dashboard-shell dashboard-super-admin">
    <header class="dashboard-heading">
        <div class="dashboard-heading-copy">
            <p class="dashboard-eyebrow">SUPER ADMIN</p>
            <h1 class="dashboard-title">Dashboard</h1>
            <p class="dashboard-description">Pusat kendali administrasi global untuk seluruh wilayah di {{ $desa->nama_desa ?? 'Desa Bendung' }}.</p>
        </div>
    </header>

    <section class="dashboard-kpi-grid dashboard-kpi-grid-super" aria-label="Ringkasan statistik Super Admin">
        <article class="dashboard-kpi-card dashboard-kpi-card-featured">
            <div class="dashboard-kpi-top"><span class="dashboard-kpi-icon" aria-hidden="true"><svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M5 21V7l7-4 7 4v14"/><path d="M9 21v-5h6v5"/></svg></span><span class="dashboard-kpi-label">Wilayah</span></div>
            <strong class="dashboard-kpi-value">{{ $stats['dusun_total'] }}</strong>
            <span class="dashboard-kpi-secondary">{{ $stats['dusun_active'] }} aktif <i aria-hidden="true">·</i> {{ $stats['dusun_inactive'] }} nonaktif</span>
            <a href="{{ route('super-admin.dusun.index') }}" class="dashboard-kpi-link">Kelola dusun</a>
        </article>
        <article class="dashboard-kpi-card dashboard-kpi-card-featured">
            <div class="dashboard-kpi-top"><span class="dashboard-kpi-icon" aria-hidden="true"><svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/></svg></span><span class="dashboard-kpi-label">Data desa</span></div>
            <strong class="dashboard-kpi-value">{{ $totalDataDesa }}</strong>
            <span class="dashboard-kpi-secondary">{{ $stats['kategori_total'] }} kategori data</span>
            <a href="{{ route('super-admin.kontak.index') }}" class="dashboard-kpi-link">Kelola data</a>
        </article>
        <article class="dashboard-kpi-card dashboard-kpi-card-featured">
            <div class="dashboard-kpi-top"><span class="dashboard-kpi-icon" aria-hidden="true"><svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/><path d="M8 7h8M8 11h6"/></svg></span><span class="dashboard-kpi-label">Informasi</span></div>
            <strong class="dashboard-kpi-value">{{ $totalInformasi }}</strong>
            <span class="dashboard-kpi-secondary">{{ $stats['agenda_active'] }} agenda <i aria-hidden="true">·</i> {{ $stats['pengumuman_active'] }} pengumuman</span>
            <a href="{{ route('super-admin.agenda.index') }}" class="dashboard-kpi-link">Kelola informasi</a>
        </article>
        <article class="dashboard-kpi-card dashboard-kpi-card-featured">
            <div class="dashboard-kpi-top"><span class="dashboard-kpi-icon" aria-hidden="true"><svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></span><span class="dashboard-kpi-label">Akun admin</span></div>
            <strong class="dashboard-kpi-value">{{ $stats['admin_dusun_active'] }}</strong>
            <span class="dashboard-kpi-secondary">{{ $stats['admin_dusun_removed'] }} akun dihapus</span>
            <a href="{{ route('super-admin.admin-dusun.index') }}" class="dashboard-kpi-link">Kelola akun admin</a>
        </article>
    </section>

    <div class="dashboard-analysis-grid dashboard-analysis-grid-super">
        <section class="dashboard-panel dashboard-chart-panel" aria-labelledby="desa-data-chart-title">
            <div class="dashboard-panel-heading">
                <div>
                    <p class="dashboard-section-kicker">Ringkasan data desa</p>
                    <h2 id="desa-data-chart-title">Data berdasarkan kategori</h2>
                    <p>Perbandingan jumlah data aktif di seluruh wilayah.</p>
                </div>
                <span class="dashboard-panel-total"><strong>{{ $totalDataDesa }}</strong><span>Total data</span></span>
            </div>
            <div class="dashboard-chart-wrap {{ $totalDataDesa === 0 ? 'is-empty' : '' }}">
                @if($totalDataDesa > 0)
                    <canvas
                        data-dashboard-chart="bar"
                        data-chart-labels='@json(array_keys($dataDesa))'
                        data-chart-values='@json(array_values($dataDesa))'
                        aria-label="Diagram batang data desa berdasarkan kategori"
                    ></canvas>
                @else
                    <div class="dashboard-empty-state">
                        <span class="dashboard-empty-icon" aria-hidden="true"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19V5"/><path d="M4 19h16"/><path d="m8 15 3-3 3 2 4-5"/></svg></span>
                        <strong>Belum ada data desa</strong>
                        <p>Data akan terlihat setelah entri tersedia pada modul terkait.</p>
                    </div>
                @endif
            </div>
            <ul class="dashboard-chart-data-list dashboard-chart-data-list-four" aria-label="Jumlah data per kategori">
                @foreach($dataDesa as $label => $value)
                    <li><span><i class="dashboard-data-dot" aria-hidden="true"></i>{{ $label }}</span><strong>{{ $value }}</strong></li>
                @endforeach
            </ul>
        </section>

        <section class="dashboard-panel dashboard-donut-panel" aria-labelledby="data-composition-title">
            <div class="dashboard-panel-heading">
                <div>
                    <p class="dashboard-section-kicker">Komposisi data</p>
                    <h2 id="data-composition-title">Sebaran kategori</h2>
                    <p>Proporsi dari total data desa.</p>
                </div>
            </div>
            @if($totalDataDesa > 0)
                <div class="dashboard-donut-layout">
                    <div class="dashboard-donut-wrap">
                        <canvas
                            data-dashboard-chart="doughnut"
                            data-chart-labels='@json(array_keys($dataDesa))'
                            data-chart-values='@json(array_values($dataDesa))'
                            aria-label="Diagram donat komposisi data desa"
                        ></canvas>
                        <span class="dashboard-donut-center"><strong>{{ $totalDataDesa }}</strong><small>Total</small></span>
                    </div>
                    <ul class="dashboard-legend-list">
                        @foreach($dataDesa as $label => $value)
                            <li><span><i class="dashboard-data-dot" aria-hidden="true"></i>{{ $label }}</span><strong>{{ number_format(($value / $totalDataDesa) * 100, 1, ',', '.') }}%</strong><small>{{ $value }}</small></li>
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="dashboard-empty-state dashboard-empty-state-compact">
                    <span class="dashboard-empty-icon" aria-hidden="true"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M8 12h8"/></svg></span>
                    <strong>Belum ada komposisi data</strong>
                    <p>Diagram akan muncul setelah data tersedia.</p>
                </div>
            @endif
        </section>
    </div>

    <div class="dashboard-secondary-grid">
        <section class="dashboard-panel" aria-labelledby="public-info-title">
            <div class="dashboard-panel-heading dashboard-panel-heading-inline">
                <div>
                    <p class="dashboard-section-kicker">Informasi publik</p>
                    <h2 id="public-info-title">Agenda dan pengumuman</h2>
                </div>
                <span class="dashboard-panel-total"><strong>{{ $totalInformasi }}</strong><span>Total</span></span>
            </div>
            <div class="dashboard-info-list dashboard-info-list-two">
                <a href="{{ route('super-admin.agenda.index') }}" class="dashboard-info-row">
                    <span class="dashboard-info-icon" aria-hidden="true"><svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></span>
                    <span><strong>Agenda</strong><small>Kelola kegiatan</small></span><b>{{ $stats['agenda_active'] }}</b>
                </a>
                <a href="{{ route('super-admin.pengumuman.index') }}" class="dashboard-info-row">
                    <span class="dashboard-info-icon" aria-hidden="true"><svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg></span>
                    <span><strong>Pengumuman</strong><small>Kelola informasi resmi</small></span><b>{{ $stats['pengumuman_active'] }}</b>
                </a>
            </div>
        </section>

        <section class="dashboard-panel dashboard-account-panel" aria-labelledby="account-status-title">
            <div class="dashboard-panel-heading dashboard-panel-heading-inline">
                <div>
                    <p class="dashboard-section-kicker">Akun pengelola</p>
                    <h2 id="account-status-title">Status akun admin</h2>
                </div>
                <a href="{{ route('super-admin.admin-dusun.index') }}" class="dashboard-text-link">Kelola akun</a>
            </div>
            @if($totalAkun > 0)
                <div class="dashboard-account-summary">
                    <div class="dashboard-account-ring" aria-hidden="true" style="--ring-active: {{ $totalAkun > 0 ? ($stats['admin_dusun_active'] / $totalAkun) * 100 : 0 }}%"></div>
                    <div><strong>{{ $stats['admin_dusun_active'] }}</strong><span>Admin aktif</span></div>
                    <ul class="dashboard-legend-list dashboard-account-legend">
                        <li><span><i class="dashboard-data-dot" aria-hidden="true"></i>Admin aktif</span><strong>{{ $stats['admin_dusun_active'] }}</strong></li>
                        <li><span><i class="dashboard-data-dot is-muted" aria-hidden="true"></i>Akun dihapus</span><strong>{{ $stats['admin_dusun_removed'] }}</strong></li>
                    </ul>
                </div>
            @else
                <div class="dashboard-empty-state dashboard-empty-state-compact"><strong>Belum ada akun admin dusun</strong><p>Status akun akan terlihat setelah akun tersedia.</p></div>
            @endif
        </section>
    </div>

    <section class="dashboard-panel dashboard-region-panel" aria-labelledby="region-status-title">
        <div class="dashboard-panel-heading dashboard-panel-heading-inline">
            <div>
                <p class="dashboard-section-kicker">Wilayah</p>
                <h2 id="region-status-title">Status dusun</h2>
                <p>Perbandingan dusun yang aktif dan nonaktif di portal publik.</p>
            </div>
            <a href="{{ route('super-admin.dusun.index') }}" class="dashboard-text-link">Kelola dusun</a>
        </div>
        @if($stats['dusun_total'] > 0)
            <div class="dashboard-segmented-bar" aria-label="{{ $stats['dusun_active'] }} dusun aktif dan {{ $stats['dusun_inactive'] }} dusun nonaktif">
                <span class="dashboard-segment-active" style="width: {{ $activeDusunPercent }}%"></span>
                <span class="dashboard-segment-inactive" style="width: {{ $inactiveDusunPercent }}%"></span>
            </div>
            <div class="dashboard-region-legend">
                <span><i class="dashboard-data-dot" aria-hidden="true"></i>{{ $stats['dusun_active'] }} aktif</span>
                <span><i class="dashboard-data-dot is-muted" aria-hidden="true"></i>{{ $stats['dusun_inactive'] }} nonaktif</span>
            </div>
        @else
            <div class="dashboard-empty-state dashboard-empty-state-compact"><strong>Belum ada dusun terdaftar</strong><p>Tambahkan wilayah melalui menu Kelola Dusun.</p></div>
        @endif
    </section>

    <section class="dashboard-panel dashboard-management-panel" aria-labelledby="management-title">
        <div class="dashboard-panel-heading">
            <div>
                <p class="dashboard-section-kicker">Pusat pengelolaan</p>
                <h2 id="management-title">Area manajemen</h2>
                <p>Akses cepat ke seluruh modul administrasi global.</p>
            </div>
        </div>
        <div class="dashboard-management-grid">
            <a href="{{ route('super-admin.desa.edit') }}" aria-label="1. Identitas Desa" class="dashboard-management-link"><span class="dashboard-management-index">1</span><span><strong>Identitas Desa</strong><small>Profil dan informasi kantor desa</small></span></a>
            <a href="{{ route('super-admin.dusun.index') }}" aria-label="2. Kelola Dusun" class="dashboard-management-link"><span class="dashboard-management-index">2</span><span><strong>Kelola Dusun</strong><small>Profil, aktivasi, dan RT/RW</small></span></a>
            <a href="{{ route('super-admin.kontak.index') }}" aria-label="3. Kontak Pelayanan" class="dashboard-management-link"><span class="dashboard-management-index">3</span><span><strong>Kontak Pelayanan</strong><small>Kontak perangkat desa dan dusun</small></span></a>
            <a href="{{ route('super-admin.umkm.index') }}" aria-label="4. Kelola UMKM" class="dashboard-management-link"><span class="dashboard-management-index">4</span><span><strong>Kelola UMKM</strong><small>Katalog usaha warga</small></span></a>
            <a href="{{ route('super-admin.fasilitas.index') }}" aria-label="5. Kelola Fasilitas" class="dashboard-management-link"><span class="dashboard-management-index">5</span><span><strong>Kelola Fasilitas</strong><small>Sarana umum dan koordinat</small></span></a>
            <a href="{{ route('super-admin.kategori-fasilitas.index') }}" aria-label="6. Kategori Fasilitas" class="dashboard-management-link"><span class="dashboard-management-index">6</span><span><strong>Kategori Fasilitas</strong><small>Master klasifikasi fasilitas</small></span></a>
            <a href="{{ route('super-admin.agenda.index') }}" aria-label="7. Agenda & Kegiatan" class="dashboard-management-link"><span class="dashboard-management-index">7</span><span><strong>Agenda &amp; Kegiatan</strong><small>Kegiatan desa dan dusun</small></span></a>
            <a href="{{ route('super-admin.pengumuman.index') }}" aria-label="8. Pengumuman" class="dashboard-management-link"><span class="dashboard-management-index">8</span><span><strong>Pengumuman</strong><small>Informasi resmi dan masa aktif</small></span></a>
            <a href="{{ route('super-admin.data-peta') }}" aria-label="9. Data / Peta" class="dashboard-management-link"><span class="dashboard-management-index">9</span><span><strong>Data / Peta</strong><small>Persebaran data fasilitas dan UMKM</small></span></a>
            <a href="{{ route('super-admin.admin-dusun.index') }}" aria-label="10. Admin Dusun" class="dashboard-management-link"><span class="dashboard-management-index">10</span><span><strong>Admin Dusun</strong><small>Akun pengelola dan penugasan wilayah</small></span></a>
        </div>
    </section>
</div>
@endsection
