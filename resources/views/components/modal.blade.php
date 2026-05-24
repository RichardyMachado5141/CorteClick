@props(['id', 'title' => '', 'size' => 'md'])

@php
    $sizes = ['sm' => 'max-w-sm', 'md' => 'max-w-md', 'lg' => 'max-w-lg', 'xl' => 'max-w-xl'];
@endphp

<div
    id="{{ $id }}"
    data-modal
    class="fixed inset-0 z-50 hidden items-center justify-center p-4"
    role="dialog"
    aria-modal="true"
    @if($title) aria-labelledby="{{ $id }}-title" @endif
>
    <div
        data-modal-backdrop
        data-modal-close="{{ $id }}"
        class="absolute inset-0 bg-ink/25 opacity-0 backdrop-blur-sm transition-opacity duration-200"
    ></div>
    <div
        data-modal-panel
        class="cc-card relative z-10 w-full {{ $sizes[$size] ?? $sizes['md'] }} scale-95 p-6 opacity-0 shadow-xl transition-all duration-200 ease-out"
    >
        @if($title)
            <div class="mb-5 flex items-center justify-between gap-4">
                <h3 id="{{ $id }}-title" class="text-lg font-semibold text-ink">{{ $title }}</h3>
                <button
                    type="button"
                    data-modal-close="{{ $id }}"
                    class="rounded-lg p-1.5 text-ink-light transition hover:bg-gray-100 hover:text-ink"
                    aria-label="Fechar"
                >
                    <x-icon name="x" class="h-5 w-5" />
                </button>
            </div>
        @endif
        {{ $slot }}
    </div>
</div>
