<!DOCTYPE html>
<html>

<head>
    <title>Buat Booking Ruang Rapat</title>
</head>

<body>
    <h2>Booking Ruangan</h2>
    @if(session('error'))<div style="color:red;">{{ session('error') }}</div>@endif
    <form method="POST" action="{{ url('/booking') }}">
        @csrf
        <label>Ruangan:</label><br>
        <select name="room_id" required>
            @foreach($rooms as $room)
            <option value="{{ $room->id }}" @if(old('room_id')==$room->id) selected @endif>
                {{ $room->name }} (kapasitas {{ $room->capacity }})
            </option>
            @endforeach
        </select><br><br>
        <label>Tanggal:</label><br>
        <input type="date" name="date" value="{{ old('date') }}" required><br><br>
        <label>Waktu Mulai:</label><br>
        <input type="time" name="start_time" value="{{ old('start_time') }}" required><br><br>
        <label>Waktu Selesai:</label><br>
        <input type="time" name="end_time" value="{{ old('end_time') }}" required><br><br>
        <button type="submit">Booking</button>
        <a href="{{ url('/bookings') }}">Kembali</a>
    </form>
</body>

</html>