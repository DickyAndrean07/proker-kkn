@props([
    'latitude' => null,
    'longitude' => null,
    'required' => false,
    'mapId' => 'coordPickerMap',
    'latName' => 'latitude',
    'lngName' => 'longitude',
])

<div class="coordinate-picker-component">
    <div class="smart-coord-box">
        <label for="{{ $mapId }}SmartInput" class="smart-coord-label">
            <span class="smart-coord-title">Titik Lokasi</span>
            <span class="smart-coord-helper">Smart Input</span>
        </label>
        <p class="form-hint">Tempel koordinat / link Google Maps, gunakan GPS, atau pilih titik pada peta.</p>
        <div class="smart-coord-controls">
            <input
                type="text"
                id="{{ $mapId }}SmartInput"
                class="form-input"
                placeholder='Contoh: 7&deg;23&apos;56.0&quot;S 112&deg;26&apos;32.5&quot;E atau -7.3988, 112.4423 atau link Maps...'
            >
            <button type="button" class="btn btn-sm btn-primary" id="{{ $mapId }}ApplySmartBtn">
                Terapkan
            </button>
            <button type="button" class="btn btn-sm btn-outline-primary" id="{{ $mapId }}GpsBtn">
                Gunakan GPS
            </button>
        </div>
        <div id="{{ $mapId }}SmartFeedback" class="smart-feedback"></div>
    </div>

    <div class="form-row coordinate-inputs">
        <div class="form-group">
            <label for="{{ $latName }}" class="form-label">
                Latitude @if($required) <span class="required-mark">*</span> @else <span class="optional-tag">(Opsional)</span> @endif
            </label>
            <input
                type="number"
                step="0.000001"
                min="-90"
                max="90"
                name="{{ $latName }}"
                id="{{ $latName }}"
                class="form-input @error($latName) is-invalid @enderror"
                value="{{ old($latName, $latitude) }}"
                placeholder="-7.123456"
                @if($required) required @endif
            >
            @error($latName)
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="{{ $lngName }}" class="form-label">
                Longitude @if($required) <span class="required-mark">*</span> @else <span class="optional-tag">(Opsional)</span> @endif
            </label>
            <input
                type="number"
                step="0.000001"
                min="-180"
                max="180"
                name="{{ $lngName }}"
                id="{{ $lngName }}"
                class="form-input @error($lngName) is-invalid @enderror"
                value="{{ old($lngName, $longitude) }}"
                placeholder="110.123456"
                @if($required) required @endif
            >
            @error($lngName)
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="map-helper-bar">
        <span class="map-instruction">Klik pada peta atau seret pin untuk menentukan titik lokasi koordinat.</span>
        @if(!$required)
            <button type="button" class="btn btn-sm btn-outline-danger" id="{{ $mapId }}ClearBtn">
                Hapus Titik
            </button>
        @endif
    </div>

    <div id="{{ $mapId }}" class="coord-picker-map"></div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const mapElement = document.getElementById('{{ $mapId }}');
    if (!mapElement) return;

    const latInput = document.getElementById('{{ $latName }}');
    const lngInput = document.getElementById('{{ $lngName }}');
    const clearBtn = document.getElementById('{{ $mapId }}ClearBtn');
    const smartInput = document.getElementById('{{ $mapId }}SmartInput');
    const applySmartBtn = document.getElementById('{{ $mapId }}ApplySmartBtn');
    const gpsBtn = document.getElementById('{{ $mapId }}GpsBtn');
    const feedback = document.getElementById('{{ $mapId }}SmartFeedback');

    let initLat = parseFloat(latInput.value);
    let initLng = parseFloat(lngInput.value);

    const hasValidCoords = !isNaN(initLat) && !isNaN(initLng);
    const defaultCenter = hasValidCoords ? [initLat, initLng] : [-7.8341, 110.7185];
    const defaultZoom = hasValidCoords ? 16 : 14;

    if (typeof L !== 'undefined') {
        const pickerMap = L.map('{{ $mapId }}').setView(defaultCenter, defaultZoom);

        // ─── Base Tile Layers (Streets & Satellite) ──────────────────
        const streetLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" rel="noopener">OpenStreetMap</a>',
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

        // Default: Citra Satelit (selaras dengan tampilan peta desa & dusun)
        satelliteLayer.addTo(pickerMap);
        let isSatelliteActive = true;

        // Custom Floating Basemap Switcher Control
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
                        pickerMap.removeLayer(streetLayer);
                        satelliteLayer.addTo(pickerMap);
                        btn.classList.add('active');
                        if (textSpan) textSpan.textContent = 'Satelit';
                        btn.title = 'Beralih ke Peta Standar (Jalan)';
                    } else {
                        pickerMap.removeLayer(satelliteLayer);
                        streetLayer.addTo(pickerMap);
                        btn.classList.remove('active');
                        if (textSpan) textSpan.textContent = 'Jalan';
                        btn.title = 'Beralih ke Citra Satelit';
                    }
                });

                return container;
            }
        });
        new BasemapSwitcherControl().addTo(pickerMap);

        let marker = null;

        function setMarker(lat, lng, zoomLevel) {
            lat = parseFloat(lat);
            lng = parseFloat(lng);
            if (isNaN(lat) || isNaN(lng)) return;

            if (marker) {
                marker.setLatLng([lat, lng]);
            } else {
                marker = L.marker([lat, lng], { draggable: true }).addTo(pickerMap);

                marker.on('dragend', function (e) {
                    const pos = e.target.getLatLng();
                    latInput.value = pos.lat.toFixed(6);
                    lngInput.value = pos.lng.toFixed(6);
                    if (feedback) feedback.innerHTML = `<span style="color: #059669;">ðŸ“ Pin digeser ke: ${pos.lat.toFixed(6)}, ${pos.lng.toFixed(6)}</span>`;
                });
            }
            latInput.value = lat.toFixed(6);
            lngInput.value = lng.toFixed(6);
            pickerMap.setView([lat, lng], zoomLevel || Math.max(pickerMap.getZoom(), 15));
        }

        if (hasValidCoords) {
            setMarker(initLat, initLng);
        }

        pickerMap.on('click', function (e) {
            setMarker(e.latlng.lat, e.latlng.lng);
            if (feedback) feedback.innerHTML = `<span style="color: #059669;">ðŸ“ Titik dipilih di peta: ${e.latlng.lat.toFixed(6)}, ${e.latlng.lng.toFixed(6)}</span>`;
        });

        function updateFromInputs() {
            const lat = parseFloat(latInput.value);
            const lng = parseFloat(lngInput.value);
            if (!isNaN(lat) && !isNaN(lng) && lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
                setMarker(lat, lng);
            }
        }

        latInput.addEventListener('input', updateFromInputs);
        lngInput.addEventListener('input', updateFromInputs);

        // â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
        // SMART PARSER (DMS, Maps URL, Comma-Separated Decimal)
        // â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
        function parseSmartLocation(raw) {
            if (!raw || typeof raw !== 'string') return null;
            let text = raw.trim();

            // 1. DMS Format: e.g. 7°23'56.0"S 112°26'32.5"E or 7°23'56"S, 112°26'32"E
            const dmsRegex = /(\d+(?:\.\d+)?)\s*°\s*(\d+(?:\.\d+)?)\s*['′]?\s*(\d+(?:\.\d+)?)\s*["″]?\s*([NSEWnsew])/g;
            const dmsMatches = [...text.matchAll(dmsRegex)];
            if (dmsMatches.length >= 2) {
                let lat = null, lng = null;
                for (const m of dmsMatches) {
                    const deg = parseFloat(m[1]);
                    const min = parseFloat(m[2]) || 0;
                    const sec = parseFloat(m[3]) || 0;
                    const dir = m[4].toUpperCase();
                    let dec = deg + (min / 60) + (sec / 3600);
                    if (dir === 'S' || dir === 'W') dec = -dec;
                    if (dir === 'N' || dir === 'S') lat = dec;
                    if (dir === 'E' || dir === 'W') lng = dec;
                }
                if (lat !== null && lng !== null) {
                    return { lat, lng, type: 'DMS (Derajat)' };
                }
            }

            // 2. Google Maps URL or query string patterns
            // Matches @-7.123,110.123 or ?q=-7.123,110.123 or destination=-7.123,110.123 or ll=-7.123,110.123
            const urlMatch = text.match(/(?:@|[?&](?:q|destination|ll)=|\/dir\/\/)(-?\d{1,2}\.\d+)[,\s]+(-?\d{1,3}\.\d+)/);
            if (urlMatch) {
                const lat = parseFloat(urlMatch[1]);
                const lng = parseFloat(urlMatch[2]);
                if (!isNaN(lat) && !isNaN(lng) && lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
                    return { lat, lng, type: 'Link Google Maps' };
                }
            }

            // Protobuf URL pattern: !3d-7.12345!4d112.12345
            const protoMatch = text.match(/!3d(-?\d{1,2}\.\d+)!4d(-?\d{1,3}\.\d+)/);
            if (protoMatch) {
                const lat = parseFloat(protoMatch[1]);
                const lng = parseFloat(protoMatch[2]);
                if (!isNaN(lat) && !isNaN(lng) && lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
                    return { lat, lng, type: 'Link Google Maps' };
                }
            }

            // 3. Simple Decimal Format: e.g. -7.400772, 112.452033 or -7.400772 112.452033
            const decMatch = text.match(/^(-?\d{1,2}\.\d+)[,\s/]+(-?\d{1,3}\.\d+)$/);
            if (decMatch) {
                const lat = parseFloat(decMatch[1]);
                const lng = parseFloat(decMatch[2]);
                if (!isNaN(lat) && !isNaN(lng) && lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
                    return { lat, lng, type: 'Koordinat Desimal' };
                }
            }

            return null;
        }

        async function executeSmartParse() {
            if (!smartInput) return;
            const val = smartInput.value.trim();
            if (!val) {
                if (feedback) feedback.innerHTML = '<span style="color: #64748b;">Silakan tempel teks koordinat atau link Maps terlebih dahulu.</span>';
                return;
            }

            // 1. Instant local client-side parse (DMS, direct coords in URL, decimal)
            const parsed = parseSmartLocation(val);
            if (parsed) {
                setMarker(parsed.lat, parsed.lng, 17);
                if (feedback) {
                    feedback.innerHTML = `<span style="color: #059669; font-weight: 600;">✅ Berhasil mengenali format ${parsed.type}: Lat ${parsed.lat.toFixed(6)}, Lng ${parsed.lng.toFixed(6)}</span>`;
                }
                smartInput.style.borderColor = '#10b981';
                return;
            }

            // 2. If it is a Google Maps link or shortlink (maps.app.goo.gl, goo.gl, etc.), resolve via backend
            if (val.includes('maps.app.goo.gl') || val.includes('goo.gl/maps') || val.includes('google.com/maps') || val.startsWith('http://') || val.startsWith('https://')) {
                if (feedback) {
                    feedback.innerHTML = `<span style="color: #2563eb; font-weight: 500;">⏳ Sedang mengurai titik lokasi dari link Google Maps...</span>`;
                }
                smartInput.style.borderColor = '#3b82f6';
                if (applySmartBtn) applySmartBtn.disabled = true;

                try {
                    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                                  document.querySelector('input[name="_token"]')?.value || '';
                    
                    const response = await fetch('{{ route('admin.resolve-coordinate') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ url: val }),
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        setMarker(data.lat, data.lng, 17);
                        if (feedback) {
                            const placeNote = data.place ? ` — <em>${data.place}</em>` : '';
                            feedback.innerHTML = `<span style="color: #059669; font-weight: 600;">✅ Berhasil mengenali ${data.type || 'Google Maps Sharelink'}${placeNote}: Lat ${parseFloat(data.lat).toFixed(6)}, Lng ${parseFloat(data.lng).toFixed(6)}</span>`;
                        }
                        smartInput.style.borderColor = '#10b981';
                        return;
                    } else {
                        throw new Error(data.message || 'Tautan tidak memuat koordinat yang valid.');
                    }
                } catch (err) {
                    if (feedback) {
                        feedback.innerHTML = `<span style="color: #dc2626; font-weight: 500;">⚠️ ${err.message || 'Gagal mengurai tautan Google Maps.'}</span>`;
                    }
                    smartInput.style.borderColor = '#ef4444';
                } finally {
                    if (applySmartBtn) applySmartBtn.disabled = false;
                }
                return;
            }

            // 3. Fallback for unrecognized format
            if (feedback) {
                feedback.innerHTML = `<span style="color: #dc2626; font-weight: 500;">⚠️ Format tidak dikenali. Contoh yang didukung: link sharelok WA/HP (<code>https://maps.app.goo.gl/...</code>), koordinat desimal (<code>-7.3988, 112.4423</code>), atau format derajat (DMS).</span>`;
            }
            smartInput.style.borderColor = '#ef4444';
        }

        if (applySmartBtn) {
            applySmartBtn.addEventListener('click', executeSmartParse);
        }

        if (smartInput) {
            smartInput.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    executeSmartParse();
                }
            });

            // Auto parse on paste with tiny delay
            smartInput.addEventListener('paste', () => {
                setTimeout(executeSmartParse, 50);
            });
        }

        // â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
        // GPS GEOLOCATION BUTTON (FOR MOBILE/FIELD SURVEY)
        // â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
        if (gpsBtn) {
            gpsBtn.addEventListener('click', () => {
                if (!navigator.geolocation) {
                    if (feedback) feedback.innerHTML = '<span style="color: #dc2626;">Perangkat Anda tidak mendukung fitur GPS Browser.</span>';
                    return;
                }

                gpsBtn.disabled = true;
                gpsBtn.innerHTML = 'â³ Mendeteksi GPS...';
                if (feedback) feedback.innerHTML = '<span style="color: #2563eb;">Sedang mencari sinyal GPS akurat... Pastikan izin lokasi diizinkan di HP.</span>';

                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        const acc = Math.round(position.coords.accuracy || 0);

                        setMarker(lat, lng, 18);
                        gpsBtn.disabled = false;
                        gpsBtn.innerHTML = 'ðŸ›°ï¸ Lokasi GPS Saya';
                        if (feedback) {
                            feedback.innerHTML = `<span style="color: #059669; font-weight: 600;">âœ… Lokasi GPS HP berhasil didapatkan! (Akurasi: Â±${acc} meter)</span>`;
                        }
                    },
                    (error) => {
                        gpsBtn.disabled = false;
                        gpsBtn.innerHTML = 'ðŸ›°ï¸ Lokasi GPS Saya';
                        let msg = 'Gagal mendeteksi lokasi GPS.';
                        if (error.code === error.PERMISSION_DENIED) {
                            msg = 'Akses lokasi GPS ditolak oleh browser/HP. Silakan izinkan lokasi di pengaturan browser.';
                        } else if (error.code === error.POSITION_UNAVAILABLE) {
                            msg = 'Sinyal GPS tidak tersedia.';
                        }
                        if (feedback) feedback.innerHTML = `<span style="color: #dc2626;">âš ï¸ ${msg}</span>`;
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            });
        }

        if (clearBtn) {
            clearBtn.addEventListener('click', () => {
                latInput.value = '';
                lngInput.value = '';
                if (smartInput) smartInput.value = '';
                if (feedback) feedback.innerHTML = '';
                if (marker) {
                    pickerMap.removeLayer(marker);
                    marker = null;
                }
            });
        }

        // Force map resize fix
        setTimeout(() => {
            pickerMap.invalidateSize();
        }, 200);
    }
});
</script>
@endpush

