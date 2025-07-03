<!DOCTYPE html>
<html>

<head>
    <title>Booking Saya</title>
</head>

<body>
    <h2>Booking Saya</h2>
    @if(session('success'))<div style="color:green;">{{ session('success') }}</div>@endif
    <a href="{{ url('/booking/create') }}">Booking Ruangan</a>
    <table border="1" cellpadding="5">
        <tr>
            <th>Ruangan</th>
            <th>Tanggal</th>
            <th>Waktu</th>
            <th>Status</th>
        </tr>
        @foreach($bookings as $b)
        <tr>
            <td>{{ $b->room->name }}</td>
            <td>{{ $b->date }}</td>
            <td>{{ $b->start_time }} - {{ $b->end_time }}</td>
            <td>{{ ucfirst($b->status) }}</td>
        </tr>
        @endforeach
    </table>
    <a href="{{ url('/') }}">Kembali</a>
</body>

</html>