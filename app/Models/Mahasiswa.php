<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table    = 'mahasiswa';
    protected $fillable = ['user_id','nim','nama'];

    public function user()     { return $this->belongsTo(User::class); }
    public function kelas()    { return $this->belongsToMany(Kelas::class, 'kelas_mahasiswa'); }
    public function logbook()  { return $this->hasMany(LogbookMingguan::class); }
    public function laporan()  { return $this->hasMany(LaporanMkPpi::class); }
    public function nilai()    { return $this->hasMany(NilaiMahasiswa::class); }
    public function penilaian(){ return $this->hasMany(Penilaian::class); }
}