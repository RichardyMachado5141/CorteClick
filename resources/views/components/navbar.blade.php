@props(['title' => '', 'subtitle' => null])

<header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-border bg-white/90 px-4 backdrop-blur-md sm:px-6 lg:px-8">
    <div class="flex items-center gap-3">
        <button type="button" onclick="toggleSidebar()" class="rounded-lg p-2 text-ink-muted transition hover:bg-gray-100 lg:hidden" aria-label="Menu">
            <x-icon name="menu" class="h-5 w-5" />
        </button>
        <div>
            @if($title)
                <h2 class="text-base font-semibold text-ink sm:text-lg">{{ $title }}</h2>
            @endif
            @if($subtitle)
                <p class="text-xs text-ink-muted">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
    <div class="flex items-center gap-2">
        <button type="button" class="relative rounded-xl p-2.5 text-ink-muted transition hover:bg-gray-100 hover:text-ink">
            <x-icon name="bell" class="h-5 w-5" />
            <span class="absolute right-2 top-2 h-2 w-2 rounded-full bg-wine"></span>
        </button>
        {{ $slot }}
    </div>
</header>
