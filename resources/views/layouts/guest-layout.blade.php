<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <title>@yield('title', 'Default')</title>
</head>
<body>

    <div class="container mt-5">
        @yield('content')
    </div>
    
</body>
</html>