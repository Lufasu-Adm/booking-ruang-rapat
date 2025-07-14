<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite('resources/css/admin.css')
</head>
<body>

<div class="page-wrapper" style="background-image: url('/assets/ai-generated-boat-picture.jpg');">

    <nav class="navbar">
        <div class="navbar-user-info">
            <p>Selamat Datang, <strong>{{ strtoupper(auth()->user()->name) }}</strong></p>
            <p>Anda adalah <strong>{{ strtoupper(auth()->user()->role) }}</strong></p>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-button">Logout</button>
        </form>
    </nav>

    <main class="main-content">
        <h1>Admin Panel</h1>
        <p>Pilih menu di bawah ini untuk mulai mengelola data.</p>
        
        <a href="{{ route('admin.bookings') }}" class="action-button">
            Kelola Booking
        </a>
        <br>
        <a href="{{ route('admin.rooms') }}" class="action-button">
            Kelola Room
        </a>
        
    </main>

</div>

</body>
</html>