<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Seeder: DosenSeeder
 * Membuat data profil dosen (3 dosen)
 * user_id merujuk ke users yang dibuat oleh UserSeeder
 */
class DosenSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'user_id'          => 2, // dosen1 - Dr. Budi Santoso
                'nip'              => '198501152010011001',
                'nama'             => 'Dr. Budi Santoso, M.Kom.',
                'bidang_keahlian'  => 'Keamanan Siber & Jaringan Komputer',
                'no_hp'            => '081234567001',
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'user_id'          => 3, // dosen2 - Siti Nurhaliza
                'nip'              => '199003202015012002',
                'nama'             => 'Siti Nurhaliza, S.Kom., M.T.',
                'bidang_keahlian'  => 'Basis Data & Data Mining',
                'no_hp'            => '081234567002',
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'user_id'          => 4, // dosen3 - Ahmad Wijaya
                'nip'              => '198807102012011003',
                'nama'             => 'Ahmad Wijaya, S.T., M.Cs.',
                'bidang_keahlian'  => 'Pemrograman Web & Mobile',
                'no_hp'            => '081234567003',
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('dosen')->insertBatch($data);
    }
}
