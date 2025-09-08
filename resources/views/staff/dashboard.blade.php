@extends('staff.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Dashboard Staff</h2>

    <!-- Staff Profile Section -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Profil Staff</h5>
        </div>
        <div class="card-body p-3">
            <div class="user-profile-compact">
                <div class="profile-item">
                    <span class="profile-label">ID Staff:</span>
                    <span class="profile-value">{{ Auth::user()->nip }}</span>
                </div>
                <div class="profile-item">
                    <span class="profile-label">Nama:</span>
                    <span class="profile-value">{{ Auth::user()->name }}</span>
                </div>
                <div class="profile-item">
                    <span class="profile-label">Email:</span>
                    <span class="profile-value">{{ Auth::user()->email }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Cards Total Layanan -->
    <div class="row">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Layanan Vera</h5>
                    <p class="card-text display-4">{{ $veraCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Layanan PD</h5>
                    <p class="card-text display-4">{{ $pdCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Layanan MSKI</h5>
                    <p class="card-text display-4">{{ $mskiCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Layanan Bank</h5>
                    <p class="card-text display-4">{{ $bankCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Semua Data Layanan Tabel -->
    <div class="card mt-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Semua Data Layanan</h5>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No Berkas</th>
                            <th>Nama User</th>
                            <th>Layanan</th>
                            <th>Keterangan</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($paginatedRequests as $request)
                        <tr>
                            <td>{{ $request->no_berkas }}</td>
                            <td>{{ $request->user->name ?? '-' }}</td>
                            <td>{{ $request->layanan_type }} - {{ $request->jenis_layanan ?? '-' }}</td>
                            <td>{{ $request->keterangan ?? '-' }}</td>
                            <td>{{ ucfirst($request->status ?? '-') }}</td>
                            <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data layanan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination Links Bootstrap 5 -->
                <div class="mt-3">
                    {{ $paginatedRequests->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
