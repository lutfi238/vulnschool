<?php

namespace App\Controllers;

use App\Models\AbsensiModel;
use App\Models\MahasiswaModel;
use App\Models\MataKuliahModel;

/**
 * Controller: AbsensiController
 * Fitur absensi untuk mahasiswa yang login
 *
 * VULN-IDOR-006: IDOR pada Detail Absensi Mahasiswa
 */
class AbsensiController extends BaseController
{
    protected AbsensiModel $absensiModel;
    protected MahasiswaModel $mahasiswaModel;
    protected MataKuliahModel $mkModel;

    public function __construct()
    {
        $this->absensiModel   = new AbsensiModel();
        $this->mahasiswaModel = new MahasiswaModel();
        $this->mkModel        = new MataKuliahModel();
    }

    /**
     * List absensi semua MK yang diambil
     */
    public function index()
    {
        $mahasiswa = $this->mahasiswaModel->findByUserId(session()->get('user_id'));

        if (!$mahasiswa) {
            return redirect()->to('/dashboard')
                ->with('error', 'Profil mahasiswa belum terdaftar.');
        }

        $mataKuliahList = $this->absensiModel->getMataKuliahAbsensi($mahasiswa['id']);

        // Hitung persentase kehadiran per MK
        foreach ($mataKuliahList as &$mk) {
            $total = (int) $mk['total_pertemuan'];
            $hadir = (int) $mk['total_hadir'];
            $mk['persentase'] = $total > 0 ? round(($hadir / $total) * 100, 2) : 0;
        }

        return view('mahasiswa/absensi/index', [
            'title'          => 'Absensi Saya',
            'mahasiswa'      => $mahasiswa,
            'mataKuliahList' => $mataKuliahList,
        ]);
    }

    /**
     * Detail absensi per MK
     *
     * VULN-IDOR-006: IDOR pada Absensi Mahasiswa
     */
    public function detail($mataKuliahId)
    {
        // VULN-IDOR-006: IDOR pada Absensi Mahasiswa
        // Deskripsi: Method detail() mengambil mahasiswa_id dari session, tetapi TIDAK
        //            melakukan pengecekan apakah mahasiswa benar-benar mengambil MK ini.
        //            Mahasiswa bisa mengakses absensi MK yang tidak diambilnya,
        //            dan jika dikombinasikan dengan manipulasi lain, bisa melihat
        //            data absensi MK yang seharusnya tidak visible untuknya.
        // Dampak: Information disclosure — mahasiswa bisa melihat informasi pertemuan,
        //         jadwal, dan data MK yang tidak seharusnya diakses.
        // Fix: Validasi bahwa mahasiswa memiliki record absensi di MK ini:
        //      $cek = $this->absensiModel
        //                  ->where('mahasiswa_id', $mahasiswa['id'])
        //                  ->where('mata_kuliah_id', $mataKuliahId)
        //                  ->countAllResults();
        //      if ($cek === 0) {
        //          throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        //      }

        $mahasiswa = $this->mahasiswaModel->findByUserId(session()->get('user_id'));

        if (!$mahasiswa) {
            return redirect()->to('/dashboard')
                ->with('error', 'Profil mahasiswa belum terdaftar.');
        }

        $mk = $this->mkModel->find($mataKuliahId);
        if (!$mk) {
            return redirect()->to('/mahasiswa/absensi')
                ->with('error', 'Mata kuliah tidak ditemukan.');
        }

        // Ambil absensi mahasiswa di MK ini (tanpa validasi enrollment — VULN!)
        $absensiList = $this->absensiModel->getAbsensiMahasiswa($mahasiswa['id'], (int) $mataKuliahId);

        // Hitung statistik
        $totalPertemuan = count($absensiList);
        $totalHadir = count(array_filter($absensiList, fn($a) => $a['status'] === 'hadir'));
        $totalSakit = count(array_filter($absensiList, fn($a) => $a['status'] === 'sakit'));
        $totalIzin  = count(array_filter($absensiList, fn($a) => $a['status'] === 'izin'));
        $totalAlpha = count(array_filter($absensiList, fn($a) => $a['status'] === 'alpha'));
        $persentase = $totalPertemuan > 0 ? round(($totalHadir / $totalPertemuan) * 100, 2) : 0;

        return view('mahasiswa/absensi/detail', [
            'title'          => 'Detail Absensi — ' . $mk['nama_mk'],
            'mahasiswa'      => $mahasiswa,
            'mk'             => $mk,
            'absensiList'    => $absensiList,
            'totalPertemuan' => $totalPertemuan,
            'totalHadir'     => $totalHadir,
            'totalSakit'     => $totalSakit,
            'totalIzin'      => $totalIzin,
            'totalAlpha'     => $totalAlpha,
            'persentase'     => $persentase,
        ]);
    }
}
