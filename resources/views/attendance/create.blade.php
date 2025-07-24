<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Hadir Rapat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* ... (CSS untuk halaman publik yang simpel) ... */
    </style>
</head>
<body>
    <div class="form-card">
        <h2>Daftar Hadir</h2>
        <p><strong>Acara:</strong> {{ $booking->purpose }}</p>
        <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($booking->date)->format('d F Y') }}</p>
        <hr>
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        <form action="{{ route('attendance.store', $booking->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="nip">NIP (Opsional)</label>
                <input type="text" name="nip" id="nip" class="form-control">
            </div>
            <div class="form-group">
                <label for="name">Nama</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="division">Divisi</label>
                <input type="text" name="division" id="division" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="agency">Instansi</label>
                <input type="text" name="agency" id="agency" class="form-control" required>
            </div>
            <button type="submit" class="btn">Simpan Kehadiran</button>
        </form>
    </div>
</body>
</html>