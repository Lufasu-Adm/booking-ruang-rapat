<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <title>Dashboard - Ruang Rapat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite(['resources/css/app.css']) 
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}" />
  </head>
  <body>
    <div class="dashboard-wrapper" style="background: url('/assets/ai-generated-boat-picture.jpg') no-repeat center center; background-size: cover;">

    {{-- Navbar --}}
    @auth
      @php
        $name = auth()->user()->name;
        $cleanName = preg_replace('/^(USER|ADMIN|SUPERADMIN)\s+/i', '', $name);
      @endphp

      <nav class="navbar">
        <div class="navbar-left">
          <p>Welcome, <strong>{{ strtoupper($cleanName) }}</strong></p>
          <p>You are <strong>{{ strtoupper(auth()->user()->role) }}</strong></p>
        </div>

        <div class="navbar-middle">
          <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">Dashboard</a>
          <a href="{{ route('booking.create') }}" class="{{ request()->is('booking/create') ? 'active' : '' }}">Booking</a>
          <a href="{{ route('rooms.index') }}" class="{{ request()->is('rooms') ? 'active' : '' }}">Room List</a>
          <a href="{{ route('bookings.index') }}" class="{{ request()->is('bookings') ? 'active' : '' }}">History</a>
        </div>

        <div class="navbar-right">
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-button-navbar">LOGOUT</button>
          </form>
        </div>
      </nav>
    @endauth

    {{-- Dashboard Content --}}
    <div class="dashboard-wrapper">
      @guest
        <p style="text-align:center; color:white; margin-top: 20vh;">
          Silakan
          <a href="{{ route('login') }}" style="color: #fff; text-decoration: underline;">login</a>
          terlebih dahulu.
        </p>
      @endguest

      @auth
        <div class="dashboard-content">
          <header class="dashboard-header">
            <h1>Welcome to the Meeting Room Reservation System</h1>
            <p>Manage and monitor your meeting room bookings</p>
          </header>

          {{-- Left & Right Layout --}}
          <section class="dashboard-summary">
            <div class="summary-left">
              <div class="card yellow">
                <h3>Total Rooms</h3>
                <p>{{ $totalRooms ?? 0 }} Rooms</p>
              </div>
              <div class="card blue">
                <h3>Total Bookings</h3>
                <p>{{ $totalBookings ?? 0 }} Bookings</p>
              </div>
              <div class="card green">
                <h3>Active Bookings</h3>
                <p>{{ $activeBookings ?? 0 }} Active</p>
              </div>
            </div>
            <div class="summary-right">
              <div class="card date-time">
                <p>{{ \Carbon\Carbon::now()->translatedFormat('l') }}</p>
                <h2>{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</h2>
                <h1 id="live-time">{{ \Carbon\Carbon::now()->format('H:i') }}</h1>
              </div>
            </div>
          </section>

          {{-- Tabel Booking Dihilangkan --}}
          {{-- <div class="booking-table-container"> ... </div> --}}
        </div>
      @endauth
    </div>

    <script>
      function updateTime() {
        const now = new Date();
        const jam = now.toLocaleTimeString("id-ID", {
          hour: "2-digit",
          minute: "2-digit",
          hour12: false
        });
        const jamElement = document.getElementById("live-time");
        if (jamElement) {
          jamElement.textContent = jam;
        }
      }

      setInterval(updateTime, 1000);
      updateTime();
    </script>

  </body>
</html>
