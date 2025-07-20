@extends('layouts.app')

@section('content')
<div class="container">
  <h2>Ganti Password Admin</h2>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('superadmin.admins.update_password', $admin->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-group mb-3">
      <label>Admin: <strong>{{ $admin->name }}</strong> ({{ $admin->email }})</label>
    </div>

    <div class="form-group mb-3">
      <label>Password Baru</label>
      <input type="password" name="password" class="form-control" required>
    </div>

    <div class="form-group mb-3">
      <label>Konfirmasi Password</label>
      <input type="password" name="password_confirmation" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary">Ubah Password</button>
    <a href="{{ route('superadmin.dashboard') }}" class="btn btn-secondary">Batal</a>
  </form>
</div>
@endsection