<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>All Divisions Booking Summary</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; margin-bottom: 2rem; } /* Added margin-bottom */
        th, td { border:1px solid #999; padding:6px 10px; text-align:left; }
        th { background-color: #eee; }
        h2 { text-align:center; margin-bottom: 0.25em; }
        h3 { margin-top: 2rem; margin-bottom: 0.5rem; } /* Style for division name */
        p { margin: 0.1em 0; }
        .page-break { page-break-after: always; } /* Class for page break */
    </style>
</head>
<body>
    {{-- Judul dokumen rekapitulasi --}}
    <h2>All Divisions Booking Summary</h2>

    {{-- Informasi pencetakan --}}
    <p><strong>Print Date:</strong> {{ now()->format('d-m-Y') }}</p>
    <p><strong>Printed By:</strong> {{ $printedBy }}</p>

    {{-- Loop untuk setiap divisi dan menampilkan booking yang terkait --}}
    @forelse($bookingsByDivision as $divisionName => $bookings)
        {{-- Judul untuk setiap divisi --}}
        <h3>Division: {{ $divisionName ?: 'No Division' }}</h3>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Room Name</th>
                    <th>Booked By</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                {{-- Loop untuk setiap booking di dalam divisi --}}
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
                        <td colspan="6" style="text-align:center;">No booking data for this division</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @empty
        {{-- Pesan jika tidak ada data booking sama sekali --}}
        <p style="text-align:center; margin-top: 2rem;">No booking data available at all.</p>
    @endforelse

</body>
</html>