<head>
    <meta charset="utf-g">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Styles -->
    {{-- PASTIKAN BARIS INI ADA DAN PATH-NYA BENAR --}}
    <link href="{{ asset('css/superadmin.css') }}" rel="stylesheet">
</head>
@extends('layouts.app')

@section('content')
{{-- Wrapper ini akan memberikan background dan tata letak dasar --}}
<div class="dashboard-wrapper"> 
    
    {{-- Container utama untuk form --}}
    <div class="form-container-edit">
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

            <div class="form-group">
                <label for="name">Nama Divisi</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $division->name) }}" required>
            </div>

            <div class="form-group">
                <label for="admin_name">Nama Admin</label>
                <input type="text" id="admin_name" name="admin_name" class="form-control" value="{{ old('admin_name', $admin->name ?? '') }}" required>
            </div>

            <div class="form-group">
                <label for="admin_email">Email Admin</label>
                <input type="email" id="admin_email" name="admin_email" class="form-control" value="{{ old('admin_email', $admin->email ?? '') }}" required>
            </div>

            <div class="form-group">
                <label for="user_name">Nama User</label>
                <input type="text" id="user_name" name="user_name" class="form-control" value="{{ old('user_name', $user->name ?? '') }}" required>
            </div>

            <div class="form-group">
                <label for="user_email">Email User</label>
                <input type="email" id="user_email" name="user_email" class="form-control" value="{{ old('user_email', $user->email ?? '') }}" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('superadmin.dashboard') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
