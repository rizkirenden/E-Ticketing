<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <title>@yield('title', 'E-TICKETING') - Aplikasi Tiketing</title>
    @vite('resources/css/app.css')
    @stack('styles')
    <style>
        .bg-custom-dark {
            background-color: #001D39;
        }

        /* Global styles untuk layout dengan fixed sidebar */
        body {
            overflow: hidden;
            /* Mencegah scroll pada body */
        }

        /* Custom scrollbar global */
        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(59, 130, 246, 0.3);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(59, 130, 246, 0.6);
        }
    </style>
</head>

<body class="bg-custom-dark">
    @yield('content')
    @vite('resources/js/app.js')
    @stack('scripts')
</body>

</html>
