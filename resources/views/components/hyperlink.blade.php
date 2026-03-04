@props([
    'href' => null,
    'route' => null,
    'active' => false,
])

@php
    $url = $href ?? ($route ? route($route) : '#');

    $baseClass = 'inline-flex items-center font-medium transition';

    $activeClass = $active
        ? 'text-primary underline'
        : 'hover:underline';

@endphp

<a
    href="{{ $url }}"
    {{ $attributes->merge([
        'class' => $baseClass . ' ' . $activeClass
    ]) }}
>
    {{ $slot }}
</a>
