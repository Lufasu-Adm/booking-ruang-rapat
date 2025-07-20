<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Ruang Rapat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="
    background: url('/assets/ai-generated-boat-picture.jpg') no-repeat center center fixed;
    background-size: cover;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0;
    padding: 0;
">

    <div style="
        background-color: #ffffff;
        padding: 2rem;
        border-radius: 1.5rem;
        width: 100%;
        max-width: 28rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        text-align: center;
    ">
        <div style="
            font-size: 1.875rem;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 1.5rem;
        ">Sign in</div>

        {{-- Flash Message --}}
        @if(session('success'))
            <div style="text-align: center; margin-bottom: 1rem; font-size: 0.875rem; color: #16a34a;">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div style="text-align: center; margin-bottom: 1rem; font-size: 0.875rem; color: #dc2626;">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}">
            @csrf
            <input type="text" name="email" placeholder="Email" required style="
                display: block;
                width: 100%;
                padding: 0.75rem;
                margin-bottom: 1rem;
                border: 1px solid #d1d5db;
                border-radius: 0.75rem;
                font-size: 1rem;
                background-color: white;
                color: #1f2937;
                box-sizing: border-box;
            ">

            <input type="password" name="password" placeholder="Password" required style="
                display: block;
                width: 100%;
                padding: 0.75rem;
                margin-bottom: 1rem;
                border: 1px solid #d1d5db;
                border-radius: 0.75rem;
                font-size: 1rem;
                background-color: white;
                color: #1f2937;
                box-sizing: border-box;
            ">

            <button type="submit" style="
                display: block;
                width: 100%;
                padding: 0.75rem;
                background-color: #306199;
                color: white;
                font-weight: bold;
                border: none;
                border-radius: 0.75rem;
                font-size: 1rem;
                cursor: pointer;
                transition: background-color 0.3s ease;
                box-sizing: border-box;
            " onmouseover="this.style.backgroundColor='#254d77'" onmouseout="this.style.backgroundColor='#306199'">
                LOGIN
            </button>
        </form>
    </div>
</body>
</html>
