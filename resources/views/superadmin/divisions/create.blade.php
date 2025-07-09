<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Divisi Baru</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite('resources/css/superadmin.css')
  
</head>
<body class="page-superadmin">
  <div class="dashboard-wrapper" style="background: url('/assets/ai-generated-boat-picture.jpg') no-repeat center center; background-size: cover; min-height: 100vh;">
    <nav class="navbar">
      <div class="navbar-left">
        <p>Welcome, <strong>{{ strtoupper(auth()->user()->name) }}</strong></p>
        <p>You are <strong>{{ strtoupper(auth()->user()->role) }}</strong></p>
      </div>
      <div class="navbar-middle">
        <a href="{{ route('superadmin.dashboard') }}">Dashboard</a>
      </div>
      <div class="navbar-right">
        <a href="{{ route('superadmin.dashboard') }}" class="back-button-navbar">BACK</a>
      </div>
    </nav>

    <div class="dashboard-content">
      <h2 class="booking-title">Tambah Divisi Baru</h2>

      @if($errors->any())
        <div class="form-error">
          @foreach($errors->all() as $error)
            <p style="color: red;">{{ $error }}</p>
          @endforeach
        </div>
      @endif

      <form action="{{ route('divisions.store') }}" method="POST" class="booking-form">
        @csrf
        <div class="form-group">
          <label for="name">Nama Divisi</label>
          <input type="text" name="name" id="name" required value="{{ old('name') }}">
        </div>

        <div class="form-group">
          <label for="admin_name">Nama Admin</label>
          <input type="text" name="admin_name" id="admin_name" required value="{{ old('admin_name') }}">
        </div>

        <div class="form-group">
          <label for="admin_email">Email Admin</label>
          <input type="email" name="admin_email" id="admin_email" required value="{{ old('admin_email') }}">
        </div>

        <div class="form-group">
          <label for="user_name">Nama User</label>
          <input type="text" name="user_name" id="user_name" required value="{{ old('user_name') }}">
        </div>

        <div class="form-group">
          <label for="user_email">Email User</label>
          <input type="email" name="user_email" id="user_email" required value="{{ old('user_email') }}">
        </div>

        <button type="submit" class="form-button primary">Simpan</button>
      </form>
    </div>
  </div>
</body>
</html>