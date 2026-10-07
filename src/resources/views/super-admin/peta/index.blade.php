@extends('layouts.super-admin')

@section('title', 'Peta Persebaran Sarana & Usaha')
@section('breadcrumb')
    <a href="{{ route('super-admin.dashboard') }}">Dashboard</a> / <span>Data / Peta</span>
@endsection

@section('content')
<div class="admin-page-header flex-between">
    <div>
        <h1 class="admin-page-title">Peta Persebaran Sarana & Usaha</h1>
        <p class="admin-page-desc">Proyeksi spasial lokasi fasilitas umum, titik usaha UMKM, dan kontak pelayanan warga.</p>
    </div>
</div>

<!-- Map Filter Controls -->
<div class="admin-card mb-3">
    <div class="admin-card-body" style="padding: 1rem 1.25rem;">
        <form method="GET" action="{{ route('super-admin.data-peta') }}" class="filter-form-bar">
            <div class="filter-group">
                <label for="dusun_id" class="filter-label">Wilayah Dusun:</label>
                <select name="dusun_id" id="dusun_id" class="form-select filter-select" onchange="this.form.submit()">
                    <option value="">-- Seluruh Dusun --</option>
                    @foreach($dusuns as $dusun)
                        <option value="{{ $dusun->id }}" {{ (string)$dusunFilter === (string)$dusun->id ? 'selected' : '' }}>
                            {{ $dusun->nama_dusun }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label for="kategori" class="filter-label">Kategori / Lapisan Data:</label>
                <select name="kategori" id="kategori" class="form-select filter-select" onchange="this.form.submit()">
                    <option value="">-- Semua Lapisan Data --</option>
                    <optgroup label="Klasifikasi Fasilitas Umum">
                        @foreach($kategoriFasilitas as $kat)
                            <option value="fasilitas_{{ $kat->id }}" {{ $kategoriFilter === 'fasilitas_' . $kat->id ? 'selected' : '' }}>
                                Fasilitas: {{ $kat->nama_kategori }}
                            </option>
                        @endforeach
                    </optgroup>
                    <optgroup label="Sektor Lainnya">
                        <option value="umkm" {{ $kategoriFilter === 'umkm' ? 'selected' : '' }}>Usaha Warga (UMKM)</option>
                        <option value="pelayanan" {{ $kategoriFilter === 'pelayanan' ? 'selected' : '' }}>Kontak Pelayanan</option>
                    </optgroup>
                </select>
            </div>

            @if(!empty($dusunFilter) || !empty($kategoriFilter))
                <a href="{{ route('super-admin.data-peta') }}" class="btn btn-sm btn-secondary">Reset Filter</a>
            @endif
        </form>
    </div>
</div>

<!-- Map Container -->
<div class="admin-card">
    <div class="admin-card-body p-0">
        <div id="superAdminMapCanvas" style="height: 620px; width: 100%; border-radius: inherit; z-index: 1;"></div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const markersData = @json($markers);
    const defaultCenter = [-7.9150, 110.5750]; // Titik tengah Desa Bendung

    const map = L.map('superAdminMapCanvas', {
        scrollWheelZoom: false
    }).setView(defaultCenter, 14);

    // ─── Base Tile Layers (Streets & Satellite) ──────────────────
    const streetLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    });

    const satelliteImagery = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Tiles &copy; Esri &mdash; Source: Esri, Maxar, Earthstar Geographics, and GIS User Community',
        maxZoom: 19,
    });

    const satelliteLabels = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
        attribution: '',
        maxZoom: 19,
    });

    const satelliteLayer = L.layerGroup([satelliteImagery, satelliteLabels]);

    // Default: Citra Satelit (selaras dengan tampilan peta desa & dusun)
    satelliteLayer.addTo(map);
    let isSatelliteActive = true;

    // Basemap Switcher Control
    const BasemapSwitcherControl = L.Control.extend({
        options: { position: 'topright' },
        onAdd: function() {
            const container = L.DomUtil.create('div', 'map-floating-actions');
            const btn = L.DomUtil.create('button', 'map-action-btn map-layer-btn active', container);
            btn.type = 'button';
            btn.title = 'Beralih ke Peta Standar (Jalan)';
            btn.setAttribute('aria-label', 'Beralih antara Citra Satelit dan Peta Standar');
            btn.innerHTML = `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/><path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/><path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/></svg><span class="map-layer-text">Satelit</span>`;

            L.DomEvent.disableClickPropagation(container);
            L.DomEvent.disableScrollPropagation(container);

            L.DomEvent.on(btn, 'click', (e) => {
                L.DomEvent.stop(e);
                isSatelliteActive = !isSatelliteActive;
                const textSpan = btn.querySelector('.map-layer-text');
                if (isSatelliteActive) {
                    map.removeLayer(streetLayer);
                    satelliteLayer.addTo(map);
                    btn.classList.add('active');
                    if (textSpan) textSpan.textContent = 'Satelit';
                    btn.title = 'Beralih ke Peta Standar (Jalan)';
                } else {
                    map.removeLayer(satelliteLayer);
                    streetLayer.addTo(map);
                    btn.classList.remove('active');
                    if (textSpan) textSpan.textContent = 'Jalan';
                    btn.title = 'Beralih ke Citra Satelit';
                }
            });

            return container;
        }
    });
    new BasemapSwitcherControl().addTo(map);

    const markerGroup = L.featureGroup();

    markersData.forEach(item => {
        if (!item.latitude || !item.longitude) return;

        const customMarker = L.circleMarker([item.latitude, item.longitude], {
            radius: 8,
            fillColor: item.color || '#2e5e3e',
            color: '#ffffff',
            weight: 2,
            opacity: 1,
            fillOpacity: 0.9
        });

        const popupContent = `
            <div class="map-popup-card">
                <div class="map-popup-type" style="color: ${item.color}; font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">
                    ${item.category}
                </div>
                <h4 style="margin: 0.25rem 0; font-size: 0.95rem; font-weight: 700; color: #1e293b;">
                    ${item.title}
                </h4>
                <div style="font-size: 0.8rem; color: #64748b; margin-bottom: 0.5rem;">
                    Wilayah: <strong>${item.dusun}</strong>
                </div>
                <div style="font-size: 0.75rem; color: #94a3b8; margin-bottom: 0.75rem;">
                    📍 ${item.latitude.toFixed(6)}, ${item.longitude.toFixed(6)}
                </div>
                <a href="${item.edit_url}" style="display: inline-block; background: #0f172a; color: #ffffff; padding: 0.25rem 0.6rem; border-radius: 4px; font-size: 0.75rem; text-decoration: none; font-weight: 600;">
                    Kelola Data →
                </a>
            </div>
        `;

        customMarker.bindPopup(popupContent);
        markerGroup.addLayer(customMarker);
    });

    markerGroup.addTo(map);

    if (markersData.length > 0) {
        map.fitBounds(markerGroup.getBounds(), { padding: [40, 40], maxZoom: 16 });
    }
});
</script>
@endpush
@endsection
