<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kelola Booking - Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite('resources/css/admin.css')
</head>
<body class="admin-bookings-page">

<div class="page-wrapper" style="background-image: url('/assets/ai-generated-boat-picture.jpg');">

    <nav class="navbar">
        @auth
        @php
            $name = auth()->user()->name;
            $cleanName = preg_replace('/\b(USER|ADMIN|SUPERADMIN|DIVISI)\b\s*/i', '', $name);
        @endphp
        <div class="navbar-user-info">
            Welcome, <strong>{{ strtoupper(trim($cleanName)) }}</strong>
        </div>

        <div class="navbar-links">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('booking.create') }}" class="{{ request()->routeIs('booking.create', 'booking/create') ? 'active' : '' }}">Booking</a>
            <a href="{{ route('admin.bookings') }}" class="{{ request()->routeIs('admin.bookings') ? 'active' : '' }}">Kelola Booking</a>
            <a href="{{ route('admin.rooms') }}" class="{{ request()->routeIs('admin.rooms') ? 'active' : '' }}">Kelola Ruangan</a>
            <a href="{{ route('rooms.index') }}" class="{{ request()->routeIs('rooms.index') ? 'active' : '' }}">Room List</a>
            <a href="{{ route('bookings.index') }}" class="{{ request()->routeIs('bookings.index') ? 'active' : '' }}">Riwayat</a>
        </div>

        <div class="navbar-right">
            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                @csrf
                <button type="submit" class="logout-button">LOGOUT</button>
            </form>
        </div>
        @endauth
    </nav>
    
    <main class="main-content">
        <div class="content-box">
            <h2>Kelola Booking</h2>
            
            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif

            <div style="overflow-x: auto;"> <!-- Wrapper untuk tabel agar bisa scroll horizontal jika perlu -->
                <table class="booking-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Ruangan</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $b)
                        <tr>
                            <td data-label="User">{{ $b->user->name }}</td>
                            <td data-label="Ruangan">{{ $b->room->name }}</td>
                            <td data-label="Tanggal">{{ \Carbon\Carbon::parse($b->date)->translatedFormat('d M Y') }}</td>
                            <td data-label="Waktu">{{ \Carbon\Carbon::parse($b->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($b->end_time)->format('H:i') }}</td>
                            <td data-label="Status">
                                <span class="status status-{{ $b->status }}">{{ ucfirst($b->status) }}</span>
                            </td>
                            <td data-label="Aksi">
                                @if($b->status == 'pending')
                                    <div style="display: flex; gap: 5px;">
                                        <form method="POST" action="{{ url('/admin/bookings/'.$b->id.'/approve') }}" style="display:inline-block;">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn-action approve">Approve</button>
                                        </form>
                                        <form method="POST" action="{{ url('/admin/bookings/'.$b->id.'/reject') }}" style="display:inline-block;">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn-action reject">Reject</button>
                                        </form>
                                    </div>
                                @else
                                    <em>-</em>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2rem;">
                                Tidak ada data booking yang perlu dikelola.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</div>

</body>
</html>
