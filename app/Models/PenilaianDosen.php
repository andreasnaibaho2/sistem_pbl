<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenilaianDosen extends Model
{
    protected $table = 'penilaian_dosen';

    protected $fillable = [
        'mahasiswa_id', 'supervisi_matkul_id', 'dosen_id',
        'lit_informasi', 'lit_media', 'lit_teknologi',
        'pr_konten', 'pr_visual', 'pr_kosakata', 'pr_tanya_jawab', 'pr_mata_gerak',
        'la_penulisan', 'la_pilihan_kata', 'la_konten',
        'nilai_dosen', 'catatan_dosen',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function supervisiMatkul()
    {
        return $this->belongsTo(SupervisiMatkul::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }
}