<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Super Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite('resources/css/superadmin.css')
</head>
<body class="page-superadmin">
{{-- Wrapper utama halaman Super Admin --}}
<div class="dashboard-wrapper">
    {{-- Bagian navbar untuk menampilkan info user dan tombol logout --}}
    <nav class="navbar">
        <div class="navbar-left">
            <p>Welcome, <strong>{{ strtoupper(auth()->user()->name) }}</strong></p>
            <p>Your role is <strong>{{ strtoupper(auth()->user()->role) }}</strong></p>
        </div>
        <div class="navbar-right">
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="navbar-button">LOGOUT</button>
            </form>
        </div>
    </nav>

    {{-- Konten utama dashboard --}}
    <div class="content-card">
        <div class="card-header">
            <h2>Super Admin Panel</h2>
            {{-- Tombol-tombol aksi untuk Super Admin --}}
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <a href="{{ route('divisions.create') }}" class="add-button">+ Add Division</a>
                <a href="{{ route('bookings.export-filter') }}" class="add-button">📄 Recap (Date Filter)</a>
                <a href="{{ route('bookings.rekap.pdfAll') }}" class="add-button" target="_blank">📄 All Recap (No Filter)</a>
            </div>
        </div>

        <div class="card-body">
            {{-- Tabel responsif untuk menampilkan daftar divisi --}}
            <table class="responsive-table">
                <thead>
                    <tr>
                        <th>Division Name</th>
                        <th>Admin</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Loop untuk menampilkan setiap divisi dan adminnya --}}
                    @forelse($divisions as $division)
                    @php
                        // Mencari user dengan role 'admin' di divisi saat ini
                        $admin = $division->users->where('role', 'admin')->first();
                    @endphp
                    <tr>
                        <td data-label="Division">{{ $division->name }}</td>
                        <td data-label="Admin">
                            @if($admin)
                                <div class="user-cell">
                                    <div class="name">{{ $admin->name }}</div>
                                    <div class="email">{{ $admin->email }}</div>
                                </div>
                            @else
                                -
                            @endif
                        </td>
                        <td data-label="Action">
                            <div class="action-buttons">
                                <div class="action-row">
                                    <a href="{{ route('divisions.edit', $division->id) }}" class="btn btn-edit">Edit</a>
                                    {{-- Form untuk menghapus divisi --}}
                                    <form action="{{ route('divisions.destroy', $division->id) }}" method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this division?');"
                                          style="width: 100%;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-delete">Delete</button>
                                    </form>
                                </div>
                                @if($admin)
                                    {{-- Tombol untuk mengubah password admin --}}
                                    <a href="{{ route('superadmin.admins.edit_password', $admin->id) }}" class="btn btn-password">
                                        Change Password
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align:center; padding: 2rem;">No divisions have been created yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Bagian pagination --}}
            @if ($divisions->hasPages())
                <div class="pagination-wrapper" style="display: flex; justify-content: center;">
                    <ul class="pagination">
                        {{-- Link ke halaman sebelumnya --}}
                        @if ($divisions->onFirstPage())
                            <li class="disabled" aria-disabled="true"><span>&laquo;</span></li>
                        @else
                            <li><a href="{{ $divisions->previousPageUrl() }}" rel="prev">&laquo;</a></li>
                        @endif

                        {{-- Menampilkan link halaman --}}
                        @foreach ($divisions->links()->elements as $element)
                            @if (is_string($element))
                                <li class="disabled" aria-disabled="true"><span>{{ $element }}</span></li>
                            @endif
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    @if ($page == $divisions->currentPage())
                                        <li class="active" aria-current="page"><span>{{ $page }}</span></li>
                                    @else
                                        <li><a href="{{ $url }}">{{ $page }}</a></li>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach

                        {{-- Link ke halaman berikutnya --}}
                        @if ($divisions->hasMorePages())
                            <li><a href="{{ $divisions->nextPageUrl() }}" rel="next">&raquo;</a></li>
                        @else
                            <li class="disabled" aria-disabled="true"><span>&raquo;</span></li>
                        @endif
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>
</body>
</html>