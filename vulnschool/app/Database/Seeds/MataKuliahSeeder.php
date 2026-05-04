<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Seeder: MataKuliahSeeder
 * Membuat 5 mata kuliah untuk D3 Teknik Informatika
 */
class MataKuliahSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'kode_mk'    => 'SKI301',
                'nama_mk'    => 'Sistem Keamanan Informasi',
                'sks'        => 3,
                'dosen_id'   => 1, // Dr. Budi Santoso
                'semester'   => 5,
                'deskripsi'  => 'Mata kuliah yang membahas konsep dasar keamanan informasi, ancaman siber, kriptografi, penetration testing, dan best practices keamanan aplikasi web.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'kode_mk'    => 'PBD201',
                'nama_mk'    => 'Pemrograman Basis Data',
                'sks'        => 3,
                'dosen_id'   => 2, // Siti Nurhaliza
                'semester'   => 3,
                'deskripsi'  => 'Mata kuliah yang membahas perancangan dan implementasi basis data relasional menggunakan MySQL, termasuk query SQL lanjut, stored procedure, dan trigger.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'kode_mk'    => 'PWB301',
                'nama_mk'    => 'Pemrograman Web Lanjut',
                'sks'        => 4,
                'dosen_id'   => 3, // Ahmad Wijaya
                'semester'   => 5,
                'deskripsi'  => 'Mata kuliah yang membahas pengembangan aplikasi web menggunakan framework PHP, REST API, dan integrasi frontend-backend.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'kode_mk'    => 'JKO201',
                'nama_mk'    => 'Jaringan Komputer',
                'sks'        => 3,
                'dosen_id'   => 1, // Dr. Budi Santoso
                'semester'   => 3,
                'deskripsi'  => 'Mata kuliah yang membahas konsep jaringan komputer, model OSI, TCP/IP, routing, switching, dan administrasi jaringan.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'kode_mk'    => 'PMO301',
                'nama_mk'    => 'Pemrograman Mobile',
                'sks'        => 3,
                'dosen_id'   => 3, // Ahmad Wijaya
                'semester'   => 5,
                'deskripsi'  => 'Mata kuliah yang membahas pengembangan aplikasi mobile menggunakan Android (Kotlin/Java) dan konsep cross-platform development.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('mata_kuliah')->insertBatch($data);
    }
}
