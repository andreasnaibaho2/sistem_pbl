<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'role_aktif', 'prodi', 'status',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ── Role checks (berdasarkan role permanen) ──
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isMahasiswa(): bool
    {
        return $this->role === 'mahasiswa';
    }

    public function isDosen(): bool
    {
        return $this->role === 'dosen';
    }

    public function isManager(): bool
    {
        return $this->role === 'manager_proyek' || $this->roleAktifManager();
    }

    // ── Role aktif checks (untuk dosen dual-role) ──
    public function roleAktifManager(): bool
    {
        return $this->role === 'dosen' && $this->role_aktif === 'manager_proyek';
    }

    public function roleAktifDosen(): bool
    {
        return $this->role === 'dosen' && $this->role_aktif === 'dosen_pengampu';
    }

    public function getRoleEfektif(): string
    {
        if ($this->role === 'dosen') {
            return $this->role_aktif ?? 'dosen_pengampu';
        }
        return $this->role;
    }

    // ── Relasi ──
    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class);
    }

    public function dosen()
    {
        return $this->hasOne(Dosen::class);
    }
}