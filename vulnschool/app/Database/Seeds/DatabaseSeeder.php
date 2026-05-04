<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Seeder Utama: DatabaseSeeder
 *
 * Menjalankan semua seeder dalam urutan yang benar
 * (menghormati relasi foreign key antar tabel)
 *
 * Cara pakai: php spark db:seed DatabaseSeeder
 */
class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Urutan penting! Tabel induk harus di-seed lebih dulu
        echo "=== VulnSchool Database Seeder ===\n\n";

        echo "[1/7] Seeding users...\n";
        $this->call('UserSeeder');

        echo "[2/7] Seeding dosen...\n";
        $this->call('DosenSeeder');

        echo "[3/7] Seeding mahasiswa...\n";
        $this->call('MahasiswaSeeder');

        echo "[4/7] Seeding mata_kuliah...\n";
        $this->call('MataKuliahSeeder');

        echo "[5/7] Seeding nilai...\n";
        $this->call('NilaiSeeder');

        echo "[6/7] Seeding absensi...\n";
        $this->call('AbsensiSeeder');

        echo "[7/7] Seeding pengumuman & komentar...\n";
        $this->call('PengumumanSeeder');

        echo "\n=== Seeding selesai! ===\n";
        echo "Total: 14 users, 3 dosen, 10 mahasiswa, 5 mata kuliah,\n";
        echo "       30 nilai, 50 absensi, 3 pengumuman, 5 komentar\n";
    }
}
