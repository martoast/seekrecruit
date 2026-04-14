@props([
    'label' => null,
    'name' => null,
    'options' => [],
    'placeholder' => null,
    'error' => null,
    'required' => false,
    'value' => null,
    'id' => null,
])

@php
    $inputId = $id ?? ($name ? 'select-' . $name : 'select-' . uniqid());
    $hasError = $error || ($name && $errors->has($name));
    $errorMessage = $error ?? ($name ? $errors->first($name) : null);
    $borderClasses = $hasError
        ? 'border-red-300 focus:border-red-500 focus:ring-red-500'
        : 'border-gray-200 focus:border-primary-500 focus:ring-primary-500';
    $selectedValue = $value ?? ($name ? old($name) : null);
@endphp

<div class="space-y-1.5">
    @if ($label)
        <label for="{{ $inputId }}" class="block text-sm font-medium text-gray-700">
            {{ $label }}
            @if ($required)<span class="text-red-500">*</span>@endif
        </label>
    @endif
    <select
        id="{{ $inputId }}"
        @if ($name) name="{{ $name }}" @endif
        @if ($required) required @endif
        {{ $attributes->merge(['class' => "block w-full rounded-xl border-2 px-4 py-2.5 text-gray-900 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-1 disabled:bg-gray-50 disabled:cursor-not-allowed {$borderClasses}"]) }}
    >
        @if ($placeholder)
            <option value="" @if (is_null($selectedValue) || $selectedValue === '') selected @endif disabled>{{ $placeholder }}</option>
        @endif
        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected((string) $selectedValue === (string) $optionValue)>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>
    @if ($errorMessage)
        <p class="text-sm text-red-600">{{ $errorMessage }}</p>
    @endif
</div>
