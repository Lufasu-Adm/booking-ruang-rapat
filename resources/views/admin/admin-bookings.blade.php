<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kelola Booking - Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite('resources/css/admin.css')
</head>
<body>

<div class="dashboard-wrapper" style="background-image: url('/assets/ai-generated-boat-picture.jpg');">

    <nav class="navbar">
        <a href="{{ url('/admin/dashboard') }}" class="navbar-brand">Dashboard Admin</a>
        
        <div class="navbar-actions">
            <a href="{{ url('/admin/dashboard') }}" class="navbar-btn back">Kembali</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="navbar-btn logout">Logout</button>
            </form>
        </div>
    </nav>
    <br>
    <div class="content-container">
        <div class="content-box">
            <h2>Kelola Booking</h2>
            
            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif

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
                            <span class="status status-{{$b->status}}">{{ $b->status }}</span>
                        </td>
                        <td data-label="Aksi">
                            @if($b->status == 'pending')
                                <form method="POST" action="{{ url('/admin/bookings/'.$b->id.'/approve') }}" style="display:inline;">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn-action approve">Approve</button>
                                </form>
                                <form method="POST" action="{{ url('/admin/bookings/'.$b->id.'/reject') }}" style="display:inline;">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn-action reject">Reject</button>
                                </form>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 2rem;">Tidak ada data booking yang perlu dikelola.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>