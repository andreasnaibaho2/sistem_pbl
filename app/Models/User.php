<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
    'name',
    'email',
    'password',
    'role',
    'prodi',
    'kelas_register',
    'status',
];
    protected $hidden   = ['password', 'remember_token'];

    protected function casts(): array {
        return ['password' => 'hashed'];
    }

    public function mahasiswa() { return $this->hasOne(Mahasiswa::class); }
    public function dosen()     { return $this->hasOne(Dosen::class); }

    public function isAdmin()     { return $this->role === 'admin'; }
    public function isManager()   { return $this->role === 'manager_proyek'; }
    public function isDosen()     { return $this->role === 'dosen'; }
    public function isMahasiswa() { return $this->role === 'mahasiswa'; }
}