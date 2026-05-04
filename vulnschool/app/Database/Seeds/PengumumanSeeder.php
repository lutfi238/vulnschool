<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Seeder: PengumumanSeeder
 * Membuat 3 pengumuman + beberapa komentar
 *
 * VULN-XSS-001: Salah satu komentar sengaja mengandung payload XSS
 */
class PengumumanSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        // ============ PENGUMUMAN ============
        $pengumuman = [
            [
                'judul'        => 'Jadwal UTS Semester Ganjil 2025/2026',
                'isi'          => 'Diberitahukan kepada seluruh mahasiswa bahwa Ujian Tengah Semester (UTS) Ganjil 2025/2026 akan dilaksanakan pada tanggal 20-25 Oktober 2025. Jadwal detail per mata kuliah akan diumumkan melalui masing-masing dosen pengampu. Harap mempersiapkan diri dengan baik.',
                'author_id'    => 1, // Admin
                'is_published' => true,
                'created_at'   => date('Y-m-d H:i:s', strtotime('-7 days')),
                'updated_at'   => date('Y-m-d H:i:s', strtotime('-7 days')),
            ],
            [
                'judul'        => 'Workshop Keamanan Siber - Capture The Flag (CTF)',
                'isi'          => 'Prodi Teknik Informatika akan mengadakan workshop CTF pada hari Sabtu, 15 November 2025 di Lab Komputer 3. Materi meliputi: Web Exploitation, Cryptography, dan Forensics. Pendaftaran gratis, kuota terbatas 30 peserta. Hubungi Dr. Budi Santoso untuk informasi lebih lanjut.',
                'author_id'    => 2, // Dosen1
                'is_published' => true,
                'created_at'   => date('Y-m-d H:i:s', strtotime('-3 days')),
                'updated_at'   => date('Y-m-d H:i:s', strtotime('-3 days')),
            ],
            [
                'judul'        => 'Pengumpulan Tugas Akhir Semester',
                'isi'          => 'Batas akhir pengumpulan tugas akhir semester adalah tanggal 20 Desember 2025. Tugas dikumpulkan dalam bentuk hardcopy dan softcopy (upload ke e-learning). Keterlambatan pengumpulan akan dikenakan pengurangan nilai.',
                'author_id'    => 1, // Admin
                'is_published' => true,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
        ];

        $this->db->table('pengumuman')->insertBatch($pengumuman);

        // ============ KOMENTAR ============
        $komentar = [
            // Komentar di pengumuman UTS
            [
                'pengumuman_id' => 1,
                'user_id'       => 5, // Andi Pratama (mhs001)
                'isi_komentar'  => 'Apakah UTS bisa diikuti secara online untuk yang sedang KP?',
                'created_at'    => date('Y-m-d H:i:s', strtotime('-6 days')),
            ],
            [
                'pengumuman_id' => 1,
                'user_id'       => 6, // Dewi Lestari (mhs002)
                'isi_komentar'  => 'Terima kasih infonya, Pak. Mohon jadwal ruangannya juga diumumkan.',
                'created_at'    => date('Y-m-d H:i:s', strtotime('-5 days')),
            ],
            // Komentar di pengumuman CTF
            [
                'pengumuman_id' => 2,
                'user_id'       => 7, // Rizky Hidayat (mhs003)
                'isi_komentar'  => 'Saya mau ikut! Bagaimana cara daftarnya, Pak?',
                'created_at'    => date('Y-m-d H:i:s', strtotime('-2 days')),
            ],
            [
                'pengumuman_id' => 2,
                'user_id'       => 9, // Fajar Nugroho (mhs005)
                'isi_komentar'  => 'Apakah perlu bawa laptop sendiri?',
                'created_at'    => date('Y-m-d H:i:s', strtotime('-1 day')),
            ],
            // VULN-XSS-001: Komentar dengan payload XSS (untuk demo)
            // Deskripsi: Komentar ini mengandung tag <script> yang akan dieksekusi
            // Dampak: Cookie/session bisa dicuri, halaman bisa dimanipulasi
            // Fix: Gunakan esc() saat menampilkan dan sanitasi input
            [
                'pengumuman_id' => 2,
                'user_id'       => 11, // Bayu Aditya (mhs007)
                'isi_komentar'  => 'Keren banget! <script>alert("XSS Demo - VulnSchool")</script>',
                'created_at'    => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('komentar_pengumuman')->insertBatch($komentar);
    }
}
