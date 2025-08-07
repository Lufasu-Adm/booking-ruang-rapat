<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manage Bookings</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite('resources/css/admin.css')
    @vite('resources/css/superadmin.css') {{-- Memuat CSS untuk pagination --}}
</head>
<body class="admin-bookings-page">

{{-- Struktur dasar halaman dengan wrapper --}}
<div class="page-wrapper" style="background-image: url('/assets/ai-generated-boat-picture.jpg');">

    {{-- Bagian navbar --}}
    <nav class="navbar">
        @auth
        @php
            $name = auth()->user()->name;
            $cleanName = preg_replace('/\b(USER|ADMIN|SUPERADMIN|DIVISI)\b\s*/i', '', $name);
        @endphp
        <div class="navbar-user-info">
            Welcome, <strong>{{ strtoupper(trim($cleanName)) }}</strong>
        </div>

        {{-- Link navigasi --}}
        <div class="navbar-links">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('booking.create') }}" class="{{ request()->routeIs('booking.create', 'booking/create') ? 'active' : '' }}">Booking</a>
            <a href="{{ route('admin.bookings') }}" class="{{ request()->routeIs('admin.bookings') ? 'active' : '' }}">Manage Bookings</a>
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
    
    {{-- Konten utama halaman --}}
    <main class="main-content">
        <div class="content-box">
            <h2>Manage Bookings</h2>
            
            {{-- Menampilkan pesan sukses dari session jika ada --}}
            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif

            <div style="overflow-x: auto;">
                <table class="booking-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Room</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Loop untuk menampilkan daftar booking --}}
                        @forelse($bookings as $b)
                        <tr>
                            <td data-label="User">{{ $b->user->name }}</td>
                            <td data-label="Room">{{ $b->room->name }}</td>
                            <td data-label="Date">{{ \Carbon\Carbon::parse($b->date)->translatedFormat('d M Y') }}</td>
                            <td data-label="Time">{{ \Carbon\Carbon::parse($b->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($b->end_time)->format('H:i') }}</td>
                            <td data-label="Status">
                                <span class="status status-{{ $b->status }}">{{ ucfirst($b->status) }}</span>
                            </td>
                            <td data-label="Action">
                                {{-- Tombol aksi hanya ditampilkan jika status booking 'pending' --}}
                                @if($b->status == 'pending')
                                    <div style="display: flex; gap: 5px;">
                                        {{-- Form untuk menyetujui booking --}}
                                        <form method="POST" action="{{ url('/admin/bookings/'.$b->id.'/approve') }}" style="display:inline-block;">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn-action approve">Approve</button>
                                        </form>
                                        {{-- Form untuk menolak booking --}}
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
                                No bookings to manage.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($bookings instanceof \Illuminate\Pagination\LengthAwarePaginator && $bookings->hasPages())
                <div class="pagination-wrapper" style="display: flex; justify-content: center; margin-top: 2rem;">
                    <ul class="pagination">
                        {{-- Link ke halaman sebelumnya --}}
                        @if ($bookings->onFirstPage())
                            <li class="disabled" aria-disabled="true"><span>&laquo;</span></li>
                        @else
                            <li><a href="{{ $bookings->previousPageUrl() }}" rel="prev">&laquo;</a></li>
                        @endif

                        {{-- Menampilkan link halaman --}}
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

                        {{-- Link ke halaman berikutnya --}}
                        @if ($bookings->hasMorePages())
                            <li><a href="{{ $bookings->nextPageUrl() }}" rel="next">&raquo;</a></li>
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