<!DOCTYPE html>
<html>

<head>
    <title>Daftar Ruang Rapat</title>
</head>

<body>
    <h2>Daftar Ruangan</h2>
    @if(Auth::user()->role === 'admin')
    <a href="{{ url('/rooms/create') }}">Tambah Ruangan</a>
    @endif
    <ul>
        @foreach($rooms as $room)
        <li>
            <b>{{ $room->name }}</b> (Kapasitas: {{ $room->capacity }})
            @if(Auth::user()->role === 'admin')
            <a href="{{ url('/rooms/'.$room->id.'/edit') }}">Edit</a>
            <form method="POST" action="{{ url('/rooms/'.$room->id) }}" style="display:inline">
                @csrf @method('DELETE')
                <button type="submit" onclick="return confirm('Yakin hapus?')">Hapus</button>
            </form>
            @endif
        </li>
        @endforeach
    </ul>
    <a href="{{ url('/booking/create') }}">Booking Ruangan</a> |
    <a href="{{ url('/bookings') }}">Riwayat Booking Saya</a> |
    <a href="{{ url('/') }}">Kembali</a>
</body>

</html>