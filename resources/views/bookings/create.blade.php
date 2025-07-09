<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book a Room</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="page-book">
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

        <div class="dashboard-content">
            <div class="booking-form-wrapper">
                <h2 class="booking-title">Book a Room</h2>

                @if(session('error'))
                    <div class="flash-message flash-message-error">{{ session('error') }}</div>
                @endif

                <form method="POST" action="{{ url('/booking') }}">
                    @csrf

                    {{-- Select Division --}}
                    <div class="form-group">
                        <label for="division_id" class="form-label">Division</label>
                        <select id="division_id" name="division_id" required class="form-input">
                            <option disabled selected hidden>Select a division</option>
                            @foreach($divisions as $division)
                                <option value="{{ $division->id }}">{{ $division->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Select Room (populated dynamically based on division) --}}
                    <div class="form-group">
                        <label for="room_id" class="form-label">Meeting Room</label>
                        <select id="room_id" name="room_id" required class="form-input" disabled>
                            <option disabled selected hidden>Select a division first</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="date" class="form-label">Meeting Date</label>
                        <input id="date" type="date" name="date" value="{{ old('date') }}" required class="form-input">
                    </div>

                    <div class="form-group">
                        <label for="department" class="form-label">Department</label>
                        <input id="department" type="text" name="department" value="{{ old('department') }}" required class="form-input" placeholder="Enter Department Name">
                    </div>

                    <div class="form-group">
                        <label for="start_time" class="form-label">Start Time</label>
                        <input id="start_time" type="time" name="start_time" value="{{ old('start_time') }}" required class="form-input">
                    </div>

                    <div class="form-group">
                        <label for="end_time" class="form-label">End Time</label>
                        <input id="end_time" type="time" name="end_time" value="{{ old('end_time') }}" required class="form-input">
                    </div>

                    <div class="form-button-wrapper">
                        <button type="submit" class="form-button">BOOK</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const divisionSelect = document.getElementById('division_id');
            const roomSelect = document.getElementById('room_id');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            divisionSelect.addEventListener('change', function () {
                const divisionId = this.value;

                fetch(`/api/rooms/by-division/${divisionId}`, {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    roomSelect.innerHTML = '';

                    if (data.length > 0) {
                        roomSelect.disabled = false;
                        roomSelect.innerHTML += `<option disabled selected hidden>Select a room</option>`;
                        data.forEach(room => {
                            roomSelect.innerHTML += `<option value="${room.id}">${room.name} (capacity: ${room.capacity})</option>`;
                        });
                    } else {
                        roomSelect.disabled = true;
                        roomSelect.innerHTML = `<option disabled selected>No rooms found</option>`;
                    }
                })
                .catch(err => {
                    console.error(err);
                    roomSelect.innerHTML = `<option disabled selected>Error loading rooms</option>`;
                    roomSelect.disabled = true;
                });
            });
        });
    </script>
</body>
</html>