<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Manage Rooms</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite('resources/css/admin.css')
    @vite('resources/css/superadmin.css') {{-- Memuat CSS untuk pagination --}}
</head>
<body class="admin-rooms-page">

{{-- Struktur dasar halaman dengan wrapper --}}
<div class="page-wrapper" style="background-image: url('/assets/Gemini_Generated_Image_apy2pfapy2pfapy2.png');">

    {{-- Bagian navbar --}}
    <nav class="navbar">
        @auth
        @php
            $name = auth()->user()->name;
            // Logika untuk membersihkan nama user dari prefix role
            $cleanName = preg_replace('/\b(USER|ADMIN|SUPERADMIN|DIVISI)\b\s*/i', '', $name);
        @endphp
        <div class="navbar-left">
            <div class="navbar-user-info">
                Welcome, <strong>{{ strtoupper(trim($cleanName)) }}</strong>
            </div>
        </div>

        {{-- Link navigasi --}}
        <div class="navbar-links">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('booking.create') }}" class="{{ request()->routeIs('booking.create') ? 'active' : '' }}">Booking</a>
            <a href="{{ route('admin.bookings') }}" class="{{ request()->routeIs('admin.bookings') ? 'active' : '' }}">Manage Bookings</a>
            <a href="{{ route('admin.rooms') }}" class="{{ request()->routeIs('admin.rooms') ? 'active' : '' }}">Manage Rooms</a>
            <a href="{{ route('rooms.index') }}" class="{{ request()->routeIs('rooms.index') ? 'active' : '' }}">Room List</a>
            <a href="{{ route('bookings.index') }}" class="{{ request()->routeIs('bookings.index') ? 'active' : '' }}">History</a>
        </div>

        {{-- Tombol logout --}}
        <div class="navbar-right">
            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                @csrf
                <button type="submit" class="logout-button">LOGOUT</button>
            </form>
        </div>
        @endauth
    </nav>

    {{-- Konten utama halaman --}}
    <main class="main-content">
        <div class="content-box">
            <div class="page-header">
                <h2>Manage Rooms</h2>
                {{-- Tombol tambah ruangan, hanya untuk user dengan role 'admin' --}}
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('rooms.create') }}" class="btn btn-primary">+ Add Room</a>
                @endif
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Capacity</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Loop untuk menampilkan daftar ruangan --}}
                    @forelse($rooms as $room)
                        <tr>
                            <td>{{ $room->name }}</td>
                            <td>{{ $room->capacity }} people</td>
                            <td>{{ $room->description ?? '-' }}</td>
                            <td>
                                {{-- Menampilkan status ketersediaan ruangan --}}
                                @if($room->is_available)
                                    <span class="status status-available">Available</span>
                                @else
                                    <span class="status status-unavailable">Unavailable</span>
                                @endif
                            </td>
                            <td class="action-cell">
                                {{-- Tombol aksi hanya ditampilkan jika user adalah 'admin' --}}
                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('rooms.edit', $room->id) }}" class="btn btn-primary">Edit</a>
                                    <form method="POST" action="{{ route('rooms.destroy', $room->id) }}" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this room?')">Delete</button>
                                    </form>
                                @else
                                    <span>-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 2rem;">
                                No room data available.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($rooms->hasPages())
                <div class="pagination-wrapper" style="display: flex; justify-content: center; margin-top: 2rem;">
                    <ul class="pagination">
                        {{-- Link ke halaman sebelumnya --}}
                        @if ($rooms->onFirstPage())
                            <li class="disabled" aria-disabled="true"><span>&laquo;</span></li>
                        @else
                            <li><a href="{{ $rooms->previousPageUrl() }}" rel="prev">&laquo;</a></li>
                        @endif

                        {{-- Menampilkan link halaman --}}
                        @foreach ($rooms->links()->elements as $element)
                            {{-- "Three Dots" Separator --}}
                            @if (is_string($element))
                                <li class="disabled" aria-disabled="true"><span>{{ $element }}</span></li>
                            @endif

                            {{-- Array Of Links --}}
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    @if ($page == $rooms->currentPage())
                                        <li class="active" aria-current="page"><span>{{ $page }}</span></li>
                                    @else
                                        <li><a href="{{ $url }}">{{ $page }}</a></li>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach

                        {{-- Link ke halaman berikutnya --}}
                        @if ($rooms->hasMorePages())
                            <li><a href="{{ $rooms->nextPageUrl() }}" rel="next">&raquo;</a></li>
                        @else
                            <li class="disabled" aria-disabled="true"><span>&raquo;</span></li>
                        @endif
                    </ul>
                </div>
            @endif
            </div>
    </main>

</div>

</body>
</html>