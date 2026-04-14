<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'src' => asset('images/muka_padel.png'),
    'alt' => 'Profile Pictures'
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
    'src' => asset('images/muka_padel.png'),
    'alt' => 'Profile Pictures'
    ]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div>
    <img 
        src="<?php echo e($src); ?>" 
        alt="<?php echo e($alt); ?>"
        class="aspect-square w-24 h-24 lg:h-12 lg:w-12 rounded-full"
        >
</div><?php /**PATH /Users/macbook/Herd/lsp-mkc-backend/resources/views/components/profile-picture.blade.php ENDPATH**/ ?>