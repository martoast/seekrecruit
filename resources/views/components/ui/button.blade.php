@props([
    'variant' => 'primary',
    'size' => 'md',
    'loading' => false,
    'type' => 'button',
])

@php
    $sizes = [
        'sm' => 'px-3 py-1.5 text-sm rounded-lg',
        'md' => 'px-5 py-2.5 text-base rounded-xl',
        'lg' => 'px-6 py-3 text-lg rounded-xl',
    ];

    $variants = [
        'primary' => 'bg-primary-500 text-white hover:bg-primary-600 focus:ring-primary-500 shadow-sm hover:shadow-md',
        'secondary' => 'bg-white text-primary-700 border-2 border-primary-200 hover:bg-primary-50 focus:ring-primary-500',
        'danger' => 'bg-red-500 text-white hover:bg-red-600 focus:ring-red-500 shadow-sm hover:shadow-md',
        'ghost' => 'text-gray-700 hover:bg-gray-100 focus:ring-gray-500',
    ];

    $base = 'relative inline-flex items-center justify-center font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed gap-2';
@endphp

<button
    {{ $attributes->merge(['type' => $type, 'class' => "{$base} {$sizes[$size]} {$variants[$variant]}"]) }}
    @if ($loading) disabled @endif
>
    @if ($loading)
        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    @endif
    {{ $slot }}
</button>
