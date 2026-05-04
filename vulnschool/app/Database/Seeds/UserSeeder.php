<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Seeder: UserSeeder
 * Membuat data pengguna default:
 * - 1 admin
 * - 3 dosen
 * - 10 mahasiswa
 *
 * VULN-AUTH-001: Semua password disimpan dalam PLAIN TEXT
 */
class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // ============ ADMIN ============
            [
                'username'   => 'admin',
                // VULN-AUTH-001: Password plain text, seharusnya di-hash
                'password'   => 'admin123',
                'email'      => 'admin@vulnschool.ac.id',
                'full_name'  => 'Administrator Sistem',
                'role'       => 'admin',
                'is_active'  => true,
                'is_admin'   => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // ============ DOSEN ============
            [
                'username'   => 'dosen1',
                'password'   => 'dosen123',
                'email'      => 'budi.santoso@vulnschool.ac.id',
                'full_name'  => 'Dr. Budi Santoso, M.Kom.',
                'role'       => 'dosen',
                'is_active'  => true,
                'is_admin'   => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username'   => 'dosen2',
                'password'   => 'dosen123',
                'email'      => 'siti.nurhaliza@vulnschool.ac.id',
                'full_name'  => 'Siti Nurhaliza, S.Kom., M.T.',
                'role'       => 'dosen',
                'is_active'  => true,
                'is_admin'   => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username'   => 'dosen3',
                'password'   => 'dosen123',
                'email'      => 'ahmad.wijaya@vulnschool.ac.id',
                'full_name'  => 'Ahmad Wijaya, S.T., M.Cs.',
                'role'       => 'dosen',
                'is_active'  => true,
                'is_admin'   => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // ============ MAHASISWA ============
            [
                'username'   => 'mhs001',
                'password'   => 'mhs123',
                'email'      => 'andi.pratama@student.vulnschool.ac.id',
                'full_name'  => 'Andi Pratama',
                'role'       => 'mahasiswa',
                'is_active'  => true,
                'is_admin'   => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username'   => 'mhs002',
                'password'   => 'mhs123',
                'email'      => 'dewi.lestari@student.vulnschool.ac.id',
                'full_name'  => 'Dewi Lestari',
                'role'       => 'mahasiswa',
                'is_active'  => true,
                'is_admin'   => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username'   => 'mhs003',
                'password'   => 'mhs123',
                'email'      => 'rizky.hidayat@student.vulnschool.ac.id',
                'full_name'  => 'Rizky Hidayat',
                'role'       => 'mahasiswa',
                'is_active'  => true,
                'is_admin'   => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username'   => 'mhs004',
                'password'   => 'mhs123',
                'email'      => 'putri.rahayu@student.vulnschool.ac.id',
                'full_name'  => 'Putri Rahayu',
                'role'       => 'mahasiswa',
                'is_active'  => true,
                'is_admin'   => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username'   => 'mhs005',
                'password'   => 'mhs123',
                'email'      => 'fajar.nugroho@student.vulnschool.ac.id',
                'full_name'  => 'Fajar Nugroho',
                'role'       => 'mahasiswa',
                'is_active'  => true,
                'is_admin'   => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username'   => 'mhs006',
                'password'   => 'mhs123',
                'email'      => 'maya.sari@student.vulnschool.ac.id',
                'full_name'  => 'Maya Sari',
                'role'       => 'mahasiswa',
                'is_active'  => true,
                'is_admin'   => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username'   => 'mhs007',
                'password'   => 'mhs123',
                'email'      => 'bayu.aditya@student.vulnschool.ac.id',
                'full_name'  => 'Bayu Aditya',
                'role'       => 'mahasiswa',
                'is_active'  => true,
                'is_admin'   => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username'   => 'mhs008',
                'password'   => 'mhs123',
                'email'      => 'nadia.fitri@student.vulnschool.ac.id',
                'full_name'  => 'Nadia Fitri',
                'role'       => 'mahasiswa',
                'is_active'  => true,
                'is_admin'   => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username'   => 'mhs009',
                'password'   => 'mhs123',
                'email'      => 'rendi.kurniawan@student.vulnschool.ac.id',
                'full_name'  => 'Rendi Kurniawan',
                'role'       => 'mahasiswa',
                'is_active'  => true,
                'is_admin'   => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username'   => 'mhs010',
                'password'   => 'mhs123',
                'email'      => 'linda.wati@student.vulnschool.ac.id',
                'full_name'  => 'Linda Wati',
                'role'       => 'mahasiswa',
                'is_active'  => true,
                'is_admin'   => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('users')->insertBatch($data);
    }
}
