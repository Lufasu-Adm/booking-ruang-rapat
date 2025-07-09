<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Room List</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  @if(Auth::user()->role === 'admin')
    @vite('resources/css/admin.css')
  @else
    @vite('resources/css/app.css')
  @endif
</head>

<body class="page-roomlist">
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
        <h2 class="booking-title">Room List</h2>

        @if(session('success'))
          <div class="flash-message flash-message-success">
            {{ session('success') }}
          </div>
        @endif

        @if(Auth::user()->role === 'admin')
          <a href="{{ url('/rooms/create') }}" class="form-button primary">
            + Add Room
          </a>
        @endif

        <form method="GET" action="{{ route('rooms.index') }}" class="division-filter-form">
  <label for="division_id">Filter by Division:</label>
  <select name="division_id" id="division_id" onchange="this.form.submit()">
    <option value="">-- All Divisions --</option>
    @foreach($divisions as $division)
      <option value="{{ $division->id }}" {{ (isset($selectedDivision) && $selectedDivision == $division->id) ? 'selected' : '' }}>
        {{ $division->name }}
      </option>
    @endforeach
  </select>
</form>


        <div class="booking-room-button-wrapper">
          <a href="{{ url('/booking/create') }}" class="form-button full-width">BOOKING ROOM</a>
        </div>

        <table class="booking-table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Capacity</th>
              <th>Location</th>
              <th>Division</th>
              <th>Status</th>
              <th>Description</th>
              @if(Auth::user()->role === 'admin')
                <th>Actions</th>
              @endif
            </tr>
          </thead>
          <tbody>
            @forelse($rooms as $room)
              <tr>
                <td>{{ $room->name }}</td>
                <td>{{ $room->capacity }}</td>
                <td>{{ $room->location ?? '-' }}</td>
                <td>{{ $room->division->name ?? '-' }}</td>
                <td>
                  @if($room->is_available)
                    <span class="status-approved">Available</span>
                  @else
                    <span class="status-rejected">Unavailable</span>
                  @endif
                </td>
                <td>{{ $room->description ?? '-' }}</td>
                @if(Auth::user()->role === 'admin')
                  <td>
                    <a href="{{ url('/rooms/'.$room->id.'/edit') }}" class="form-button secondary">Edit</a>
                    <form method="POST" action="{{ url('/rooms/'.$room->id) }}" style="display:inline">
                      @csrf @method('DELETE')
                      <button type="submit" class="logout-button secondary" onclick="return confirm('Are you sure you want to delete this room?')">Delete</button>
                    </form>
                  </td>
                @endif
              </tr>
            @empty
              <tr>
                <td colspan="{{ Auth::user()->role === 'admin' ? 7 : 6 }}" class="text-center">
                  No rooms available.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>