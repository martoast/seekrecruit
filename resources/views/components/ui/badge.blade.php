@props([
    'variant' => 'default',
    'size' => 'md',
])

@php
    $sizes = [
        'sm' => 'text-xs',
        'md' => 'text-sm',
    ];

    $variants = [
        'default' => 'bg-gray-50 text-gray-700 border-gray-200',
        'success' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'warning' => 'bg-amber-50 text-amber-700 border-amber-200',
        'danger' => 'bg-red-50 text-red-700 border-red-200',
        'info' => 'bg-blue-50 text-blue-700 border-blue-200',
    ];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center font-medium rounded-lg border px-2.5 py-0.5 {$sizes[$size]} {$variants[$variant]}"]) }}>
    {{ $slot }}
</span>
