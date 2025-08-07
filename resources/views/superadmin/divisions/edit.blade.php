<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Edit Divisi - Sistem Reservasi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    {{-- Google Fonts untuk styling --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- CSS Internal untuk styling halaman --}}
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            height: 100%;
            background-color: #f0f2f5; /* Fallback background */
        }

        .page-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-image: url('/assets/ai-generated-boat-picture.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            position: relative;
        }

        .page-wrapper::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 0;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            margin: 1.5rem;
            position: relative;
            z-index: 1;
        }

        .navbar-title { 
            font-size: 1.2rem; 
            color: white; 
            font-weight: 600;
        }

        .navbar-button {
            background-color: rgba(108, 117, 125, 0.8);
            color: white;
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: background-color 0.3s ease;
            white-space: nowrap;
            text-decoration: none;
        }
        .navbar-button:hover {
            background-color: rgba(108, 117, 125, 1);
        }

        .main-content {
            flex-grow: 1;
            padding: 0 1.5rem 1.5rem 1.5rem;
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        .form-card {
            width: 100%;
            max-width: 600px;
            background-color: #ffffff;
            color: #1f2937;
            border-radius: 12px;
            padding: 2.5rem;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }

        .form-title {
            font-size: 1.75rem;
            margin-top: 0;
            margin-bottom: 2rem;
            text-align: center;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #374151;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            background-color: #f9fafb;
            box-sizing: border-box;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
        }
        
        .form-actions {
            margin-top: 2rem;
        }

        .btn {
            width: 100%;
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
            text-align: center;
            font-size: 1rem;
        }

        .btn-primary {
            background-color: #0d6efd;
            color: white;
        }
        .btn-primary:hover {
            background-color: #0b5ed7;
        }

        .alert {
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 8px;
            border: 1px solid transparent;
        }
        .alert-danger {
            color: #842029;
            background-color: #f8d7da;
            border-color: #f5c2c7;
        }
        .alert-danger ul {
            margin: 0;
            padding-left: 1.2rem;
        }

    </style>
</head>
<body>

{{-- Wrapper utama halaman --}}
<div class="page-wrapper">
    {{-- Navbar sederhana dengan judul dan tombol kembali --}}
    <nav class="navbar">
        <div class="navbar-title">
            Manajemen Divisi
        </div>
        <div class="navbar-right">
            <a href="{{ route('superadmin.dashboard') }}" class="navbar-button">Kembali</a>
        </div>
    </nav>

    {{-- Konten utama form --}}
    <main class="main-content">
        <div class="form-card">
            <h2 class="form-title">Edit Divisi</h2>

            {{-- Menampilkan pesan error validasi jika ada --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form untuk mengedit data divisi dan admin --}}
            <form action="{{ route('divisions.update', $division->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Form group untuk Nama Divisi --}}
                <div class="form-group">
                    <label for="name">Nama Divisi</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $division->name) }}" required>
                </div>

                {{-- Form group untuk Nama Admin --}}
                <div class="form-group">
                    <label for="admin_name">Nama Admin</label>
                    <input type="text" id="admin_name" name="admin_name" class="form-control" value="{{ old('admin_name', $admin->name ?? '') }}" required>
                </div>

                {{-- Form group untuk Email Admin --}}
                <div class="form-group">
                    <label for="admin_email">Email Admin</label>
                    <input type="email" id="admin_email" name="admin_email" class="form-control" value="{{ old('admin_email', $admin->email ?? '') }}" required>
                </div>

                {{-- Tombol submit untuk menyimpan perubahan --}}
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </main>
</div>

</body>
</html>