@props([
    'label' => null,
    'name',
    'showForget' => false,
    'showShow' => false,
])

<div class="form-control w-full" x-data="{ show: false }">
    @if($label)
        <label class="label" for="{{ $name }}">
            <span class="label-text">{{ $label }}</span>
        </label>
    @endif

    <input
        :type="show ? 'text' : 'password'"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name) }}"
        {{ $attributes->merge([
            'class' => 'input h-[4rem] lg:h-[3rem] input-bordered w-full border-[#ACCD3A] bg-transparent focus:ring-0 focus:outline-none text-xl lg:text-sm'
        ]) }}
    />

   <div class="flex justify-between">
    
    @if ($showShow)
         <!-- Checkbox toggle -->
    <label class="label cursor-pointer justify-start gap-2 mt-4">
        <input
            type="checkbox"
            class="checkbox checkbox-sm rounded-sm border-[#ACCD3A] border-2 text-white"
            x-model="show"
        />
        <span class="label-text text-xl lg:text-sm text-white">Lihat kata sandi</span>
    </label>
    @endif
    
    {{-- Forgot --}}
                @if ($showForget)
                    <div class="flex justify-center items-center text-white text-sm mt-4">
                    <x-hyperlink
                        route="login"
                        class="link link-hover ml-2 text-white"
                    >
                        Lupa Kata Sandi?
                    </x-hyperlink>
                </div>
                @endif
   </div>

    @error($name)
        <label class="label">
            <span class="label-text-alt text-error">{{ $message }}</span>
        </label>
    @enderror
</div>
