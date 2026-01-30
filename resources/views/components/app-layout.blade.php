<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <title>@yield('title')</title>
     @vite(['resources/css/app.css', 'resources/css/output.css', 'resources/js/app.js'])
</head>
<body>

    @include('partials.navbar')

    <main>
        {{$slot}}
    </main>

    @include('partials.footer')
    
</body>
</html>