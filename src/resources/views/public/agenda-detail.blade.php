@extends('layouts.public')

@section('title', $agenda->judul . ' - Agenda - Portal Informasi')
@push('meta')
    <meta name="description" content="Agenda: {{ $agenda->judul }}. {{ $effectiveStatus }}.{{ $agenda->lokasi_text ? ' Lokasi: ' . $agenda->lokasi_text . '.' : '' }}">
@endpush

@section('content')
<div class="page-public-detail page-agenda-detail">
    <section class="detail-shell" aria-labelledby="agenda-title">
        <div class="container">
            @if($agenda->scope_level === 'DUSUN' && $agenda->dusun)
                <a href="{{ route('dusun.show', $agenda->dusun_id) }}#agenda" class="detail-back-link">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    <span>Kembali ke Agenda &amp; Kegiatan</span>
                </a>
            @else
                <a href="{{ route('home') }}#agenda" class="detail-back-link">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    <span>Kembali ke Agenda &amp; Kegiatan</span>
                </a>
            @endif

            @php
                $poster = $agenda->agendaMedias->where('media_role', 'POSTER_AWAL')->first();
                $dokumentasi = $agenda->agendaMedias->where('media_role', 'DOKUMENTASI');
            @endphp

            <div class="detail-layout detail-layout-agenda" data-reveal>
                <figure class="detail-media-card">
                    <div class="detail-media-label">Poster</div>
                    @if($poster)
                        <img
                            src="{{ asset('storage/' . $poster->media_path) }}"
                            alt="Poster {{ $agenda->judul }}"
                            class="detail-media-img"
                            loading="lazy"
                        >
                    @else
                        <x-partials.media-placeholder class="detail-media-img" />
                    @endif
                </figure>

                <article class="detail-main-card">
                    <div class="detail-kicker">
                        <span class="section-badge" aria-hidden="true">Agenda / Kegiatan</span>
                        <x-partials.status-badge :status="$effectiveStatus" />
                    </div>

                    <header class="detail-header-compact">
                        <h1 class="detail-page-title" id="agenda-title">{{ $agenda->judul }}</h1>
                        <p class="detail-page-subtitle">
                            @if($agenda->scope_level === 'DUSUN' && $agenda->dusun)
                                {{ $agenda->dusun->nama_dusun }}
                            @else
                                Agenda Desa
                            @endif
                        </p>
                    </header>

                    <dl class="detail-fact-grid">
                        <div class="detail-fact">
                            <dt>Tanggal mulai</dt>
                            <dd>{{ \Illuminate\Support\Carbon::parse($agenda->tanggal_mulai)->locale('id')->isoFormat('D MMMM YYYY') }}</dd>
                        </div>
                        @if($agenda->tanggal_selesai && $agenda->tanggal_selesai->ne($agenda->tanggal_mulai))
                            <div class="detail-fact">
                                <dt>Tanggal selesai</dt>
                                <dd>{{ \Illuminate\Support\Carbon::parse($agenda->tanggal_selesai)->locale('id')->isoFormat('D MMMM YYYY') }}</dd>
                            </div>
                        @endif
                        @if($agenda->jam)
                            <div class="detail-fact">
                                <dt>Jam</dt>
                                <dd>{{ $agenda->jam }}</dd>
                            </div>
                        @endif
                        @if($agenda->lokasi_text)
                            <div class="detail-fact detail-fact-wide">
                                <dt>Lokasi</dt>
                                <dd>{{ $agenda->lokasi_text }}</dd>
                            </div>
                        @endif
                    </dl>

                    @if($agenda->deskripsi_singkat)
                        <section class="detail-block" aria-labelledby="agenda-desc-title">
                            <h2 class="detail-block-title" id="agenda-desc-title">Deskripsi</h2>
                            <p class="detail-reading-text">{{ $agenda->deskripsi_singkat }}</p>
                        </section>
                    @endif
                </article>
            </div>

            @if($dokumentasi->isNotEmpty())
                <section class="detail-gallery" aria-labelledby="dokumentasi-title" data-reveal>
                    <div class="detail-section-head">
                        <span class="section-badge" aria-hidden="true">Media Kegiatan</span>
                        <h2 class="detail-section-title" id="dokumentasi-title">Dokumentasi</h2>
                    </div>
                    <div class="detail-gallery-grid">
                        @foreach($dokumentasi as $media)
                            <figure class="detail-gallery-item">
                                <img
                                    src="{{ asset('storage/' . $media->media_path) }}"
                                    alt="Dokumentasi {{ $agenda->judul }}"
                                    class="detail-gallery-img"
                                    loading="lazy"
                                >
                            </figure>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </section>
</div>
@endsection
