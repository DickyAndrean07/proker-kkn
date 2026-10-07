@props(['lat' => null, 'lng' => null, 'label' => 'Buka Petunjuk Arah', 'class' => ''])

@if($lat !== null && $lng !== null)
    @php
        $mapsUrl = 'https://www.google.com/maps/dir/?api=1&destination=' . urlencode($lat . ',' . $lng);
    @endphp
    <a
        href="{{ $mapsUrl }}"
        class="btn-directions {{ $class }}"
        target="_blank"
        rel="noopener noreferrer"
        aria-label="{{ $label }} — membuka Google Maps"
    >
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <polygon points="3 11 22 2 13 21 11 13 3 11"/>
        </svg>
        {{ $label }}
    </a>
@endif
