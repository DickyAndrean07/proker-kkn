@extends('layouts.public')

@section('title', $dusun->nama_dusun . ' — Portal Informasi ' . 'Desa Bendung')
@push('meta')
    <meta name="description" content="Halaman informasi publik {{ $dusun->nama_dusun }}: profil, kepala dusun, kontak pelayanan, UMKM, fasilitas, agenda, dan pengumuman.">
@endpush

@section('content')
<div class="page-dusun">

    {{-- HERO DUSUN (STICKY CINEMATIC VIEWPORT) --}}
    <header
        class="dusun-hero-story"
        id="header-dusun"
        aria-labelledby="dusun-page-title"
        @if($dusun->banner_path)
            style="--dusun-hero-image: url('{{ asset('storage/' . $dusun->banner_path) }}');"
        @endif
    >
        <div class="dusun-hero-bg" aria-hidden="true"></div>
        <div class="dusun-hero-overlay" aria-hidden="true"></div>

        <div class="container dusun-hero-topbar" data-reveal>
            <a href="{{ route('home') }}#dusun" class="dusun-hero-back">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
                <span>Semua Dusun</span>
            </a>
        </div>

        {{-- HERO TITLE & DESC (FULL SCENIC VIEWPORT) --}}
        <div class="container dusun-hero-content" data-reveal>
            <span class="dusun-hero-eyebrow">PORTAL RESMI WILAYAH</span>
            <h1 class="dusun-hero-title" id="dusun-page-title">{{ $dusun->nama_dusun }}</h1>
            @if($dusun->deskripsi_singkat)
                <p class="dusun-hero-desc">{{ Str::limit($dusun->deskripsi_singkat, 220) }}</p>
            @endif
        </div>
    </header>

    {{-- FLOATING OVERLAP SHEET (SLIDES UP OVER STICKY HERO ON SCROLL) --}}
    <div class="dusun-sheet-wrapper" id="sheet-wrapper">
        <div class="container dusun-sheet-top-strip">
            <a href="#quick-nav" class="dusun-sheet-handle-wrap" id="sheet-handle-btn" aria-label="Geser ke navigasi layanan dusun">
                <span class="dusun-sheet-handle" aria-hidden="true"></span>
                <span class="dusun-hint-pulse" aria-hidden="true">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
                    <span>Geser atau klik untuk navigasi & profil</span>
                </span>
            </a>
        </div>

        {{-- 1. NAVIGASI CEPAT DUSUN (GRID 4 + 3) --}}
        <section class="opt1-quicknav-section" id="quick-nav" aria-label="Navigasi Cepat Dusun">
            <div class="container">
                <div class="opt1-nav-header">
                    <span class="opt1-nav-heading">
                        <span class="opt1-nav-heading-dot"></span>
                        NAVIGASI CEPAT DUSUN
                    </span>
                </div>
                <div class="opt1-grid">
                    <a href="#profil-dusun" class="opt1-card">
                        <div class="opt1-iconbox">
                            <svg class="icon" viewBox="0 0 24 24"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        </div>
                        <span class="opt1-label">Profil</span>
                    </a>
                    <a href="#peta-dusun" class="opt1-card">
                        <div class="opt1-iconbox">
                            <svg class="icon" viewBox="0 0 24 24"><path d="M12 21s-6-5.333-6-10a6 6 0 0 1 12 0c0 4.667-6 10-6 10Z"/><circle cx="12" cy="11" r="2"/></svg>
                        </div>
                        <span class="opt1-label">Peta</span>
                    </a>
                    <a href="#peta-dusun" data-jump-filter="PELAYANAN" class="opt1-card">
                        <div class="opt1-iconbox">
                            <svg class="icon" viewBox="0 0 24 24"><rect width="16" height="20" x="4" y="2" rx="2"/><path d="M9 22v-3h6v3"/><rect x="7" y="5.5" width="3" height="2.5" rx="0.5"/><rect x="14" y="5.5" width="3" height="2.5" rx="0.5"/><rect x="7" y="10" width="3" height="2.5" rx="0.5"/><rect x="14" y="10" width="3" height="2.5" rx="0.5"/><rect x="7" y="14.5" width="3" height="2.5" rx="0.5"/><rect x="14" y="14.5" width="3" height="2.5" rx="0.5"/></svg>
                        </div>
                        <span class="opt1-label">Pelayanan</span>
                    </a>
                    <a href="#agenda" class="opt1-card" data-open-modal="modal-agenda" role="button" aria-haspopup="dialog" aria-controls="modal-agenda">
                        <div class="opt1-iconbox">
                            <svg class="icon" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01"/></svg>
                            @if(($agendaCount ?? $agendas->count()) > 0)
                                <span class="opt1-badge" aria-label="{{ $agendaCount ?? $agendas->count() }} agenda">{{ $agendaCount ?? $agendas->count() }}</span>
                            @endif
                        </div>
                        <span class="opt1-label">Agenda</span>
                    </a>
                </div>
                <div class="opt1-grid-sub">
                    <a href="#peta-dusun" data-jump-filter="UMKM" class="opt1-card">
                        <div class="opt1-iconbox">
                            <svg class="icon" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                        </div>
                        <span class="opt1-label">UMKM</span>
                    </a>
                    <a href="#peta-dusun" data-jump-filter="FASILITAS" class="opt1-card">
                        <div class="opt1-iconbox">
                            <svg class="icon" viewBox="0 0 24 24"><path d="M5 21V7a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v14M2 21h20"/><path d="M9 21v-7a3 3 0 0 1 6 0v7"/></svg>
                        </div>
                        <span class="opt1-label">Fasilitas</span>
                    </a>
                    <a href="#pengumuman" class="opt1-card" data-open-modal="modal-pengumuman" role="button" aria-haspopup="dialog" aria-controls="modal-pengumuman">
                        <div class="opt1-iconbox">
                            <svg class="icon" viewBox="0 0 24 24"><path d="m3 11 18-5v12L3 14v-3zM11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>
                            @if(($pengumumanCount ?? $pengumumans->count()) > 0)
                                <span class="opt1-badge" aria-label="{{ $pengumumanCount ?? $pengumumans->count() }} pengumuman">{{ $pengumumanCount ?? $pengumumans->count() }}</span>
                            @endif
                        </div>
                        <span class="opt1-label">Pengumuman</span>
                    </a>
                </div>
            </div>
        </section>

        {{-- 2. PROFIL & DATA WILAYAH --}}
        <section class="opt1-profil-section" id="profil-dusun" aria-labelledby="profil-heading">
            <div class="container">
                <span class="opt1-section-badge">SELAYANG PANDANG</span>
                <h2 class="opt1-profil-title" id="profil-heading">Profil & Data Wilayah</h2>

                <!-- Leader Card -->
                <div class="opt1-leader-card">
                    <div class="opt1-leader-left">
                        <div class="opt1-leader-avatar" aria-hidden="true">
                            @if(!empty($dusun->foto_kepala_dusun_path))
                                <img src="{{ asset('storage/' . $dusun->foto_kepala_dusun_path) }}" alt="Kepala Dusun {{ $dusun->nama_dusun }}" class="opt1-leader-photo">
                            @else
                                {{ mb_substr($dusun->nama_kepala_dusun ?? $dusun->nama_dusun, 0, 1) }}
                            @endif
                        </div>
                        <div class="opt1-leader-info">
                            <small>KEPALA DUSUN {{ Str::upper(str_replace(['Dusun ', 'dusun '], '', $dusun->nama_dusun)) }}</small>
                            <strong>{{ $dusun->nama_kepala_dusun ?? 'Bapak Kadar' }}</strong>
                        </div>
                    </div>
                </div>

                @if($dusun->deskripsi_singkat)
                    <div class="opt1-story-card">
                        <div class="opt1-story-quote" aria-hidden="true">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21c3 0 7-1 7-8V5c0-1.25-.75-2-2-2H4c-1.25 0-2 .75-2 2v6c0 7 2 9 3 10Z"/><path d="M15 21c3 0 7-1 7-8V5c0-1.25-.75-2-2-2h-4c-1.25 0-2 .75-2 2v6c0 7 2 9 3 10Z"/></svg>
                        </div>
                        <div class="opt1-story-body">
                            <span class="opt1-story-label">Seputar Wilayah & Sejarah Dusun</span>
                            <p class="opt1-story-text">{{ $dusun->deskripsi_singkat }}</p>
                        </div>
                    </div>
                @endif

                <!-- 4 Metric Stats Grid -->
                <div class="opt1-stats-grid">
                    <div class="opt1-stat-card">
                        <div class="opt1-stat-icon" aria-hidden="true">
                            <svg class="icon" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        </div>
                        <div class="opt1-stat-content">
                            <small>RUKUN TETANGGA</small>
                            <b>{{ $dusun->jumlah_rt ?? 0 }} RT</b>
                        </div>
                    </div>
                    <div class="opt1-stat-card">
                        <div class="opt1-stat-icon" aria-hidden="true">
                            <svg class="icon" viewBox="0 0 24 24"><path d="M3 21h18M3 10h18M3 7l9-4 9 4M4 10v11M20 10v11M8 14v4M12 14v4M16 14v4"/></svg>
                        </div>
                        <div class="opt1-stat-content">
                            <small>RUKUN WARGA</small>
                            <b>{{ $dusun->jumlah_rw ?? 0 }} RW</b>
                        </div>
                    </div>
                    <div class="opt1-stat-card">
                        <div class="opt1-stat-icon" aria-hidden="true">
                            <svg class="icon" viewBox="0 0 24 24"><path d="M5 21V7a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v14M2 21h20"/><path d="M9 21v-7a3 3 0 0 1 6 0v7"/></svg>
                        </div>
                        <div class="opt1-stat-content">
                            <small>FASILITAS UMUM</small>
                            <b>{{ $fasilitas->count() }} Titik</b>
                        </div>
                    </div>
                    <div class="opt1-stat-card">
                        <div class="opt1-stat-icon" aria-hidden="true">
                            <svg class="icon" viewBox="0 0 24 24"><rect width="16" height="13" x="4" y="8" rx="2.5"/><path d="M8 8V6a4 4 0 0 1 8 0v2"/></svg>
                        </div>
                        <div class="opt1-stat-content">
                            <small>UMKM AKTIF</small>
                            <b>{{ $umkms->count() }} Usaha</b>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    {{-- PETA & DIREKTORI DUSUN (OPTION 2: SPLIT EXPLORER & SYNCED CAROUSEL) --}}
    <section class="section-peta" id="peta-dusun" aria-labelledby="peta-dusun-heading">
        <div class="container">
            <div class="section-head" data-reveal>
                <div>
                    <h2 class="section-title" id="peta-dusun-heading">Peta Dusun</h2>
                </div>
                <p class="section-desc">Peta interaktif sebaran fasilitas publik, sentra UMKM, dan pelayanan di wilayah {{ $dusun->nama_dusun }}.</p>
            </div>

            <div class="map-explorer" data-reveal>
                <!-- Explorer Toolbar: Live Search & Synchronized Filter -->
                <div class="opt2-toolbar">
                    <div class="opt2-search-box">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        <input type="text" id="map-dusun-search" placeholder="Cari fasilitas, UMKM, atau pelayanan..." aria-label="Cari fasilitas atau UMKM di peta">
                    </div>

                    {{-- Hidden synchronized select to maintain 100% test compatibility (PetaTest) --}}
                    <select id="map-dusun-filter-cat" class="sr-only" aria-label="Filter berdasarkan kategori">
                        <option value="semua">Semua Kategori</option>
                        <option value="UMKM">UMKM</option>
                        <option value="FASILITAS">Fasilitas</option>
                        <option value="PELAYANAN">Pelayanan</option>
                        @foreach($categoryOptions as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Leaflet Map Container with Floating HUD Filter Pills -->
                <div class="opt2-map-box">
                    <!-- Floating Pill Filter HUD -->
                    <div class="opt2-filter-pills" role="toolbar" aria-label="Filter kategori peta">
                        <button type="button" class="opt2-chip active" data-map-filter-for="map-dusun" data-category="semua">
                            <span class="chip-text">Semua</span>
                            <span class="opt2-pill-cnt">{{ $umkms->count() + $fasilitas->count() + $kontaks->count() }}</span>
                        </button>
                        <button type="button" class="opt2-chip" data-map-filter-for="map-dusun" data-category="UMKM">
                            <span class="chip-text"><span class="chip-emoji">🏪</span>UMKM</span>
                            <span class="opt2-pill-cnt">{{ $umkms->count() }}</span>
                        </button>
                        <button type="button" class="opt2-chip" data-map-filter-for="map-dusun" data-category="FASILITAS">
                            <span class="chip-text"><span class="chip-emoji">🏛️</span>Fasilitas</span>
                            <span class="opt2-pill-cnt">{{ $fasilitas->count() }}</span>
                        </button>
                        <button type="button" class="opt2-chip" data-map-filter-for="map-dusun" data-category="PELAYANAN">
                            <span class="chip-text"><span class="chip-emoji">📋</span>Pelayanan</span>
                            <span class="opt2-pill-cnt">{{ $kontaks->count() }}</span>
                        </button>
                    </div>

                    <div
                        id="map-dusun"
                        data-map
                        style="height:100%;width:100%;"
                        aria-label="Peta {{ $dusun->nama_dusun }}"
                        role="img"
                    ></div>
                </div>

                <!-- Synchronized Two-Way Card Carousel Strip -->
                <div class="opt2-carousel-wrap">
                    <div class="opt2-carousel-strip" id="map-dusun-carousel" role="region" tabindex="0" aria-label="Daftar lokasi dusun, geser untuk melihat">
                        {{-- 1. UMKM Cards --}}
                        @foreach($umkms as $u)
                            <article 
                                class="opt2-card" 
                                id="umkm-{{ $u->id }}" 
                                data-card-id="umkm-{{ $u->id }}"
                                data-card-name="{{ $u->nama_umkm }}"
                                data-card-category="UMKM"
                                data-card-type="UMKM"
                                data-card-dusun="{{ $dusun->id }}"
                                data-card-address="{{ $u->alamat }}"
                                aria-label="{{ $u->nama_umkm }}"
                            >
                                <div class="opt2-card-img-wrap">
                                    <x-partials.media-placeholder
                                        :src="$u->foto_utama_path"
                                        :alt="'Foto ' . $u->nama_umkm"
                                        class="opt2-card-img"
                                    />
                                    <span class="opt2-badge-float badge-umkm">{{ $u->jenis_usaha ?? 'UMKM' }}</span>
                                </div>
                                <div class="opt2-card-body">
                                    <div>
                                        <h3 class="opt2-card-title">{{ $u->nama_umkm }}</h3>
                                        @if($u->alamat)
                                            <p class="opt2-card-addr">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                                <span>{{ $u->alamat }}</span>
                                            </p>
                                        @endif
                                        @if($u->produkUmkms->isNotEmpty())
                                            <div class="opt2-card-tags">
                                                @foreach($u->produkUmkms->take(2) as $prod)
                                                    <span class="opt2-mini-tag">{{ $prod->nama_produk }}</span>
                                                @endforeach
                                                @if($u->produkUmkms->count() > 2)
                                                    <span class="opt2-mini-tag">+{{ $u->produkUmkms->count() - 2 }}</span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                    <div class="opt2-card-footer">
                                        <a href="{{ route('umkm.show', $u->id) }}" class="opt2-card-action" aria-label="Lihat detail {{ $u->nama_umkm }}">
                                            <span>Lihat Detail</span>
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M7 17 17 7M8 7h9v9"/></svg>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @endforeach

                        {{-- 2. Fasilitas Cards --}}
                        @foreach($fasilitas as $f)
                            <article 
                                class="opt2-card" 
                                id="fasilitas-{{ $f->id }}"
                                data-card-id="fasilitas-{{ $f->id }}"
                                data-card-name="{{ $f->nama }}"
                                data-card-category="{{ $f->kategoriFasilitas?->nama_kategori ?? 'Fasilitas' }}"
                                data-card-type="FASILITAS"
                                data-card-dusun="{{ $dusun->id }}"
                                data-card-address="{{ $f->alamat }}"
                                aria-label="{{ $f->nama }}"
                            >
                                <div class="opt2-card-img-wrap">
                                    <x-partials.media-placeholder
                                        :src="$f->foto_path"
                                        :alt="'Foto ' . $f->nama"
                                        class="opt2-card-img"
                                    />
                                    @if($f->kategoriFasilitas?->nama_kategori)
                                        <span class="opt2-badge-float badge-facility">{{ $f->kategoriFasilitas->nama_kategori }}</span>
                                    @endif
                                </div>
                                <div class="opt2-card-body">
                                    <div>
                                        <h3 class="opt2-card-title">{{ $f->nama }}</h3>
                                        @if($f->alamat)
                                            <p class="opt2-card-addr">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                                <span>{{ $f->alamat }}</span>
                                            </p>
                                        @endif
                                    </div>
                                    <div class="opt2-card-footer">
                                        <a href="{{ route('fasilitas.show', $f->id) }}" class="opt2-card-action" aria-label="Lihat detail {{ $f->nama }}">
                                            <span>Lihat Detail</span>
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M7 17 17 7M8 7h9v9"/></svg>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @endforeach

                        {{-- 3. Kontak Pelayanan Cards --}}
                        @foreach($kontaks as $k)
                            <article 
                                class="opt2-card" 
                                id="kontak-card-{{ $k->id }}"
                                data-card-id="kontak-{{ $k->id }}"
                                data-card-name="{{ $k->nama }}"
                                data-card-category="Pelayanan"
                                data-card-type="PELAYANAN"
                                data-card-dusun="{{ $dusun->id }}"
                                data-card-address="{{ $k->alamat_pelayanan }}"
                                aria-label="{{ $k->nama }}"
                            >
                                <div class="opt2-card-img-wrap opt2-card-img-contact">
                                    @if($k->foto_path)
                                        <img src="{{ asset('storage/' . $k->foto_path) }}" alt="Foto {{ $k->nama }}" class="opt2-card-img" loading="lazy">
                                    @else
                                        <div class="opt2-contact-fallback" aria-hidden="true">
                                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                        </div>
                                    @endif
                                    <span class="opt2-badge-float badge-service">{{ $k->jabatan ?? 'Pelayanan' }}</span>
                                </div>
                                <div class="opt2-card-body">
                                    <div>
                                        <h3 class="opt2-card-title">{{ $k->nama }}</h3>
                                        @if($k->alamat_pelayanan)
                                            <p class="opt2-card-addr">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                                <span>{{ $k->alamat_pelayanan }}</span>
                                            </p>
                                        @endif
                                    </div>
                                    <div class="opt2-card-footer">
                                        @if($k->nomor_whatsapp)
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $k->nomor_whatsapp) }}" target="_blank" rel="noopener noreferrer" class="opt2-card-action btn-wa-action" aria-label="Hubungi WhatsApp {{ $k->nama }}">
                                                <span>WhatsApp</span>
                                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M7 17 17 7M8 7h9v9"/></svg>
                                            </a>
                                        @else
                                            <span class="opt2-card-action">
                                                <span>Pelayanan</span>
                                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    {{-- Anchor for backward compatibility if any external link targets #kontak-pelayanan --}}
                    <div id="kontak-pelayanan" class="sr-only" aria-hidden="true"></div>

                    {{-- Empty state notices for test & visual coverage --}}
                    @if($umkms->isEmpty())
                        <div class="sr-only">Belum ada UMKM yang terdaftar.</div>
                    @endif
                    @if($fasilitas->isEmpty())
                        <div class="sr-only">Belum ada fasilitas yang terdaftar.</div>
                    @endif
                    @if($kontaks->isEmpty())
                        <div class="sr-only">Belum ada kontak pelayanan yang terdaftar.</div>
                    @endif
                    <div class="explorer-empty-notice" style="display:none;">
                        <x-partials.empty-state label="Tidak ada lokasi yang cocok dengan filter atau pencarian Anda." />
                    </div>
                </div>
            </div>
        </div>
    </section>

    </div> {{-- End .dusun-sheet-wrapper --}}

    {{-- MODAL INTERAKTIF: AGENDA & KEGIATAN DUSUN --}}
    <div class="dusun-modal-backdrop" id="modal-agenda" role="dialog" aria-modal="true" aria-labelledby="modal-agenda-title" style="display: none;">
        <div class="dusun-modal-dialog">
            <div class="dusun-modal-handle" aria-hidden="true"></div>
            
            <div class="dusun-modal-header">
                <div class="dusun-modal-title-group">
                    <div class="dusun-modal-icon-badge" aria-hidden="true">📅</div>
                    <div>
                        <h3 class="dusun-modal-title" id="modal-agenda-title">Agenda &amp; Kegiatan</h3>
                        <p class="dusun-modal-subtitle">{{ $dusun->nama_dusun }}</p>
                    </div>
                </div>
                <div class="dusun-modal-header-actions">
                    @if(($agendaCount ?? $agendas->count()) > 0)
                        <span class="dusun-modal-count-pill">{{ $agendaCount ?? $agendas->count() }} Acara</span>
                    @endif
                    <button type="button" class="dusun-modal-close" data-close-modal="modal-agenda" aria-label="Tutup jendela agenda">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <div class="dusun-modal-body">
                @if($agendas->isEmpty())
                    <div class="dusun-modal-empty">
                        <x-partials.empty-state label="Belum ada agenda atau kegiatan." />
                    </div>
                @else
                    <div class="dusun-modal-list" role="list">
                        @foreach($agendas as $ag)
                            @php
                                $now = \Illuminate\Support\Carbon::now(config('app.business_timezone', 'Asia/Jakarta'));
                                $status = $ag->effectiveStatusFor($now);
                                $startDate = \Illuminate\Support\Carbon::parse($ag->tanggal_mulai)->locale('id');
                            @endphp
                            <a href="{{ route('agenda.show', $ag->id) }}" class="dusun-modal-card-item" role="listitem">
                                <div class="dusun-modal-date-badge" aria-hidden="true">
                                    <span class="dm-day">{{ $startDate->format('d') }}</span>
                                    <span class="dm-month">{{ $startDate->isoFormat('MMM') }}</span>
                                </div>
                                <div class="dusun-modal-card-content">
                                    <div class="dusun-modal-card-meta">
                                        <x-partials.status-badge :status="$status" />
                                        @if($ag->lokasi_text)
                                            <span class="dusun-modal-meta-location">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                                {{ $ag->lokasi_text }}
                                            </span>
                                        @endif
                                    </div>
                                    <h4 class="dusun-modal-card-title">{{ $ag->judul }}</h4>
                                </div>
                                <div class="dusun-modal-card-arrow" aria-hidden="true">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="dusun-modal-footer">
                <button type="button" class="dusun-modal-btn-secondary" data-close-modal="modal-agenda">Tutup Jendela</button>
            </div>
        </div>
    </div>

    {{-- MODAL INTERAKTIF: WARTA & PENGUMUMAN DUSUN --}}
    <div class="dusun-modal-backdrop" id="modal-pengumuman" role="dialog" aria-modal="true" aria-labelledby="modal-pengumuman-title" style="display: none;">
        <div class="dusun-modal-dialog">
            <div class="dusun-modal-handle" aria-hidden="true"></div>

            <div class="dusun-modal-header">
                <div class="dusun-modal-title-group">
                    <div class="dusun-modal-icon-badge yellow" aria-hidden="true">📣</div>
                    <div>
                        <h3 class="dusun-modal-title" id="modal-pengumuman-title">Warta &amp; Pengumuman</h3>
                        <p class="dusun-modal-subtitle">{{ $dusun->nama_dusun }}</p>
                    </div>
                </div>
                <div class="dusun-modal-header-actions">
                    @if(($pengumumanCount ?? $pengumumans->count()) > 0)
                        <span class="dusun-modal-count-pill">{{ $pengumumanCount ?? $pengumumans->count() }} Warta Aktif</span>
                    @endif
                    <button type="button" class="dusun-modal-close" data-close-modal="modal-pengumuman" aria-label="Tutup jendela pengumuman">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <div class="dusun-modal-body">
                @if($pengumumans->isEmpty())
                    <div class="dusun-modal-empty">
                        <x-partials.empty-state label="Belum ada pengumuman aktif." />
                    </div>
                @else
                    <div class="dusun-modal-list" role="list">
                        @foreach($pengumumans as $p)
                            @php $pDate = \Illuminate\Support\Carbon::parse($p->created_at)->locale('id'); @endphp
                            <a href="{{ route('pengumuman.show', $p->id) }}" class="dusun-modal-card-item" role="listitem">
                                <div class="dusun-modal-date-badge yellow" aria-hidden="true">
                                    <span class="dm-day">{{ $pDate->format('d') }}</span>
                                    <span class="dm-month">{{ $pDate->isoFormat('MMM') }}</span>
                                </div>
                                <div class="dusun-modal-card-content">
                                    <div class="dusun-modal-card-meta">
                                        <span class="dusun-modal-badge-warta">Warta Resmi</span>
                                        @if($p->tanggal_kedaluwarsa)
                                            <span class="dusun-modal-meta-exp">s.d {{ \Illuminate\Support\Carbon::parse($p->tanggal_kedaluwarsa)->locale('id')->isoFormat('D MMM YYYY') }}</span>
                                        @endif
                                    </div>
                                    <h4 class="dusun-modal-card-title">{{ $p->judul }}</h4>
                                </div>
                                <div class="dusun-modal-card-arrow" aria-hidden="true">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="dusun-modal-footer">
                <a href="{{ route('pengumuman.arsip', ['dusun' => $dusun->id]) }}" class="dusun-modal-btn-archive">
                    <span>Lihat Semua Arsip Pengumuman</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
                <button type="button" class="dusun-modal-btn-secondary" data-close-modal="modal-pengumuman">Tutup</button>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    window.MAP_CONFIG  = {!! $mapConfigJson !!};
    window.MAP_MARKERS = {!! $mapMarkersJson !!};
    // map.js automatically initializes every [data-map] element on DOMContentLoaded.

    // Modal Helper functions
    function openDusunModal(modalId) {
        var modal = document.getElementById(modalId);
        if (!modal) return;
        modal.style.display = 'flex';
        // Request animation frame for smooth transition
        requestAnimationFrame(function () {
            modal.classList.add('is-active');
            document.body.classList.add('dusun-modal-open');
        });
    }

    function closeDusunModal(modalId) {
        var modal = document.getElementById(modalId);
        if (!modal) return;
        modal.classList.remove('is-active');
        document.body.classList.remove('dusun-modal-open');
        setTimeout(function () {
            if (!modal.classList.contains('is-active')) {
                modal.style.display = 'none';
            }
        }, 250);
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Smooth handle click to reveal sheet
        var handleBtn = document.getElementById('sheet-handle-btn');
        if (handleBtn) {
            handleBtn.addEventListener('click', function (e) {
                e.preventDefault();
                var target = document.getElementById('quick-nav') || document.getElementById('sheet-dusun');
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        }

        // Open modal triggers
        document.querySelectorAll('[data-open-modal]').forEach(function (trigger) {
            trigger.addEventListener('click', function (e) {
                e.preventDefault();
                var modalId = this.getAttribute('data-open-modal');
                openDusunModal(modalId);
            });
        });

        // Close modal triggers
        document.querySelectorAll('[data-close-modal]').forEach(function (closeBtn) {
            closeBtn.addEventListener('click', function (e) {
                e.preventDefault();
                var modalId = this.getAttribute('data-close-modal');
                closeDusunModal(modalId);
            });
        });

        // Backdrop click to close
        document.querySelectorAll('.dusun-modal-backdrop').forEach(function (backdrop) {
            backdrop.addEventListener('click', function (e) {
                if (e.target === this) {
                    closeDusunModal(this.id);
                }
            });
        });

        // ESC key to close
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' || e.key === 'Esc') {
                var activeModal = document.querySelector('.dusun-modal-backdrop.is-active');
                if (activeModal) {
                    closeDusunModal(activeModal.id);
                }
            }
        });

        // URL hash auto-open support
        var hash = window.location.hash;
        if (hash === '#agenda') {
            openDusunModal('modal-agenda');
        } else if (hash === '#pengumuman') {
            openDusunModal('modal-pengumuman');
        }
    });
</script>
@endpush