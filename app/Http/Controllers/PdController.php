<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LayananPd;
use App\Models\Layanan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PdController extends Controller
{
    public function create()
    {
        // Ambil layanan PD dari tabel layanans
        $jenis_layanan = Layanan::where('layanan_type', 'PD')
            ->where('is_active', true)
            ->pluck('jenis_layanan')
            ->toArray();

        return view('user.layanan-pd.create', [
            'jenis_layanan' => $jenis_layanan,
            'userNip' => Auth::user()->nip,
            'noBerkasPreview' => 'PD-' . Carbon::now()->format('YmdHis')
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_satker' => 'required|string',
            'jenis_layanan' => 'required|string|exists:layanans,jenis_layanan',
            'keterangan' => 'required|string',
            'file_upload' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,png',
        ]);

        // Upload file
        $filePath = $request->file('file_upload')->store('uploads/layanan', 'public');

        // Generate nomor berkas unik
        $today = Carbon::now()->format('Ymd');
        $jumlahHariIni = LayananPd::whereDate('created_at', Carbon::today())->count() + 1;

        $noBerkas = 'PD-' . $today . '-' . str_pad($jumlahHariIni, 3, '0', STR_PAD_LEFT);

        // Simpan data PD
        LayananPd::create([
            'no_berkas' => $noBerkas,
            'id_satker' => Auth::user()->nip,
            'jenis_layanan' => $request->jenis_layanan,
            'keterangan' => $request->keterangan,
            'file_path' => $filePath,
            'user_id' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Layanan PD berhasil dikirim.');
    }
}
