<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'href' => null,
    'route' => null,
    'active' => false,
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
    'href' => null,
    'route' => null,
    'active' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $url = $href ?? ($route ? route($route) : '#');

    $baseClass = 'inline-flex items-center font-medium transition';

    $activeClass = $active
        ? 'text-primary underline'
        : 'hover:underline';

?>

<a
    href="<?php echo e($url); ?>"
    <?php echo e($attributes->merge([
        'class' => $baseClass . ' ' . $activeClass
    ])); ?>

>
    <?php echo e($slot); ?>

</a>
<?php /**PATH /Users/macbook/Herd/lsp-mkc-backend/resources/views/components/hyperlink.blade.php ENDPATH**/ ?>