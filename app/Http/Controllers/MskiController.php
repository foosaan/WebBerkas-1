<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mski;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MskiController extends Controller
{
    public function create()
    {
        $jenis_layanan = [
            'PERMOHONAN TUP',
            'PERMOHONAN KIPS',
            'PENDAFTARAN OM SPAN',
            'PENDAFTARAN DAN PENONAKTIFAN PIN PPSPM',
            'PENDAFTARAN DAN PENONAKTIFAN USER SAKTI',
            'PENGAJUAN RPD',
            'DISPENSASI GUP TAMBAHAN',
            'CUSTOMER SERVICE',
            'PENDAFTARAN INJENT'
        ];

        return view('user.layanan-mski.create', [
            'jenis_layanan' => $jenis_layanan,
            'userNip' => Auth::user()->nip,
            'noBerkasPreview' => 'MSKI-' . Carbon::now()->format('YmdHis')
        ]);
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

        $today = Carbon::now()->format('Ymd');
        $jumlahHariIni = Mski::whereDate('created_at', Carbon::today())->count() + 1;
        $noBerkas = 'MSKI-' . $today . '-' . str_pad($jumlahHariIni, 3, '0', STR_PAD_LEFT);


        Mski::create([
            'no_berkas' => $noBerkas,
            'id_satker' => Auth::user()->nip,
            'jenis_layanan' => $request->jenis_layanan,
            'keterangan' => $request->keterangan,
            'file_path' => $filePath,
            'user_id' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Layanan MSKI berhasil dikirim.');
    }
}
