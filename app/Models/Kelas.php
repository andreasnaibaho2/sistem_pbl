<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MataKuliah;
use App\Models\Dosen;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\LogbookMingguan;
use App\Models\LaporanMkPpi;
use App\Models\Penilaian;

class Kelas extends Model
{
    protected $table    = 'kelas';
    protected $fillable = ['kode_kelas','nama_kelas','matkul_id','dosen_id','manager_id','tahun_akademik'];

    public function mataKuliah() { return $this->belongsTo(MataKuliah::class, 'matkul_id'); }
    public function dosen()      { return $this->belongsTo(Dosen::class); }
    public function manager()    { return $this->belongsTo(User::class, 'manager_id'); }
    public function mahasiswa()  { return $this->belongsToMany(Mahasiswa::class, 'kelas_mahasiswa'); }
    public function logbook()    { return $this->hasMany(LogbookMingguan::class); }
    public function laporan()    { return $this->hasMany(LaporanMkPpi::class); }
    public function penilaian()  { return $this->hasMany(Penilaian::class); }
}