<!DOCTYPE html>
<html class="overflow-hidden-x">
<head>
    <title><?php echo $__env->yieldContent('title'); ?></title>
     <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/css/output.css', 'resources/js/app.js', 'resource/js/auth.js']); ?>
</head>
<body class="bg-white">

   <div class="flex">
        <div class="hidden lg:pl-4 lg:pr-4 lg:py-4 lg:block">
            <?php echo $__env->make('partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

        
        <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo e($slot); ?>

        </div>
   </div>
    
</body>
</html><?php /**PATH /Users/macbook/Herd/lsp-mkc-backend/resources/views/components/app-layout.blade.php ENDPATH**/ ?>