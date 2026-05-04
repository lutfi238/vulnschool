<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Seeder: AbsensiSeeder
 * Membuat 50 record absensi (kehadiran) mahasiswa
 */
class AbsensiSeeder extends Seeder
{
    public function run()
    {
        $data = [];
        $now = date('Y-m-d H:i:s');
        $statusList = ['hadir', 'hadir', 'hadir', 'hadir', 'sakit', 'izin', 'alpha'];
        // Lebih banyak 'hadir' agar realistis

        mt_srand(123);

        // SKI301 (mata_kuliah_id=1): 5 pertemuan x 10 mahasiswa = 50 record
        $baseDate = '2025-09-01';
        $count = 0;

        for ($pertemuan = 1; $pertemuan <= 5 && $count < 50; $pertemuan++) {
            $tanggal = date('Y-m-d', strtotime($baseDate . ' + ' . (($pertemuan - 1) * 7) . ' days'));

            for ($mhsId = 1; $mhsId <= 10 && $count < 50; $mhsId++) {
                $status = $statusList[mt_rand(0, count($statusList) - 1)];
                $keterangan = null;

                if ($status === 'sakit') {
                    $keterangan = 'Surat keterangan dokter terlampir';
                } elseif ($status === 'izin') {
                    $keterangan = 'Keperluan keluarga';
                }

                $data[] = [
                    'mahasiswa_id'   => $mhsId,
                    'mata_kuliah_id' => 1, // SKI301
                    'pertemuan'      => $pertemuan,
                    'tanggal'        => $tanggal,
                    'status'         => $status,
                    'keterangan'     => $keterangan,
                    'created_at'     => $now,
                ];

                $count++;
            }
        }

        $this->db->table('absensi')->insertBatch($data);
    }
}
