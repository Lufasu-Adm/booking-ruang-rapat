<!DOCTYPE html>
<html>

<head>
    <title>Tambah Ruangan</title>
</head>

<body>
    <h2>Tambah Ruangan</h2>
    @if($errors->any())
    <div style="color: red;">
        @foreach($errors->all() as $error)
        <div>{{ $error }}</div>
        @endforeach
    </div>
    @endif
    <form method="POST" action="{{ url('/rooms') }}">
        @csrf
        <label>Nama Ruangan:</label><br>
        <input type="text" name="name" value="{{ old('name') }}" required><br><br>

        <label>Kapasitas:</label><br>
        <input type="number" name="capacity" value="{{ old('capacity') }}" min="1" required><br><br>

        <label>Deskripsi:</label><br>
        <textarea name="description">{{ old('description') }}</textarea><br><br>

        <button type="submit">Simpan</button>
        <a href="{{ url('/rooms') }}">Kembali</a>
    </form>
</body>

</html>