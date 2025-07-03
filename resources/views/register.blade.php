<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register - Ruang Rapat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>
<body>

    <div class="register-container">
        <div class="register-title">Register</div>

        {{-- Flash Message --}}
        @if(session('success'))
            <div class="flash-message flash-message-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="flash-message flash-message-error">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <input type="text" name="name" placeholder="Name" required class="register-input" value="{{ old('name') }}">
            <input type="email" name="email" placeholder="Email" required class="register-input" value="{{ old('email') }}">
            <input type="password" name="password" placeholder="Password" required class="register-input">
            <input type="password" name="password_confirmation" placeholder="Confirm Password" required class="register-input">

            <button type="submit" class="register-button">REGISTER</button>
        </form>

        <div class="form-footer">
            Already have an account? <a href="{{ route('login') }}">Login</a>
        </div>
    </div>

</body>
</html>