<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Selamat Datang di Aplikasi Peminjaman Ruang Rapat</title>
    <style>
        /* ========================== */
        /* ========== BASE ========== */
        /* ========================== */
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow-y: auto;
            background: url('/assets/Gemini_Generated_Image_apy2pfapy2pfapy2.png') no-repeat center center fixed;
            background-size: cover;
            font-family: sans-serif;
            color: white; /* Warna default teks */
        }

        .wrapper {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        /* Overlay hitam dengan transparansi */
        .wrapper::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5); /* overlay hitam dengan transparansi */
            z-index: 0;
            opacity: 0.1; 
        }

        .content {
            background: rgba(0, 0, 0, 0.8); /* Latar belakang konten dengan transparansi */
            padding: 2rem;
            border-radius: 1.5rem;
            max-width: 700px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            color: #f3f4f6; /* Teks menjadi lebih terang untuk kontras */
            z-index: 1; /* Agar konten tetap berada di atas overlay */
        }

        h1 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
            color:rgb(255, 255, 255); /* Warna judul */
        }

        p {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            color:rgb(255, 255, 255);
        }

        .btn {
            background-color: #306199;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 0.75rem;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 1rem;
        }

        /* Hover efek untuk tombol */
        .btn:hover {
            background-color: #254d77;
        }

        /* Tombol register dengan warna khusus */
        .btn-register {
            background-color: #16a34a;
        }

        /* Hover efek khusus untuk tombol register */
        .btn-register:hover {
            background-color: #14532d;
        }

    </style>
</head>
<body>

    {{-- Konten Utama Halaman Landing Page --}}
    <div class="wrapper">
        <div class="content">
            <h1>Peminjaman Ruang Rapat</h1>
            <p>Kelola dan pesan ruang rapat dengan mudah dan efisien.</p>
            
            {{-- Tombol untuk menuju halaman login --}}
            <a href="{{ route('login') }}" class="btn">Login</a>
            
            {{-- Tombol register (dapat diaktifkan jika fitur registrasi diperlukan) --}}
            {{-- <a href="{{ route('register') }}" class="btn btn-register">Register</a> --}}
        </div>
    </div>

</body>
</html>