@extends('layouts.public')

@section('title', $fasilitas->nama . ' - Fasilitas - Portal Informasi')
@push('meta')
    <meta name="description" content="{{ $fasilitas->nama }}: {{ $fasilitas->kategoriFasilitas?->nama_kategori ?? 'Fasilitas' }} di {{ $fasilitas->dusun?->nama_dusun ?? 'Desa Bendung' }}.">
@endpush

@section('content')
<div class="page-public-detail page-fasilitas-detail">
    <section class="detail-shell" aria-labelledby="fasilitas-title">
        <div class="container">
            <a href="{{ route('dusun.show', $fasilitas->dusun_id) }}#fasilitas" class="detail-back-link">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                <span>Kembali ke Fasilitas</span>
            </a>

            <div class="detail-layout" data-reveal>
                <figure class="detail-media-card">
                    <x-partials.media-placeholder
                        :src="$fasilitas->foto_path"
                        :alt="'Foto ' . $fasilitas->nama"
                        class="detail-media-img"
                    />
                    <figcaption class="detail-media-caption">
                        Dokumentasi fasilitas {{ $fasilitas->nama }}
                    </figcaption>
                </figure>

                <article class="detail-main-card">
                    <div class="detail-kicker">
                        <span class="section-badge" aria-hidden="true">Fasilitas</span>
                        @if($fasilitas->kategoriFasilitas)
                            <span class="detail-chip">{{ $fasilitas->kategoriFasilitas->nama_kategori }}</span>
                        @endif
                    </div>

                    <header class="detail-header-compact">
                        <h1 class="detail-page-title" id="fasilitas-title">{{ $fasilitas->nama }}</h1>
                        <p class="detail-page-subtitle">
                            @if($fasilitas->dusun)
                                {{ $fasilitas->dusun->nama_dusun }}
                            @else
                                Fasilitas publik Desa Bendung
                            @endif
                        </p>
                    </header>

                    <dl class="detail-fact-grid">
                        <div class="detail-fact detail-fact-wide">
                            <dt>Alamat</dt>
                            <dd>{{ $fasilitas->alamat ?? '-' }}</dd>
                        </div>
                        @if($fasilitas->dusun)
                            <div class="detail-fact">
                                <dt>Dusun</dt>
                                <dd>{{ $fasilitas->dusun->nama_dusun }}</dd>
                            </div>
                        @endif
                        @if($fasilitas->latitude !== null && $fasilitas->longitude !== null)
                            <div class="detail-fact">
                                <dt>Koordinat</dt>
                                <dd>{{ $fasilitas->latitude }}, {{ $fasilitas->longitude }}</dd>
                            </div>
                        @endif
                    </dl>

                    @if($fasilitas->deskripsi)
                        <section class="detail-block" aria-labelledby="fasilitas-desc-title">
                            <h2 class="detail-block-title" id="fasilitas-desc-title">Informasi fasilitas</h2>
                            <p class="detail-reading-text">{{ $fasilitas->deskripsi }}</p>
                        </section>
                    @endif

                    <div class="detail-action-row">
                        @if($directionsUrl)
                            <x-partials.directions-btn :lat="$fasilitas->latitude" :lng="$fasilitas->longitude" label="Buka Petunjuk Arah" />
                        @endif

                        @if($fasilitas->nomor_whatsapp ?? null)
                            <x-partials.whatsapp-btn :nomor="$fasilitas->nomor_whatsapp" label="Hubungi via WhatsApp" />
                        @endif
                    </div>

                    @if($directionsUrl)
                        <p class="detail-action-note">Petunjuk arah dibuka melalui aplikasi peta pada perangkat Anda.</p>
                    @endif
                </article>
            </div>
        </div>
    </section>
</div>
@endsection
