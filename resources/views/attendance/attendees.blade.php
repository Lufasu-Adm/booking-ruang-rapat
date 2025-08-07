@extends('layouts.app')

@section('content')
<div class="content-box" style="
    font-family: 'Poppins', sans-serif; 
    background-color: #ffffff; 
    border-radius: 12px; 
    padding: 2rem; 
    margin: 2rem auto; 
    max-width: 800px; 
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);">

    <div style="text-align: center; margin-bottom: 1.5rem;">
        <h2 style="font-size: 1.75rem; color: #333; margin: 0;">Daftar Hadir: {{ $booking->purpose }}</h2>
        <p style="color: #666; margin-top: 0.5rem;">Tanggal: {{ \Carbon\Carbon::parse($booking->date)->format('d F Y') }}</p>
    </div>
    <hr style="border: none; border-top: 1px solid #eee; margin-bottom: 1.5rem;">
    
    {{-- (PERBAIKAN) Wadah QR Code dengan Flexbox untuk posisi tengah sempurna --}}
    <div class="qr-code-container" style="
        display: flex; 
        flex-direction: column; 
        align-items: center; 
        margin: 2rem 0;">
        
        {!! QrCode::size(200)->generate(route('attendance.create', $booking->id)) !!}
        <p style="color: #555; margin-top: 1rem; font-weight: 500;">Scan untuk mengisi daftar hadir</p>
    </div>

    <table class="data-table" style="width: 100%; border-collapse: collapse; margin-bottom: 2rem;">
        <thead style="background-color: #f8f9fa;">
            <tr>
                <th style="padding: 12px 15px; text-align: left; border-bottom: 2px solid #dee2e6; color: #495057;">No</th>
                <th style="padding: 12px 15px; text-align: left; border-bottom: 2px solid #dee2e6; color: #495057;">NIP</th>
                <th style="padding: 12px 15px; text-align: left; border-bottom: 2px solid #dee2e6; color: #495057;">Nama</th>
                <th style="padding: 12px 15px; text-align: left; border-bottom: 2px solid #dee2e6; color: #495057;">Divisi</th>
                <th style="padding: 12px 15px; text-align: left; border-bottom: 2px solid #dee2e6; color: #495057;">Instansi</th>
                <th style="padding: 12px 15px; text-align: left; border-bottom: 2px solid #dee2e6; color: #495057;">Waktu Mengisi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendees as $attendee)
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 12px 15px;">{{ $loop->iteration }}</td>
                <td style="padding: 12px 15px;">{{ $attendee->nip ?? '-' }}</td>
                <td style="padding: 12px 15px;">{{ $attendee->name }}</td>
                <td style="padding: 12px 15px;">{{ $attendee->division }}</td>
                <td style="padding: 12px 15px;">{{ $attendee->agency }}</td>
                <td style="padding: 12px 15px;">{{ $attendee->created_at->format('d M Y, H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 2rem; color: #777;">
                    Belum ada peserta yang mengisi daftar hadir.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div style="text-align: center;">
        <a href="{{ route('bookings.index') }}" class="btn-back" style="
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s;">
            Kembali ke Riwayat
        </a>
    </div>
</div>
@endsection