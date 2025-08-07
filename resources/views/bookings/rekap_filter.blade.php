<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Booking Summary - Reservation System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Internal CSS untuk styling halaman --}}
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            height: 100%;
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
            gap: 1.5rem;
        }

        .navbar-title {
            font-size: 1.2rem;
            color: white;
            white-space: nowrap;
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
        .navbar-button:hover { background-color: rgba(108, 117, 125, 1); }

        .main-content {
            flex-grow: 1;
            padding: 0 1.5rem 1.5rem 1.5rem;
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: center;
        }

        .content-box {
            width: 100%;
            max-width: 1200px;
            background-color: #ffffff;
            color: #1f2937;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
            align-self: flex-start;
        }

        .form-title {
            font-size: 1.75rem;
            margin-top: 0;
            margin-bottom: 1.5rem;
            text-align: center;
            font-weight: 600;
        }

        .filter-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding: 1rem;
            background-color: #f9fafb;
            border-radius: 8px;
        }

        .division-filter-form {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            width: 100%;
            justify-content: center;
            flex-wrap: wrap;
        }

        .division-filter-form label { font-weight: 500; }

        .form-input {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            border: 1px solid #d1d5db;
            font-family: 'Poppins', sans-serif;
            min-width: 180px;
        }

        .form-button {
            padding: 0.5rem 1rem;
            font-size: 14px;
            font-weight: 600;
            color: white;
            background-color: #0d6efd;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
            text-align: center;
            min-width: 150px;
            margin: 0;
        }
        .form-button:hover { background-color: #0b5ed7; }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1.5rem;
        }
        .data-table th, .data-table td {
            padding: 0.8rem 1rem;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
            vertical-align: middle;
        }
        .data-table th {
            background-color: #f9fafb;
            font-weight: 600;
        }

        /* Navbar Responsiveness */
        @media (max-width: 1024px) {
            .navbar { flex-direction: column; align-items: stretch; gap: 1rem; }
        }
    </style>
</head>
<body>

<div class="page-wrapper">
    {{-- Navbar dengan judul dan tombol kembali ke dashboard --}}
    <nav class="navbar">
        <div class="navbar-title">
            Room Booking System - Admin
        </div>

        <div class="navbar-right">
            {{-- Tombol kembali yang mengarah ke dashboard sesuai role user --}}
            @if (strtolower(auth()->user()->role) === 'super_admin')
                <a href="{{ route('superadmin.dashboard') }}" class="navbar-button">Return to Dashboard</a>
            @else
                <a href="{{ route('dashboard') }}" class="navbar-button">Return to Dashboard</a>
            @endif
        </div>
    </nav>

    {{-- Konten utama untuk menampilkan ringkasan booking --}}
    <main class="main-content">
        <div class="content-box">
            <h2 class="form-title">Booking Summary by Date</h2>

            {{-- Form filter untuk memilih rentang tanggal --}}
            <div class="filter-container">
                <form class="division-filter-form" method="GET" action="{{ route('bookings.export-filter') }}">
                    <label for="start_date">From Date:</label>
                    <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}" required class="form-input">

                    <label for="end_date">To Date:</label>
                    <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}" required class="form-input">

                    <button type="submit" class="form-button">Preview Summary</button>
                </form>
            </div>

            {{-- Menampilkan tabel data jika ada booking yang ditemukan --}}
            @if (!empty($bookings) && count($bookings) > 0)
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Room</th>
                            <th>User's Department</th>
                            <th>Booked By</th>
                            <th>Date</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bookings as $i => $booking)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $booking->room->name }}</td>
                                <td>{{ $booking->department }}</td>
                                <td>{{ $booking->user->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($booking->date)->format('d-m-Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Form untuk mengunduh rekap dalam format PDF --}}
                <form method="POST" action="{{ route('bookings.rekap.pdf') }}" target="_blank" style="margin-top: 1.5rem; text-align: right;">
                    @csrf
                    <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                    <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                    <button type="submit" class="form-button" style="background-color: #198754; padding: 0.6rem 1.2rem;">Download PDF</button>
                </form>
            @elseif(request()->has('start_date') && request()->has('end_date'))
                {{-- Pesan jika tidak ada data booking untuk rentang tanggal yang dipilih --}}
                <p style="text-align: center; margin-top: 2rem; font-size: 1.1rem; color: #4b5563;">No data available for the selected date range.</p>
            @endif
        </div>
    </main>
</div>

</body>
</html>