@extends('staff.app')

@section('content')
<div class="container">

    <h2 class="mb-4">Berkas Ditolak</h2>

    @php
        $divisi = strtoupper(Auth::user()->divisi);
    @endphp

    <!-- Tabs -->
    <ul class="nav nav-tabs" id="layananTabs" role="tablist">
        @if($divisi === 'VERA')
            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#vera" role="tab">Layanan Vera</a></li>
        @elseif($divisi === 'PD')
            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#pd" role="tab">Layanan PD</a></li>
        @elseif($divisi === 'MSKI')
            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#mski" role="tab">Layanan MSKI</a></li>
        @elseif($divisi === 'BANK')
            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#bank" role="tab">Layanan Bank</a></li>
        @endif
    </ul>

    <div class="tab-content p-3 border border-top-0 rounded-bottom">

        {{-- VERA --}}
        @if($divisi === 'VERA')
        <div class="tab-pane fade show active" id="vera" role="tabpanel">
             @includeWhen(isset($veraRequests), 'staff._table', ['requests' => $veraRequests, 'jenis' => 'vera'])
        </div>

        

        {{-- PD --}}
        @elseif($divisi === 'PD')
        <div class="tab-pane fade show active" id="pd" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No Berkas</th>
                            <th>Nama User</th>
                            <th>Jenis Layanan</th>
                            <th>Keterangan</th>
                            <th>File</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Alasan Penolakan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pdRequests as $request)
                            @if($request->status == 'ditolak')
                            <tr>
                                <td>{{ $request->no_berkas }}</td>
                                <td>{{ $request->user->name ?? '-' }}</td>
                                <td>{{ $request->jenis_layanan }}</td>
                                <td>{{ $request->keterangan ?? '-' }}</td>
                                <td>
                                    @if($request->file_path)
                                        <a href="{{ asset('storage/'.$request->file_path) }}" target="_blank">Lihat File</a>
                                    @else - @endif
                                </td>
                                <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ ucfirst($request->status ?? '-') }}</td>
                                <td>{{ $request->alasan_penolakan ?? '-' }}</td>
                                <td>
                                    <form action="{{ route('staff.updateStatus', [$request->id, 'pd']) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="form-control form-control-sm" onchange="toggleAlasan(this)">
                                            <option value="baru">Baru</option>
                                            <option value="diproses">Diproses</option>
                                            <option value="selesai">Selesai</option>
                                            <option value="ditolak" selected>Ditolak</option>
                                        </select>
                                        <textarea name="alasan_penolakan" class="form-control mt-2 alasan-box"
                                            style="display:block;" required
                                            placeholder="Tuliskan alasan penolakan...">{{ $request->alasan_penolakan }}</textarea>
                                        <button type="submit" class="btn btn-sm btn-primary mt-2">Simpan</button>
                                    </form>
                                </td>
                            </tr>
                            @endif
                        @empty
                            <tr><td colspan="9" class="text-center">Tidak ada data layanan PD</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- MSKI --}}
        @elseif($divisi === 'MSKI')
        <div class="tab-pane fade show active" id="mski" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No Berkas</th>
                            <th>Nama User</th>
                            <th>Jenis Layanan</th>
                            <th>Keterangan</th>
                            <th>File</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Alasan Penolakan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mskiRequests as $request)
                            @if($request->status == 'ditolak')
                            <tr>
                                <td>{{ $request->no_berkas }}</td>
                                <td>{{ $request->user->name ?? '-' }}</td>
                                <td>{{ $request->jenis_layanan }}</td>
                                <td>{{ $request->keterangan ?? '-' }}</td>
                                <td>
                                    @if($request->file_path)
                                        <a href="{{ asset('storage/'.$request->file_path) }}" target="_blank">Lihat File</a>
                                    @else - @endif
                                </td>
                                <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ ucfirst($request->status ?? '-') }}</td>
                                <td>{{ $request->alasan_penolakan ?? '-' }}</td>
                                <td>
                                    <form action="{{ route('staff.updateStatus', [$request->id, 'mski']) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="form-control form-control-sm" onchange="toggleAlasan(this)">
                                            <option value="baru">Baru</option>
                                            <option value="diproses">Diproses</option>
                                            <option value="selesai">Selesai</option>
                                            <option value="ditolak" selected>Ditolak</option>
                                        </select>
                                        <textarea name="alasan_penolakan" class="form-control mt-2 alasan-box"
                                            style="display:block;" required
                                            placeholder="Tuliskan alasan penolakan...">{{ $request->alasan_penolakan }}</textarea>
                                        <button type="submit" class="btn btn-sm btn-primary mt-2">Simpan</button>
                                    </form>
                                </td>
                            </tr>
                            @endif
                        @empty
                            <tr><td colspan="9" class="text-center">Tidak ada data layanan MSKI</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- BANK --}}
        @elseif($divisi === 'BANK')
        <div class="tab-pane fade show active" id="bank" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No Berkas</th>
                            <th>Nama User</th>
                            <th>Jenis Layanan</th>
                            <th>Keterangan</th>
                            <th>File</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Alasan Penolakan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bankRequests as $request)
                            @if($request->status == 'ditolak')
                            <tr>
                                <td>{{ $request->no_berkas }}</td>
                                <td>{{ $request->user->name ?? '-' }}</td>
                                <td>{{ $request->jenis_layanan }}</td>
                                <td>{{ $request->keterangan ?? '-' }}</td>
                                <td>
                                    @if($request->file_path)
                                        <a href="{{ asset('storage/'.$request->file_path) }}" target="_blank">Lihat File</a>
                                    @else - @endif
                                </td>
                                <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ ucfirst($request->status ?? '-') }}</td>
                                <td>{{ $request->alasan_penolakan ?? '-' }}</td>
                                <td>
                                    <form action="{{ route('staff.updateStatus', [$request->id, 'bank']) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="form-control form-control-sm" onchange="toggleAlasan(this)">
                                            <option value="baru">Baru</option>
                                            <option value="diproses">Diproses</option>
                                            <option value="selesai">Selesai</option>
                                            <option value="ditolak" selected>Ditolak</option>
                                        </select>
                                        <textarea name="alasan_penolakan" class="form-control mt-2 alasan-box"
                                            style="display:block;" required
                                            placeholder="Tuliskan alasan penolakan...">{{ $request->alasan_penolakan }}</textarea>
                                        <button type="submit" class="btn btn-sm btn-primary mt-2">Simpan</button>
                                    </form>
                                </td>
                            </tr>
                            @endif
                        @empty
                            <tr><td colspan="9" class="text-center">Tidak ada data layanan Bank</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>
</div>

{{-- SweetAlert2 CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function toggleAlasan(select){
    let textarea = select.closest("form").querySelector(".alasan-box");
    if(select.value === "ditolak"){
        textarea.style.display = "block";
        textarea.setAttribute("required", "required"); // wajib diisi
    } else {
        textarea.style.display = "none";
        textarea.removeAttribute("required");
        textarea.value = "";
    }
}

// Notifikasi SweetAlert kalau ada pesan sukses
@if(session('success'))
Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: '{{ session('success') }}',
    timer: 2000,
    showConfirmButton: false
});
@endif
</script>
@endsection
