<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DosenModel;
use App\Models\UserModel;
use App\Models\MataKuliahModel;

/**
 * Controller: Admin/Dosen
 * CRUD dosen untuk admin
 *
 * VULN-MASS-002: Mass Assignment di update profil dosen
 */
class Dosen extends BaseController
{
    protected DosenModel $dosenModel;
    protected UserModel $userModel;

    public function __construct()
    {
        $this->dosenModel = new DosenModel();
        $this->userModel  = new UserModel();
    }

    /**
     * Daftar semua dosen (dengan search & pagination)
     */
    public function index()
    {
        $keyword = $this->request->getGet('q');
        $perPage = 10;

        if ($keyword) {
            $dosenList = $this->dosenModel
                ->select('dosen.*, users.username, users.email, users.full_name')
                ->join('users', 'users.id = dosen.user_id')
                ->like('dosen.nama', $keyword)
                ->orLike('dosen.nip', $keyword)
                ->orLike('users.email', $keyword)
                ->orderBy('dosen.nip', 'ASC')
                ->paginate($perPage);
            $pager = $this->dosenModel->pager;
            $total = $this->dosenModel
                ->like('dosen.nama', $keyword)
                ->orLike('dosen.nip', $keyword)
                ->countAllResults();
        } else {
            $dosenList = $this->dosenModel
                ->select('dosen.*, users.username, users.email, users.full_name')
                ->join('users', 'users.id = dosen.user_id')
                ->orderBy('dosen.nip', 'ASC')
                ->paginate($perPage);
            $pager = $this->dosenModel->pager;
            $total = $this->dosenModel->countAllResults();
        }

        return view('dosen/admin/index', [
            'title'    => 'Kelola Dosen',
            'dosenList' => $dosenList,
            'keyword'  => $keyword,
            'pager'    => $pager,
            'total'    => $total,
        ]);
    }

    /**
     * Form tambah dosen
     */
    public function create()
    {
        return view('dosen/admin/form', [
            'title'  => 'Tambah Dosen',
            'dosen'  => null,
            'isEdit' => false,
        ]);
    }

    /**
     * Simpan dosen baru
     */
    public function store()
    {
        $userData = [
            'username'  => $this->request->getPost('username'),
            'password'  => $this->request->getPost('password'), // VULN-AUTH-001: plain text
            'email'     => $this->request->getPost('email'),
            'full_name' => $this->request->getPost('nama'),
            'role'      => 'dosen',
            'is_active' => 1,
            'is_admin'  => 0,
        ];

        try {
            $this->userModel->insert($userData);
            $userId = $this->userModel->getInsertID();

            $dosenData = [
                'user_id'          => $userId,
                'nip'              => $this->request->getPost('nip'),
                'nama'             => $this->request->getPost('nama'),
                'bidang_keahlian'  => $this->request->getPost('bidang_keahlian'),
                'no_hp'            => $this->request->getPost('no_hp'),
            ];

            $this->dosenModel->insert($dosenData);

            return redirect()->to('/admin/dosen')
                ->with('success', 'Data dosen berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Form edit dosen
     */
    public function edit($id)
    {
        $dosen = $this->dosenModel->getDosenWithUser($id);

        if (!$dosen) {
            return redirect()->to('/admin/dosen')
                ->with('error', 'Data dosen tidak ditemukan.');
        }

        return view('dosen/admin/form', [
            'title'  => 'Edit Dosen',
            'dosen'  => $dosen,
            'isEdit' => true,
        ]);
    }

    /**
     * Update data dosen
     *
     * VULN-MASS-002: Mass Assignment di Edit Profil Dosen
     */
    public function update($id)
    {
        $dosen = $this->dosenModel->find($id);
        if (!$dosen) {
            return redirect()->to('/admin/dosen')
                ->with('error', 'Data dosen tidak ditemukan.');
        }

        try {
            // VULN-MASS-002: Mass Assignment di Edit Profil Dosen
            // Deskripsi: Seluruh data POST diteruskan langsung ke update() tanpa whitelist.
            //            Field 'user_id' termasuk di $allowedFields pada DosenModel,
            //            sehingga attacker bisa menambah field 'user_id' pada POST body
            //            untuk mengganti user_id dosen ini → mengambil alih akun dosen lain.
            // Dampak: Dosen A bisa mengubah user_id profil dosen B sehingga:
            //         - Dosen A login sebagai dosen B
            //         - Account takeover / privilege escalation
            // Fix: Whitelist field yang boleh di-update:
            //      $data = [
            //          'nip'             => $this->request->getPost('nip'),
            //          'nama'            => $this->request->getPost('nama'),
            //          'bidang_keahlian' => $this->request->getPost('bidang_keahlian'),
            //          'no_hp'           => $this->request->getPost('no_hp'),
            //      ];
            //      $this->dosenModel->update($id, $data);
            $this->dosenModel->update($id, $this->request->getPost());

            // Update juga data user terkait
            $this->userModel->update($dosen['user_id'], [
                'full_name' => $this->request->getPost('nama'),
                'email'     => $this->request->getPost('email'),
            ]);

            return redirect()->to('/admin/dosen')
                ->with('success', 'Data dosen berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Hapus dosen
     */
    public function delete($id)
    {
        $dosen = $this->dosenModel->find($id);
        if (!$dosen) {
            return redirect()->to('/admin/dosen')
                ->with('error', 'Data dosen tidak ditemukan.');
        }

        try {
            $this->dosenModel->delete($id);
            $this->userModel->delete($dosen['user_id']);

            return redirect()->to('/admin/dosen')
                ->with('success', 'Data dosen berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->to('/admin/dosen')
                ->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    /**
     * Detail dosen
     */
    public function detail($id)
    {
        $dosen = $this->dosenModel->getDosenWithUser($id);
        if (!$dosen) {
            return redirect()->to('/admin/dosen')
                ->with('error', 'Data dosen tidak ditemukan.');
        }

        $mkModel = new MataKuliahModel();
        $mataKuliah = $mkModel->getByDosenId($id);

        return view('dosen/admin/detail', [
            'title'      => 'Detail Dosen',
            'dosen'      => $dosen,
            'mataKuliah' => $mataKuliah,
        ]);
    }
}
