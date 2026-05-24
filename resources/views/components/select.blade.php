@props(['label' => null, 'name' => null, 'placeholder' => 'Selecione...'])

<div class="space-y-1.5">
    @if($label)
        <label @if($name) for="{{ $name }}" @endif class="block text-sm font-medium text-ink">{{ $label }}</label>
    @endif
    <select @if($name) id="{{ $name }}" name="{{ $name }}" @endif {{ $attributes->merge(['class' => 'cc-input cursor-pointer']) }}>
        <option value="">{{ $placeholder }}</option>
        {{ $slot }}
    </select>
</div>
