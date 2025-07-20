<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Tambah Ruangan - Sistem Reservasi</title>
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
                <a href="{{ route('admin.rooms') }}" class="{{ request()->routeIs(['admin.rooms', 'rooms.create', 'rooms.edit']) ? 'active' : '' }}">Kelola Ruangan</a>
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
                <h2 class="form-title">Tambah Ruangan Baru</h2>

                @if($errors->any())
                    <div class="alert-error">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('rooms.store') }}">
                    @csrf
                    
                    <div class="form-group">
                        <label for="name" class="form-label">Nama Ruangan:</label>
                        <input type="text" id="name" name="name" class="form-input" value="{{ old('name') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="capacity" class="form-label">Kapasitas:</label>
                        <input type="number" id="capacity" name="capacity" class="form-input" value="{{ old('capacity') }}" min="1" required>
                    </div>

                    <div class="form-group">
                        <label for="location" class="form-label">Lokasi Ruangan:</label>
                        <input type="text" id="location" name="location" class="form-input" value="{{ old('location') }}" placeholder="Contoh: Gedung PIP, Lt. 3" required>
                    </div>
                    
                    <div class="form-group">
                        @if(auth()->user()->role === 'super admin')
                            <label for="division_id" class="form-label">Divisi:</label>
                            <select name="division_id" id="division_id" class="form-input" required>
                                <option value="">-- Pilih Divisi --</option>
                                @foreach($divisions as $division)
                                    <option value="{{ $division->id }}" {{ old('division_id') == $division->id ? 'selected' : '' }}>
                                        {{ $division->name }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <input type="hidden" name="division_id" value="{{ auth()->user()->division_id }}">
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">Deskripsi (Opsional):</label>
                        <textarea name="description" id="description" class="form-input">{{ old('description') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="is_available" class="form-label">Status Ketersediaan:</label>
                        <select name="is_available" id="is_available" class="form-input">
                            <option value="1" {{ old('is_available', '1') == '1' ? 'selected' : '' }}>Available</option>
                            <option value="0" {{ old('is_available') == '0' ? 'selected' : '' }}>Unavailable</option>
                        </select>
                    </div>

                    <button type="submit" class="form-button">Simpan Ruangan</button>
                    <a href="{{ route('admin.rooms') }}" class="form-footer">← Kembali</a>
                </form>
            </div>
        </main>
    </div>

</body>
</html>