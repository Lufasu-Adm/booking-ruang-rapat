<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Super Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite('resources/css/superadmin.css')
</head>

<body class="page-superadmin">
  <div class="dashboard-wrapper" style="background: url('/assets/ai-generated-boat-picture.jpg') no-repeat center center; background-size: cover;">
    <nav class="navbar">
      <div class="navbar-left">
        <p>Welcome, <strong>{{ strtoupper(auth()->user()->name) }}</strong></p>
        <p>You are <strong>{{ strtoupper(auth()->user()->role) }}</strong></p>
      </div>
      <div class="navbar-middle">
        <a href="{{ route('superadmin.dashboard') }}">Dashboard</a>
      </div>
      <div class="navbar-right">
        {{-- Tombol BACK --}}
        <a href="{{ route('superadmin.dashboard') }}" class="back-button-navbar">BACK</a>

        {{-- Tombol Logout --}}
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
          @csrf
          <button type="submit" class="logout-button-navbar">Logout</button>
        </form>
      </div>
    </nav>

    <div class="dashboard-content">
      <h2 class="booking-title">Super Admin Panel</h2>

      <a href="{{ url('/divisions/create') }}" class="form-button primary">+ Add New Division</a>

      <div class="booking-form-wrapper">
        <table class="booking-table">
          <thead>
            <tr>
              <th>Division</th>
              <th>Admin</th>
              <th>User</th>
            </tr>
          </thead>
          <tbody>
            @foreach($divisions as $division)
              <tr>
                <td>{{ $division->name }}</td>
                <td>
                  @php
                    $admin = $division->users->where('role', 'admin')->first();
                  @endphp
                  {{ $admin ? $admin->name . ' (' . $admin->email . ')' : '-' }}
                </td>
                <td>
                  @php
                    $user = $division->users->where('role', 'user')->first();
                  @endphp
                  {{ $user ? $user->name . ' (' . $user->email . ')' : '-' }}
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>