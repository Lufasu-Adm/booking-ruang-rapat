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
    @vite('resources/css/superadmin.css') {{-- Memuat CSS untuk pagination --}}
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
                <a href="{{ route('admin.bookings') }}" class="{{ request()->routeIs('admin.bookings') ? 'active' : '' }}">Manage Booking</a>
                <a href="{{ route('admin.rooms') }}" class="{{ request()->routeIs('admin.rooms') ? 'active' : '' }}">Manage Rooms</a>
                <a href="{{ route('rooms.index') }}" class="{{ request()->routeIs('rooms.index') ? 'active' : '' }}">Room List</a>
                <a href="{{ route('bookings.index') }}" class="{{ request()->routeIs('bookings.index') ? 'active' : '' }}">History</a>
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
                        <th>Purpose</th>
                        <th>Booking Time</th>
                        <th>PIC</th>
                        <th>Booking Status</th>
                        <th>Attendance List</th> {{-- <-- KOLOM BARU --}}
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $b)
                        <tr>
                            <td data-label="Booking Room">{{ $b->room->name }}</td>
                            <td data-label="Location">{{ $b->room->location ?? '-' }}</td>
                            <td data-label="Booking Date">{{ \Carbon\Carbon::parse($b->date)->format('d M Y') }}</td>
                            <td data-label="Division">{{ $b->room->division->name ?? '-' }}</td>
                            <td data-label="Department">{{ $b->department }}</td>
                            <td data-label="Purpose">{{ $b->purpose ?? '-' }}</td>
                            <td data-label="Booking Time">
                                @if ($b->start_time && $b->end_time)
                                    {{ \Carbon\Carbon::parse($b->start_time)->format('H:i') }}
                                    -
                                    {{ \Carbon\Carbon::parse($b->end_time)->format('H:i') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td data-label="PIC">{{ $b->pic?->name ?? $b->user->name }}</td>
                            <td data-label="Booking Status"><span class="status status-{{ $b->status }}">{{ ucfirst($b->status) }}</span></td>
                            
                            {{-- PERBAIKAN DI SINI: Menampilkan QR Code dan Tombol --}}
                            <td data-label="Attendance List">
                                @if($b->status === 'approved')
                                    <div style="text-align:center; min-width: 120px;">
                                        {!! QrCode::size(80)->generate(route('attendance.create', $b->id)) !!}
                                        <a href="{{ route('admin.bookings.attendees', $b->id) }}" class="btn-action" style="display:block; margin-top:5px; background-color: #0d6efd; color: white; text-align: center;">View Attendees</a>
                                    </div>
                                @else
                                    -
                                @endif
                            </td>
                            {{-- AKHIR PERBAIKAN --}}
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" style="text-align: center; padding: 2rem;">No booking history found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination Start -->
            @if ($bookings instanceof \Illuminate\Pagination\LengthAwarePaginator && $bookings->hasPages())
                <div class="pagination-wrapper" style="display: flex; justify-content: center; margin-top: 2rem;">
                    <ul class="pagination">
                        {{-- Previous Page Link --}}
                        @if ($bookings->onFirstPage())
                            <li class="disabled" aria-disabled="true"><span>&laquo;</span></li>
                        @else
                            <li><a href="{{ $bookings->previousPageUrl() }}" rel="prev">&laquo;</a></li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($bookings->links()->elements as $element)
                            {{-- "Three Dots" Separator --}}
                            @if (is_string($element))
                                <li class="disabled" aria-disabled="true"><span>{{ $element }}</span></li>
                            @endif

                            {{-- Array Of Links --}}
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    @if ($page == $bookings->currentPage())
                                        <li class="active" aria-current="page"><span>{{ $page }}</span></li>
                                    @else
                                        <li><a href="{{ $url }}">{{ $page }}</a></li>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($bookings->hasMorePages())
                            <li><a href="{{ $bookings->nextPageUrl() }}" rel="next">&raquo;</a></li>
                        @else
                            <li class="disabled" aria-disabled="true"><span>&raquo;</span></li>
                        @endif
                    </ul>
                </div>
            @endif
            <!-- Pagination End -->
        </div>
    </main>

</div>

</body>
</html>