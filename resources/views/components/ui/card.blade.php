@props([
    'padding' => 'md',
])

@php
    $paddings = [
        'none' => 'p-0',
        'sm' => 'p-4',
        'md' => 'p-6',
        'lg' => 'p-8',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden']) }}>
    @isset($header)
        <div class="px-6 py-4 border-b border-gray-100">{{ $header }}</div>
    @endisset

    <div class="{{ $paddings[$padding] ?? $paddings['md'] }}">
        {{ $slot }}
    </div>

    @isset($footer)
        <div class="px-6 py-4 bg-gray-50 rounded-b-2xl border-t border-gray-100">{{ $footer }}</div>
    @endisset
</div>
