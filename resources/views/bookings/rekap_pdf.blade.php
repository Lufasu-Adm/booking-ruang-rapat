<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Booking Summary PDF' }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        /* Header Styles */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .header-table td {
            /* border: 1px solid #000; -- Removed */
            padding: 10px;
            text-align: center;
            vertical-align: middle;
        }

        .header-table .logo {
            width: 35%;
        }

        .header-table .title-cell {
            width: 30%;
            font-size: 12px;
            font-weight: normal;
        }
        
        .title-cell h3 {
            font-size: 14px;
            margin-bottom: 8px;
            font-weight: bold;
        }
        
        .title-cell p {
            font-size: 10px;
            color: #333;
            margin: 2px 0;
        }

        .header-table img {
            display: block;
            margin: 0 auto;
        }

        .logo-danantara {
            height: 40px;
            width: auto;
        }
        .logo-pal {
            height: 75px;
            width: auto;
        }

        /* Additional Styles */
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

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            page-break-inside: auto;
        }

        table.data-table tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        table.data-table th, table.data-table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        h3, p {
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
    {{-- Document Header --}}
    <table class="header-table">
        <tr>
            <td class="logo">
                <!-- <img src="{{ public_path('assets/LOGO DANANTARA.png') }}" alt="Logo Danantara" class="logo-danantara"> -->
            </td>
            <td class="title-cell">
                <h3>{{ $title ?? 'Room Booking Summary' }}</h3>
                @if ($startDate && $endDate)
                    <p>Period: {{ \Carbon\Carbon::parse($startDate)->format('d-m-Y') }} to {{ \Carbon\Carbon::parse($endDate)->format('d-m-Y') }}</p>
                @endif
            </td>
            <td class="logo">
                <!-- <img src="{{ public_path('assets/LOGO PAL MEMBER OF DEFEND ID.PNG') }}" alt="Logo PAL" class="logo-pal"> -->
            </td>
        </tr>
    </table>

    @forelse ($groupedBookings as $divisionName => $bookingsInDivision)
        <h4 class="division-title">Division: {{ $divisionName ?: 'Others' }}</h4>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Room</th>
                    <th>Purpose</th>
                    <th>User's Department</th>
                    <th>Booked By</th>
                    <th>Date</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bookingsInDivision as $i => $booking)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $booking->room->name }}</td>
                        <td>{{ $booking->purpose }}</td>
                        <td>{{ $booking->department }}</td>
                        <td>{{ $booking->user->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($booking->date)->format('d-m-Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @empty
        <p style="margin-top: 20px; text-align: center;">No booking data to display.</p>
    @endforelse
</body>
</html>