<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
     @vite(['resources/css/app.css', 'resources/css/output.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <x-navbar />

    <main class="p-6">
        {{ $slot }}
    </main>
</body>
</html>
