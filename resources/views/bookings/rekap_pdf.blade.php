<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Rekap Booking PDF' }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .division-title {
            margin-top: 25px;
            margin-bottom: 5px;
            padding-bottom: 5px;
            border-bottom: 1px solid #555;
            font-size: 14px;
        }
        .division-title:first-of-type {
            margin-top: 0;
        }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; page-break-inside: auto; }
        tr { page-break-inside: avoid; page-break-after: auto; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        h3, p { margin: 0; padding: 0; }
    </style>
</head>
<body>
    <h3>{{ $title ?? 'Rekap Booking Ruangan' }}</h3>

    @if ($startDate && $endDate)
        <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d-m-Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d-m-Y') }}</p>
    @endif

    {{-- Loop untuk setiap divisi --}}
    @forelse ($groupedBookings as $divisionName => $bookingsInDivision)
        <h4 class="division-title">Divisi: {{ $divisionName ?: 'Lainnya' }}</h4>
        
        <table>
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
                {{-- Loop untuk setiap booking di dalam divisi tersebut --}}
                @foreach ($bookingsInDivision as $i => $booking)
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
    @empty
        <p style="margin-top: 20px; text-align: center;">Tidak ada data booking untuk ditampilkan.</p>
    @endforelse

</body>
</html>