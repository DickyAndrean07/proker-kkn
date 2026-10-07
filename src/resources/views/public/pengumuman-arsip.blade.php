@extends('layouts.public')

@section('title', 'Arsip Pengumuman - ' . $contextLabel . ' - Portal Informasi')
@push('meta')
    <meta name="description" content="Arsip pengumuman yang telah melewati masa aktif pada konteks {{ $contextLabel }}. Dapat dibaca oleh publik.">
@endpush

@section('content')
<div class="page-public-archive page-pengumuman-archive">
    <section class="archive-shell" aria-labelledby="archive-title">
        <div class="container">
            <a href="{{ $backUrl }}" class="detail-back-link">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                <span>Kembali ke Pengumuman</span>
            </a>

            <header class="archive-header" data-reveal>
                <div>
                    <span class="section-badge" aria-hidden="true">Arsip</span>
                    <h1 class="detail-page-title" id="archive-title">Arsip Pengumuman</h1>
                    <p class="detail-page-subtitle">Pengumuman yang telah melewati masa aktif tetap dapat dibaca di sini.</p>
                </div>

                <div class="archive-filter-card" aria-label="Filter arsip pengumuman">
                    <span class="archive-filter-label">Konteks</span>
                    <strong class="archive-filter-value">{{ $contextLabel }}</strong>
                    @if($contextDusun)
                        <a href="{{ route('pengumuman.arsip') }}" class="archive-filter-link">Lihat arsip Desa</a>
                    @else
                        <a href="{{ route('home') }}#dusun" class="archive-filter-link">Pilih arsip Dusun</a>
                    @endif
                </div>
            </header>

            @if($pengumumans->isEmpty())
                <div class="archive-empty" data-reveal>
                    <x-partials.empty-state label="Belum ada pengumuman yang sudah berakhir masa aktifnya." />
                </div>
            @else
                <div class="archive-list" data-reveal>
                    @foreach($pengumumans as $p)
                        <article class="archive-card" id="arsip-pengumuman-{{ $p->id }}">
                            <div class="archive-date-mark" aria-hidden="true">
                                @php $expiredDate = \Illuminate\Support\Carbon::parse($p->tanggal_kedaluwarsa)->locale('id'); @endphp
                                <span>{{ $expiredDate->format('d') }}</span>
                                <small>{{ $expiredDate->isoFormat('MMM') }}</small>
                            </div>

                            <div class="archive-card-body">
                                <div class="archive-card-meta">
                                    <span class="badge badge-arsip">Arsip</span>
                                    <span>Berakhir {{ \Illuminate\Support\Carbon::parse($p->tanggal_kedaluwarsa)->locale('id')->isoFormat('D MMMM YYYY') }}</span>
                                </div>
                                <h2 class="archive-card-title">
                                    <a href="{{ route('pengumuman.show', $p->id) }}">{{ $p->judul }}</a>
                                </h2>
                                @if($p->isi)
                                    <p class="archive-card-excerpt">{{ Str::limit(strip_tags($p->isi), 120) }}</p>
                                @endif
                            </div>

                            <a href="{{ route('pengumuman.show', $p->id) }}" class="archive-card-link" aria-label="Baca pengumuman {{ $p->judul }}">
                                Baca
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M7 17 17 7M8 7h9v9"/></svg>
                            </a>
                        </article>
                    @endforeach
                </div>

                @if($pengumumans->hasPages())
                    <div class="archive-pagination">
                        {{ $pengumumans->links('partials.pagination') }}
                    </div>
                @endif
            @endif
        </div>
    </section>
</div>
@endsection
