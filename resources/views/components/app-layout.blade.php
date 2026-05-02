<!DOCTYPE html>
<html class="overflow-hidden-x">
<head>
    <title>@yield('title')</title>
     @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/auth.js'])
</head>
<body class="bg-white">

   <div class="flex">
        <div class="hidden lg:pl-4 lg:pr-4 lg:py-4 lg:block">
            @include('partials.sidebar')
        </div>

        {{-- <div class="p-0 lg:pr-4" > --}}
        @include('partials.navbar')
            {{$slot}}
        </div>
   </div>
    
</body>
</html>