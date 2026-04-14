<!DOCTYPE html>
<html data-theme="light">
<head>
    <title>Auth</title>
     <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/css/output.css', 'resources/js/app.js']); ?>
</head>
<body <?php echo e($attributes->merge([
    'class' => ''
])); ?>>
    <main class="">
        <?php echo e($slot); ?>

    </main>
</body>
</html>
<?php /**PATH /Users/macbook/Herd/lsp-mkc-backend/resources/views/components/guest-layout.blade.php ENDPATH**/ ?>