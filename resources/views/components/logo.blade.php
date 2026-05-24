@props(['size' => 'default'])

@php
    $sizes = ['sm' => 'text-lg', 'default' => 'text-xl', 'lg' => 'text-2xl'];
    $sizeClass = $sizes[$size] ?? $sizes['default'];
@endphp

<a href="{{ route('login') }}" {{ $attributes->merge(['class' => 'inline-flex items-center gap-2.5 group']) }}>
    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-wine text-white shadow-sm shadow-wine/20 transition duration-200 group-hover:scale-105 group-hover:shadow-md">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
        </svg>
    </span>
    <span class="{{ $sizeClass }} font-bold tracking-tight text-ink">
        Corte<span class="text-wine">Click</span>
    </span>
</a>
