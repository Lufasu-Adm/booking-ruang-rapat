<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Book a Room - Reservation System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite('resources/css/admin.css')
</head>
<body class="booking-create-page">

    <div class="page-wrapper" style="background-image: url('/assets/ai-generated-boat-picture.jpg');">
        
<nav class="navbar">
            @auth
            @php
                $name = auth()->user()->name;
                $cleanName = preg_replace('/^(USER\s+|ADMIN\s+|SUPERADMIN\s+|DIVISI\s+)+/i', '', $name);
            @endphp
            <div class="navbar-user-info">
                Welcome, <strong>{{ strtoupper($cleanName) }}</strong>
            </div>

            <div class="navbar-links">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('booking.create') }}" class="{{ request()->routeIs('booking.create') ? 'active' : '' }}">Booking</a>
                <a href="{{ route('admin.bookings') }}" class="{{ request()->routeIs('admin.bookings') ? 'active' : '' }}">Manage Booking</a>
                <a href="{{ route('admin.rooms') }}" class="{{ request()->routeIs('admin.rooms') ? 'active' : '' }}">Manage Rooms</a>
                <a href="{{ route('rooms.index') }}" class="{{ request()->routeIs('rooms.index') ? 'active' : '' }}">Room List</a>
                <a href="{{ route('bookings.index') }}" class="{{ request()->routeIs('bookings.index') ? 'active' : '' }}">History</a>
            </div>

            <div class="navbar-right">
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="logout-button">LOGOUT</button>
                </form>
            </div>
            @endauth
        </nav>

        <main class="main-content">
            <div class="booking-form-wrapper">
                <h2 class="booking-title">Room Reservation</h2>

                <form method="POST" action="{{ route('booking.store') }}">
                    @csrf

                    @if(session('error'))
                        <div class="flash-message flash-message-error">{{ session('error') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="flash-message flash-message-error">
                            @foreach ($errors->all() as $error)
                                <div>{{$error}}</div>
                            @endforeach
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="division_id" class="form-label">Division</label>
                        <select id="division_id" name="division_id" required class="form-input">
                            <option value="" disabled selected hidden>Select a division</option>
                            @foreach($divisions as $division)
                                <option value="{{ $division->id }}">{{ $division->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="room_id" class="form-label">Meeting Room</label>
                        <select id="room_id" name="room_id" required class="form-input" disabled>
                            <option value="" disabled selected hidden>Select a division first</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="purpose" class="form-label">Purpose</label>
                        <input id="purpose" type="text" name="purpose" value="{{ old('purpose') }}" required class="form-input" placeholder="Enter the purpose of the meeting">
                    </div>

                    <div class="form-group">
                        <label for="department" class="form-label">Department</label>
                        <input id="department" type="text" name="department" value="{{ old('department') }}" required class="form-input" placeholder="Enter department name">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Meeting Time</label>
                        <div class="time-input-container">
                            <input id="start_time" type="time" name="start_time" value="{{ old('start_time') }}" required class="form-input">
                            <span>to</span>
                            <input id="end_time" type="time" name="end_time" value="{{ old('end_time') }}" required class="form-input">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="date" class="form-label">Meeting Date</label>
                        <input id="date" type="date" name="date" value="{{ old('date') }}" required class="form-input">
                    </div>
                    <div class="form-button-wrapper">
                        <button type="submit" class="form-button">SUBMIT RESERVATION</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const divisionSelect = document.getElementById('division_id');
            const roomSelect = document.getElementById('room_id');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            divisionSelect.addEventListener('change', function () {
                const divisionId = this.value;
                roomSelect.innerHTML = `<option disabled selected>Loading rooms...</option>`;
                roomSelect.disabled = true;

                if (!divisionId) return;

                fetch(`/api/rooms/by-division/${divisionId}`, {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error('Network response was not ok');
                    return res.json();
                })
                .then(data => {
                    roomSelect.innerHTML = '';
                    if (data.length > 0) {
                        roomSelect.disabled = false;
                        roomSelect.innerHTML = `<option value="" disabled selected hidden>Select a room</option>`;
                        data.forEach(room => {
                            roomSelect.innerHTML += `<option value="${room.id}">${room.name} (capacity: ${room.capacity})</option>`;
                        });
                    } else {
                        roomSelect.disabled = true;
                        roomSelect.innerHTML = `<option disabled selected>No rooms found</option>`;
                    }
                })
                .catch(err => {
                    console.error('Fetch error:', err);
                    roomSelect.innerHTML = `<option disabled selected>Error loading rooms</option>`;
                    roomSelect.disabled = true;
                });
            });
        });
    </script>
</body>

</html>