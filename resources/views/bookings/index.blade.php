<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Booking History</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>
<body>
    <div class="dashboard-wrapper" style="background: url('/assets/ai-generated-boat-picture.jpg') no-repeat center center; background-size: cover;">
    
@auth
<nav class="navbar">
    <div class="navbar-left">
        <p>Welcome, <strong>{{ strtoupper(auth()->user()->name) }}</strong></p>
        <p>You are <strong>{{ strtoupper(auth()->user()->role) }}</strong></p>
    </div>

    <div class="navbar-middle">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <a href="{{ route('booking.create') }}">Booking</a>
        <a href="{{ route('rooms.index') }}">Room List</a>
        <a href="{{ route('bookings.index') }}">History</a>
    </div>

    <div class="navbar-right">
        <a href="{{ route('dashboard') }}" class="back-button-navbar">BACK</a>
    </div>
</nav>
@endauth

<div class="dashboard-wrapper">
  <div class="dashboard-content">
    <div class="booking-form-wrapper">
        <h2 style="text-align: center; font-size: 2rem; font-weight: bold; margin-bottom: 2rem;">Booking History</h2>

        @if(session('success'))
            <div class="flash-message flash-message-success">{{ session('success') }}</div>
        @endif

        <table class="booking-table">
            <thead>
                <tr>
                    <th>Booking Room</th>
                    <th>Location</th>
                    <th>Booking Date</th>
                    <th>Division</th>
                    <th>Department</th>
                    <th>Booking Time</th>
                    <th>PIC</th>
                    <th>Booking Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $b)
                    <tr>
                        <td>{{ $b->room->name }}</td>
                        <td>{{ $b->room->location ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($b->date)->format('d M Y') }}</td>
                        <td>{{ $b->room->division->name ?? '-' }}</td>
                        <td>{{ $b->department }}</td>
                        <td>{{ substr($b->start_time, 0, 5) }} - {{ substr($b->end_time, 0, 5) }}</td>
                        <td>{{ $b->pic?->name ?? '-' }}</td>
                        <td><span class="status-{{ $b->status }}">{{ ucfirst($b->status) }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="pagination-wrapper">
            <ul class="pagination">
                @if ($bookings->onFirstPage())
                    <li><span class="disabled">&laquo;</span></li>
                @else
                    <li><a href="{{ $bookings->previousPageUrl() }}" rel="prev">&laquo;</a></li>
                @endif

                @foreach ($bookings->getUrlRange(1, $bookings->lastPage()) as $page => $url)
                    @if ($page == $bookings->currentPage())
                        <li class="active"><span>{{ $page }}</span></li>
                    @else
                        <li><a href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach

                @if ($bookings->hasMorePages())
                    <li><a href="{{ $bookings->nextPageUrl() }}" rel="next">&raquo;</a></li>
                @else
                    <li><span class="disabled">&raquo;</span></li>
                @endif
            </ul>
        </div>
    </div>
  </div>
</div>

</body>
</html>