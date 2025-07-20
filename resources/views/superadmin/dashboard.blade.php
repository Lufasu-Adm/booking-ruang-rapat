<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Super Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite('resources/css/superadmin.css')
</head>
<body class="page-superadmin">
<div class="dashboard-wrapper">
    <nav class="navbar">
        <div class="navbar-left">
            <p>Welcome, <strong>{{ strtoupper(auth()->user()->name) }}</strong></p>
            <p>You are <strong>{{ strtoupper(auth()->user()->role) }}</strong></p>
        </div>
        <div class="navbar-right">
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="navbar-button">LOGOUT</button>
            </form>
        </div>
    </nav>

    <div class="content-card">
        <div class="card-header">
            <h2>Super Admin Panel</h2>
            {{-- PERBAIKAN DI SINI --}}
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <a href="{{ route('divisions.create') }}" class="add-button">+ Tambah Divisi</a>
                <a href="{{ route('bookings.export-filter') }}" class="add-button">📄 Laporan (Filter Tanggal)</a>
                <a href="{{ route('bookings.rekap.pdfAll') }}" class="add-button" target="_blank">📄 Rekap Semua (Tanpa Filter)</a>
            </div>
            {{-- AKHIR PERBAIKAN --}}
        </div>

        <div class="card-body">
            <table class="responsive-table">
                <thead>
                    <tr>
                        <th>Nama Divisi</th>
                        <th>Admin</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($divisions as $division)
                    @php
                        $admin = $division->users->where('role', 'admin')->first();
                    @endphp
                    <tr>
                        <td data-label="Divisi">{{ $division->name }}</td>
                        <td data-label="Admin">
                            @if($admin)
                                <div class="user-cell">
                                    <div class="name">{{ $admin->name }}</div>
                                    <div class="email">{{ $admin->email }}</div>
                                </div>
                            @else
                                -
                            @endif
                        </td>
                        <td data-label="Action">
                            <div class="action-buttons">
                                <div class="action-row">
                                    <a href="{{ route('divisions.edit', $division->id) }}" class="btn btn-edit">Edit</a>
                                    <form action="{{ route('divisions.destroy', $division->id) }}" method="POST" 
                                          onsubmit="return confirm('Are you sure you want to delete this division?');" 
                                          style="width: 100%;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-delete">Delete</button>
                                    </form>
                                </div>
                                @if($admin)
                                    <a href="{{ route('superadmin.admins.edit_password', $admin->id) }}" class="btn btn-password">
                                        Change Password
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align:center; padding: 2rem;">Belum ada divisi yang dibuat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>