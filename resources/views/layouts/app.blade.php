<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Aplikasi Booking Ruang')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">
    <nav class="bg-blue-600 text-white p-4 mb-4">
        <div class="container mx-auto">
            <h1 class="text-xl font-bold">Sistem Booking Ruangan</h1>
        </div>
    </nav>

    <div class="container mx-auto px-4">
        @yield('content')
    </div>

    <footer class="text-center text-sm py-4 mt-8 text-gray-500">
        &copy; {{ date('Y') }} Aplikasi Booking Ruangan
    </footer>
</body>
</html>