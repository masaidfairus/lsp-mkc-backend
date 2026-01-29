<form action="POST" {{ $attributes->merge([
    'class' => 'flex flex-col gap-2 w-full'
]) }}>
    {{ $slot }}
</form>
