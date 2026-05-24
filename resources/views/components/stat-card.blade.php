@props(['label', 'value', 'variation' => null, 'icon' => 'chart', 'trend' => 'up'])

<div class="cc-card-hover p-6">
    <div class="flex items-start justify-between">
        <div>
            <p class="text-sm font-medium text-ink-muted">{{ $label }}</p>
            <p class="mt-2 text-3xl font-bold tracking-tight text-ink">{{ $value }}</p>
            @if($variation)
                <p class="mt-2 text-sm font-medium text-emerald-600">{{ $variation }}</p>
            @endif
        </div>
        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-wine-light text-wine">
            <x-icon :name="$icon" class="h-5 w-5" />
        </div>
    </div>
</div>
