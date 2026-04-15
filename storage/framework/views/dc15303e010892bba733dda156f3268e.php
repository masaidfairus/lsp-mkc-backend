<?php if (isset($component)) { $__componentOriginal69dc84650370d1d4dc1b42d016d7226b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b = $attributes; } ?>
<?php $component = App\View\Components\GuestLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('guest-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\GuestLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

    <!-- Bg Img -->
      <div class="absolute inset-0 z-0">
        <img
            src="<?php echo e(asset('images/verif_bg.png')); ?>"
            alt="Verification Background"
            class="w-full h-full object-cover"
        >
    </div>
    
    <div class="h-[100vh] flex justify-center items-center ">
        
            <div class="w-[80vw] h-[40vh] lg:h-[] lg:w-[25vw]">
            <!-- Card -->
                <div class="relative z-10 bg-black shadow-2xl lg:shadow-sm shadow-white w-full h-full lg:h-fit flex flex-col justify-center items-center rounded-lg py-12 lg:py-20 px-16 lg:px-6">
                    
                    <div class="flex flex-col justify-center items-center space-y-8 lg:space-y-2 w-full">
                        
                        <div class="mb-20 lg:mb-4 text-white text-8xl lg:text-4xl">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        
                        <div class="text-center text-6xl lg:text-2xl text-white">
                            <h1>Email Successfully <br> Verified</h1>
                        </div>
                        
                        <div class="text-[1.8rem] lg:text-sm text-center font-light text-white">
                            <p>
                                Mari bersiap untuk masuk, mulailah dengan <br> menyiapkan lingkungan kerja anda!🎊📝
                            </p>
                        </div>
                    </div>
                </div>
            </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $attributes = $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $component = $__componentOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?><?php /**PATH /Users/macbook/Herd/lsp-mkc-backend/resources/views/auth/activation.blade.php ENDPATH**/ ?>