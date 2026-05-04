<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\NilaiModel;

/**
 * Seeder: NilaiSeeder
 * Membuat 30 record nilai dengan variasi realistis
 */
class NilaiSeeder extends Seeder
{
    public function run()
    {
        $data = [];
        $sem = '2025/Ganjil';
        $now = date('Y-m-d H:i:s');

        // 30 kombinasi: semua mhs(1-10) ambil MK1, semua mhs ambil MK2, 5 mhs ambil MK3, 5 mhs ambil MK4
        $combos = [
            [1,1],[2,1],[3,1],[4,1],[5,1],[6,1],[7,1],[8,1],[9,1],[10,1],
            [1,2],[2,2],[3,2],[4,2],[5,2],[6,2],[7,2],[8,2],[9,2],[10,2],
            [1,3],[2,3],[3,3],[4,3],[5,3],
            [6,4],[7,4],[8,4],[9,4],[10,4],
        ];

        mt_srand(42);
        foreach ($combos as $c) {
            $t = mt_rand(5500, 9800) / 100;
            $u = mt_rand(5000, 9500) / 100;
            $a = mt_rand(4500, 9700) / 100;
            $na = NilaiModel::hitungNilaiAkhir($t, $u, $a);
            $g  = NilaiModel::tentukanGrade($na);

            $data[] = [
                'mahasiswa_id' => $c[0], 'mata_kuliah_id' => $c[1],
                'nilai_tugas' => $t, 'nilai_uts' => $u, 'nilai_uas' => $a,
                'nilai_akhir' => $na, 'grade' => $g, 'semester_tahun' => $sem,
                'created_at' => $now, 'updated_at' => $now,
            ];
        }

        $this->db->table('nilai')->insertBatch($data);
    }
}
