<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LayananPd;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PdController extends Controller
{
    public function create()
    {
        $jenis_layanan = [
            'DPP PPNPN',
            'DISPENSASI',
            'SKKP PINDAH',
            'SKKP PENSIUN',
            'Pembatalan Kontrak'
        ];

        // Kirim NIP user ke view
        $userNip = Auth::user()->nip;

        return view('user.layanan-pd.create', compact('jenis_layanan', 'userNip',));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_satker' => 'required|string',
            'jenis_layanan' => 'required|string',
            'keterangan' => 'required|string',
            'file_upload' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,png|max:2048',
        ]);

        $filePath = $request->file('file_upload')->store('uploads/layanan', 'public');

        // Buat no berkas sementara untuk ditampilkan (bukan dikirim ke controller)
        $today = Carbon::now()->format('Ymd');
        $jumlahHariIni = LayananPd::whereDate('created_at', Carbon::today())->count() + 1;
        $noBerkas = 'PD-' . $today . '-' . str_pad($jumlahHariIni, 3, '0', STR_PAD_LEFT);

        LayananPd::create([
            'no_berkas'      => $noBerkas,
            'id_satker'      => Auth::user()->nip,
            'jenis_layanan'  => $request->jenis_layanan,
            'keterangan'     => $request->keterangan,
            'file_path'      => $filePath,
            'user_id'        => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Layanan PD berhasil dikirim.');
    }
}
