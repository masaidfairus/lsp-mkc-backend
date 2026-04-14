<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'type' => null
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'type' => null
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<button <?php echo e($attributes->merge([
            'class' => 'bg-[#CDF447] text-black rounded-lg p-5 lg:p-2 hover:bg-transparent border border-[#CDF447] hover:text-white transition text-xl lg:text-sm'
        ])); ?>


        type="<?php echo e($type); ?>"
        >
    <?php echo e($slot); ?>

</button><?php /**PATH /Users/macbook/Herd/lsp-mkc-backend/resources/views/components/button.blade.php ENDPATH**/ ?>