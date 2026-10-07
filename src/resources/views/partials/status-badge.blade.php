@props(['status'])

@php
    $map = [
        'AKAN_DATANG' => ['class' => 'badge-akan-datang', 'label' => 'Akan Datang'],
        'BERLANGSUNG' => ['class' => 'badge-berlangsung', 'label' => 'Berlangsung'],
        'SELESAI'     => ['class' => 'badge-selesai',     'label' => 'Selesai'],
    ];
    $cfg = $map[$status] ?? ['class' => 'badge-selesai', 'label' => $status];
@endphp

<span class="badge {{ $cfg['class'] }}" aria-label="Status: {{ $cfg['label'] }}">
    {{ $cfg['label'] }}
</span>
