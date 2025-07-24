@extends('layouts.app')

@section('content')
<div class="content-box">
    <h2>Daftar Hadir: {{ $booking->purpose }}</h2>
    <p>Tanggal: {{ \Carbon\Carbon::parse($booking->date)->format('d F Y') }}</p>
    <hr>
    
    {{-- Wadah QR Code dengan kelas khusus --}}
    <div class="qr-code-container">
        {!! QrCode::size(200)->generate(route('attendance.create', $booking->id)) !!}
        <p>Scan untuk mengisi daftar hadir</p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>NIP</th>
                <th>Nama</th>
                <th>Divisi</th>
                <th>Instansi</th>
                <th>Waktu Mengisi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendees as $attendee)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $attendee->nip ?? '-' }}</td>
                <td>{{ $attendee->name }}</td>
                <td>{{ $attendee->division }}</td>
                <td>{{ $attendee->agency }}</td>
                <td>{{ $attendee->created_at->format('d M Y, H:i') }}</td>
            </tr>
            @empty
            <tr>
                {{-- Tambahkan kelas untuk text-align --}}
                <td colspan="6" class="text-center">Belum ada peserta yang mengisi daftar hadir.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    {{-- Tombol dengan kelas khusus --}}
    <a href="{{ route('bookings.index') }}" class="btn-back">Kembali ke Riwayat</a>
</div>
@endsection