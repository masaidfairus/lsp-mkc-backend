@props([
    'label' => null,
    'name',
    'type' => 'text',
    'value' => null
])

<div class="form-control w-full">
    @if ($label)
        <label class="label" for="{{ name }}">
            <span class="label-text">{{ label }}</span>
        </label>
    @endif

    <input 
        type="{{$name}}"
        id="{{$name}}"
        name="{{$name}}"
        value="{{ old($name, $value) }}"
        {{ $attributes->merge([
            'class' => 'input h-[4rem] lg:h-[3rem] input-bordered w-full border-[#ACCD3A] bg-transparent focus:ring-0 focus:outline-none text-xl lg:text-sm'
        ]) }}
    >

     @error($name)
        <label class="label">
            <span class="label-text-alt text-error">
                {{ $message }}
            </span>
        </label>
    @enderror
</div>