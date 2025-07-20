@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Rekap Booking (Super Admin)</h2>

    {{-- Form filter tanggal --}}
    <form method="GET" action="{{ route('bookings.export-filter') }}">
        <label for="start_date">Dari Tanggal:</label>
        <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}" required>

        <label for="end_date">Sampai Tanggal:</label>
        <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}" required>

        <button type="submit">Preview Rekap</button>
        
        {{-- Tombol ini sudah dipastikan mengarah ke dashboard superadmin --}}
        <a href="{{ route('superadmin.dashboard') }}" style="text-decoration: none; padding: 0.5rem 1rem; background-color: #6c757d; color: white; border-radius: 5px; margin-left: 0.5rem; font-size: 14px;">Kembali ke Dashboard</a>
    </form>

    {{-- Tampilkan tabel jika ada hasil --}}
    @if (!empty($bookings) && count($bookings) > 0)
        <hr>
        <table border="1" cellpadding="5" style="width: 100%; margin-top: 1rem;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Ruangan</th>
                    <th>Departemen Pengguna</th>
                    <th>Nama Pemesan</th>
                    <th>Tanggal</th>
                    <th>Jam</th>
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

        {{-- Tombol download PDF --}}
        <form method="POST" action="{{ route('bookings.rekap.pdf') }}" target="_blank" style="margin-top: 1rem;">
            @csrf
            <input type="hidden" name="start_date" value="{{ request('start_date') }}">
            <input type="hidden" name="end_date" value="{{ request('end_date') }}">
            <button type="submit">Download PDF</button>
        </form>

    @elseif(request()->has('start_date') && request()->has('end_date'))
        <p style="margin-top: 1rem;">Tidak ada data untuk tanggal tersebut.</p>
    @endif
</div>
@endsection