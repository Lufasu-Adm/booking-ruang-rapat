<!DOCTYPE html>
<html>

<head>
    <title>Kelola Booking - Admin</title>
</head>

<body>
    <h2>Kelola Booking</h2>
    @if(session('success'))<div style="color:green;">{{ session('success') }}</div>@endif
    <table border="1" cellpadding="5">
        <tr>
            <th>User</th>
            <th>Ruangan</th>
            <th>Tanggal</th>
            <th>Waktu</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        @foreach($bookings as $b)
        <tr>
            <td>{{ $b->user->name }}</td>
            <td>{{ $b->room->name }}</td>
            <td>{{ $b->date }}</td>
            <td>{{ $b->start_time }} - {{ $b->end_time }}</td>
            <td>{{ ucfirst($b->status) }}</td>
            <td>
                @if($b->status == 'pending')
                <form method="POST" action="{{ url('/admin/bookings/'.$b->id.'/approve') }}" style="display:inline;">
                    @csrf @method('PATCH')
                    <button type="submit">Approve</button>
                </form>
                <form method="POST" action="{{ url('/admin/bookings/'.$b->id.'/reject') }}" style="display:inline;">
                    @csrf @method('PATCH')
                    <button type="submit">Reject</button>
                </form>
                @else
                {{ ucfirst($b->status) }}
                @endif
            </td>
        </tr>
        @endforeach
    </table>
    <a href="{{ url('/') }}">Kembali</a>
</body>

</html>