<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Model: KomentarPengumumanModel
 * Mengelola komentar pada pengumuman
 *
 * VULN-XSS-001: Stored XSS
 * Field 'isi_komentar' TIDAK disanitasi saat disimpan maupun ditampilkan.
 * Penyerang bisa menyisipkan tag <script> yang akan dieksekusi
 * di browser pengguna lain.
 */
class KomentarPengumumanModel extends Model
{
    protected $table            = 'komentar_pengumuman';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    // VULN-XSS-001: Stored XSS via Komentar
    // Deskripsi: isi_komentar diterima apa adanya tanpa sanitasi
    // Dampak: Script berbahaya tersimpan di database dan dieksekusi saat ditampilkan
    // Fix: Sanitasi input dengan htmlspecialchars() atau gunakan esc() di view
    protected $allowedFields = [
        'pengumuman_id',
        'user_id',
        'isi_komentar',
    ];

    // Hanya created_at
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // Tidak ada updated_at

    /**
     * Ambil semua komentar untuk pengumuman tertentu
     * Diurutkan dari yang terlama
     */
    public function getByPengumuman(int $pengumumanId): array
    {
        return $this->select('komentar_pengumuman.*, users.full_name, users.username')
                    ->join('users', 'users.id = komentar_pengumuman.user_id')
                    ->where('pengumuman_id', $pengumumanId)
                    ->orderBy('komentar_pengumuman.created_at', 'ASC')
                    ->findAll();
    }
}
