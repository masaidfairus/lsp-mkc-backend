<form action="/login" method="POST" {{ $attributes->merge([
    'class' => 'flex flex-col gap-2 w-full'
]) }}>
    @csrf
    {{ $slot }}
</form>

{{-- Ganti property action jadi dynamic --}}
