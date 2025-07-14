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

    <div class="dashboard-wrapper" style="background: url('/assets/ai-generated-boat-picture.jpg') no-repeat center center; background-size: cover;">
        
        <nav class="navbar">
            <div class="navbar-title">
                Tambah Divisi Baru
            </div>
            <a href="{{ route('superadmin.dashboard') }}" class="back-button">Kembali</a>
        </nav>

        <div class="form-container">
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

            <form action="{{ route('divisions.store') }}" method="POST" class="form-grid">
                @csrf
                
                <div class="form-group">
                    <label for="name">Nama Divisi</label>
                    <input type="text" name="name" id="name" required value="{{ old('name') }}">
                </div>

                <div class="form-group">
                    <label for="admin_name">Nama Admin</label>
                    <input type="text" name="admin_name" id="admin_name" required value="{{ old('admin_name') }}">
                </div>

                <div class="form-group">
                    <label for="admin_email">Email Admin</label>
                    <input type="email" name="admin_email" id="admin_email" required value="{{ old('admin_email') }}">
                </div>

                <div class="form-group">
                    <label for="user_name">Nama User</label>
                    <input type="text" name="user_name" id="user_name" required value="{{ old('user_name') }}">
                </div>
                
                <div class="form-group">
                    <label for="user_email">Email User</label>
                    <input type="email" name="user_email" id="user_email" required value="{{ old('user_email') }}">
                </div>

                <div class="form-actions">
                    <button type="submit" class="form-button primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>