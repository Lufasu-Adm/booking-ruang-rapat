<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Ruangan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>
<body class="page-wrapper" style="background: url('/assets/ai-generated-boat-picture.jpg') no-repeat center center fixed; background-size: cover;">
    <div class="login-card">
        <h2 class="login-title">Edit Ruangan</h2>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert-error">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('rooms.update', $room->id) }}">
            @csrf
            @method('PUT')

            {{-- Asumsi file _form.blade.php menggunakan class seperti .form-group, .form-label, .form-input --}}
            @include('rooms._form', ['room' => $room])

            <button type="submit" class="form-button">Update</button>
            <a href="{{ route(auth()->user()->role === 'super admin' ? 'rooms.index' : 'admin.rooms') }}" class="form-footer">← Kembali</a>
        </form>
    </div>
</body>
</html>