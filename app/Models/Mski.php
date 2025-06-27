<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mski extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_berkas',
        'id_satker',
        'jenis_layanan',
        'keterangan',
        'file_path',
    ];
}
