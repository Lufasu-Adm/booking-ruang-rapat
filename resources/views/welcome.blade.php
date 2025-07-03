<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Selamat Datang di Aplikasi Peminjaman Ruang Rapat</title>
    @vite('resources/css/app.css') {{-- Pakai Vite untuk Tailwind --}}
</head>
<body class="bg-gray-100 text-gray-800 min-h-screen flex flex-col items-center justify-center relative font-sans">

    {{-- Navigasi Auth --}}
    <div class="absolute top-0 right-0 p-6 space-x-4">
        @auth
            <a href="{{ url('/') }}" class="link">Dashboard</a>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="btn-red">Logout</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="link">Login</a>
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="link text-green-600">Register</a>
            @endif
        @endauth
    </div>

    {{-- Konten Utama --}}
    <main class="text-center max-w-xl">
        <h1 class="text-5xl font-extrabold text-gray-900 mb-6">Aplikasi Peminjaman Ruang Rapat</h1>
        <p class="text-lg text-gray-600 mb-8">Kelola dan pesan ruang rapat dengan mudah dan efisien.</p>
    </main>

</body>
</html>
