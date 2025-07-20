<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Booking Semua Divisi</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; margin-bottom: 2rem; } /* Tambahkan margin-bottom */
        th, td { border:1px solid #999; padding:6px 10px; text-align:left; }
        th { background-color: #eee; }
        h2 { text-align:center; margin-bottom: 0.25em; }
        h3 { margin-top: 2rem; margin-bottom: 0.5rem; } /* Style untuk nama divisi */
        p { margin: 0.1em 0; }
        .page-break { page-break-after: always; } /* Class untuk pindah halaman */
    </style>
</head>
<body>
    <h2>Rekap Booking Semua Divisi</h2>

    <p>Tanggal Cetak: {{ now()->format('d M Y H:i') }}</p>
    <p>Dicetak oleh: {{ $printedBy }}</p>

    {{-- Lakukan iterasi untuk setiap divisi --}}
    @forelse($bookingsByDivision as $divisionName => $bookings)
        <h3>Divisi: {{ $divisionName ?: 'Tidak Ada Divisi' }}</h3>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Ruangan</th>
                    <th>Peminjam</th>
                    <th>Mulai</th>
                    <th>Selesai</th>
                    <th>Status</th>
                   
                </tr>
            </thead>
            <tbody>
                {{-- Iterasi untuk setiap booking di dalam divisi tersebut --}}
                @forelse($bookings as $booking)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $booking->room->name ?? '-' }}</td>
                        <td>{{ $booking->user->name ?? '-' }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }}
                            {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }}
                            {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                        </td>
                        <td>{{ ucfirst($booking->status) }}</td>
                        
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;">Tidak ada data booking untuk divisi ini</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @empty
        <p style="text-align:center; margin-top: 2rem;">Tidak ada data booking sama sekali.</p>
    @endforelse

</body>
</html>