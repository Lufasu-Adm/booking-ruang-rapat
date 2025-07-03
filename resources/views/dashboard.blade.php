{{-- resources/views/dashboard.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Dashboard</h1>

    <div class="bg-white shadow-md rounded-lg p-6">
        <p class="text-lg mb-4">
            Selamat datang, <strong>{{ auth()->user()->name }}</strong>!
        </p>

        @if(auth()->user()->role === 'admin')
            <p class="text-green-600 font-semibold mb-4">Kamu adalah <strong>Admin</strong>.</p>
            <ul class="list-disc list-inside space-y-2 text-blue-600">
                <li><a href="{{ route('rooms.index') }}" class="underline hover:text-blue-800">Kelola Ruangan</a></li>
                <li><a href="{{ url('/admin/bookings') }}" class="underline hover:text-blue-800">Kelola Permintaan Booking</a></li>
            </ul>
        @else
            <p class="text-gray-700 mb-4">Kamu adalah <strong>User</strong>.</p>
            <ul class="list-disc list-inside space-y-2 text-blue-600">
                <li><a href="{{ route('rooms.index') }}" class="underline hover:text-blue-800">Lihat Ruangan</a></li>
                <li><a href="{{ route('booking.create') }}" class="underline hover:text-blue-800">Booking Ruangan</a></li>
                <li><a href="{{ route('bookings.index') }}" class="underline hover:text-blue-800">Riwayat Booking</a></li>
            </ul>
        @endif

        <div class="mt-6">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded transition">
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>
@endsection