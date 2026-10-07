@extends('layouts.admin')

@section('title', 'Kelola Fasilitas')
@section('breadcrumb')
    <a href="{{ route('admin-dusun.dashboard') }}">Dashboard</a> / <span>Kelola Fasilitas</span>
@endsection

@section('content')
<div class="admin-page-header flex-between">
    <div>
        <h1 class="admin-page-title">Kelola Fasilitas Dusun</h1>
        <p class="admin-page-desc">Daftar sarana, prasarana, dan fasilitas umum di wilayah {{ $dusun->nama_dusun }}.</p>
    </div>
    <div>
        <a href="{{ route('admin-dusun.fasilitas.create') }}" class="btn btn-primary">
            + Tambah Fasilitas
        </a>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-body p-0">
        @if($fasilitasList->isEmpty())
            <div class="admin-empty-state">
                <div class="empty-icon">📍</div>
                <h3 class="empty-title">Belum ada fasilitas yang terdaftar</h3>
                <p class="empty-desc">Tambahkan fasilitas umum seperti posyandu, balai pertemuan, sarana olahraga, atau tempat ibadah.</p>
                <a href="{{ route('admin-dusun.fasilitas.create') }}" class="btn btn-primary" style="margin-top: 1rem;">
                    + Tambah Fasilitas Pertama
                </a>
            </div>
        @else
            <div class="admin-table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nama Fasilitas</th>
                            <th>Kategori</th>
                            <th>Alamat</th>
                            <th>Koordinat</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fasilitasList as $fasilitas)
                            <tr>
                                <td class="table-thumb-col">
                                    @if($fasilitas->foto_path)
                                        <img src="{{ asset('storage/' . $fasilitas->foto_path) }}" alt="{{ $fasilitas->nama }}" class="table-thumb">
                                    @else
                                        <div class="table-thumb-placeholder">📍</div>
                                    @endif
                                </td>
                                <td>
                                    <strong class="item-title">{{ $fasilitas->nama }}</strong>
                                </td>
                                <td>
                                    <span class="badge badge-primary">{{ $fasilitas->kategoriFasilitas->nama_kategori }}</span>
                                </td>
                                <td>{{ $fasilitas->alamat }}</td>
                                <td>
                                    <span class="coord-badge" title="Titik Lokasi Tersedia">
                                        📍 {{ $fasilitas->latitude }}, {{ $fasilitas->longitude }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <div class="action-buttons">
                                        <a href="{{ route('admin-dusun.fasilitas.edit', $fasilitas->id) }}" class="btn btn-sm btn-outline-primary">
                                            Edit
                                        </a>
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="openDeactivateModal('{{ route('admin-dusun.fasilitas.destroy', $fasilitas->id) }}', '{{ addslashes($fasilitas->nama) }}')"
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
                {{ $fasilitasList->links('partials.pagination') }}
            </div>
        @endif
    </div>
</div>
@endsection
