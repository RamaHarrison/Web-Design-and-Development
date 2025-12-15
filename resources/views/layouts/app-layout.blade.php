<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <title>@yield('title', 'Default')</title>
</head>
<body>
    <div>
        <x-sidebar />
    </div>
    
    <div class="wrapper">
        <x-navbar />
        <div class="row">
            @hasSection('sidebar-main')
                <main class="col-9 p-0">
                    <div class="container-fluid px-5 pb-5">
                        @yield('content')
                    </div>
                </main>
                <div class="container-fluid border col-3 ps-0">
                    @yield('sidebar-main')
                </div>
            @else
                <main class="col-12 p-0">
                    <div class="container-fluid px-5">
                        @yield('content')
                    </div>
                </main>
            @endif
        </div>
    </div>
</body>

@yield('scripts')
</html>