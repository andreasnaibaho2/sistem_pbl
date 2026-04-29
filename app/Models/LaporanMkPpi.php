<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanMkPpi extends Model
{
    protected $table    = 'laporan_mk_ppi';
    protected $fillable = [
        'kode_laporan','mahasiswa_id','kelas_id','jenis_laporan',
        'file_path','status_verifikasi','catatan_verifikasi'
    ];

    public function mahasiswa() { return $this->belongsTo(Mahasiswa::class); }
    public function kelas()     { return $this->belongsTo(Kelas::class); }
}