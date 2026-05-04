<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Model: UserModel
 * Mengelola data pengguna (admin, dosen, mahasiswa)
 *
 * VULN-MASS-001: Mass Assignment
 * Field 'is_admin' dan 'role' ada di $allowedFields
 * sehingga bisa diubah via mass assignment dari request input
 */
class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    // VULN-MASS-001: Mass Assignment
    // Deskripsi: Field 'is_admin' dan 'role' seharusnya TIDAK boleh ada di $allowedFields
    //            karena bisa dimanipulasi oleh user melalui form input atau API request
    // Dampak: User biasa bisa mengirim parameter is_admin=1 atau role=admin
    //         untuk menjadikan diri sendiri admin
    // Fix: Hapus 'is_admin' dan 'role' dari array $allowedFields,
    //      gunakan method khusus untuk mengubah role/admin status
    protected $allowedFields = [
        'username',
        'password',
        'email',
        'full_name',
        'role',       // VULN: Seharusnya tidak boleh di-mass assign
        'is_active',
        'is_admin',   // VULN: Seharusnya tidak boleh di-mass assign
        'reset_token',
    ];

    // Timestamps
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validasi dasar (sengaja minimal untuk tujuan pembelajaran)
    protected $validationRules = [
        'username' => 'required|min_length[3]|max_length[50]',
        'password' => 'required|min_length[5]',
        'email'    => 'required|valid_email',
    ];

    protected $validationMessages = [];
    protected $skipValidation     = false;

    /**
     * Cari user berdasarkan username
     * Digunakan saat proses login
     */
    public function findByUsername(string $username): ?array
    {
        return $this->where('username', $username)->first();
    }

    /**
     * Cari user berdasarkan reset token
     */
    public function findByResetToken(string $token): ?array
    {
        return $this->where('reset_token', $token)->first();
    }

    /**
     * Ambil semua user berdasarkan role
     */
    public function getByRole(string $role): array
    {
        return $this->where('role', $role)->findAll();
    }
}
