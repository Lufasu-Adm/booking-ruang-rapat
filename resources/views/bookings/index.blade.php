<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Booking History</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite('resources/css/admin.css')
</head>
<body class="admin-booking-history-page">

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
            <a href="{{ route('booking.create') }}" class="{{ request()->routeIs('booking/create') ? 'active' : '' }}">Booking</a>
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
            <h2>Booking History</h2>

            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif

            <table class="data-table">
                <thead>
                    <tr>
                        <th>Booking Room</th>
                        <th>Location</th>
                        <th>Booking Date</th>
                        <th>Division</th>
                        <th>Department</th>
                        <th>Booking Time</th>
                        <th>PIC</th>
                        <th>Booking Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $b)
                        <tr>
                            <td>{{ $b->room->name }}</td>
                            <td>{{ $b->room->location ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($b->date)->format('d M Y') }}</td>
                            <td>{{ $b->room->division->name ?? '-' }}</td>
                            <td>{{ $b->department }}</td>
                            <td>
                                @if ($b->start_time && $b->end_time)
                                    {{ \Carbon\Carbon::parse($b->start_time)->format('H:i') }}
                                    -
                                    {{ \Carbon\Carbon::parse($b->end_time)->format('H:i') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $b->pic?->name ?? $b->user->name }}</td>
                            <td><span class="status status-{{ $b->status }}">{{ ucfirst($b->status) }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="pagination-wrapper">
                {{ $bookings->links('pagination::simple-tailwind') }}
            </div>
        </div>
    </main>

</div>

</body>
</html>