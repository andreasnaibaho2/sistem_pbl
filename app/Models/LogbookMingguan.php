<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogbookMingguan extends Model
{
    protected $table    = 'logbook_mingguan';
    protected $fillable = [
        'kode_logbook','mahasiswa_id','kelas_id','minggu_ke',
        'tanggal','aktivitas','kendala','solusi',
        'status_verifikasi','catatan_dosen'
    ];

    public function mahasiswa() { return $this->belongsTo(Mahasiswa::class); }
    public function kelas()     { return $this->belongsTo(Kelas::class); }
}