<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Divisi Baru</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite('resources/css/superadmin.css')
</head>
<body class="page-superadmin">

{{-- Wrapper utama halaman --}}
<div class="dashboard-wrapper" style="background: url('/assets/ai-generated-boat-picture.jpg') no-repeat center center; background-size: cover;">
    
    {{-- Navbar dengan judul dan tombol kembali --}}
    <nav class="navbar">
        <div class="navbar-title">
            Tambah Divisi Baru
        </div>
        <a href="{{ route('superadmin.dashboard') }}" class="back-button">Kembali</a>
    </nav>

    {{-- Kontainer form untuk menambah divisi --}}
    <div class="form-container">
        {{-- Menampilkan pesan error validasi jika ada --}}
        @if($errors->any())
            <div class="alert">
                <strong>Terjadi Kesalahan:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form untuk mengirim data divisi dan admin baru --}}
        <form action="{{ route('divisions.store') }}" method="POST" class="form-grid">
            @csrf
            
            {{-- Form group untuk nama divisi --}}
            <div class="form-group">
                <label for="name">Nama Divisi</label>
                <input type="text" name="name" id="name" required value="{{ old('name') }}">
            </div>

            {{-- Form group untuk nama admin --}}
            <div class="form-group">
                <label for="admin_name">Nama Admin</label>
                <input type="text" name="admin_name" id="admin_name" required value="{{ old('admin_name') }}">
            </div>

            {{-- Form group untuk email admin --}}
            <div class="form-group">
                <label for="admin_email">Email Admin</label>
                <input type="email" name="admin_email" id="admin_email" required value="{{ old('admin_email') }}">
            </div>
            
            {{-- Tombol submit form --}}
            <div class="form-actions">
                <button type="submit" class="form-button primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>