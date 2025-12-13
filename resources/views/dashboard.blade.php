<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard - Reservation System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite('resources/css/app.css')
</head>
<body class="dashboard-page">

    {{-- 
        Wrapper utama halaman dashboard.
        Latar belakang diatur menggunakan CSS inline.
    --}}
    <div class="page-wrapper" style="background-image: url('/assets/Gemini_Generated_Image_apy2pfapy2pfapy2.png');">
        
        {{-- 
            Navbar utama aplikasi.
            Hanya ditampilkan jika pengguna sudah login.
        --}}
        <nav class="navbar">
            @auth
            @php
                // Logika untuk membersihkan nama pengguna dari prefix role
                $name = auth()->user()->name;
                $cleanName = preg_replace('/^(USER\s+|ADMIN\s+|SUPERADMIN\s+|DIVISI\s+)+/i', '', $name);
            @endphp
            {{-- Bagian informasi user di navbar --}}
            <div class="navbar-user-info">
                Welcome, <strong>{{ strtoupper($cleanName) }}</strong>
            </div>

            {{-- Link navigasi --}}
            <div class="navbar-links">
                {{-- Link dashboard dengan kelas 'active' jika rute saat ini adalah 'dashboard' --}}
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('booking.create') }}" class="{{ request()->routeIs('booking.create') ? 'active' : '' }}">Booking</a>
                <a href="{{ route('admin.bookings') }}" class="{{ request()->routeIs('admin.bookings') ? 'active' : '' }}">Manage Bookings</a>
                <a href="{{ route('admin.rooms') }}" class="{{ request()->routeIs('admin.rooms') ? 'active' : '' }}">Manage Rooms</a>
                <a href="{{ route('rooms.index') }}" class="{{ request()->routeIs('rooms.index') ? 'active' : '' }}">Room List</a>
                <a href="{{ route('bookings.index') }}" class="{{ request()->routeIs('bookings.index') ? 'active' : '' }}">History</a>
            </div>

            {{-- Bagian logout di sisi kanan navbar --}}
            <div class="navbar-right">
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="logout-button">LOGOUT</button>
                </form>
            </div>
            @endauth
        </nav>

        {{-- Konten utama halaman --}}
        <main class="main-content">
            @auth
                {{-- Tombol untuk mengunduh rekap PDF, hanya untuk role 'admin' atau 'superadmin' --}}
                @if (auth()->user()->role === 'admin' || auth()->user()->role === 'superadmin')
                    <div style="text-align: right; margin-bottom: 1rem; margin-right: 2rem;">
                        <a href="{{ route('bookings.export-filter') }}" class="rekap-button" style="background-color: #306199; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 600;">
                            Download Recap PDF
                        </a>
                    </div>
                @endif

                {{-- Bagian kartu (cards) yang menampilkan statistik --}}
                <section class="cards-container">
                    <div class="card">
                        <h3>Total Rooms</h3>
                        <p style="font-size: 2.5rem; font-weight: 700;">{{ $totalRooms ?? 8 }}</p>
                    </div>
                    <div class="card">
                        <h3>Total Bookings</h3>
                        <p style="font-size: 2.5rem; font-weight: 700;">{{ $totalBookings ?? 124 }}</p>
                    </div>
                    <div class="card">
                        <h3>Active Bookings</h3>
                        <p style="font-size: 2.5rem; font-weight: 700;">{{ $activeBookings ?? 5 }}</p>
                    </div>
                    <div class="card" style="text-align: center;">
                        <p style="font-size: 1.5rem;">{{ \Carbon\Carbon::now()->format('l, d F Y') }}</p>
                        {{-- Jam real-time yang diperbarui oleh JavaScript --}}
                        <h1 id="live-time" style="font-size: 3.5rem; font-weight: 700;">{{ \Carbon\Carbon::now()->format('H:i') }}</h1>
                    </div>
                </section>
            @endauth
        </main>
    </div>

    {{-- Script JavaScript untuk memperbarui jam secara real-time --}}
    <script>
        function updateTime() {
            const now = new Date();
            // Format waktu ke format 24 jam (HH:mm:ss)
            const timeString = now.toLocaleTimeString("en-GB", {
                hour: "2-digit",
                minute: "2-digit",
                second: "2-digit",
                hour12: false
            });
            const timeElement = document.getElementById("live-time");
            if (timeElement) {
                timeElement.textContent = timeString;
            }
        }
        // Perbarui jam setiap 1 detik
        setInterval(updateTime, 1000);
        // Panggil pertama kali agar waktu langsung muncul
        updateTime();
    </script>
</body>
</html>