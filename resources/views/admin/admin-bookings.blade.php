<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Booking - Admin</title>
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

        <div style="margin-top: 120px;">
            <h2 class="booking-title">Kelola Booking Ruangan</h2>

            @if(session('success'))
                <div class="alert-success" style="text-align:center;">
                    {{ session('success') }}
                </div>
            @endif

            <table class="booking-table">
                <thead>
                    <tr>
                        <th>Nama Pengguna</th>
                        <th>Ruangan</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bookings as $booking)
                        <tr>
                            <td>{{ $booking->user->name }}</td>
                            <td>{{ $booking->room->name }}</td>
                            <td>{{ $booking->date }}</td>
                            <td>{{ $booking->start_time }} - {{ $booking->end_time }}</td>
                            <td>
                                <span class="status-{{ $booking->status }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td>
                                @if ($booking->status === 'pending')
                                    <form method="POST" action="{{ route('admin.bookings.approve', $booking->id) }}" style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="form-button" style="width:auto; background-color:#16a34a;">Approve</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.bookings.reject', $booking->id) }}" style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="form-button" style="width:auto; background-color:#dc2626;">Reject</button>
                                    </form>
                                @else
                                    <span>-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center;">Tidak ada data booking.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div style="text-align:center; margin-top: 2rem;">
                <a href="{{ route('admin.dashboard') }}" class="dashboard-button">← Kembali ke Dashboard</a>
            </div>
        </div>
    </div>

</body>
</html>