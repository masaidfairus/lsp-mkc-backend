<!DOCTYPE html>
<html data-theme="light">

<head>
    <title>LSP MKC</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body {{ $attributes->merge([
    'class' => ''
]) }}>
    <main class="">
        {{ $slot }}
    </main>
</body>

</html>