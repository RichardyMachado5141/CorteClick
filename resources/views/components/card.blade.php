@props(['hover' => false, 'padding' => true])

<div {{ $attributes->merge(['class' => ($hover ? 'cc-card-hover' : 'cc-card') . ($padding ? ' p-6' : '')]) }}>
    {{ $slot }}
</div>
