<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title')</title>
     @vite(['resources/css/app.css', 'resources/css/output.css', 'resources/js/app.js'])
</head>
<body>

    <main>
        @yield('content')
    </main>

    
</body>
</html>