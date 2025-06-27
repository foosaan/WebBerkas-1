@extends('admin.app')

@section('content')
    {{-- BAGIAN DASHBOARD ASLI, TIDAK DIUBAH --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
    </div>

    {{-- DIV BARU: FORM EDIT ADMIN --}}
    <div class="container-fluid mt-5">
        <h2 class="mb-4">Edit Admin</h2>

        <form action="{{ route('admin.admins.update', $admin->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" name="name" class="form-control" required value="{{ old('name', $admin->name) }}">
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">NIP</label>
                <input type="text" name="nip" class="form-control" required value="{{ old('nip', $admin->nip) }}">
                @error('nip') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required value="{{ old('email', $admin->email) }}">
                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">Perbarui</button>
                <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
@endsection