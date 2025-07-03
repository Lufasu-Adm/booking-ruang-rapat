<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Ruang Rapat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>
<body>

    <div class="login-card">
        <div class="login-title">Sign in</div>

        {{-- Flash Message --}}
        @if(session('success'))
            <div class="flash-message flash-message-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="flash-message flash-message-error">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <input type="text" name="email" placeholder="Username" required class="form-input">
            <input type="password" name="password" placeholder="Password" required class="form-input">
            <button type="submit" class="form-button">LOGIN</button>
        </form>

        <div class="form-footer">
            Don’t have an account? <a href="{{ route('register') }}">Register</a>
        </div>
    </div>

</body>
</html>