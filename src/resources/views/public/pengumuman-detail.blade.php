@extends('layouts.public')

@section('title', $pengumuman->judul . ' - Pengumuman - Portal Informasi')
@push('meta')
    <meta name="description" content="Pengumuman: {{ $pengumuman->judul }}. Konteks: {{ $pengumuman->scope_level === 'DUSUN' ? ($pengumuman->dusun?->nama_dusun ?? 'Dusun') : 'Desa' }}.">
@endpush

@section('content')
<div class="page-public-detail page-pengumuman-detail">
    <article class="detail-shell reading-shell" aria-labelledby="pengumuman-title">
        <div class="container">
            @if($pengumuman->scope_level === 'DUSUN' && $pengumuman->dusun)
                <a href="{{ route('dusun.show', $pengumuman->dusun_id) }}#pengumuman" class="detail-back-link">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    <span>Kembali ke Pengumuman</span>
                </a>
            @else
                <a href="{{ route('home') }}#pengumuman" class="detail-back-link">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    <span>Kembali ke Pengumuman</span>
                </a>
            @endif

            <div class="reading-card" data-reveal>
                <header class="reading-header">
                    <div class="detail-kicker">
                        <span class="section-badge" aria-hidden="true">Pengumuman</span>
                        @if($isArchived)
                            <span class="badge badge-arsip">Arsip</span>
                        @else
                            <span class="badge badge-aktif">Aktif</span>
                        @endif
                    </div>

                    <h1 class="detail-page-title" id="pengumuman-title">{{ $pengumuman->judul }}</h1>

                    <dl class="reading-meta">
                        <div>
                            <dt>Konteks</dt>
                            <dd>
                                @if($pengumuman->scope_level === 'DUSUN')
                                    Dusun {{ $pengumuman->dusun?->nama_dusun ?? '-' }}
                                @else
                                    Desa
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt>Status berlaku</dt>
                            <dd>
                                @if($isArchived)
                                    Berakhir {{ \Illuminate\Support\Carbon::parse($pengumuman->tanggal_kedaluwarsa)->locale('id')->isoFormat('D MMMM YYYY') }}
                                @else
                                    Berlaku hingga {{ \Illuminate\Support\Carbon::parse($pengumuman->tanggal_kedaluwarsa)->locale('id')->isoFormat('D MMMM YYYY') }}
                                @endif
                            </dd>
                        </div>
                    </dl>
                </header>

                <section class="reading-content" aria-label="Isi pengumuman">
                    {!! nl2br(e($pengumuman->isi)) !!}
                </section>
            </div>
        </div>
    </article>
</div>
@endsection
