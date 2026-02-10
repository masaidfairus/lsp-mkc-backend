<!DOCTYPE html>
<html class="overflow-hidden-x">
<head>
    <title>@yield('title')</title>
     @vite(['resources/css/app.css', 'resources/css/output.css', 'resources/js/app.js', 'resource/js/auth.js'])
</head>
<<<<<<< Updated upstream
<body class="bg-white">
    <header>
        @include('partials.navbar')
    </header>
=======
<body class="bg-gray-100">
    @include('partials.navbar')
>>>>>>> Stashed changes

    <main >
        {{$slot}}
    </main>
    
</body>
</html>