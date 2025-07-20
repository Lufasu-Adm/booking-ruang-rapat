<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Room List</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite('resources/css/admin.css')
</head>
<body class="rooms-index-page">

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
            <h2>Room List</h2>

            @if(session('success'))
                <div class="flash-message flash-message-success" style="background-color:#d1fae5; color:#065f46; padding:1rem; border-radius:8px; text-align:center; margin-bottom:1.5rem;">
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="filter-container">
                <form method="GET" action="{{ route('rooms.index') }}" class="division-filter-form">
                    <label for="division_id">Filter by Division:</label>
                    <select name="division_id" id="division_id" onchange="this.form.submit()">
                        <option value="">-- All Divisions --</option>
                        @foreach($divisions as $division)
                            <option value="{{ $division->id }}" {{ (isset($selectedDivision) && $selectedDivision == $division->id) ? 'selected' : '' }}>
                                {{ $division->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            @if(request()->has('division_id') && request()->division_id != '')
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Capacity</th>
                        <th>Location</th>
                        <th>Division</th>
                        <th>Status</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rooms as $room)
                    <tr>
                        <td>{{ $room->name }}</td>
                        <td>{{ $room->capacity }}</td>
                        <td>{{ $room->location ?? '-' }}</td>
                        <td>{{ $room->division->name ?? '-' }}</td>
                        <td>
                            @if($room->is_available)
                                <span class="status status-available">Available</span>
                            @else
                                <span class="status status-unavailable">Unavailable</span>
                            @endif
                        </td>
                        <td>{{ $room->description ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center" style="text-align:center; padding: 2rem;">
                            No rooms available for this division.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            @else
            <div style="text-align:center; padding: 4rem 2rem; background-color: #f9fafb; border-radius: 8px; margin-top: 1.5rem;">
                <p style="margin:0; font-size: 1.1rem; color: #6b7280;">Silakan pilih divisi untuk menampilkan daftar ruangan.</p>
            </div>
            @endif
        </div>
    </main>

</div>

</body>
</html>
