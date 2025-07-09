<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Ruangan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>

<body class="bg">

    <div class="login-card">
        <h2 class="login-title">Tambah Ruangan</h2>

        @if($errors->any())
            <div class="alert-error">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('rooms.store') }}">
            @csrf

            <label for="name" class="form-label">Nama Ruangan:</label>
            <input type="text" name="name" class="form-input" value="{{ old('name') }}" required>

            <label for="capacity" class="form-label">Kapasitas:</label>
            <input type="number" name="capacity" class="form-input" value="{{ old('capacity') }}" min="1" required>

            <label for="location" class="form-label">Lokasi Ruangan:</label>
            <input type="text" name="location" class="form-input" value="{{ old('location') }}" placeholder="Contoh: Gedung PIP, Lt. 3" required>

            {{-- Divisi: Super Admin bisa pilih, Admin tidak bisa --}}
            @if(auth()->user()->role === 'super admin')
                <label for="division_id" class="form-label">Divisi:</label>
                <select name="division_id" class="form-input" required>
                    <option value="">-- Pilih Divisi --</option>
                    @foreach($divisions as $division)
                        <option value="{{ $division->id }}" {{ old('division_id') == $division->id ? 'selected' : '' }}>
                            {{ $division->name }}
                        </option>
                    @endforeach
                </select>
            @else
                <input type="hidden" name="division_id" value="{{ auth()->user()->division_id }}">
            @endif

            <label for="description" class="form-label">Deskripsi:</label>
            <textarea name="description" class="form-input">{{ old('description') }}</textarea>

            <label for="is_available" class="form-label">Status Ketersediaan:</label>
            <select name="is_available" class="form-input">
                <option value="1" {{ old('is_available') == '1' ? 'selected' : '' }}>Available</option>
                <option value="0" {{ old('is_available') == '0' ? 'selected' : '' }}>Unavailable</option>
            </select>

            <button type="submit" class="form-button">Simpan</button>
            <a href="{{ route('rooms.index') }}" class="form-footer" style="display:block; text-align:center; margin-top:1rem;">← Kembali</a>
        </form>
    </div>

</body>
</html>