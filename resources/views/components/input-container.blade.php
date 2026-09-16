@props([
    'id' => null,
    'action' => null,
    'method' => 'POST',
])

<form
    id="{{ $id }}"
    action="{{ $action }}"
    method="{{ $method }}"
    {{ $attributes->merge([
        'class' => 'flex flex-col gap-2 w-full'
    ]) }}
>
    @csrf
    {{ $slot }}
</form>

{{-- Ganti property action jadi dynamic --}}
