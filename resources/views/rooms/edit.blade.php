<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Ruangan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>

<body>
    <div class="login-card">
        <h2 class="login-title">Edit Ruangan</h2>

        @if($errors->any())
            <div class="alert-error">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ url('/rooms/' . $room->id) }}">
            @csrf
            @method('PUT')

            <label for="name" class="form-label">Nama Ruangan:</label>
            <input type="text" name="name" class="form-input" value="{{ old('name', $room->name) }}" required>

            <label for="capacity" class="form-label">Kapasitas:</label>
            <input type="number" name="capacity" class="form-input" value="{{ old('capacity', $room->capacity) }}" min="1" required>

            <label for="location" class="form-label">Lokasi Ruangan:</label>
            <input type="text" name="location" class="form-input" value="{{ old('location', $room->location) }}" placeholder="Contoh: Gedung PIP, Lt. 3" required>

            <label for="division" class="form-label">Divisi:</label>
            <input type="text" name="division" class="form-input" value="{{ old('division', $room->division) }}" placeholder="Contoh: TI" required>

            <label for="description" class="form-label">Deskripsi:</label>
            <textarea name="description" class="form-input">{{ old('description', $room->description) }}</textarea>

            <label for="is_available" class="form-label">Status Ketersediaan:</label>
            <select name="is_available" class="form-input">
                <option value="1" {{ old('is_available', $room->is_available) == 1 ? 'selected' : '' }}>Available</option>
                <option value="0" {{ old('is_available', $room->is_available) == 0 ? 'selected' : '' }}>Unavailable</option>
            </select>

            <button type="submit" class="form-button">Update</button>
            <a href="{{ url('/rooms') }}" class="form-footer" style="display:block; text-align:center; margin-top:1rem;">← Kembali</a>
        </form>
    </div>
</body>

</html>