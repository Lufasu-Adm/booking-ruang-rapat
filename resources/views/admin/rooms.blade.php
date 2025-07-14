<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Ruangan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite('resources/css/admin.css')
</head>
<body>

    <nav class="navbar">
        <div class="navbar-user-info">
            <p>Selamat Datang, <strong>{{ strtoupper(auth()->user()->name) }}</strong></p>
            <p>Anda adalah <strong>{{ strtoupper(auth()->user()->role) }}</strong></p>
        </div>
        <div class="navbar-title">
           
        </div>
        
        <div class="navbar-user-actions">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">Kembali</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">Logout</button>
            </form>
        </div>
    </nav>

    <div class="page-wrapper">
        <div class="content-container">

            <div class="page-header">
                <h2>Kelola Ruangan</h2>
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('rooms.create') }}" class="btn btn-primary">+ Tambah Ruangan</a>
                @endif
            </div>

            <table class="data-table">
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
                    @forelse($rooms as $room)
                    <tr>
                        <td>{{ $room->name }}</td>
                        <td>{{ $room->capacity }} orang</td>
                        <td>{{ $room->description }}</td>
                        <td>
                            @if ($room->is_available)
                                <span class="status status-available">Available</span>
                            @else
                                <span class="status status-unavailable">Unavailable</span>
                            @endif
                        </td>
                        <td class="action-cell">
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('rooms.edit', $room->id) }}" class="btn btn-primary">Edit</a>
                                <form method="POST" action="{{ route('rooms.destroy', $room->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus ruangan ini?')">Hapus</button>
                                </form>
                            @else
                                <span>-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 2rem; color: var(--gray-text);">
                            Belum ada data ruangan yang tersedia.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            </div>
    </div>

</body>
</html>