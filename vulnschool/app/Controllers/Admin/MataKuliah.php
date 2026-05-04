<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MataKuliahModel;
use App\Models\DosenModel;
use App\Models\NilaiModel;

/**
 * Controller: Admin/MataKuliah
 * CRUD mata kuliah untuk admin
 *
 * VULN-SQLI-003: Second-Order SQL Injection via kode_mk
 */
class MataKuliah extends BaseController
{
    protected MataKuliahModel $mkModel;
    protected DosenModel $dosenModel;

    public function __construct()
    {
        $this->mkModel    = new MataKuliahModel();
        $this->dosenModel = new DosenModel();
    }

    /**
     * Daftar semua mata kuliah
     */
    public function index()
    {
        $mataKuliah = $this->mkModel->getAllWithDosen();

        return view('matakuliah/admin/index', [
            'title'      => 'Kelola Mata Kuliah',
            'mataKuliah' => $mataKuliah,
        ]);
    }

    /**
     * Form tambah MK
     */
    public function create()
    {
        $dosenList = $this->dosenModel->findAll();

        return view('matakuliah/admin/form', [
            'title'     => 'Tambah Mata Kuliah',
            'mk'        => null,
            'isEdit'    => false,
            'dosenList' => $dosenList,
        ]);
    }

    /**
     * Simpan MK baru
     *
     * VULN-SQLI-003: Second-Order SQL Injection
     */
    public function store()
    {
        // VULN-SQLI-003: Second-Order SQL Injection via kode_mk
        // Deskripsi: Nilai kode_mk disimpan ke database tanpa sanitasi.
        //            Saat kode_mk ini digunakan di query lain (misal pencarian,
        //            laporan, atau join), payload SQLi yang tersimpan akan tereksekusi.
        //            Ini berbeda dari first-order SQLi karena injection terjadi saat
        //            data DIBACA dari database, bukan saat INSERT.
        // Dampak: Attacker menyimpan payload SQLi di field kode_mk yang nantinya
        //         dieksekusi saat admin melihat laporan atau query lain yang
        //         menggunakan kode_mk tanpa binding
        // Fix: Sanitasi input sebelum simpan DAN gunakan parameter binding saat query:
        //      $kode_mk = htmlspecialchars($this->request->getPost('kode_mk'));
        //      Selalu gunakan Query Builder, bukan raw query
        $data = [
            'kode_mk'   => $this->request->getPost('kode_mk'),
            'nama_mk'   => $this->request->getPost('nama_mk'),
            'sks'        => $this->request->getPost('sks'),
            'dosen_id'   => $this->request->getPost('dosen_id'),
            'semester'   => $this->request->getPost('semester'),
            'deskripsi'  => $this->request->getPost('deskripsi'),
        ];

        try {
            $this->mkModel->insert($data);
            return redirect()->to('/admin/mata-kuliah')
                ->with('success', 'Mata kuliah berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Form edit MK
     */
    public function edit($id)
    {
        $mk = $this->mkModel->find($id);
        if (!$mk) {
            return redirect()->to('/admin/mata-kuliah')
                ->with('error', 'Mata kuliah tidak ditemukan.');
        }

        $dosenList = $this->dosenModel->findAll();

        return view('matakuliah/admin/form', [
            'title'     => 'Edit Mata Kuliah',
            'mk'        => $mk,
            'isEdit'    => true,
            'dosenList' => $dosenList,
        ]);
    }

    /**
     * Update MK
     */
    public function update($id)
    {
        $mk = $this->mkModel->find($id);
        if (!$mk) {
            return redirect()->to('/admin/mata-kuliah')
                ->with('error', 'Mata kuliah tidak ditemukan.');
        }

        // VULN-SQLI-003: kode_mk tetap disimpan tanpa sanitasi
        $data = [
            'kode_mk'   => $this->request->getPost('kode_mk'),
            'nama_mk'   => $this->request->getPost('nama_mk'),
            'sks'        => $this->request->getPost('sks'),
            'dosen_id'   => $this->request->getPost('dosen_id'),
            'semester'   => $this->request->getPost('semester'),
            'deskripsi'  => $this->request->getPost('deskripsi'),
        ];

        try {
            $this->mkModel->update($id, $data);
            return redirect()->to('/admin/mata-kuliah')
                ->with('success', 'Mata kuliah berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Hapus MK
     */
    public function delete($id)
    {
        $mk = $this->mkModel->find($id);
        if (!$mk) {
            return redirect()->to('/admin/mata-kuliah')
                ->with('error', 'Mata kuliah tidak ditemukan.');
        }

        try {
            $this->mkModel->delete($id);
            return redirect()->to('/admin/mata-kuliah')
                ->with('success', 'Mata kuliah berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->to('/admin/mata-kuliah')
                ->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    /**
     * Detail MK + daftar mahasiswa yang mengambil
     *
     * VULN-SQLI-003: kode_mk dari DB digunakan di raw query (second-order)
     */
    public function detail($id)
    {
        $mk = $this->mkModel->find($id);
        if (!$mk) {
            return redirect()->to('/admin/mata-kuliah')
                ->with('error', 'Mata kuliah tidak ditemukan.');
        }

        // Ambil data dosen pengampu
        $dosen = $this->dosenModel->find($mk['dosen_id']);

        // Ambil daftar mahasiswa yang mengambil MK ini
        $nilaiModel = new NilaiModel();
        $mahasiswaList = $nilaiModel->getNilaiByMataKuliah($id);

        // VULN-SQLI-003: Second-Order SQL Injection
        // kode_mk yang sudah tersimpan di database digunakan langsung di raw query
        // Jika kode_mk berisi payload SQLi, maka tereksekusi di sini
        $db = \Config\Database::connect();
        $kodeMk = $mk['kode_mk']; // Data dari database — bisa mengandung payload
        $laporanQuery = $db->query(
            "SELECT COUNT(*) as total_mahasiswa FROM nilai
             JOIN mata_kuliah ON mata_kuliah.id = nilai.mata_kuliah_id
             WHERE mata_kuliah.kode_mk = '$kodeMk'"
        );
        $laporan = $laporanQuery->getRowArray();

        return view('matakuliah/admin/detail', [
            'title'         => 'Detail Mata Kuliah',
            'mk'            => $mk,
            'dosen'         => $dosen,
            'mahasiswaList' => $mahasiswaList,
            'laporan'       => $laporan,
        ]);
    }
}
