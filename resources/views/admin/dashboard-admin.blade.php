<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Ruang Rapat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="background-color: #306199; font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; color: white; box-sizing: border-box; margin: 0; padding: 0; height: 100%; display: flex; justify-content: center; align-items: center; position: relative;">

    <div style="display: flex; flex-direction: column; min-height: 100vh; justify-content: center; align-items: center; position: relative; width: 100%; text-align: center;">
        <!-- Header Admin -->
        <div style="position: absolute; top: 1rem; left: 2rem; font-size: 1rem; color: white;">
            <p>Selamat Datang, <strong>{{ strtoupper(auth()->user()->name) }}</strong></p>
            <p>Role: <strong>{{ strtoupper(auth()->user()->role) }}</strong></p>
        </div>

        <!-- Tombol Admin -->
        <div style="display: flex; flex-direction: column; align-items: center; gap: 2rem; margin-top: 15vh;">
            <!-- Kelola Booking -->
            <a href="{{ route('admin.bookings') }}" style="background-color: white; color: #306199; font-weight: bold; font-size: 1.5rem; text-decoration: none; padding: 2rem 4rem; border-radius: 1.5rem; width: 280px; text-align: center; box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2); transition: background-color 0.3s ease;">
                Kelola Booking
            </a>

            <!-- Kelola Ruangan Divisinya -->
            <a href="{{ route('admin.rooms') }}" style="background-color: white; color: #306199; font-weight: bold; font-size: 1.5rem; text-decoration: none; padding: 2rem 4rem; border-radius: 1.5rem; width: 280px; text-align: center; box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2); transition: background-color 0.3s ease;">
                Kelola Ruangan
            </a>
        </div>

        <!-- Logout -->
        <div style="margin-top: 3rem; text-align: center;">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="background-color: #dc2626; color: white; border: none; padding: 0.75rem 2rem; font-size: 1rem; border-radius: 0.75rem; cursor: pointer; transition: background-color 0.3s ease;">
                    Logout
                </button>
            </form>
        </div>
    </div>

</body>
</html>