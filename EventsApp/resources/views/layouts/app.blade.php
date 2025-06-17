<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard - Mazer Admin Dashboard')</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/compiled/svg/favicon.svg') }}" type="image/x-icon">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/iconly.css') }}">
    <link rel="stylesheet" href="{{asset('assets/compiled/css/simple-datatables.css')}}">

    @stack('styles')
</head>

<body>
    <script src="{{ asset('assets/static/js/initTheme.js') }}"></script>
    <div id="app">
        <!-- Sidebar -->
        @include('layouts.partials.sidebar')

        <div id="main">
            <!-- Navbar -->
            @include('layouts.partials.navbar')

            <!-- Main Content -->
            @yield('content')

            <!-- Footer -->
            @include('layouts.partials.footer')
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('assets/static/js/components/dark.js') }}"></script>
    <script src="{{ asset('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/compiled/js/app.js') }}"></script>
    <script src="{{ asset('assets/compiled/js/simple-datatables.js') }}"></script>

    @stack('scripts')
</body>
</html>