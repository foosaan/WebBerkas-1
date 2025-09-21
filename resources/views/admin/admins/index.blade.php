@extends('admin.app')

@section('content')
    <!-- BAGIAN DASHBOARD YANG TIDAK DIUBAH -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Admin</h1>
       
    </div>

    <!-- DIV BARU YANG DITAMBAHKAN -->
    <div class="container-fluid">
    

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
                                <th scope="col" style="padding: 15px; font-weight: 600; color: #6c757d; text-align: center; width: 80px;">No</th>
                                <th scope="col" style="padding: 15px; font-weight: 600; color: #6c757d; text-align: center; width: 100px;">Avatar</th>
                                <th scope="col" style="padding: 15px; font-weight: 600; color: #6c757d;">Username</th>
                                <th scope="col" style="padding: 15px; font-weight: 600; color: #6c757d;">Email</th>
                                <th scope="col" style="padding: 15px; font-weight: 600; color: #6c757d;">Nama</th>
                                <th scope="col" style="padding: 15px; font-weight: 600; color: #6c757d;">NIP</th>
                                <th scope="col" style="padding: 15px; font-weight: 600; color: #6c757d; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($admins as $index => $admin)
                                <tr style="border-bottom: 1px solid #f1f1f1;">
                                    <td style="padding: 15px; text-align: center; vertical-align: middle;">{{ $index + 1 }}</td>
                                    <td style="padding: 15px; text-align: center; vertical-align: middle;">
                                        <div style="width: 40px; height: 40px; margin: 0 auto;">
                                            @if($admin->avatar)
                                                <img src="{{ asset('storage/' . $admin->avatar) }}" alt="Avatar"
                                                    style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #e9ecef;">
                                            @else
                                                <div style="width: 40px; height: 40px; border-radius: 50%; background-color: #6c63ff; display: flex; align-items: center; justify-content: center;">
                                                    <svg width="20" height="20" fill="white" viewBox="0 0 24 24">
                                                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td style="padding: 15px; vertical-align: middle; color: #6c63ff; font-weight: 500;">
                                        {{ $admin->username ?? strtolower(str_replace(' ', '', $admin->name)) }}
                                    </td>
                                    <td style="padding: 15px; vertical-align: middle; color: #6c757d;">{{ $admin->email }}</td>
                                    <td style="padding: 15px; vertical-align: middle; color: #343a40; font-weight: 500;">
                                        {{ $admin->name }}
                                    </td>
                                    <td style="padding: 15px; vertical-align: middle; color: #343a40; font-weight: 500;">
                                        {{ $admin->nip }}
                                    </td>
                                    <td style="padding: 15px; text-align: center; vertical-align: middle;">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-sm"
                                                style="background-color: #ffc107; color: #212529; border-radius: 4px; padding: 6px 12px; margin-right: 5px; text-decoration: none; font-size: 12px;">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST"
                                                style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm"
                                                    style="background-color: #dc3545; color: white; border-radius: 4px; padding: 6px 12px; border: none; font-size: 12px;"
                                                    onclick="return confirm('Hapus admin ini?')">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="padding: 40px; text-align: center; color: #6c757d; font-style: italic;">
                                        <div style="display: flex; flex-direction: column; align-items: center;">
                                            <svg width="48" height="48" fill="#dee2e6" viewBox="0 0 24 24" style="margin-bottom: 16px;">
                                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                                            </svg>
                                            <p style="margin: 0; font-size: 16px;">Belum ada admin yang terdaftar</p>
                                            <p style="margin: 4px 0 0 0; font-size: 14px; color: #adb5bd;">Silakan tambah admin baru dengan menekan tombol "Tambah Data"</p>
                                        </div>
                                    </td>
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

    <style>
        .table-hover tbody tr:hover {
            background-color: #f8f9ff !important;
        }

        .btn:hover {
            transform: translateY(-1px);
            transition: all 0.2s ease;
        }

        .card {
            border: none;
            border-radius: 8px;
        }

        .breadcrumb {
            background-color: transparent;
            padding: 0;
            margin-bottom: 0;
        }

        .breadcrumb-item+.breadcrumb-item::before {
            content: "/" !important;
            color: #6c757d;
        }

        @media (max-width: 768px) {
            .table-responsive {
                font-size: 14px;
            }

            .btn-group {
                display: flex;
                flex-direction: column;
                gap: 4px;
            }

            th,
            td {
                padding: 10px 8px !important;
            }
        }
    </style>
@endsection
