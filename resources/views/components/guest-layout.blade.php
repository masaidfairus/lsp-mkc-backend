<!DOCTYPE html>
<html data-theme="light">
<head>
    <title>Auth</title>
     @vite(['resources/css/app.css', 'resources/css/output.css', 'resources/js/app.js'])
</head>
<body {{ $attributes->merge([
    'class' => ''
]) }}>
    <main class="">
        {{ $slot }}
    </main>
</body>
</html>
