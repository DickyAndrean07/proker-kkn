@extends('layouts.admin')

@section('title', 'Kelola UMKM')
@section('breadcrumb')
    <a href="{{ route('admin-dusun.dashboard') }}">Dashboard</a> / <span>Kelola UMKM</span>
@endsection

@section('content')
<div class="admin-page-header flex-between">
    <div>
        <h1 class="admin-page-title">Kelola Usaha Mikro & Kecil (UMKM)</h1>
        <p class="admin-page-desc">Daftar usaha lokal warga di wilayah {{ $dusun->nama_dusun }}.</p>
    </div>
    <div>
        <a href="{{ route('admin-dusun.umkm.create') }}" class="btn btn-primary">
            + Tambah UMKM
        </a>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-body p-0">
        @if($umkmList->isEmpty())
            <div class="admin-empty-state">
                <div class="empty-icon">🏪</div>
                <h3 class="empty-title">Belum ada UMKM yang terdaftar</h3>
                <p class="empty-desc">Tambahkan data usaha warga untuk mempromosikan produk lokal dusun.</p>
                <a href="{{ route('admin-dusun.umkm.create') }}" class="btn btn-primary" style="margin-top: 1rem;">
                    + Tambah UMKM Pertama
                </a>
            </div>
        @else
            <div class="admin-table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nama UMKM</th>
                            <th>Pemilik</th>
                            <th>Jenis Usaha</th>
                            <th>Produk Unggulan</th>
                            <th>WhatsApp</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($umkmList as $umkm)
                            <tr>
                                <td class="table-thumb-col">
                                    @if($umkm->foto_utama_path)
                                        <img src="{{ asset('storage/' . $umkm->foto_utama_path) }}" alt="{{ $umkm->nama_umkm }}" class="table-thumb">
                                    @else
                                        <div class="table-thumb-placeholder">🏪</div>
                                    @endif
                                </td>
                                <td>
                                    <strong class="item-title">{{ $umkm->nama_umkm }}</strong>
                                    <div class="item-subtitle">{{ Str::limit($umkm->alamat, 40) }}</div>
                                </td>
                                <td>{{ $umkm->nama_pemilik }}</td>
                                <td><span class="badge badge-neutral">{{ $umkm->jenis_usaha }}</span></td>
                                <td>
                                    @if($umkm->produkUmkms->isNotEmpty())
                                        <span class="product-tag-list">
                                            {{ $umkm->produkUmkms->pluck('nama_produk')->join(', ') }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($umkm->nomor_whatsapp)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $umkm->nomor_whatsapp) }}" target="_blank" rel="noopener" class="wa-link">
                                            💬 {{ $umkm->nomor_whatsapp }}
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <div class="action-buttons">
                                        <a href="{{ route('admin-dusun.umkm.edit', $umkm->id) }}" class="btn btn-sm btn-outline-primary">
                                            Edit
                                        </a>
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="openDeactivateModal('{{ route('admin-dusun.umkm.destroy', $umkm->id) }}', '{{ addslashes($umkm->nama_umkm) }}')"
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
                {{ $umkmList->links('partials.pagination') }}
            </div>
        @endif
    </div>
</div>
@endsection
