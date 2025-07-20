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

    <div class="page-wrapper" style="background-image: url('/assets/ai-generated-boat-picture.jpg');">
        
        <nav class="navbar">
            @auth
            <div class="navbar-left">
                <div class="navbar-user-info">
                    Welcome, <strong>{{ strtoupper(auth()->user()->clean_name ?? auth()->user()->name) }}</strong>
                </div>
            </div>

            <div class="navbar-links">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('booking.create') }}" class="{{ request()->routeIs('booking/create') ? 'active' : '' }}">Booking</a>
                <a href="{{ route('admin.bookings') }}" class="{{ request()->routeIs('admin.bookings') ? 'active' : '' }}">Kelola Booking</a>
                <a href="{{ route('admin.rooms') }}" class="{{ request()->routeIs('admin.rooms', 'rooms.edit') ? 'active' : '' }}">Kelola Ruangan</a>
                <a href="{{ route('rooms.index') }}" class="{{ request()->routeIs('rooms.index') ? 'active' : '' }}">Room List</a>
                <a href="{{ route('bookings.index') }}" class="{{ request()->routeIs('bookings.index') ? 'active' : '' }}">Riwayat</a>
            </div>

            <div class="navbar-right">
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="logout-button">Logout</button>
                </form>
            </div>
            @endauth
        </nav>

        <main class="main-content">
            <div class="form-card">
                <h2 class="form-title">Edit Ruangan</h2>

                @if(session('success'))
                    <div class="alert-success">{{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert-error">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('rooms.update', $room->id) }}">
                    @csrf
                    @method('PUT')

                    {{-- File _form.blade.php akan di-render di sini --}}
                    {{-- Pastikan file tersebut menggunakan class: .form-group, .form-label, .form-input --}}
                    @include('rooms._form', ['room' => $room])

                    <button type="submit" class="form-button">Update Ruangan</button>
                    <a href="{{ route(auth()->user()->role === 'super admin' ? 'rooms.index' : 'admin.rooms') }}" class="form-footer">← Kembali ke Daftar Ruangan</a>
                </form>
            </div>
        </main>
    </div>

</body>
</html>