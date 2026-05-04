<?php

namespace App\Controllers;

use App\Models\DosenModel;
use App\Models\MataKuliahModel;
use App\Models\NilaiModel;
use App\Models\AbsensiModel;
use App\Models\MahasiswaModel;

/**
 * Controller: DosenController
 * Fitur mata kuliah untuk dosen yang login
 *
 * VULN-IDOR-003: IDOR pada Detail Mata Kuliah Dosen
 * VULN-MASS-002: Mass Assignment di Edit Profil Dosen (via profil update)
 */
class DosenController extends BaseController
{
    protected DosenModel $dosenModel;
    protected MataKuliahModel $mkModel;

    public function __construct()
    {
        $this->dosenModel = new DosenModel();
        $this->mkModel    = new MataKuliahModel();
    }

    // ========================================================================
    // FITUR DOSEN: MATA KULIAH
    // ========================================================================

    /**
     * List mata kuliah yang diampu oleh dosen yang login
     */
    public function mataKuliah()
    {
        $dosen = $this->dosenModel->findByUserId(session()->get('user_id'));

        $mataKuliah = [];
        if ($dosen) {
            $mataKuliah = $this->mkModel->getByDosenId($dosen['id']);
        }

        return view('dosen/matakuliah/index', [
            'title'      => 'Mata Kuliah Saya',
            'dosen'      => $dosen,
            'mataKuliah' => $mataKuliah,
        ]);
    }

    /**
     * Detail mata kuliah + daftar mahasiswa yang mengambil
     *
     * VULN-IDOR-003: Insecure Direct Object Reference pada Detail MK Dosen
     */
    public function detailMataKuliah($id)
    {
        // VULN-IDOR-003: IDOR pada Detail Mata Kuliah Dosen
        // Deskripsi: Tidak ada validasi apakah dosen yang login adalah pengampu
        //            dari mata kuliah ini. Parameter $id langsung digunakan untuk
        //            mengambil data MK tanpa pengecekan kepemilikan.
        // Dampak: Dosen A bisa melihat detail mata kuliah milik dosen B
        //         termasuk daftar mahasiswa dan nilai mereka, hanya dengan
        //         mengubah ID di URL: /dosen/mata-kuliah/1 → /dosen/mata-kuliah/2
        // Fix: Tambahkan validasi kepemilikan:
        //      $dosen = $this->dosenModel->findByUserId(session()->get('user_id'));
        //      $mk = $this->mkModel->find($id);
        //      if (!$mk || (int)$mk['dosen_id'] !== (int)$dosen['id']) {
        //          throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        //      }

        $mk = $this->mkModel->find($id);

        if (!$mk) {
            return redirect()->to('/dosen/mata-kuliah')
                ->with('error', 'Mata kuliah tidak ditemukan.');
        }

        // Ambil data dosen pengampu (untuk ditampilkan)
        $dosenPengampu = $this->dosenModel->find($mk['dosen_id']);

        // Ambil daftar mahasiswa yang mengambil MK ini beserta nilainya
        $nilaiModel = new NilaiModel();
        $mahasiswaList = $nilaiModel->getNilaiByMataKuliah($id);

        return view('dosen/matakuliah/detail', [
            'title'         => 'Detail Mata Kuliah',
            'mk'            => $mk,
            'dosenPengampu' => $dosenPengampu,
            'mahasiswaList' => $mahasiswaList,
        ]);
    }

    // ========================================================================
    // FITUR DOSEN: PROFIL
    // ========================================================================

    /**
     * Lihat profil dosen
     */
    public function profil()
    {
        $dosen = $this->dosenModel->findByUserId(session()->get('user_id'));

        if (!$dosen) {
            return redirect()->to('/dashboard')
                ->with('error', 'Profil dosen Anda belum terdaftar.');
        }

        $dosen = $this->dosenModel->getDosenWithUser($dosen['id']);
        $mataKuliah = $this->mkModel->getByDosenId($dosen['id']);

        return view('dosen/profil', [
            'title'      => 'Profil Dosen',
            'dosen'      => $dosen,
            'mataKuliah' => $mataKuliah,
        ]);
    }

    /**
     * Form edit profil dosen
     */
    public function editProfil()
    {
        $dosen = $this->dosenModel->findByUserId(session()->get('user_id'));

        if (!$dosen) {
            return redirect()->to('/dashboard')
                ->with('error', 'Profil dosen Anda belum terdaftar.');
        }

        $dosen = $this->dosenModel->getDosenWithUser($dosen['id']);

        return view('dosen/profil_edit', [
            'title' => 'Edit Profil Dosen',
            'dosen' => $dosen,
        ]);
    }

    /**
     * Simpan perubahan profil dosen
     *
     * VULN-MASS-002: Mass Assignment di Edit Profil Dosen
     */
    public function updateProfil()
    {
        $dosen = $this->dosenModel->findByUserId(session()->get('user_id'));

        if (!$dosen) {
            return redirect()->to('/dashboard')
                ->with('error', 'Profil dosen Anda belum terdaftar.');
        }

        $id = $dosen['id'];

        try {
            // VULN-MASS-002: Mass Assignment di Edit Profil Dosen
            // Deskripsi: Seluruh data POST diteruskan langsung ke update()
            //            tanpa whitelist field yang diizinkan.
            //            Field 'user_id' termasuk di $allowedFields pada DosenModel,
            //            sehingga attacker bisa menambahkan field 'user_id' di POST
            //            body untuk mengganti pemilik profil dosen ini.
            // Dampak: Dosen bisa mengubah user_id profil sendiri ke user_id
            //         dosen lain → saat login, dosen lain seolah memiliki
            //         profil ini (account takeover)
            // Fix: Whitelist field yang boleh di-update:
            //      $data = [
            //          'bidang_keahlian' => $this->request->getPost('bidang_keahlian'),
            //          'no_hp'           => $this->request->getPost('no_hp'),
            //      ];
            //      $this->dosenModel->update($id, $data);
            $this->dosenModel->update($id, $this->request->getPost());

            return redirect()->to('/dosen/profil')
                ->with('success', 'Profil berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui profil: ' . $e->getMessage())
                ->withInput();
        }
    }

    // ========================================================================
    // FITUR DOSEN: NILAI
    // ========================================================================

    /**
     * List mata kuliah yang diampu untuk pilih input nilai
     */
    public function nilaiIndex()
    {
        $dosen = $this->dosenModel->findByUserId(session()->get('user_id'));

        $mataKuliah = [];
        if ($dosen) {
            $mataKuliah = $this->mkModel->getByDosenId($dosen['id']);
        }

        return view('dosen/nilai/index', [
            'title'      => 'Input Nilai',
            'dosen'      => $dosen,
            'mataKuliah' => $mataKuliah,
        ]);
    }

    /**
     * Form input nilai untuk semua mahasiswa di MK tertentu
     */
    public function nilaiInput($mataKuliahId)
    {
        $mk = $this->mkModel->find($mataKuliahId);
        if (!$mk) {
            return redirect()->to('/dosen/nilai')
                ->with('error', 'Mata kuliah tidak ditemukan.');
        }

        $nilaiModel = new NilaiModel();
        $mahasiswaList = $nilaiModel->getNilaiByMataKuliah($mataKuliahId);

        return view('dosen/nilai/input', [
            'title'         => 'Input Nilai — ' . $mk['nama_mk'],
            'mk'            => $mk,
            'mahasiswaList' => $mahasiswaList,
        ]);
    }

    /**
     * Simpan/update nilai batch
     */
    public function nilaiStore($mataKuliahId)
    {
        $mk = $this->mkModel->find($mataKuliahId);
        if (!$mk) {
            return redirect()->to('/dosen/nilai')
                ->with('error', 'Mata kuliah tidak ditemukan.');
        }

        $nilaiModel = new NilaiModel();
        $nilaiIds = $this->request->getPost('nilai_id');
        $tugas    = $this->request->getPost('nilai_tugas');
        $uts      = $this->request->getPost('nilai_uts');
        $uas      = $this->request->getPost('nilai_uas');

        if (!empty($nilaiIds)) {
            foreach ($nilaiIds as $i => $id) {
                $t = (float) ($tugas[$i] ?? 0);
                $u = (float) ($uts[$i] ?? 0);
                $a = (float) ($uas[$i] ?? 0);
                $na = NilaiModel::hitungNilaiAkhir($t, $u, $a);
                $g  = NilaiModel::tentukanGrade($na);

                $nilaiModel->update($id, [
                    'nilai_tugas' => $t,
                    'nilai_uts'   => $u,
                    'nilai_uas'   => $a,
                    'nilai_akhir' => $na,
                    'grade'       => $g,
                ]);
            }
        }

        return redirect()->to('/dosen/nilai/input/' . $mataKuliahId)
            ->with('success', 'Nilai berhasil disimpan.');
    }

    /**
     * Form edit nilai individual
     */
    public function nilaiEdit($nilaiId)
    {
        $nilaiModel = new NilaiModel();
        $nilai = $nilaiModel->getNilaiDetail($nilaiId);

        if (!$nilai) {
            return redirect()->to('/dosen/nilai')
                ->with('error', 'Data nilai tidak ditemukan.');
        }

        return view('dosen/nilai/edit', [
            'title' => 'Edit Nilai',
            'nilai' => $nilai,
        ]);
    }

    /**
     * Update nilai individual
     */
    public function nilaiUpdate($nilaiId)
    {
        $nilaiModel = new NilaiModel();
        $nilai = $nilaiModel->find($nilaiId);

        if (!$nilai) {
            return redirect()->to('/dosen/nilai')
                ->with('error', 'Data nilai tidak ditemukan.');
        }

        $t = (float) $this->request->getPost('nilai_tugas');
        $u = (float) $this->request->getPost('nilai_uts');
        $a = (float) $this->request->getPost('nilai_uas');
        $na = NilaiModel::hitungNilaiAkhir($t, $u, $a);
        $g  = NilaiModel::tentukanGrade($na);

        $nilaiModel->update($nilaiId, [
            'nilai_tugas' => $t,
            'nilai_uts'   => $u,
            'nilai_uas'   => $a,
            'nilai_akhir' => $na,
            'grade'       => $g,
        ]);

        return redirect()->to('/dosen/nilai/input/' . $nilai['mata_kuliah_id'])
            ->with('success', 'Nilai berhasil diperbarui.');
    }

    // ========================================================================
    // FITUR DOSEN: ABSENSI
    // VULN-MASS-003: Mass Assignment di Update Absensi
    // ========================================================================

    /**
     * List MK yang diampu — pilih untuk input absensi
     */
    public function absensiIndex()
    {
        $dosen = $this->dosenModel->findByUserId(session()->get('user_id'));
        $mataKuliah = [];
        if ($dosen) {
            $mataKuliah = $this->mkModel->getByDosenId($dosen['id']);
        }

        return view('dosen/absensi/index', [
            'title'      => 'Absensi',
            'dosen'      => $dosen,
            'mataKuliah' => $mataKuliah,
        ]);
    }

    /**
     * Input absensi per pertemuan
     */
    public function absensiInput($mataKuliahId)
    {
        $mk = $this->mkModel->find($mataKuliahId);
        if (!$mk) {
            return redirect()->to('/dosen/absensi')
                ->with('error', 'Mata kuliah tidak ditemukan.');
        }

        $absensiModel = new AbsensiModel();
        $pertemuan = $this->request->getGet('pertemuan') ?? 1;

        // Ambil absensi untuk pertemuan ini
        $absensiList = $absensiModel
            ->select('absensi.*, mahasiswa.nim, mahasiswa.nama')
            ->join('mahasiswa', 'mahasiswa.id = absensi.mahasiswa_id')
            ->where('absensi.mata_kuliah_id', $mataKuliahId)
            ->where('absensi.pertemuan', $pertemuan)
            ->orderBy('mahasiswa.nim', 'ASC')
            ->findAll();

        // Jika belum ada record untuk pertemuan ini, ambil daftar mahasiswa dari nilai
        if (empty($absensiList)) {
            $mahasiswaModel = new MahasiswaModel();
            $nilaiModel = new NilaiModel();
            $enrolled = $nilaiModel
                ->select('mahasiswa.id, mahasiswa.nim, mahasiswa.nama')
                ->join('mahasiswa', 'mahasiswa.id = nilai.mahasiswa_id')
                ->where('nilai.mata_kuliah_id', $mataKuliahId)
                ->findAll();
            $isNew = true;
        } else {
            $enrolled = [];
            $isNew = false;
        }

        // Hitung jumlah pertemuan yang sudah ada
        $maxPertemuan = $absensiModel
            ->selectMax('pertemuan', 'max_p')
            ->where('mata_kuliah_id', $mataKuliahId)
            ->first();
        $totalPertemuan = (int) ($maxPertemuan['max_p'] ?? 0);

        return view('dosen/absensi/input', [
            'title'          => 'Input Absensi — ' . $mk['nama_mk'],
            'mk'             => $mk,
            'pertemuan'      => (int) $pertemuan,
            'absensiList'    => $absensiList,
            'enrolled'       => $enrolled ?? [],
            'isNew'          => $isNew ?? true,
            'totalPertemuan' => $totalPertemuan,
        ]);
    }

    /**
     * Simpan absensi batch
     *
     * VULN-MASS-003: Mass Assignment di Update Absensi
     */
    public function absensiStore($mataKuliahId)
    {
        $mk = $this->mkModel->find($mataKuliahId);
        if (!$mk) {
            return redirect()->to('/dosen/absensi')
                ->with('error', 'Mata kuliah tidak ditemukan.');
        }

        $absensiModel = new AbsensiModel();
        $pertemuan    = (int) $this->request->getPost('pertemuan');
        $tanggal      = $this->request->getPost('tanggal');
        $mahasiswaIds = $this->request->getPost('mahasiswa_id');
        $statuses     = $this->request->getPost('status');
        $keterangans  = $this->request->getPost('keterangan');
        $absensiIds   = $this->request->getPost('absensi_id');

        if (!empty($mahasiswaIds)) {
            foreach ($mahasiswaIds as $i => $mhsId) {
                $s = $statuses[$i] ?? 'hadir';
                $k = $keterangans[$i] ?? null;

                if (!empty($absensiIds[$i])) {
                    // VULN-MASS-003: Mass Assignment di Update Absensi
                    // Deskripsi: Menggunakan $this->request->getPost() langsung untuk update.
                    //            Dosen bisa menambahkan field 'mahasiswa_id' di POST body
                    //            untuk mengubah kepemilikan record absensi ke mahasiswa lain.
                    // Dampak: Dosen bisa memindahkan absensi (hadir) ke mahasiswa lain,
                    //         atau assign absensi alpha ke mahasiswa yang sebenarnya hadir.
                    //         Merusak integritas data kehadiran.
                    // Fix: Whitelist hanya field yang dibolehkan:
                    //      $absensiModel->update($absensiIds[$i], [
                    //          'status'     => $s,
                    //          'keterangan' => $k,
                    //          'tanggal'    => $tanggal,
                    //      ]);
                    $updateData = [
                        'status'        => $s,
                        'keterangan'    => $k,
                        'tanggal'       => $tanggal,
                        'mahasiswa_id'  => $mhsId,
                        'mata_kuliah_id'=> $mataKuliahId,
                        'pertemuan'     => $pertemuan,
                    ];
                    $absensiModel->update($absensiIds[$i], $updateData);
                } else {
                    // Insert baru
                    $absensiModel->insert([
                        'mahasiswa_id'   => $mhsId,
                        'mata_kuliah_id' => $mataKuliahId,
                        'pertemuan'      => $pertemuan,
                        'tanggal'        => $tanggal,
                        'status'         => $s,
                        'keterangan'     => $k,
                    ]);
                }
            }
        }

        return redirect()->to('/dosen/absensi/input/' . $mataKuliahId . '?pertemuan=' . $pertemuan)
            ->with('success', 'Absensi pertemuan ke-' . $pertemuan . ' berhasil disimpan.');
    }

    /**
     * Rekap absensi semua mahasiswa per MK
     */
    public function absensiRekap($mataKuliahId)
    {
        $mk = $this->mkModel->find($mataKuliahId);
        if (!$mk) {
            return redirect()->to('/dosen/absensi')
                ->with('error', 'Mata kuliah tidak ditemukan.');
        }

        $absensiModel = new AbsensiModel();
        $rekapList = $absensiModel->getRekapSummary((int) $mataKuliahId);

        // Hitung persentase kehadiran per mahasiswa
        foreach ($rekapList as &$r) {
            $total = (int) $r['total_pertemuan'];
            $hadir = (int) $r['total_hadir'];
            $r['persentase'] = $total > 0 ? round(($hadir / $total) * 100, 2) : 0;
        }

        return view('dosen/absensi/rekap', [
            'title'     => 'Rekap Absensi — ' . $mk['nama_mk'],
            'mk'        => $mk,
            'rekapList' => $rekapList,
        ]);
    }
}
