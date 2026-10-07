/**
 * map.js — Leaflet map initialization for Portal Informasi Desa Bendung.
 *
 * Contract:
 *   window.MAP_CONFIG  — injected by Blade via Js::from()
 *   window.MAP_MARKERS — injected by Blade via Js::from()
 *
 * Both values are produced server-side; all strings are safe-encoded via
 * Illuminate\Support\Js::from() before reaching this file.
 *
 * Popup content is built with DOM APIs (textContent / createElement) to
 * prevent XSS from admin-entered strings (name, address, category, etc.).
 */

import L from 'leaflet';

// Fix Leaflet default icon paths when bundled with Vite
import iconRetinaUrl from 'leaflet/dist/images/marker-icon-2x.png';
import iconUrl from 'leaflet/dist/images/marker-icon.png';
import shadowUrl from 'leaflet/dist/images/marker-shadow.png';

delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl,
    iconUrl,
    shadowUrl,
});

// ─── Marker icon factory (High-contrast thematic badges) ──────────────────────

const ICON_CONFIGS = {
    UMKM: {
        color: '#156635',
        title: 'UMKM',
        svg: `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>`,
    },
    PELAYANAN: {
        color: '#c46a3a',
        title: 'Pelayanan Publik',
        svg: `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="21" x2="21" y2="21"/><line x1="3" y1="10" x2="21" y2="10"/><polyline points="5 6 12 3 19 6"/><line x1="4" y1="10" x2="4" y2="21"/><line x1="20" y1="10" x2="20" y2="21"/><line x1="8" y1="14" x2="8" y2="17"/><line x1="12" y1="14" x2="12" y2="17"/><line x1="16" y1="14" x2="16" y2="17"/></svg>`,
    },
    FASILITAS: {
        color: '#A16207',
        title: 'Fasilitas & Titik',
        svg: `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>`,
    },
    DEFAULT: {
        color: '#A16207',
        title: 'Fasilitas & Titik',
        svg: `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>`,
    },
};

function makeIcon(markerType) {
    const cfg = ICON_CONFIGS[markerType] ?? ICON_CONFIGS.DEFAULT;
    const svgHtml = `
        <div style="position:relative;width:38px;height:48px;display:flex;flex-direction:column;align-items:center;cursor:pointer;filter:drop-shadow(0 4px 8px rgba(0,0,0,0.38));transition:transform 0.2s cubic-bezier(0.175,0.885,0.32,1.275);">
            <div style="width:36px;height:36px;border-radius:50%;background:${cfg.color};border:2.5px solid #ffffff;display:flex;align-items:center;justify-content:center;box-shadow:inset 0 1px 2px rgba(255,255,255,0.35);">
                ${cfg.svg}
            </div>
            <div style="width:0;height:0;border-left:7px solid transparent;border-right:7px solid transparent;border-top:10px solid ${cfg.color};margin-top:-2px;"></div>
        </div>`;
    return L.divIcon({
        html: svgHtml,
        className: 'portal-custom-badge-icon',
        iconSize: [38, 48],
        iconAnchor: [19, 46],
        popupAnchor: [0, -46],
        tooltipAnchor: [0, -48],
    });
}

// ─── Coordinate validation helper ─────────────────────────────────────────────

function parseCoordinate(lat, lng) {
    if (lat === null || lat === undefined || lng === null || lng === undefined) {
        return null;
    }
    const numLat = typeof lat === 'number' ? lat : parseFloat(lat);
    const numLng = typeof lng === 'number' ? lng : parseFloat(lng);
    if (
        !Number.isFinite(numLat) ||
        !Number.isFinite(numLng) ||
        numLat < -90 ||
        numLat > 90 ||
        numLng < -180 ||
        numLng > 180
    ) {
        return null;
    }
    return [numLat, numLng];
}

// ─── Popup builder (DOM-based, XSS-safe) ─────────────────────────────────────

function escapeHtml(str) {
    if (!str) return '';
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}

function buildPopup(marker) {
    const wrap = document.createElement('div');
    wrap.className = 'portal-popup-card';
    wrap.style.cssText = 'min-width:215px;max-width:265px;font-family:inherit;padding:2px;';

    // 1. Image or Fallback Media Wrap
    const mediaWrap = document.createElement('div');
    mediaWrap.className = 'portal-popup-media';
    mediaWrap.style.cssText = 'position:relative;width:100%;height:125px;border-radius:10px;overflow:hidden;margin-bottom:9px;background:#e9f0e8;box-shadow:inset 0 0 0 1px rgba(0,0,0,0.06);';

    // Badge float
    const badge = document.createElement('span');
    badge.className = 'portal-popup-badge';
    badge.textContent = marker.category || 'LOKASI';
    badge.style.cssText = 'position:absolute;top:7px;left:7px;z-index:3;padding:3px 8px;border-radius:6px;font-size:0.68rem;font-weight:800;background:rgba(16,38,24,0.88);color:#ffffff;backdrop-filter:blur(4px);letter-spacing:0.02em;box-shadow:0 2px 6px rgba(0,0,0,0.22);';
    mediaWrap.appendChild(badge);

    // Fallback banner element (shows if photo is missing or fails to load)
    const fallbackBanner = document.createElement('div');
    fallbackBanner.style.cssText = `position:absolute;inset:0;display:${marker.photo_url ? 'none' : 'flex'};flex-direction:column;align-items:center;justify-content:center;background:linear-gradient(135deg,#173d28,#256b4c);color:#ffffff;gap:4px;`;
    fallbackBanner.innerHTML = `<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="opacity:0.9;"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg><span style="font-size:0.70rem;font-weight:700;opacity:0.95;">${escapeHtml(marker.name)}</span>`;
    mediaWrap.appendChild(fallbackBanner);

    if (marker.photo_url) {
        const img = document.createElement('img');
        img.src = marker.photo_url;
        img.alt = marker.name || '';
        img.style.cssText = 'position:absolute;inset:0;width:100%;height:100%;object-fit:cover;display:block;';
        img.onerror = () => {
            img.style.display = 'none';
            fallbackBanner.style.display = 'flex';
        };
        mediaWrap.appendChild(img);
    }

    wrap.appendChild(mediaWrap);

    // 2. Name
    const name = document.createElement('strong');
    name.textContent = marker.name;
    name.style.cssText = 'display:block;font-size:0.95rem;font-weight:800;color:#173823;line-height:1.3;margin-bottom:3px;';
    wrap.appendChild(name);

    // 3. Address (optional)
    if (marker.address) {
        const addr = document.createElement('p');
        addr.style.cssText = 'display:flex;align-items:flex-start;gap:5px;font-size:0.76rem;color:#55685c;line-height:1.4;margin:0 0 8px;';
        addr.innerHTML = `<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:2px;color:#256b4c;"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg><span>${escapeHtml(marker.address)}</span>`;
        wrap.appendChild(addr);
    }

    // 4. Action row
    const actions = document.createElement('div');
    actions.style.cssText = 'display:flex;gap:6px;align-items:center;margin-top:7px;padding-top:7px;border-top:1px solid rgba(46,94,62,0.1);';

    if (marker.detail_url) {
        const detailLink = document.createElement('a');
        detailLink.href = marker.detail_url;
        detailLink.textContent = 'Lihat Detail';
        detailLink.style.cssText = 'display:inline-flex;align-items:center;font-size:0.76rem;font-weight:700;color:#ffffff;background:#256b4c;padding:5px 10px;border-radius:6px;text-decoration:none;transition:background 0.15s;';
        detailLink.onmouseover = () => { detailLink.style.background = '#173d28'; };
        detailLink.onmouseout = () => { detailLink.style.background = '#256b4c'; };
        actions.appendChild(detailLink);
    }

    const coords = parseCoordinate(marker.lat, marker.lng);
    if (coords) {
        const [lat, lng] = coords;
        const mapsUrl = `https://www.google.com/maps/dir/?api=1&destination=${encodeURIComponent(lat)},${encodeURIComponent(lng)}`;
        const dirLink = document.createElement('a');
        dirLink.href = mapsUrl;
        dirLink.target = '_blank';
        dirLink.rel = 'noopener noreferrer';
        dirLink.textContent = 'Petunjuk Arah ↗';
        dirLink.style.cssText = 'display:inline-flex;align-items:center;font-size:0.75rem;font-weight:700;color:#1c523a;background:#f4f8f4;padding:5px 9px;border-radius:6px;text-decoration:none;border:1px solid rgba(46,94,62,0.18);';
        actions.appendChild(dirLink);
    }

    if (actions.children.length > 0) {
        wrap.appendChild(actions);
    }

    return wrap;
}

// ─── Viewport focus helper ───────────────────────────────────────────────────

const SINGLE_MARKER_ZOOM = 16;
const DEFAULT_MAX_ZOOM = 16;
const DEFAULT_PADDING = [35, 35];

/**
 * Adjusts the map viewport to fit the given list of markers.
 *
 * @param {L.Map} map - Leaflet map instance
 * @param {Array<Object>} markers - Array of markers with valid coordinates
 * @param {Object} [options]
 * @param {boolean} [options.isInitial=false] - True if this is the initial load
 * @param {boolean} [options.animate=true] - Whether to animate viewport changes
 * @param {number} [options.singleZoom=16] - Zoom level for single marker
 * @param {number} [options.maxZoom=16] - Max zoom level for fitBounds
 * @param {[number, number]} [options.padding=[35, 35]] - Padding around bounds
 */
export function focusMapOnMarkers(map, markers, options = {}) {
    if (!map || !Array.isArray(markers)) return;

    const isInitial = options.isInitial ?? false;
    const animate = options.animate ?? !isInitial;
    const singleZoom = options.singleZoom ?? SINGLE_MARKER_ZOOM;
    const maxZoom = options.maxZoom ?? DEFAULT_MAX_ZOOM;
    const padding = options.padding ?? DEFAULT_PADDING;

    const validCoords = [];
    markers.forEach(m => {
        if (!m) return;
        const coords = parseCoordinate(m.lat, m.lng);
        if (coords) {
            validCoords.push(coords);
        }
    });

    // 0 valid markers:
    // - Initial load: Handled by initial map creation with MAP_CONFIG fallback
    // - Filter change: Retain current viewport, no-op
    if (validCoords.length === 0) {
        return;
    }

    // 1 valid marker: focus with reasonable zoom level
    if (validCoords.length === 1) {
        const [lat, lng] = validCoords[0];
        if (isInitial || !animate) {
            map.setView([lat, lng], singleZoom);
        } else {
            map.flyTo([lat, lng], singleZoom, {
                duration: 0.8,
            });
        }
        return;
    }

    // 2+ valid markers: fitBounds enclosing all markers
    const bounds = L.latLngBounds(validCoords);
    if (bounds.isValid()) {
        const fitOptions = {
            padding,
            maxZoom,
            animate: !isInitial && animate,
        };
        if (!isInitial && animate) {
            fitOptions.duration = 0.8;
        }
        map.fitBounds(bounds, fitOptions);
    }
}

// ─── Core init ────────────────────────────────────────────────────────────────

export function initMap(elementId) {
    const config = window.MAP_CONFIG ?? {};
    const allMarkers = window.MAP_MARKERS ?? [];

    const el = document.getElementById(elementId);
    if (!el) return null;

    // Prevent re-initialization if container already initialized
    if (el._leaflet_id) return null;

    // Center defaults to Jawa Tengah if not provided
    const center = config.center ?? [-7.6298, 110.8603];
    const zoom = config.zoom ?? 13;

    const map = L.map(elementId, {
        center,
        zoom,
        zoomControl: false,
    });

    // ─── Base Tile Layers (Streets & Satellite) ──────────────────────────────
    const streetLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright" rel="noopener">OpenStreetMap</a>',
        maxZoom: 19,
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

    // Initial default layer (Satellite Imagery by default)
    satelliteLayer.addTo(map);
    let isSatelliteActive = true;

    // Fallback message on tile error
    map.on('tileerror', () => {
        const notice = el.querySelector('.map-tile-error');
        if (!notice) {
            const div = document.createElement('div');
            div.className = 'map-tile-error';
            div.style.cssText = 'position:absolute;bottom:8px;left:50%;transform:translateX(-50%);background:rgba(250,247,242,0.9);border-radius:8px;padding:4px 12px;font-size:0.8rem;color:#7a8f6b;z-index:500;pointer-events:none;';
            div.textContent = 'Peta sementara tidak tersedia.';
            el.style.position = 'relative';
            el.appendChild(div);
        }
    });

    let currentVisibleMarkers = [];

    // ─── Custom Floating Map Action Controls (Recenter & Basemap Switcher) ─────
    const MapActionControls = L.Control.extend({
        options: { position: 'topright' },
        onAdd: function() {
            const container = L.DomUtil.create('div', 'map-floating-actions');

            // 1. Recenter Button
            const btnRecenter = L.DomUtil.create('button', 'map-action-btn map-recenter-btn', container);
            btnRecenter.type = 'button';
            btnRecenter.title = 'Pusatkan Ulang Peta';
            btnRecenter.setAttribute('aria-label', 'Pusatkan Ulang Peta');
            btnRecenter.innerHTML = `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3"/></svg>`;

            // 2. Layer Toggle Button (Satellite / Streets) - Active by default
            const btnLayer = L.DomUtil.create('button', 'map-action-btn map-layer-btn active', container);
            btnLayer.type = 'button';
            btnLayer.title = 'Beralih ke Peta Standar (Jalan)';
            btnLayer.setAttribute('aria-label', 'Beralih ke Peta Standar atau Citra Satelit');
            btnLayer.innerHTML = `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/><path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/><path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/></svg>`;

            L.DomEvent.disableClickPropagation(container);
            L.DomEvent.disableScrollPropagation(container);

            L.DomEvent.on(btnRecenter, 'click', (e) => {
                L.DomEvent.stop(e);
                const targetMarkers = (currentVisibleMarkers && currentVisibleMarkers.length > 0)
                    ? currentVisibleMarkers 
                    : allMarkers;
                focusMapOnMarkers(map, targetMarkers, { isInitial: false, animate: true });
            });

            L.DomEvent.on(btnLayer, 'click', (e) => {
                L.DomEvent.stop(e);
                isSatelliteActive = !isSatelliteActive;
                if (isSatelliteActive) {
                    map.removeLayer(streetLayer);
                    satelliteLayer.addTo(map);
                    btnLayer.classList.add('active');
                    btnLayer.title = 'Beralih ke Peta Standar (Jalan)';
                } else {
                    map.removeLayer(satelliteLayer);
                    streetLayer.addTo(map);
                    btnLayer.classList.remove('active');
                    btnLayer.title = 'Beralih ke Citra Satelit';
                }
            });

            return container;
        }
    });

    new MapActionControls().addTo(map);

    // ─── Filters & Dual-Sync Explorer ──────────────────────────────────────────
    const filterDusunEl = document.getElementById(`${elementId}-filter-dusun`);
    const filterCatEl = document.getElementById(`${elementId}-filter-cat`);
    const searchEl = document.getElementById(`${elementId}-search`);
    const carouselEl = document.getElementById(`${elementId}-carousel`);
    const chipButtons = document.querySelectorAll(`[data-map-filter-for="${elementId}"]`);

    let activeMarkers = [];
    let currentCategory = 'semua';

    function normalizeCategory(str) {
        if (!str) return '';
        const txt = document.createElement('textarea');
        txt.innerHTML = String(str);
        return txt.value.trim().toLowerCase();
    }

    function renderMarkers(renderOptions = {}) {
        const isInitial = renderOptions.isInitial ?? false;
        const animate = renderOptions.animate ?? !isInitial;

        // Remove existing markers
        activeMarkers.forEach(m => m.remove());
        activeMarkers = [];

        const selectedDusun = filterDusunEl ? filterDusunEl.value : 'semua';
        const selectedCat = (currentCategory && currentCategory !== 'semua')
            ? currentCategory
            : (filterCatEl && filterCatEl.value ? filterCatEl.value : 'semua');
        const selectedCatNorm = normalizeCategory(selectedCat);
        const searchQuery = searchEl ? searchEl.value.trim().toLowerCase() : '';

        const visibleMarkerData = [];

        allMarkers.forEach(marker => {
            if (selectedDusun !== 'semua' && String(marker.dusun_id) !== String(selectedDusun)) return;
            
            const markerCat = String(marker.category ?? '');
            const markerCatNorm = normalizeCategory(markerCat);
            const markerName = String(marker.name ?? '').toLowerCase();
            const markerType = String(marker.marker_type ?? '').toUpperCase();

            if (selectedCatNorm && selectedCatNorm !== 'semua') {
                let catMatches = false;
                if (selectedCatNorm === 'umkm') {
                    catMatches = markerType === 'UMKM' || markerCatNorm.includes('umkm');
                } else if (selectedCatNorm === 'pelayanan') {
                    catMatches = markerType === 'PELAYANAN' || markerCatNorm.includes('pelayanan');
                } else if (selectedCatNorm === 'fasilitas') {
                    catMatches = markerType === 'FASILITAS' || markerType === 'DEFAULT' || (!markerCatNorm.includes('umkm') && !markerCatNorm.includes('pelayanan') && markerType !== 'UMKM' && markerType !== 'PELAYANAN');
                } else {
                    catMatches = (markerCat === selectedCat || markerCatNorm === selectedCatNorm || markerCatNorm.includes(selectedCatNorm));
                }
                if (!catMatches) return;
            }

            if (searchQuery) {
                const matchSearch = markerName.includes(searchQuery) || 
                                    markerCatNorm.includes(searchQuery) ||
                                    String(marker.address ?? '').toLowerCase().includes(searchQuery);
                if (!matchSearch) return;
            }

            const coords = parseCoordinate(marker.lat, marker.lng);
            if (!coords) return;

            const [lat, lng] = coords;

            const lMarker = L.marker([lat, lng], {
                icon: makeIcon(marker.marker_type),
                title: marker.name,
            });

            lMarker._markerData = marker;

            // Rich interactive popup on click
            lMarker.bindPopup(() => buildPopup(marker), {
                maxWidth: 275,
                minWidth: 220,
                className: 'portal-popup',
                autoPan: false,
            });

            // Dual-sync: clicking pin in map centers & highlights corresponding card in carousel
            lMarker.on('click', () => {
                if (carouselEl) {
                    const card = carouselEl.querySelector(`[data-card-id="${marker.id}"]`) ||
                                 carouselEl.querySelector(`[data-card-name="${CSS.escape(marker.name)}"]`);
                    if (card) {
                        carouselEl.querySelectorAll('.opt2-card').forEach(c => c.classList.remove('selected'));
                        card.classList.add('selected');
                        card.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
                    }
                }
                const zoomLevel = Math.max(map.getZoom(), 16);
                const mapHeight = map.getSize().y || 480;
                const yShift = Math.round(mapHeight * 0.20);
                const projected = map.project(lMarker.getLatLng(), zoomLevel);
                const popupCenterLatLng = map.unproject(projected.subtract([0, yShift]), zoomLevel);
                map.panTo(popupCenterLatLng, { animate: true, duration: 0.4 });
            });

            lMarker.addTo(map);
            activeMarkers.push(lMarker);
            visibleMarkerData.push(marker);
        });

        // Sync carousel cards visibility and empty states
        if (carouselEl) {
            let matchCount = 0;
            carouselEl.querySelectorAll('.opt2-card').forEach(card => {
                const cardCat = (card.getAttribute('data-card-category') || '').trim();
                const cardCatNorm = cardCat.toLowerCase();
                const cardName = (card.getAttribute('data-card-name') || '').toLowerCase();
                const cardType = (card.getAttribute('data-card-type') || '').toUpperCase();
                const cardDusun = card.getAttribute('data-card-dusun') || '';

                let matchDusun = selectedDusun === 'semua' || String(cardDusun) === String(selectedDusun);
                let matchCat = !selectedCatNorm || selectedCatNorm === 'semua';

                if (!matchCat) {
                    if (selectedCatNorm === 'umkm') {
                        matchCat = cardType === 'UMKM' || cardCatNorm.includes('umkm');
                    } else if (selectedCatNorm === 'pelayanan') {
                        matchCat = cardType === 'PELAYANAN' || cardCatNorm.includes('pelayanan');
                    } else if (selectedCatNorm === 'fasilitas') {
                        matchCat = cardType === 'FASILITAS' || (!cardCatNorm.includes('umkm') && !cardCatNorm.includes('pelayanan') && cardType !== 'UMKM' && cardType !== 'PELAYANAN');
                    } else {
                        matchCat = (cardCat === selectedCat || cardCatNorm === selectedCatNorm || cardCatNorm.includes(selectedCatNorm));
                    }
                }

                let matchQuery = !searchQuery || 
                                 cardName.includes(searchQuery) || 
                                 cardCatNorm.includes(searchQuery) ||
                                 (card.getAttribute('data-card-address') || '').toLowerCase().includes(searchQuery);

                if (matchDusun && matchCat && matchQuery) {
                    card.style.display = '';
                    matchCount++;
                } else {
                    card.style.display = 'none';
                    card.classList.remove('selected');
                }
            });

            const emptyNotice = carouselEl.parentElement.querySelector('.explorer-empty-notice');
            if (emptyNotice) {
                emptyNotice.style.display = matchCount === 0 ? 'block' : 'none';
            }
        }

        currentVisibleMarkers = visibleMarkerData;

        // Adjust viewport data-driven
        focusMapOnMarkers(map, visibleMarkerData, { isInitial, animate });
    }

    // Two-way sync: clicking card in carousel flies to marker in map
    if (carouselEl && !carouselEl._hasClickInit) {
        carouselEl._hasClickInit = true;
        carouselEl.addEventListener('click', (e) => {
            const card = e.target.closest('.opt2-card');
            // Allow clicking normal link buttons without triggering flyTo override
            if (!card || e.target.closest('a') || e.target.closest('button')) return;

            const cardId = card.getAttribute('data-card-id');
            const cardName = card.getAttribute('data-card-name');

            carouselEl.querySelectorAll('.opt2-card').forEach(c => c.classList.remove('selected'));
            card.classList.add('selected');

            const targetMarker = activeMarkers.find(m => {
                const d = m._markerData;
                return d && (d.id === cardId || d.name === cardName);
            });

            if (targetMarker) {
                // If marker doesn't have photo_url, try to borrow from card image in DOM
                if (!targetMarker._markerData.photo_url) {
                    const cardImg = card.querySelector('img.opt2-card-img');
                    if (cardImg && cardImg.src) {
                        targetMarker._markerData.photo_url = cardImg.src;
                    }
                }

                // 1. Scroll map into viewport smoothly so user clearly sees the focused marker & photo
                const mapContainer = el.closest('.opt2-map-box') || el;
                if (mapContainer) {
                    const rect = mapContainer.getBoundingClientRect();
                    if (rect.top < 70 || rect.bottom > window.innerHeight) {
                        mapContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }

                // 2. Open popup immediately
                targetMarker.openPopup();

                // 3. Project coordinate and center comfortably so popup & photo are fully in view
                const markerLatLng = targetMarker.getLatLng();
                const zoomLevel = 17;
                const mapHeight = map.getSize().y || 480;
                const yShift = Math.round(mapHeight * 0.20);
                const projected = map.project(markerLatLng, zoomLevel);
                const offsetPoint = projected.subtract([0, yShift]);
                const popupCenterLatLng = map.unproject(offsetPoint, zoomLevel);

                map.flyTo(popupCenterLatLng, zoomLevel, {
                    animate: true,
                    duration: 0.5,
                });
            }
        });
    }

    // Filter selects
    if (filterDusunEl) {
        filterDusunEl.addEventListener('change', () => {
            renderMarkers({ isInitial: false, animate: true });
        });
    }

    if (filterCatEl) {
        filterCatEl.addEventListener('change', () => {
            currentCategory = filterCatEl.value || 'semua';
            // Sync chip buttons if any
            const val = currentCategory.toLowerCase();
            chipButtons.forEach(btn => {
                const btnCat = (btn.getAttribute('data-category') || '').toLowerCase();
                btn.classList.toggle('active', btnCat === val);
            });
            renderMarkers({ isInitial: false, animate: true });
        });
    }

    // Filter chips
    chipButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            chipButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const cat = btn.getAttribute('data-category') || 'semua';
            currentCategory = cat;
            if (filterCatEl) {
                const matchedOption = Array.from(filterCatEl.options).find(o => o.value.toLowerCase() === cat.toLowerCase());
                if (matchedOption) {
                    filterCatEl.value = matchedOption.value;
                }
            }
            renderMarkers({ isInitial: false, animate: true });
        });
    });

    // Search input
    if (searchEl) {
        searchEl.addEventListener('input', () => {
            renderMarkers({ isInitial: false, animate: false });
        });
    }

    // Jump filter triggers (e.g. from Hero quick navigation)
    document.addEventListener('click', (e) => {
        const trigger = e.target.closest('[data-jump-filter]');
        if (trigger) {
            const targetCat = trigger.getAttribute('data-jump-filter');
            const targetBtn = document.querySelector(`[data-map-filter-for="${elementId}"][data-category="${targetCat}"]`);
            if (targetBtn) {
                targetBtn.click();
            } else if (filterCatEl) {
                filterCatEl.value = targetCat;
                renderMarkers({ isInitial: false, animate: true });
            }
        }
    });

    renderMarkers({ isInitial: true, animate: false });

    return map;
}

// Auto-init any element with data-map attribute
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-map]').forEach(el => {
        initMap(el.id);
    });
});

