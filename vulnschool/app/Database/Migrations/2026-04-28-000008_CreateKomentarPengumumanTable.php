<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration: Tabel komentar_pengumuman
 * Menyimpan komentar pada pengumuman
 *
 * VULN-XSS-001: Komentar tidak di-sanitize — target Stored XSS
 */
class CreateKomentarPengumumanTable extends Migration
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
            'pengumuman_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            // VULN-XSS-001: Stored XSS via Komentar
            // Deskripsi: Field isi_komentar menerima HTML/JS tanpa sanitasi
            // Dampak: Penyerang bisa menyisipkan script berbahaya yang dijalankan browser pengguna lain
            // Fix: Gunakan esc() atau htmlspecialchars() saat menampilkan, dan validasi input
            'isi_komentar' => [
                'type' => 'TEXT',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('pengumuman_id', 'pengumuman', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('komentar_pengumuman');
    }

    public function down()
    {
        $this->forge->dropTable('komentar_pengumuman');
    }
}
