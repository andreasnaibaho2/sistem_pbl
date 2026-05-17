<?php

namespace App\Imports;

use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class MahasiswaImport implements ToCollection, WithHeadingRow
{
    public array $errors   = [];
    public int   $inserted = 0;

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $no    = $index + 2; // baris 1 = header
            $nim   = trim($row['nim']   ?? '');
            $nama  = trim($row['nama']  ?? '');
            $prodi = strtolower(trim($row['prodi'] ?? ''));

            // Skip baris kosong
            if (empty($nim) && empty($nama)) continue;

            // Validasi
            if (empty($nim)) {
                $this->errors[] = "Baris {$no}: NIM kosong, dilewati.";
                continue;
            }
            if (empty($nama)) {
                $this->errors[] = "Baris {$no}: Nama kosong, dilewati.";
                continue;
            }
            if (!in_array($prodi, ['mekatronika', 'otomasi', 'informatika'])) {
                $this->errors[] = "Baris {$no}: Prodi '{$row['prodi']}' tidak valid (NIM: {$nim}), dilewati.";
                continue;
            }
            if (User::where('email', $nim . '@pbl.com')->exists() ||
                Mahasiswa::where('nim', $nim)->exists()) {
                $this->errors[] = "Baris {$no}: NIM {$nim} sudah terdaftar, dilewati.";
                continue;
            }

            // Insert
            $user = User::create([
                'name'     => $nama,
                'email'    => $nim . '@pbl.com',
                'password' => Hash::make($nim),
                'role'     => 'mahasiswa',
                'prodi'    => $prodi,
                'status'   => 'active',
            ]);

            Mahasiswa::create([
                'user_id' => $user->id,
                'nim'     => $nim,
                'nama'    => $nama,
            ]);

            $this->inserted++;
        }
    }
}