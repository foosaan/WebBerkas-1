<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bank;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BankController extends Controller
{
    public function create()
    {
        $jenis_layanan = [
            'LAYANAN KONFIRMASI PENERIMAAN',
            'LAPORAN SALDO REKENING',
            'BAR REKENING MILIK SATKER LINGKUP K/L',
            'RETUR',
            'KOREKSI PENERIMAAN',
            'KONFIRMASI SETORAN'
        ];

        return view('user.layanan-bank.create', [
            'jenis_layanan' => $jenis_layanan,
            'userNip' => Auth::user()->nip,
            'noBerkasPreview' => 'BANK-' . Carbon::now()->format('YmdHis')
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
        $jumlahHariIni = Bank::whereDate('created_at', Carbon::today())->count() + 1;
        $noBerkas = 'BANK-' . $today . '-' . str_pad($jumlahHariIni, 3, '0', STR_PAD_LEFT);

        Bank::create([
            'no_berkas' => $noBerkas,
            'id_satker' => Auth::user()->nip,
            'jenis_layanan' => $request->jenis_layanan,
            'keterangan' => $request->keterangan,
            'file_path' => $filePath,
            'user_id' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Layanan Bank berhasil dikirim.');
    }
}
