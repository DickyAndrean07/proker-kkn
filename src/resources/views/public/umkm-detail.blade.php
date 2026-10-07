@extends('layouts.public')

@section('title', $umkm->nama_umkm . ' - UMKM - Portal Informasi')
@push('meta')
    <meta name="description" content="{{ $umkm->nama_umkm }}: {{ $umkm->jenis_usaha }} di {{ $umkm->dusun?->nama_dusun ?? 'Desa Bendung' }}.">
@endpush

@section('content')
<div class="page-public-detail page-umkm-detail">
    <section class="detail-shell" aria-labelledby="umkm-title">
        <div class="container">
            <a href="{{ route('dusun.show', $umkm->dusun_id) }}#umkm" class="detail-back-link">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                <span>Kembali ke UMKM</span>
            </a>

            <div class="detail-layout" data-reveal>
                <figure class="detail-media-card detail-media-feature">
                    <x-partials.media-placeholder
                        :src="$umkm->foto_utama_path"
                        :alt="'Foto ' . $umkm->nama_umkm"
                        class="detail-media-img"
                    />
                    <figcaption class="detail-media-caption">
                        Dokumentasi usaha {{ $umkm->nama_umkm }}
                    </figcaption>
                </figure>

                <article class="detail-main-card">
                    <div class="detail-kicker">
                        <span class="section-badge" aria-hidden="true">UMKM</span>
                        @if($umkm->jenis_usaha)
                            <span class="detail-chip">{{ $umkm->jenis_usaha }}</span>
                        @endif
                    </div>

                    <header class="detail-header-compact">
                        <h1 class="detail-page-title" id="umkm-title">{{ $umkm->nama_umkm }}</h1>
                        <p class="detail-page-subtitle">
                            @if($umkm->dusun)
                                {{ $umkm->dusun->nama_dusun }}
                            @else
                                Informasi UMKM Desa Bendung
                            @endif
                        </p>
                    </header>

                    <dl class="detail-fact-grid">
                        <div class="detail-fact">
                            <dt>Pemilik</dt>
                            <dd>{{ $umkm->nama_pemilik }}</dd>
                        </div>
                        <div class="detail-fact">
                            <dt>Jenis usaha</dt>
                            <dd>{{ $umkm->jenis_usaha }}</dd>
                        </div>
                        @if($umkm->jam_operasional)
                            <div class="detail-fact">
                                <dt>Jam operasional</dt>
                                <dd>{{ $umkm->jam_operasional }}</dd>
                            </div>
                        @endif
                    </dl>

                    @if($umkm->produkUmkms->isNotEmpty())
                        <section class="detail-block" aria-labelledby="produk-title">
                            <h2 class="detail-block-title" id="produk-title">Produk</h2>
                            <ul class="detail-tag-list" aria-label="Produk {{ $umkm->nama_umkm }}">
                                @foreach($umkm->produkUmkms as $p)
                                    <li class="detail-tag">{{ $p->nama_produk }}</li>
                                @endforeach
                            </ul>
                        </section>
                    @endif

                    <div class="detail-action-row">
                        <x-partials.whatsapp-btn :nomor="$umkm->nomor_whatsapp" label="Hubungi via WhatsApp" />

                        @if($umkm->latitude !== null && $umkm->longitude !== null)
                            <x-partials.directions-btn :lat="$umkm->latitude" :lng="$umkm->longitude" label="Petunjuk Arah" />
                        @endif
                    </div>
                </article>
            </div>

            <div class="detail-secondary-grid" data-reveal>
                @if($umkm->deskripsi)
                    <section class="detail-info-panel" aria-labelledby="deskripsi-title">
                        <h2 class="detail-block-title" id="deskripsi-title">Informasi usaha</h2>
                        <p class="detail-reading-text">{{ $umkm->deskripsi }}</p>
                    </section>
                @endif

                @if($umkm->alamat)
                    <section class="detail-info-panel" aria-labelledby="alamat-title">
                        <h2 class="detail-block-title" id="alamat-title">Alamat</h2>
                        <p class="detail-reading-text">{{ $umkm->alamat }}</p>

                        @if($umkm->latitude !== null && $umkm->longitude !== null)
                            <p class="detail-coordinate">{{ $umkm->latitude }}, {{ $umkm->longitude }}</p>
                        @endif
                    </section>
                @endif
            </div>
        </div>
    </section>
</div>
@endsection
