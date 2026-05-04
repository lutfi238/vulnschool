<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Model: MataKuliahModel
 * Mengelola data mata kuliah
 */
class MataKuliahModel extends Model
{
    protected $table            = 'mata_kuliah';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'kode_mk',
        'nama_mk',
        'sks',
        'dosen_id',
        'semester',
        'deskripsi',
    ];

    // Timestamps
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Cari mata kuliah berdasarkan kode
     */
    public function findByKode(string $kodeMk): ?array
    {
        return $this->where('kode_mk', $kodeMk)->first();
    }

    /**
     * Ambil mata kuliah lengkap dengan data dosen pengampu
     */
    public function getAllWithDosen(): array
    {
        return $this->select('mata_kuliah.*, dosen.nama as nama_dosen, dosen.nip')
                    ->join('dosen', 'dosen.id = mata_kuliah.dosen_id')
                    ->findAll();
    }

    /**
     * Ambil mata kuliah berdasarkan dosen_id
     */
    public function getByDosenId(int $dosenId): array
    {
        return $this->where('dosen_id', $dosenId)->findAll();
    }
}
