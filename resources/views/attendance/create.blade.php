<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Hadir Rapat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7f6;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 1rem;
            box-sizing: border-box;
        }
        .form-card {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
            padding: 2rem;
            width: 100%;
            max-width: 500px;
        }
        .form-card h2 {
            text-align: center;
            color: #2c3e50;
            margin-top: 0;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        .form-card p {
            text-align: center;
            margin: 0.25rem 0;
            color: #555;
        }
        .form-card hr {
            border: none;
            border-top: 1px solid #e0e0e0;
            margin: 1.5rem 0;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .form-group {
            margin-bottom: 1.25rem;
        }
        .form-group label {
            display: block;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #495057;
        }
        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ced4da;
            border-radius: 8px;
            box-sizing: border-box;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control:focus {
            outline: none;
            border-color: #80bdff;
            box-shadow: 0 0 0 3px rgba(0,123,255,0.25);
        }
        .btn {
            width: 100%;
            padding: 14px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

@php
    $formFields = [
        ['name' => 'nip', 'label' => 'NIP (Opsional)', 'required' => false],
        ['name' => 'name', 'label' => 'Nama', 'required' => true],
        ['name' => 'division', 'label' => 'Divisi', 'required' => true],
        ['name' => 'agency', 'label' => 'Instansi', 'required' => true],
    ];
@endphp

<div class="form-card">
    <h2>Daftar Hadir</h2>
    <p><strong>Acara:</strong> {{ $booking->purpose }}</p>
    <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($booking->date)->format('d F Y') }}</p>
    <hr>

    @session('success')
        <div class="alert-success">{{ $value }}</div>
    @endsession

    <form action="{{ route('attendance.store', $booking->id) }}" method="POST">
        @csrf
        
        @foreach ($formFields as $field)
            <div class="form-group">
                <label for="{{ $field['name'] }}">{{ $field['label'] }}</label>
                <input type="text" 
                       name="{{ $field['name'] }}" 
                       id="{{ $field['name'] }}" 
                       class="form-control" 
                       @if($field['required']) required @endif>
            </div>
        @endforeach
        
        <button type="submit" class="btn">Simpan Kehadiran</button>
    </form>
</div>

</body>
</html>