<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vera;
use App\Models\LayananPd;
use App\Models\Mski;
use App\Models\Bank;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class StaffController extends Controller
{
    // ==================== DASHBOARD ====================
    public function dashboard()
    {
        // Ambil semua data dari semua divisi
        $veraRequests = Vera::latest()->get();
        $pdRequests   = LayananPd::latest()->get();
        $mskiRequests = Mski::latest()->get();
        $bankRequests = Bank::latest()->get();

        // Tambahkan property layanan_type agar bisa ditampilkan di tabel
        $veraRequests->each(fn($r) => $r->layanan_type = 'Vera');
        $pdRequests->each(fn($r) => $r->layanan_type = 'PD');
        $mskiRequests->each(fn($r) => $r->layanan_type = 'MSKI');
        $bankRequests->each(fn($r) => $r->layanan_type = 'Bank');

        // Gabungkan semua data
        $allRequests = $veraRequests->concat($pdRequests)
                                    ->concat($mskiRequests)
                                    ->concat($bankRequests);

        // Pagination 5 data per halaman
        $perPage = 5;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $allRequests->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginatedRequests = new LengthAwarePaginator(
            $currentItems,
            $allRequests->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // Card counts
        $veraCount = Vera::count();
        $pdCount   = LayananPd::count();
        $mskiCount = Mski::count();
        $bankCount = Bank::count();

        // Daftar tahun unik untuk filter
        $tahunList = $allRequests->pluck('created_at')
            ->map(fn($date) => $date->format('Y'))
            ->unique()
            ->sortDesc()
            ->values();

        // Kirim ke view
        return view('staff.dashboard', compact(
            'veraCount',
            'pdCount',
            'mskiCount',
            'bankCount',
            'paginatedRequests',
            'allRequests',
            'veraRequests',
            'pdRequests',
            'mskiRequests',
            'bankRequests',
            'tahunList'
        ));
    }

    // ==================== BERKAS MASUK ====================
    public function index()
    {
        $divisi = strtoupper(Auth::user()->divisi);

        $veraRequests = $divisi === 'VERA' ? Vera::latest()->get() : collect();
        $pdRequests   = $divisi === 'PD' ? LayananPd::latest()->get() : collect();
        $mskiRequests = $divisi === 'MSKI' ? Mski::latest()->get() : collect();
        $bankRequests = $divisi === 'BANK' ? Bank::latest()->get() : collect();

        // Gabungkan semua untuk filter/search (opsional)
        $allRequests = $veraRequests->concat($pdRequests)->concat($mskiRequests)->concat($bankRequests);

        // Daftar tahun unik untuk filter
        $tahunList = $allRequests->pluck('created_at')
            ->map(fn($date) => $date->format('Y'))
            ->unique()
            ->sortDesc()
            ->values();

        return view('staff.berkasmasuk', compact(
            'veraRequests',
            'pdRequests',
            'mskiRequests',
            'bankRequests',
            'allRequests',
            'tahunList'
        ));
    }

    // ==================== UPDATE STATUS ====================
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:diterima,diproses,selesai,ditolak',
            'alasan_penolakan' => 'nullable|string|max:255',
        ]);

        $requestData = Vera::findOrFail($id);
        $requestData->status = $request->status;

        if ($request->status === 'ditolak') {
            $requestData->alasan_penolakan = $request->alasan_penolakan;
        } else {
            $requestData->alasan_penolakan = null;
        }

        $requestData->save();

        return redirect()->back()->with('success', 'Status berhasil diperbarui.');
    }

    // ==================== BERKAS DIPROSES ====================
    public function berkasProses()
    {
        $divisi = strtoupper(Auth::user()->divisi);

        $veraRequests = $divisi === 'VERA' ? Vera::where('status', 'diproses')->latest()->get() : collect();
        $pdRequests   = $divisi === 'PD' ? LayananPd::where('status', 'diproses')->latest()->get() : collect();
        $mskiRequests = $divisi === 'MSKI' ? Mski::where('status', 'diproses')->latest()->get() : collect();
        $bankRequests = $divisi === 'BANK' ? Bank::where('status', 'diproses')->latest()->get() : collect();

        return view('staff.berkasproses', compact(
            'veraRequests',
            'pdRequests',
            'mskiRequests',
            'bankRequests'
        ));
    }

    // ==================== BERKAS SELESAI ====================
    public function berkasSelesai()
    {
        $divisi = strtoupper(Auth::user()->divisi);

        $veraRequests = $divisi === 'VERA' ? Vera::where('status', 'selesai')->latest()->get() : collect();
        $pdRequests   = $divisi === 'PD' ? LayananPd::where('status', 'selesai')->latest()->get() : collect();
        $mskiRequests = $divisi === 'MSKI' ? Mski::where('status', 'selesai')->latest()->get() : collect();
        $bankRequests = $divisi === 'BANK' ? Bank::where('status', 'selesai')->latest()->get() : collect();

        return view('staff.berkasselesai', compact(
            'veraRequests',
            'pdRequests',
            'mskiRequests',
            'bankRequests'
        ));
    }

    // ==================== BERKAS DITOLAK ====================
    public function berkasDitolak()
    {
        $divisi = strtoupper(Auth::user()->divisi);

        $veraRequests = $divisi === 'VERA' ? Vera::where('status', 'ditolak')->latest()->get() : collect();
        $pdRequests   = $divisi === 'PD' ? LayananPd::where('status', 'ditolak')->latest()->get() : collect();
        $mskiRequests = $divisi === 'MSKI' ? Mski::where('status', 'ditolak')->latest()->get() : collect();
        $bankRequests = $divisi === 'BANK' ? Bank::where('status', 'ditolak')->latest()->get() : collect();

        return view('staff.berkasditolak', compact(
            'veraRequests',
            'pdRequests',
            'mskiRequests',
            'bankRequests'
        ));
    }
}
