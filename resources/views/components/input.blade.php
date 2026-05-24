@props(['label' => null, 'name' => null, 'type' => 'text', 'placeholder' => '', 'value' => '', 'icon' => null])

<div {{ $attributes->only('class')->merge(['class' => 'space-y-1.5']) }}>
    @if($label)
        <label @if($name) for="{{ $name }}" @endif class="block text-sm font-medium text-ink">{{ $label }}</label>
    @endif
    <div class="relative">
        @if($icon)
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-ink-light">
                <x-icon :name="$icon" class="h-4 w-4" />
            </div>
        @endif
        <input
            @if($name) id="{{ $name }}" name="{{ $name }}" @endif
            type="{{ $type }}"
            value="{{ $value }}"
            placeholder="{{ $placeholder }}"
            {{ $attributes->except('class')->merge(['class' => 'cc-input' . ($icon ? ' pl-10' : '')]) }}
        />
    </div>
</div>
