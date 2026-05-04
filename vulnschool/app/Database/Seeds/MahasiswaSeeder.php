<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Seeder: MahasiswaSeeder
 * Membuat data profil 10 mahasiswa
 * user_id merujuk ke users yang dibuat oleh UserSeeder (id 5-14)
 */
class MahasiswaSeeder extends Seeder
{
    public function run()
    {
        $jurusan = 'Teknik Informatika';

        $data = [
            [
                'user_id'    => 5,
                'nim'        => 'D011211001',
                'nama'       => 'Andi Pratama',
                'jurusan'    => $jurusan,
                'angkatan'   => 2023,
                'alamat'     => 'Jl. Ahmad Yani No. 10, Pontianak',
                'no_hp'      => '085712340001',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id'    => 6,
                'nim'        => 'D011211002',
                'nama'       => 'Dewi Lestari',
                'jurusan'    => $jurusan,
                'angkatan'   => 2023,
                'alamat'     => 'Jl. Gajah Mada No. 25, Pontianak',
                'no_hp'      => '085712340002',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id'    => 7,
                'nim'        => 'D011211003',
                'nama'       => 'Rizky Hidayat',
                'jurusan'    => $jurusan,
                'angkatan'   => 2023,
                'alamat'     => 'Jl. Tanjungpura No. 5, Pontianak',
                'no_hp'      => '085712340003',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id'    => 8,
                'nim'        => 'D011211004',
                'nama'       => 'Putri Rahayu',
                'jurusan'    => $jurusan,
                'angkatan'   => 2023,
                'alamat'     => 'Jl. Sultan Abdurrahman No. 15, Pontianak',
                'no_hp'      => '085712340004',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id'    => 9,
                'nim'        => 'D011211005',
                'nama'       => 'Fajar Nugroho',
                'jurusan'    => $jurusan,
                'angkatan'   => 2024,
                'alamat'     => 'Jl. Imam Bonjol No. 8, Pontianak',
                'no_hp'      => '085712340005',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id'    => 10,
                'nim'        => 'D011211006',
                'nama'       => 'Maya Sari',
                'jurusan'    => $jurusan,
                'angkatan'   => 2024,
                'alamat'     => 'Jl. Pahlawan No. 20, Pontianak',
                'no_hp'      => '085712340006',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id'    => 11,
                'nim'        => 'D011211007',
                'nama'       => 'Bayu Aditya',
                'jurusan'    => $jurusan,
                'angkatan'   => 2024,
                'alamat'     => 'Jl. Sutoyo No. 12, Pontianak',
                'no_hp'      => '085712340007',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id'    => 12,
                'nim'        => 'D011211008',
                'nama'       => 'Nadia Fitri',
                'jurusan'    => $jurusan,
                'angkatan'   => 2024,
                'alamat'     => 'Jl. Diponegoro No. 30, Pontianak',
                'no_hp'      => '085712340008',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id'    => 13,
                'nim'        => 'D011211009',
                'nama'       => 'Rendi Kurniawan',
                'jurusan'    => $jurusan,
                'angkatan'   => 2024,
                'alamat'     => 'Jl. Veteran No. 7, Pontianak',
                'no_hp'      => '085712340009',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id'    => 14,
                'nim'        => 'D011211010',
                'nama'       => 'Linda Wati',
                'jurusan'    => $jurusan,
                'angkatan'   => 2024,
                'alamat'     => 'Jl. Merdeka No. 18, Pontianak',
                'no_hp'      => '085712340010',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('mahasiswa')->insertBatch($data);
    }
}
