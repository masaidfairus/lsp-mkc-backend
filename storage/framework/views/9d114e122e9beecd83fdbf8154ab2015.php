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
    
    <div class="w-[100vw] h-[100vh] flex justify-center lg:items-center pt-[20vh] lg:pt-0">
        
            <div class="w-[80vw] h-[40vh] lg:h-[] lg:w-[25vw]">
            <!-- Card -->
                <div class="relative z-10 bg-black shadow-2xl lg:shadow-lg shadow-white w-full h-full lg:h-fit flex flex-col justify-center items-center rounded-lg space-y-14 pt-12 lg:pt-20 px-16 lg:px-6 lg:pb-8">
                    <div class="text-white">
                        <h1 class="text-3xl font-bold">Reset Your Password</h1>
                        <p class="text-sm">Pulihkan kata sandimu dan masuk <br> dengan akun yang sama!</p>
                    </div>
                    
                    <form method="" class="flex flex-col space-y-8 lg:space-y-2 w-full text-white">
                      <div class="flex flex-col space-y-2">
                        <label for="current">Current Password</label>
                        <input class="border border-white rounded h-8" type="password" id="current">
                      </div>
                    </form>
                    
                    <div class="bg-white border border-white hover:bg-transparent hover:text-white transition w-full flex justify-center items-center rounded-lg font-medium py-4 lg:py-2 text-5xl lg:text-base">
                        <a href="">Change Password</a>
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
<?php endif; ?><?php /**PATH /Users/macbook/Herd/lsp-mkc-backend/resources/views/auth/reset.blade.php ENDPATH**/ ?>