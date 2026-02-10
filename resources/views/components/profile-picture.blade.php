@props([
    'src' => asset('images/muka_padel.png'),
    'alt' => 'Profile Pictures'
    ])

<div>
    <img 
        src="{{ $src }}" 
        alt="{{ $alt }}"
        class="aspect-square w-24 h-24 rounded-full"
        >
</div>