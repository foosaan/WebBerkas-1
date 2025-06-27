@extends('user.app')

@section('content')
<div class="container">
    <!-- User Profile Section - Compact Vertical Layout -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Profil Pengguna</h5>
        </div>
        <div class="card-body p-3">
            <div class="user-profile-compact">
    <div class="profile-item">
        <span class="profile-label">ID Satker:</span>
        <span class="profile-value">{{ Auth::user()->nip }}</span>
    </div>
    <div class="profile-item">
        <span class="profile-label">Nama Petugas:</span>
        <span class="profile-value">{{ Auth::user()->name }}</span>
    </div>
    <div class="profile-item">
        <span class="profile-label">Satuan Kerja:</span>
        <span class="profile-value">{{ Auth::user()->nama_satker ?? '-' }}</span>
    </div>
    <div class="profile-item">
        <span class="profile-label">Email:</span>
        <span class="profile-value">{{ Auth::user()->email }}</span>
    </div>
        </div>

        </div>
    </div>
    
    <h2 class="mb-4">Dashboard Layanan</h2>

     <!-- Filter -->
    <div class="row mb-3">
        <div class="col-md-3">
            <select class="form-control" id="filterStatus">
                <option value="">Semua Berkas</option>
                <option value="diterima">Diterima</option>
                <option value="diproses">Diproses</option>
                <option value="selesai">Selesai</option>
                <option value="ditolak">Ditolak</option>
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-control" id="filterBulan">
                <option value="">Semua Bulan</option>
                @for ($m = 1; $m <= 12; $m++)
                    <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}">{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
                @endfor
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-control" id="filterTahun">
                <option value="">Semua Tahun</option>
                @foreach ($tahunList as $tahun)
                    <option value="{{ $tahun }}">{{ $tahun }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary btn-block" id="btnFilter">Search</button>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-tabs" id="layananTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="semua-tab" data-toggle="tab" href="#semua" role="tab">Semua</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="vera-tab" data-toggle="tab" href="#vera" role="tab">Layanan Vera</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pd-tab" data-toggle="tab" href="#pd" role="tab">Layanan PD</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="mski-tab" data-toggle="tab" href="#mski" role="tab">Layanan MSKI</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="bank-tab" data-toggle="tab" href="#bank" role="tab">Layanan Bank</a>
                </li>
            </ul>

            <div class="tab-content p-3 border border-top-0 rounded-bottom" id="layananTabsContent">
                <!-- Tab Semua -->
                <div class="tab-pane fade show active" id="semua" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No Berkas</th>
                                    <th>Jenis Layanan</th>
                                    <th>Keterangan</th>
                                    <th>File</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($allRequests as $request)
                                    <tr>
                                        <td>{{ $request->no_berkas }}</td>
                                        <td>{{ $request->layanan_type . ' - ' . $request->jenis_layanan }}</td>
                                        <td>{{ $request->keterangan ?? '-' }}</td>
                                        <td>
                                            @if($request->file_path)
                                                <a href="{{ asset('storage/'.$request->file_path) }}" target="_blank">Lihat File</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                         <td>{{ $request->status ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data layanan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab Vera -->
                <div class="tab-pane fade" id="vera" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No Berkas</th>
                                    <th>Jenis Layanan</th>
                                    <th>Keterangan</th>
                                    <th>File</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($veraRequests as $request)
                                    <tr>
                                        <td>{{ $request->no_berkas }}</td>
                                        <td>{{ $request->jenis_layanan }}</td>
                                        <td>{{ $request->keterangan ?? '-' }}</td>
                                        <td>
                                            @if($request->file_path)
                                                <a href="{{ asset('storage/'.$request->file_path) }}" target="_blank">Lihat File</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $request->status ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data layanan Vera</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab PD -->
                <div class="tab-pane fade" id="pd" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No Berkas</th>
                                    <th>Jenis Layanan</th>
                                    <th>Keterangan</th>
                                    <th>File</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pdRequests as $request)
                                    <tr>
                                        <td>{{ $request->no_berkas }}</td>
                                        <td>{{ $request->jenis_layanan }}</td>
                                        <td>{{ $request->keterangan ?? '-' }}</td>
                                        <td>
                                            @if($request->file_path)
                                                <a href="{{ asset('storage/'.$request->file_path) }}" target="_blank">Lihat File</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $request->status ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data layanan PD</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab MSKI -->
                <div class="tab-pane fade" id="mski" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No Berkas</th>
                                    <th>Jenis Layanan</th>
                                    <th>Keterangan</th>
                                    <th>File</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($mskiRequests as $request)
                                    <tr>
                                        <td>{{ $request->no_berkas }}</td>
                                        <td>{{ $request->jenis_layanan }}</td>
                                        <td>{{ $request->keterangan ?? '-' }}</td>
                                        <td>
                                            @if($request->file_path)
                                                <a href="{{ asset('storage/'.$request->file_path) }}" target="_blank">Lihat File</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $request->status ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data layanan MSKI</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab Bank -->
                <div class="tab-pane fade" id="bank" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No Berkas</th>
                                    <th>Jenis Layanan</th>
                                    <th>Keterangan</th>
                                    <th>File</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bankRequests as $request)
                                    <tr>
                                        <td>{{ $request->no_berkas }}</td>
                                        <td>{{ $request->jenis_layanan }}</td>
                                        <td>{{ $request->keterangan ?? '-' }}</td>
                                        <td>
                                            @if($request->file_path)
                                                <a href="{{ asset('storage/'.$request->file_path) }}" target="_blank">Lihat File</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $request->status ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data layanan Bank</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .user-profile-compact {
        line-height: 1.6;
    }
    .profile-item {
        margin-bottom: 0.3rem;
        display: flex;
        align-items: center;
    }
    .profile-label {
        font-weight: bold;
        min-width: 80px;
        display: inline-block;
        margin-right: 0.5rem;
    }
    .profile-value {
        flex: 1;
    }
    .card-body.p-3 {
        padding: 1rem !important;
    }
    .card-header {
        font-weight: bold;
        padding: 0.75rem 1rem;
    }
    .table th {
        white-space: nowrap;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterStatus = document.getElementById('filterStatus');
        const filterBulan = document.getElementById('filterBulan');
        const filterTahun = document.getElementById('filterTahun');
        const btnFilter = document.getElementById('btnFilter');

        btnFilter.addEventListener('click', function (e) {
            e.preventDefault();

            const status = filterStatus.value.trim().toLowerCase();
            const bulan = filterBulan.value.trim();
            const tahun = filterTahun.value.trim();

            const rows = document.querySelectorAll('#semua tbody tr');

            rows.forEach(row => {
                const tglText = row.cells[4]?.textContent.trim(); // kolom Tanggal
                const rowStatus = row.cells[5]?.textContent.trim().toLowerCase(); // kolom Status

                let matchStatus = true;
                let matchBulan = true;
                let matchTahun = true;

                if (status) {
                    matchStatus = rowStatus.includes(status);
                }

                if (tglText) {
                    const parts = tglText.split(/[\/\s:]+/); // [dd, mm, yyyy, HH, ii]
                    const tglMonth = parts[1]; // format bulan = 2 digit
                    const tglYear = parts[2]; // format tahun = 4 digit

                    if (bulan) {
                        matchBulan = bulan === tglMonth;
                    }

                    if (tahun) {
                        matchTahun = tahun === tglYear;
                    }
                }

                const isMatch = matchStatus && matchBulan && matchTahun;
                row.style.display = isMatch ? '' : 'none';
            });
        });
    });
</script>
@endsection
