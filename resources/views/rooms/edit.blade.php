<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Edit Ruangan - Sistem Reservasi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite('resources/css/app.css')
</head>
<body class="form-page">

    {{-- Wrapper utama halaman dengan gambar latar belakang --}}
    <div class="page-wrapper" style="background-image: url('/assets/ai-generated-boat-picture.jpg');">
        
        {{-- Bagian navbar --}}
        <nav class="navbar">
            @auth
            <div class="navbar-left">
                <div class="navbar-user-info">
                    {{-- Menampilkan nama user --}}
                    Welcome, <strong>{{ strtoupper(auth()->user()->clean_name ?? auth()->user()->name) }}</strong>
                </div>
            </div>

            {{-- Link navigasi --}}
            <div class="navbar-links">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('booking.create') }}" class="{{ request()->routeIs('booking/create') ? 'active' : '' }}">Booking</a>
                <a href="{{ route('admin.bookings') }}" class="{{ request()->routeIs('admin.bookings') ? 'active' : '' }}">Kelola Booking</a>
                {{-- Link untuk mengelola ruangan, aktif jika di halaman kelola atau edit ruangan --}}
                <a href="{{ route('admin.rooms') }}" class="{{ request()->routeIs('admin.rooms', 'rooms.edit') ? 'active' : '' }}">Kelola Ruangan</a>
                <a href="{{ route('rooms.index') }}" class="{{ request()->routeIs('rooms.index') ? 'active' : '' }}">Room List</a>
                <a href="{{ route('bookings.index') }}" class="{{ request()->routeIs('bookings.index') ? 'active' : '' }}">Riwayat</a>
            </div>

            {{-- Tombol logout --}}
            <div class="navbar-right">
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="logout-button">Logout</button>
                </form>
            </div>
            @endauth
        </nav>

        {{-- Konten utama halaman --}}
        <main class="main-content">
            <div class="form-card">
                <h2 class="form-title">Edit Ruangan</h2>

                {{-- Menampilkan pesan sukses dari session --}}
                @if(session('success'))
                    <div class="alert-success">{{ session('success') }}</div>
                @endif

                {{-- Menampilkan pesan error validasi jika ada --}}
                @if($errors->any())
                    <div class="alert-error">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                {{-- Form untuk mengupdate data ruangan --}}
                <form method="POST" action="{{ route('rooms.update', $room->id) }}">
                    @csrf
                    @method('PUT')

                    {{-- Mengimpor form dari partial view _form.blade.php --}}
                    @include('rooms._form', ['room' => $room])

                    {{-- Tombol untuk menyimpan perubahan --}}
                    <button type="submit" class="form-button">Update Ruangan</button>
                    {{-- Tombol kembali ke daftar ruangan, disesuaikan dengan role user --}}
                    <a href="{{ route(auth()->user()->role === 'super admin' ? 'rooms.index' : 'admin.rooms') }}" class="form-footer">← Kembali ke Daftar Ruangan</a>
                </form>
            </div>
        </main>
    </div>

</body>
</html>