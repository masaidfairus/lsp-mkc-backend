<button {{ $attributes->merge([
            'class' => 'bg-[#CDF447] text-black rounded-lg p-5 lg:p-2 hover:bg-transparent border border-[#CDF447] hover:text-white transition text-xl lg:text-sm'
        ]) }}
        >
    {{$slot}}
</button>