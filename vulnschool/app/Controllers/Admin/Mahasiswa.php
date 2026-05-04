<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MahasiswaModel;
use App\Models\UserModel;

/**
 * Controller: Admin/Mahasiswa
 * CRUD mahasiswa untuk admin
 *
 * VULN-SQLI-002: SQL Injection di fitur pencarian
 */
class Mahasiswa extends BaseController
{
    protected MahasiswaModel $mahasiswaModel;
    protected UserModel $userModel;

    public function __construct()
    {
        $this->mahasiswaModel = new MahasiswaModel();
        $this->userModel      = new UserModel();
    }

    /**
     * Daftar semua mahasiswa (dengan search & pagination)
     *
     * VULN-SQLI-002: SQL Injection pada pencarian
     */
    public function index()
    {
        $keyword = $this->request->getGet('q');
        $page    = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 10;

        $db = \Config\Database::connect();

        if ($keyword) {
            // VULN-SQLI-002: SQL Injection pada Pencarian Mahasiswa
            // Deskripsi: Input pencarian ($keyword) langsung dimasukkan ke query SQL
            //            tanpa sanitasi atau parameter binding
            // Dampak: Attacker bisa melakukan UNION-based SQLi untuk:
            //         - Mengekstrak data dari tabel lain (users, nilai, dll.)
            //         - Membaca password plain text semua user
            //         - Enumerasi seluruh database
            // Fix: Gunakan Query Builder dengan parameter binding:
            //      $this->mahasiswaModel->like('nama', $keyword)->orLike('nim', $keyword)->paginate($perPage)
            $sql = "SELECT mahasiswa.*, users.username, users.email
                    FROM mahasiswa
                    JOIN users ON users.id = mahasiswa.user_id
                    WHERE mahasiswa.nama LIKE '%$keyword%'
                       OR mahasiswa.nim LIKE '%$keyword%'
                       OR users.email LIKE '%$keyword%'";
            $mahasiswa = $db->query($sql)->getResultArray();
            $total     = count($mahasiswa);
            // Manual pagination pada result
            $mahasiswa = array_slice($mahasiswa, ($page - 1) * $perPage, $perPage);
            $pager     = null; // Tidak pakai CI pager untuk raw query
        } else {
            // Tanpa keyword — pakai Query Builder biasa
            $mahasiswa = $this->mahasiswaModel
                ->select('mahasiswa.*, users.username, users.email')
                ->join('users', 'users.id = mahasiswa.user_id')
                ->orderBy('mahasiswa.nim', 'ASC')
                ->paginate($perPage);
            $pager = $this->mahasiswaModel->pager;
            $total = $this->mahasiswaModel->countAllResults();
        }

        return view('mahasiswa/admin/index', [
            'title'     => 'Kelola Mahasiswa',
            'mahasiswa' => $mahasiswa,
            'keyword'   => $keyword,
            'pager'     => $pager,
            'total'     => $total,
        ]);
    }

    /**
     * Form tambah mahasiswa baru
     */
    public function create()
    {
        return view('mahasiswa/admin/form', [
            'title'     => 'Tambah Mahasiswa',
            'mahasiswa' => null,
            'isEdit'    => false,
        ]);
    }

    /**
     * Simpan mahasiswa baru
     */
    public function store()
    {
        // Buat user account dulu
        $userData = [
            'username'  => $this->request->getPost('username'),
            'password'  => $this->request->getPost('password'), // VULN-AUTH-001: plain text
            'email'     => $this->request->getPost('email'),
            'full_name' => $this->request->getPost('nama'),
            'role'      => 'mahasiswa',
            'is_active' => 1,
            'is_admin'  => 0,
        ];

        try {
            $this->userModel->insert($userData);
            $userId = $this->userModel->getInsertID();

            // Buat data mahasiswa
            $mhsData = [
                'user_id'  => $userId,
                'nim'      => $this->request->getPost('nim'),
                'nama'     => $this->request->getPost('nama'),
                'jurusan'  => $this->request->getPost('jurusan'),
                'angkatan' => $this->request->getPost('angkatan'),
                'alamat'   => $this->request->getPost('alamat'),
                'no_hp'    => $this->request->getPost('no_hp'),
            ];

            $this->mahasiswaModel->insert($mhsData);

            return redirect()->to('/admin/mahasiswa')
                ->with('success', 'Data mahasiswa berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Form edit mahasiswa
     */
    public function edit($id)
    {
        $mahasiswa = $this->mahasiswaModel->getMahasiswaWithUser($id);

        if (!$mahasiswa) {
            return redirect()->to('/admin/mahasiswa')
                ->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        return view('mahasiswa/admin/form', [
            'title'     => 'Edit Mahasiswa',
            'mahasiswa' => $mahasiswa,
            'isEdit'    => true,
        ]);
    }

    /**
     * Update data mahasiswa
     */
    public function update($id)
    {
        $mahasiswa = $this->mahasiswaModel->find($id);

        if (!$mahasiswa) {
            return redirect()->to('/admin/mahasiswa')
                ->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $mhsData = [
            'nim'      => $this->request->getPost('nim'),
            'nama'     => $this->request->getPost('nama'),
            'jurusan'  => $this->request->getPost('jurusan'),
            'angkatan' => $this->request->getPost('angkatan'),
            'alamat'   => $this->request->getPost('alamat'),
            'no_hp'    => $this->request->getPost('no_hp'),
        ];

        try {
            $this->mahasiswaModel->update($id, $mhsData);

            // Update juga data user terkait
            $this->userModel->update($mahasiswa['user_id'], [
                'full_name' => $this->request->getPost('nama'),
                'email'     => $this->request->getPost('email'),
            ]);

            return redirect()->to('/admin/mahasiswa')
                ->with('success', 'Data mahasiswa berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Hapus mahasiswa
     */
    public function delete($id)
    {
        $mahasiswa = $this->mahasiswaModel->find($id);

        if (!$mahasiswa) {
            return redirect()->to('/admin/mahasiswa')
                ->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        try {
            // Hapus mahasiswa (user akan ter-cascade)
            $this->mahasiswaModel->delete($id);
            // Hapus user terkait
            $this->userModel->delete($mahasiswa['user_id']);

            return redirect()->to('/admin/mahasiswa')
                ->with('success', 'Data mahasiswa berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->to('/admin/mahasiswa')
                ->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    /**
     * Detail mahasiswa
     */
    public function detail($id)
    {
        $mahasiswa = $this->mahasiswaModel->getMahasiswaWithUser($id);

        if (!$mahasiswa) {
            return redirect()->to('/admin/mahasiswa')
                ->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        // Ambil data nilai mahasiswa ini
        $nilaiModel = new \App\Models\NilaiModel();
        $nilaiList = $nilaiModel->getNilaiByMahasiswa($id);

        // Ambil data absensi
        $absensiModel = new \App\Models\AbsensiModel();
        $db = \Config\Database::connect();
        $absensi = $db->query("SELECT absensi.*, mata_kuliah.kode_mk, mata_kuliah.nama_mk
                               FROM absensi
                               JOIN mata_kuliah ON mata_kuliah.id = absensi.mata_kuliah_id
                               WHERE absensi.mahasiswa_id = $id
                               ORDER BY absensi.tanggal DESC")
                      ->getResultArray();

        return view('mahasiswa/admin/detail', [
            'title'     => 'Detail Mahasiswa',
            'mahasiswa' => $mahasiswa,
            'nilaiList' => $nilaiList,
            'absensi'   => $absensi,
        ]);
    }
}
