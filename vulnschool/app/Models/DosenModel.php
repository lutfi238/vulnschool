<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Model: DosenModel
 * Mengelola data profil dosen
 */
class DosenModel extends Model
{
    protected $table            = 'dosen';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'user_id',
        'nip',
        'nama',
        'bidang_keahlian',
        'no_hp',
    ];

    // Timestamps
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Cari dosen berdasarkan NIP
     */
    public function findByNip(string $nip): ?array
    {
        return $this->where('nip', $nip)->first();
    }

    /**
     * Cari dosen berdasarkan user_id
     */
    public function findByUserId(int $userId): ?array
    {
        return $this->where('user_id', $userId)->first();
    }

    /**
     * Ambil data dosen lengkap dengan data user
     */
    public function getDosenWithUser(int $id): ?array
    {
        return $this->select('dosen.*, users.username, users.email, users.full_name')
                    ->join('users', 'users.id = dosen.user_id')
                    ->where('dosen.id', $id)
                    ->first();
    }

    /**
     * Ambil semua dosen dengan data user
     */
    public function getAllWithUser(): array
    {
        return $this->select('dosen.*, users.username, users.email, users.full_name')
                    ->join('users', 'users.id = dosen.user_id')
                    ->findAll();
    }
}
