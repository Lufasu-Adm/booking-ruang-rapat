<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Ruangan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>
<body class="bg">

    <div class="dashboard-wrapper">
        <!-- Header -->
        <div class="user-header">
            <p>Selamat Datang, <strong>{{ strtoupper(auth()->user()->name) }}</strong></p>
            <p>Role: <strong>{{ strtoupper(auth()->user()->role) }}</strong></p>
        </div>

        <h2 style="text-align:center; margin-bottom: 2rem;">Daftar Ruangan</h2>

        <table class="booking-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Kapasitas</th>
                    <th>Deskripsi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rooms as $room)
                <tr>
                    <td>{{ $room->name }}</td>
                    <td>{{ $room->capacity }}</td>
                    <td>{{ $room->description }}</td>
                    <td>
                        @if($room->is_available)
                            <span class="status-approved">Available</span>
                        @else
                            <span class="status-rejected">Unavailable</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('rooms.edit', $room->id) }}" class="form-button" style="padding: 0.5rem 1rem; text-decoration: none;">Edit</a>
                        <form method="POST" action="{{ route('rooms.destroy', $room->id) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="logout-button" style="padding: 0.5rem 1rem;">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="booking-footer">
            <a href="{{ route('rooms.create') }}" class="form-button" style="width: auto;">+ Tambah Ruangan</a>
            <br><br>
            <a href="{{ route('admin.dashboard') }}" class="back-button-navbar">← Kembali ke Dashboard</a>
        </div>
    </div>

</body>
</html>