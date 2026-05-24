@props(['variant' => 'primary', 'href' => null, 'type' => 'button'])

@php
    $classes = match($variant) {
        'primary' => 'cc-btn-primary',
        'secondary' => 'cc-btn-secondary',
        'danger' => 'cc-btn-danger',
        'ghost' => 'cc-btn-ghost',
        default => 'cc-btn-primary',
    };
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
