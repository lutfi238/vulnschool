<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Model: PengumumanModel
 * Mengelola data pengumuman
 */
class PengumumanModel extends Model
{
    protected $table            = 'pengumuman';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'judul',
        'isi',
        'author_id',
        'is_published',
    ];

    // Timestamps
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Ambil pengumuman yang sudah dipublikasi, terbaru dulu
     */
    public function getPublished(): array
    {
        return $this->select('pengumuman.*, users.full_name as author_name')
                    ->join('users', 'users.id = pengumuman.author_id')
                    ->where('is_published', true)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Ambil semua pengumuman (termasuk draft), untuk admin
     */
    public function getAllWithAuthor(): array
    {
        return $this->select('pengumuman.*, users.full_name as author_name')
                    ->join('users', 'users.id = pengumuman.author_id')
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Ambil pengumuman dengan detail dan jumlah komentar
     */
    public function getWithCommentCount(int $id): ?array
    {
        $pengumuman = $this->select('pengumuman.*, users.full_name as author_name')
                          ->join('users', 'users.id = pengumuman.author_id')
                          ->where('pengumuman.id', $id)
                          ->first();

        if ($pengumuman) {
            $db = \Config\Database::connect();
            $pengumuman['jumlah_komentar'] = $db->table('komentar_pengumuman')
                                                ->where('pengumuman_id', $id)
                                                ->countAllResults();
        }

        return $pengumuman;
    }
}
