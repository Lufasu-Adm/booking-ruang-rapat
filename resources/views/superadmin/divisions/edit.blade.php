@extends('layouts.app')

@section('content')
<div class="container">
  <h2>Edit Divisi</h2>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('divisions.update', $division->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-group mb-3">
      <label>Nama Divisi</label>
      <input type="text" name="name" class="form-control" value="{{ old('name', $division->name) }}" required>
    </div>

    <div class="form-group mb-3">
      <label>Nama Admin</label>
      <input type="text" name="admin_name" class="form-control" value="{{ old('admin_name', $admin->name ?? '') }}" required>
    </div>

    <div class="form-group mb-3">
      <label>Email Admin</label>
      <input type="email" name="admin_email" class="form-control" value="{{ old('admin_email', $admin->email ?? '') }}" required>
    </div>

    <div class="form-group mb-3">
      <label>Nama User</label>
      <input type="text" name="user_name" class="form-control" value="{{ old('user_name', $user->name ?? '') }}" required>
    </div>

    <div class="form-group mb-3">
      <label>Email User</label>
      <input type="email" name="user_email" class="form-control" value="{{ old('user_email', $user->email ?? '') }}" required>
    </div>

    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    <a href="{{ route('superadmin.dashboard') }}" class="btn btn-secondary">Batal</a>
  </form>
</div>
@endsection