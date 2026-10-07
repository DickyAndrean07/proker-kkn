@props(['label' => 'Belum ada data.'])

<div class="empty-state" role="status">
    <div class="empty-state-icon" aria-hidden="true">
        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8" x2="12" y2="12"/>
            <line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
    </div>
    <p class="empty-state-title">Belum ada data</p>
    <p class="empty-state-text">{{ $label }}</p>
</div>
