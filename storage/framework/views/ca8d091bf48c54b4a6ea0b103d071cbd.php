<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'label' => null,
    'name',
    'showForget' => false,
    'showShow' => false,
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
    'label' => null,
    'name',
    'showForget' => false,
    'showShow' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="form-control w-full" x-data="{ show: false }">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($label): ?>
        <label class="label" for="<?php echo e($name); ?>">
            <span class="label-text"><?php echo e($label); ?></span>
        </label>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <input
        :type="show ? 'text' : 'password'"
        name="<?php echo e($name); ?>"
        id="<?php echo e($name); ?>"
        value="<?php echo e(old($name)); ?>"
        <?php echo e($attributes->merge([
            'class' => 'input h-[4rem] lg:h-[3rem] input-bordered w-full border-[#ACCD3A] bg-transparent focus:ring-0 focus:outline-none text-xl lg:text-sm'
        ])); ?>

    />

   <div class="flex justify-between">
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showShow): ?>
         <!-- Checkbox toggle -->
    <label class="label cursor-pointer justify-start gap-2 mt-4">
        <input
            type="checkbox"
            class="checkbox checkbox-sm rounded-sm border-[#ACCD3A] border-2 text-white"
            x-model="show"
        />
        <span class="label-text text-xl lg:text-sm text-white">Lihat kata sandi</span>
    </label>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    
    
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showForget): ?>
                    <div class="flex justify-center items-center text-white text-sm mt-4">
                    <?php if (isset($component)) { $__componentOriginal35a284f17d11439403d9029e03ea2ccf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal35a284f17d11439403d9029e03ea2ccf = $attributes; } ?>
<?php $component = App\View\Components\Hyperlink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('hyperlink'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Hyperlink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => 'login','class' => 'link link-hover ml-2 text-white']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                        Lupa Kata Sandi?
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal35a284f17d11439403d9029e03ea2ccf)): ?>
<?php $attributes = $__attributesOriginal35a284f17d11439403d9029e03ea2ccf; ?>
<?php unset($__attributesOriginal35a284f17d11439403d9029e03ea2ccf); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal35a284f17d11439403d9029e03ea2ccf)): ?>
<?php $component = $__componentOriginal35a284f17d11439403d9029e03ea2ccf; ?>
<?php unset($__componentOriginal35a284f17d11439403d9029e03ea2ccf); ?>
<?php endif; ?>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
   </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <label class="label">
            <span class="label-text-alt text-error"><?php echo e($message); ?></span>
        </label>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH /Users/macbook/Herd/lsp-mkc-backend/resources/views/components/password.blade.php ENDPATH**/ ?>