<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration: Tabel users
 * Menyimpan data semua pengguna (admin, dosen, mahasiswa)
 *
 * VULN-AUTH-001: Password disimpan dalam PLAIN TEXT (tanpa hashing)
 * VULN-MASS-001: Field is_admin bisa di-mass assign
 * VULN-AUTH-002: Reset token tanpa expiry
 */
class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'unique'     => true,
            ],
            // VULN-AUTH-001: Password Plain Text
            // Deskripsi: Password disimpan tanpa hashing (plain text)
            // Dampak: Jika database bocor, semua password terekspos langsung
            // Fix: Gunakan password_hash() saat menyimpan dan password_verify() saat login
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'full_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'role' => [
                'type'       => 'ENUM',
                'constraint' => ['admin', 'dosen', 'mahasiswa'],
                'default'    => 'mahasiswa',
            ],
            'is_active' => [
                'type'    => 'BOOLEAN',
                'default' => true,
            ],
            // VULN-MASS-001: Mass Assignment pada is_admin
            // Deskripsi: Field is_admin ada di $allowedFields model, bisa diubah via request
            // Dampak: User biasa bisa menjadikan diri sendiri admin
            // Fix: Hapus 'is_admin' dari $allowedFields di UserModel
            'is_admin' => [
                'type'    => 'BOOLEAN',
                'default' => false,
            ],
            // VULN-AUTH-002: Reset Token tanpa Expiry
            // Deskripsi: Token reset password tidak punya batas waktu
            // Dampak: Token yang bocor bisa dipakai kapan saja
            // Fix: Tambahkan field reset_token_expires_at dan validasi saat reset
            'reset_token' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'default'    => null,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
