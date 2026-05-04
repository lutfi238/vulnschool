<?php

namespace App\Controllers;

use App\Models\NilaiModel;
use App\Models\MahasiswaModel;
use App\Models\MataKuliahModel;
use App\Models\DosenModel;

/**
 * Controller: NilaiController
 * Fitur nilai untuk mahasiswa yang login
 *
 * VULN-IDOR-004: IDOR pada Detail Nilai Mahasiswa (TARGET P11 PRAKTIKUM)
 * VULN-IDOR-005: IDOR pada List Nilai (Bulk)
 * VULN-SQLI-004: SQL Injection di Filter Nilai
 */
class NilaiController extends BaseController
{
    protected NilaiModel $nilaiModel;
    protected MahasiswaModel $mahasiswaModel;

    public function __construct()
    {
        $this->nilaiModel     = new NilaiModel();
        $this->mahasiswaModel = new MahasiswaModel();
    }

    /**
     * Transcript nilai mahasiswa
     *
     * VULN-IDOR-005: IDOR pada List Nilai (Bulk)
     * VULN-SQLI-004: SQL Injection di Filter Nilai
     */
    public function index()
    {
        // VULN-IDOR-005: IDOR pada List Nilai (Bulk)
        // Deskripsi: Parameter mhs_id dari GET digunakan langsung untuk query nilai.
        //            Jika tidak ada parameter, baru fallback ke session mahasiswa_id.
        //            Attacker bisa menambah ?mhs_id=2 di URL untuk melihat
        //            semua nilai milik mahasiswa lain sekaligus (bulk data leak).
        // Dampak: Seluruh transcript akademik mahasiswa lain terekspos.
        //         Attacker bisa iterate ?mhs_id=1, ?mhs_id=2, dst untuk scrape
        //         semua data nilai dalam sistem.
        // Fix: JANGAN gunakan parameter GET untuk menentukan mahasiswa:
        //      $mahasiswa = $this->mahasiswaModel->findByUserId(session()->get('user_id'));
        //      $mahasiswa_id = $mahasiswa['id'];
        //      // Hapus sepenuhnya $this->request->getGet('mhs_id')
        $mahasiswa = $this->mahasiswaModel->findByUserId(session()->get('user_id'));

        $mhs_id = $this->request->getGet('mhs_id') ?? ($mahasiswa ? $mahasiswa['id'] : null);

        if (!$mhs_id) {
            return redirect()->to('/dashboard')
                ->with('error', 'Profil mahasiswa belum terdaftar.');
        }

        // VULN-SQLI-004: SQL Injection di Filter Nilai
        // Deskripsi: Parameter semester dari GET request disisipkan langsung ke raw SQL
        //            tanpa sanitasi atau parameter binding.
        // Dampak: Attacker bisa inject query SQL untuk mengekstrak data sensitif:
        //         /mahasiswa/nilai?semester=' UNION SELECT password,2,3,4,5,6,7,8 FROM users --
        // Fix: Gunakan Query Builder dengan parameter binding:
        //      $this->nilaiModel->where('semester_tahun', $semester)->findAll()
        $semester = $this->request->getGet('semester');

        if ($semester) {
            $db = \Config\Database::connect();
            $sql = "SELECT nilai.*, mata_kuliah.kode_mk, mata_kuliah.nama_mk, mata_kuliah.sks
                    FROM nilai
                    JOIN mata_kuliah ON mata_kuliah.id = nilai.mata_kuliah_id
                    WHERE nilai.mahasiswa_id = $mhs_id
                    AND nilai.semester_tahun = '$semester'";
            $nilaiList = $db->query($sql)->getResultArray();
        } else {
            $nilaiList = $this->nilaiModel->getNilaiByMahasiswa((int) $mhs_id);
        }

        // Hitung IPK
        $totalSks = 0;
        $totalBobot = 0;
        foreach ($nilaiList as &$n) {
            $sks = (int) ($n['sks'] ?? 0);
            $bobot = NilaiModel::gradeToBobot($n['grade'] ?? 'E');
            $totalSks += $sks;
            $totalBobot += ($sks * $bobot);
        }
        $ipk = $totalSks > 0 ? round($totalBobot / $totalSks, 2) : 0;

        // Ambil data mahasiswa yang ditampilkan (untuk header transcript)
        $targetMahasiswa = $this->mahasiswaModel->getMahasiswaWithUser((int) $mhs_id);

        return view('mahasiswa/nilai/index', [
            'title'       => 'Transcript Nilai',
            'nilaiList'   => $nilaiList,
            'mahasiswa'   => $targetMahasiswa ?? $mahasiswa,
            'totalSks'    => $totalSks,
            'ipk'         => $ipk,
            'semester'    => $semester,
        ]);
    }

    /**
     * Detail satu record nilai
     *
     * VULN-IDOR-004: IDOR pada Detail Nilai Mahasiswa (TARGET UTAMA P11 PRAKTIKUM)
     */
    public function detail($nilai_id)
    {
        // VULN-IDOR-004: IDOR pada Detail Nilai Mahasiswa
        // ══════════════════════════════════════════════════════════════════════
        // TARGET UTAMA DEMONSTRASI P11 PRAKTIKUM KEAMANAN INFORMASI
        // ══════════════════════════════════════════════════════════════════════
        //
        // Deskripsi: Method detail() hanya menerima nilai_id tanpa validasi kepemilikan.
        //            Mahasiswa A bisa akses nilai mahasiswa B dengan menebak/iterate ID.
        //            Tidak ada pengecekan apakah nilai tersebut milik mahasiswa yang login.
        //
        // Dampak: Privacy violation tinggi — nilai akademik adalah data sensitif.
        //         Attacker bisa scrape semua nilai dengan iterate ID:
        //         /mahasiswa/nilai/1, /mahasiswa/nilai/2, /mahasiswa/nilai/3, ...
        //         Semua informasi nilai, nama MK, grade, dan identitas mahasiswa lain terekspos.
        //
        // Reproduksi:
        //   1. Login sebagai mhs001, akses /mahasiswa/nilai → catat ID nilai sendiri (misal 1,2,3)
        //   2. Ganti ID di URL ke nilai milik mhs002: /mahasiswa/nilai/4
        //   3. Halaman menampilkan detail nilai milik mahasiswa lain!
        //   4. Iterate /mahasiswa/nilai/5, /6, /7, ... untuk scrape seluruh database nilai
        //
        // Fix:
        //   $mahasiswa = $this->mahasiswaModel->findByUserId(session()->get('user_id'));
        //   $nilai = $this->nilaiModel
        //                ->where('id', $nilai_id)
        //                ->where('mahasiswa_id', $mahasiswa['id'])
        //                ->first();
        //   if (!$nilai) {
        //       throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        //   }

        $nilai = $this->nilaiModel->getNilaiDetail((int) $nilai_id);

        if (!$nilai) {
            return redirect()->to('/mahasiswa/nilai')
                ->with('error', 'Data nilai tidak ditemukan.');
        }

        return view('mahasiswa/nilai/detail', [
            'title' => 'Detail Nilai',
            'nilai' => $nilai,
        ]);
    }
}
