@props(['status' => 'pendente'])

@php
    $class = match(strtolower($status)) {
        'confirmado' => 'cc-badge-confirmado',
        'pendente' => 'cc-badge-pendente',
        'cancelado' => 'cc-badge-cancelado',
        default => 'cc-badge bg-gray-100 text-ink-muted',
    };
@endphp

<span {{ $attributes->merge(['class' => $class]) }}>
    {{ $slot->isEmpty() ? ucfirst($status) : $slot }}
</span>
