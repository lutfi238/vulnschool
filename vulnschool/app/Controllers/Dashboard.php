<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\MahasiswaModel;
use App\Models\DosenModel;
use App\Models\MataKuliahModel;
use App\Models\NilaiModel;
use App\Models\AbsensiModel;
use App\Models\PengumumanModel;

/**
 * Controller: Dashboard
 * Menampilkan dashboard sesuai role user yang login
 */
class Dashboard extends BaseController
{
    /**
     * Route ke dashboard yang sesuai berdasarkan role
     */
    public function index()
    {
        $role = session()->get('role');

        return match ($role) {
            'admin'     => $this->adminDashboard(),
            'dosen'     => $this->dosenDashboard(),
            'mahasiswa' => $this->mahasiswaDashboard(),
            default     => redirect()->to('/login')->with('error', 'Role tidak dikenali.'),
        };
    }

    /**
     * Dashboard Admin
     * Statistik: total user, mahasiswa, dosen, mata kuliah
     * Tabel: 5 user terbaru, 5 pengumuman terbaru
     */
    private function adminDashboard()
    {
        $userModel       = new UserModel();
        $mahasiswaModel  = new MahasiswaModel();
        $dosenModel      = new DosenModel();
        $mataKuliahModel = new MataKuliahModel();
        $pengumumanModel = new PengumumanModel();

        // Statistik
        $stats = [
            'total_users'      => $userModel->countAllResults(),
            'total_mahasiswa'  => $mahasiswaModel->countAllResults(),
            'total_dosen'      => $dosenModel->countAllResults(),
            'total_matakuliah' => $mataKuliahModel->countAllResults(),
        ];

        // 5 user terbaru
        $recentUsers = $userModel->orderBy('created_at', 'DESC')->findAll(5);

        // 5 pengumuman terbaru
        $recentPengumuman = $pengumumanModel->getAllWithAuthor();
        $recentPengumuman = array_slice($recentPengumuman, 0, 5);

        return view('dashboard/admin', [
            'title'             => 'Dashboard Admin',
            'stats'             => $stats,
            'recentUsers'       => $recentUsers,
            'recentPengumuman'  => $recentPengumuman,
        ]);
    }

    /**
     * Dashboard Dosen
     * Statistik: mata kuliah diampu, total mahasiswa
     * Tabel: mata kuliah diampu, pengumuman terbaru
     */
    private function dosenDashboard()
    {
        $dosenModel      = new DosenModel();
        $mataKuliahModel = new MataKuliahModel();
        $nilaiModel      = new NilaiModel();
        $pengumumanModel = new PengumumanModel();

        // Cari data profil dosen berdasarkan user_id
        $dosen = $dosenModel->findByUserId(session()->get('user_id'));

        $mataKuliah = [];
        $totalMahasiswa = 0;

        if ($dosen) {
            // Mata kuliah yang diampu
            $mataKuliah = $mataKuliahModel->getByDosenId($dosen['id']);

            // Hitung total mahasiswa unik di semua mata kuliah dosen ini
            $db = \Config\Database::connect();
            $mkIds = array_column($mataKuliah, 'id');
            if (!empty($mkIds)) {
                $mkIdsStr = implode(',', $mkIds);
                $query = $db->query("SELECT COUNT(DISTINCT mahasiswa_id) as total FROM nilai WHERE mata_kuliah_id IN ($mkIdsStr)");
                $result = $query->getRowArray();
                $totalMahasiswa = $result['total'] ?? 0;
            }
        }

        // Pengumuman terbaru
        $recentPengumuman = $pengumumanModel->getPublished();
        $recentPengumuman = array_slice($recentPengumuman, 0, 5);

        return view('dashboard/dosen', [
            'title'            => 'Dashboard Dosen',
            'dosen'            => $dosen,
            'mataKuliah'       => $mataKuliah,
            'totalMahasiswa'   => $totalMahasiswa,
            'recentPengumuman' => $recentPengumuman,
        ]);
    }

    /**
     * Dashboard Mahasiswa
     * Statistik: IPK, total SKS, total MK, persentase kehadiran
     * Grafik: nilai per mata kuliah
     * Daftar pengumuman terbaru
     */
    private function mahasiswaDashboard()
    {
        $mahasiswaModel  = new MahasiswaModel();
        $nilaiModel      = new NilaiModel();
        $absensiModel    = new AbsensiModel();
        $pengumumanModel = new PengumumanModel();

        // Cari data profil mahasiswa berdasarkan user_id
        $mahasiswa = $mahasiswaModel->findByUserId(session()->get('user_id'));

        $nilaiList    = [];
        $ipk          = 0;
        $totalSks     = 0;
        $totalMk      = 0;
        $avgKehadiran = 0;

        if ($mahasiswa) {
            // Ambil semua nilai
            $nilaiList = $nilaiModel->getNilaiByMahasiswa($mahasiswa['id']);
            $totalMk = count($nilaiList);

            // Hitung IPK
            $totalBobot = 0;
            $totalSksTemp = 0;
            foreach ($nilaiList as $n) {
                $bobotGrade = $this->gradeToBobot($n['grade']);
                $sks = (int) $n['sks'];
                $totalBobot += $bobotGrade * $sks;
                $totalSksTemp += $sks;
            }
            $totalSks = $totalSksTemp;
            $ipk = $totalSks > 0 ? round($totalBobot / $totalSks, 2) : 0;

            // Rata-rata persentase kehadiran di semua mata kuliah
            $kehadiranList = [];
            $mkIds = array_unique(array_column($nilaiList, 'mata_kuliah_id'));
            foreach ($mkIds as $mkId) {
                $persen = $absensiModel->hitungPersentaseKehadiran($mahasiswa['id'], $mkId);
                if ($persen > 0) {
                    $kehadiranList[] = $persen;
                }
            }
            $avgKehadiran = count($kehadiranList) > 0
                ? round(array_sum($kehadiranList) / count($kehadiranList), 1)
                : 0;
        }

        // Pengumuman terbaru
        $recentPengumuman = $pengumumanModel->getPublished();
        $recentPengumuman = array_slice($recentPengumuman, 0, 5);

        return view('dashboard/mahasiswa', [
            'title'            => 'Dashboard Mahasiswa',
            'mahasiswa'        => $mahasiswa,
            'nilaiList'        => $nilaiList,
            'ipk'              => $ipk,
            'totalSks'         => $totalSks,
            'totalMk'          => $totalMk,
            'avgKehadiran'     => $avgKehadiran,
            'recentPengumuman' => $recentPengumuman,
        ]);
    }

    /**
     * Konversi grade huruf ke bobot angka (skala 4)
     */
    private function gradeToBobot(string $grade): float
    {
        return match ($grade) {
            'A'  => 4.0,
            'B+' => 3.5,
            'B'  => 3.0,
            'C+' => 2.5,
            'C'  => 2.0,
            'D'  => 1.0,
            'E'  => 0.0,
            default => 0.0,
        };
    }
}
