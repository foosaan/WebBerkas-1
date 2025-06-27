<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vera;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class VeraController extends Controller
{
    public function create()
    {
        $jenis_layanan = [
            'LPJ BULANAN',
            'USER E-REKONSILIASI & LK',
            'PENYESUAIAN PAGU',
            'IJIN PENGGUNAAN MP SISA TAHUN LALU',
            'KETERANGAN SALDO AKHIR KAS BLU',
            'MPHL BJS',
            'PERMOHONAN SKTB',
            'PENERIMAAN LK SATKER',
            'REKONSILIASI LK & LPJ'
        ];

        // Kirim NIP user ke view
        $userNip = Auth::user()->nip;
        
        return view('user.layanan-vera.create', compact('jenis_layanan', 'userNip'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_satker' => 'required|string',
            'jenis_layanan' => 'required|string',
            'keterangan' => 'nullable|string',
            'file_upload' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,png|max:2048',
        ]);

        // Simpan file ke storage
        $filePath = $request->file('file_upload')->store('uploads/layanan', 'public');

        // Generate no_berkas: contoh "VB-20250626-001"
        $today = Carbon::now()->format('Ymd');
        $jumlahHariIni = Vera::whereDate('created_at', Carbon::today())->count() + 1;
        $noBerkas = 'VERA-' . $today . '-' . str_pad($jumlahHariIni, 3, '0', STR_PAD_LEFT);

        Vera::create([
            'no_berkas'      => $noBerkas,
            'id_satker'      => Auth::user()->nip,
            'jenis_layanan'  => $request->jenis_layanan,
            'keterangan'     => $request->keterangan,
            'file_path'      => $filePath,
            'user_id'        => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Layanan VERAberhasil dikirim.');
    }
}
