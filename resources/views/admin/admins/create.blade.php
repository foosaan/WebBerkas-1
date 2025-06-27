@extends('admin.app')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
    </div>

    {{-- DIV TAMBAHAN BERISI TABEL USER MANAGER --}}
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" style="color: #6c63ff; text-decoration: none;">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">User Manager</li>
            </ol>
        </nav>

        <!-- Add Data Button -->
        <div class="mb-4">
            <a href="{{ route('admin.admins.create') }}" class="btn"
                style="background-color: #28a745; color: white; border-radius: 5px; padding: 8px 16px; text-decoration: none; font-weight: 500;">
                + Tambah Data
            </a>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Data Table -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                            <tr>
                                <th scope="col" style="text-align: center;">No</th>
                                <th scope="col" style="text-align: center;">Avatar</th>
                                <th scope="col">Username</th>
                                <th scope="col">Email</th>
                                <th scope="col">Nama</th>
                                <th scope="col">NIP</th>
                                <th scope="col" style="text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($admins as $index => $admin)
                                <tr>
                                    <td style="text-align: center;">{{ $index + 1 }}</td>
                                    <td style="text-align: center;">
                                        @if($admin->avatar)
                                            <img src="{{ asset('storage/' . $admin->avatar) }}" alt="Avatar"
                                                style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                                        @else
                                            <div style="width: 40px; height: 40px; border-radius: 50%; background-color: #6c63ff; display: flex; align-items: center; justify-content: center;">
                                                <svg width="20" height="20" fill="white" viewBox="0 0 24 24">
                                                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $admin->username ?? strtolower(str_replace(' ', '', $admin->name)) }}</td>
                                    <td>{{ $admin->email }}</td>
                                    <td>{{ $admin->name }}</td>
                                    <td>{{ $admin->nip }}</td>
                                    <td style="text-align: center;">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-sm btn-warning">Edit</a>
                                            <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus admin ini?')">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="text-align: center;">Belum ada admin yang terdaftar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Admin Count Info -->
        <div class="mt-3">
            <small class="text-muted">
                <strong>Total Admin:</strong> {{ $adminCount ?? count($admins) }}
            </small>
        </div>
    </div>

    {{-- DIV TAMBAHAN BERISI FORM TAMBAH ADMIN --}}
    <div class="container-fluid mt-5">
        <h2 class="mb-4">Tambah Admin</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('admin.admins.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">NIP</label>
                <input type="text" name="nip" class="form-control" required value="{{ old('nip') }}">
                @error('nip') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
@endsection
