{{-- Formulir input untuk tambah/edit ruangan --}}
<label for="name" class="form-label">Nama Ruangan:</label>
<input type="text" name="name" class="form-input" value="{{ old('name', $room->name ?? '') }}" required>

<label for="capacity" class="form-label">Kapasitas:</label>
<input type="number" name="capacity" class="form-input" value="{{ old('capacity', $room->capacity ?? '') }}" min="1" required>

<label for="location" class="form-label">Lokasi:</label>
<input type="text" name="location" class="form-input" value="{{ old('location', $room->location ?? '') }}" required>

{{-- Divisi (khusus Super Admin) --}}
@if(auth()->user()->role === 'super admin')
    <label for="division_id" class="form-label">Divisi:</label>
    <select name="division_id" class="form- input" required>
        <option value="">-- Pilih Divisi --</option>
        @foreach($divisions as $division)
            <option value="{{ $division->id }}" {{ old('division_id', $room->division_id ?? '') == $division->id ? 'selected' : '' }}>
                {{ $division->name }}
            </option>
        @endforeach
    </select>
@else
    <input type="hidden" name="division_id" value="{{ $room->division_id ?? auth()->user()->division_id }}">
@endif

<label for="description" class="form-label">Deskripsi:</label>
<textarea name="description" class="form-input" rows="3">{{ old('description', $room->description ?? '') }}</textarea>

<label for="is_available" class="form-label">Status:</label>
<select name="is_available" class="form-input">
    <option value="1" {{ old('is_available', $room->is_available ?? 1) == 1 ? 'selected' : '' }}>Tersedia</option>
    <option value="0" {{ old('is_available', $room->is_available ?? 0) == 0 ? 'selected' : '' }}>Tidak Tersedia</option>
</select>
