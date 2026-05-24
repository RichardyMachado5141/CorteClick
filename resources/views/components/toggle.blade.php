@props(['active' => false, 'name' => 'toggle'])

<button
    type="button"
    role="switch"
    {{ $attributes->merge(['class' => 'relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-wine/30 focus:ring-offset-2 ' . ($active ? 'bg-wine' : 'bg-gray-200')]) }}
    aria-checked="{{ $active ? 'true' : 'false' }}"
>
    <span
        class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $active ? 'translate-x-5' : 'translate-x-0' }}"
    ></span>
</button>
