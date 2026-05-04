<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Model: MahasiswaModel
 * Mengelola data profil mahasiswa
 */
class MahasiswaModel extends Model
{
    protected $table            = 'mahasiswa';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'user_id',
        'nim',
        'nama',
        'jurusan',
        'angkatan',
        'alamat',
        'no_hp',
        'foto',
    ];

    // Timestamps
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Cari mahasiswa berdasarkan NIM
     */
    public function findByNim(string $nim): ?array
    {
        return $this->where('nim', $nim)->first();
    }

    /**
     * Cari mahasiswa berdasarkan user_id
     */
    public function findByUserId(int $userId): ?array
    {
        return $this->where('user_id', $userId)->first();
    }

    /**
     * Ambil data mahasiswa lengkap dengan data user
     */
    public function getMahasiswaWithUser(int $id): ?array
    {
        return $this->select('mahasiswa.*, users.username, users.email, users.full_name')
                    ->join('users', 'users.id = mahasiswa.user_id')
                    ->where('mahasiswa.id', $id)
                    ->first();
    }

    /**
     * Ambil semua mahasiswa dengan data user
     */
    public function getAllWithUser(): array
    {
        return $this->select('mahasiswa.*, users.username, users.email, users.full_name')
                    ->join('users', 'users.id = mahasiswa.user_id')
                    ->findAll();
    }
}
