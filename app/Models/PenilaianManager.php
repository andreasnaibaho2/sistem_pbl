<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenilaianManager extends Model
{
    protected $table = 'penilaian_manager';

    protected $fillable = [
        'mahasiswa_id', 'pengajuan_proyek_id', 'manager_id',
        'ls_critical_thinking', 'ls_kolaborasi', 'ls_kreativitas', 'ls_komunikasi',
        'lf_fleksibilitas', 'lf_kepemimpinan', 'lf_produktivitas', 'lf_social_skill',
        'lp_rpp', 'lp_logbook', 'lp_dokumen_projek',
        'nilai_manager', 'catatan_manager',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function pengajuanProyek()
    {
        return $this->belongsTo(PengajuanProyek::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}