<div style="text-align: center;">
    Pekanbaru, <?php echo new \Illuminate\Support\EncodedHtmlString(now()->translatedFormat('d F Y')); ?>

    <br><br><br>
    Kepada Yth.
    <br>
    Saudara <?php echo new \Illuminate\Support\EncodedHtmlString($user->name ?? 'Nama Asesi'); ?>

    <br><br><br>
    Terima kasih telah melakukan registrasi akun pada Aplikasi Pendaftaran Uji Kompetensi LSP MKC, dengan informasi akun
    sebagai berikut:
    <br><br><br>
    NIK/Paspor: <?php echo new \Illuminate\Support\EncodedHtmlString($user->identity_number ?? '-'); ?>

    <br>
    Email: <?php echo new \Illuminate\Support\EncodedHtmlString($user->email ?? '-'); ?>

    <br>
    Password: <?php echo new \Illuminate\Support\EncodedHtmlString($password ?? '-'); ?>

    <br><br><br>
    Untuk dapat menggunakan akun tersebut, silakan klik link berikut untuk mengaktifkan akun Anda:
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($actionText)): ?>
        <?php
    $color = match ($level) {
        'success', 'error' => $level,
        default => 'primary',
    };
            ?>
        <?php if (isset($component)) { $__componentOriginal15a5e11357468b3880ae1300c3be6c4f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal15a5e11357468b3880ae1300c3be6c4f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => $__env->getContainer()->make(Illuminate\View\Factory::class)->make('mail::button'),'data' => ['url' => $actionUrl,'color' => $color]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('mail::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($actionUrl),'color' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($color)]); ?>
            <?php echo new \Illuminate\Support\EncodedHtmlString($actionText); ?>

         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal15a5e11357468b3880ae1300c3be6c4f)): ?>
<?php $attributes = $__attributesOriginal15a5e11357468b3880ae1300c3be6c4f; ?>
<?php unset($__attributesOriginal15a5e11357468b3880ae1300c3be6c4f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal15a5e11357468b3880ae1300c3be6c4f)): ?>
<?php $component = $__componentOriginal15a5e11357468b3880ae1300c3be6c4f; ?>
<?php unset($__componentOriginal15a5e11357468b3880ae1300c3be6c4f); ?>
<?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($actionText)): ?>
         <?php $__env->slot('subcopy', null, []); ?> 
            <?php echo app('translator')->get(
        "If you're having trouble clicking the \":actionText\" button, copy and paste the URL below\n" .
        'into your web browser:',
        [
            'actionText' => $actionText,
        ]
    ); ?> <span class="break-all">[<?php echo new \Illuminate\Support\EncodedHtmlString($displayableActionUrl); ?>](<?php echo new \Illuminate\Support\EncodedHtmlString($actionUrl); ?>)</span>
         <?php $__env->endSlot(); ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    Jika tombol di atas tidak berfungsi, silakan salin dan tempel link berikut ke browser Anda: <?php echo new \Illuminate\Support\EncodedHtmlString($actionUrl); ?>


    <br><br>
    PENTING:
    <br>
    - Link aktivasi hanya berlaku selama 1x24 jam.
    <br>
    - Disarankan agar pemohon mengganti password setelah login.
    <br><br><br>

    Salam,
    <br>
    LSP MKC
    <br><br>
    ===========================================<br>
    Ini adalah email otomatis. Mohon tidak membalas email ini.
</div><?php /**PATH E:\Main\Code\lsp-mkc-backend\resources\views/vendor/notifications/email.blade.php ENDPATH**/ ?>