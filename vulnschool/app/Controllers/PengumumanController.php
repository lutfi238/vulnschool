<?php

namespace App\Controllers;

use App\Models\PengumumanModel;
use App\Models\KomentarPengumumanModel;

/**
 * Controller: PengumumanController
 * Menangani pengumuman (informasi publik/kampus)
 *
 * VULN-XSS-001: Stored XSS di Komentar (tampil di view)
 * VULN-XSS-002: Reflected XSS di Search
 */
class PengumumanController extends BaseController
{
    protected PengumumanModel $pengumumanModel;
    protected KomentarPengumumanModel $komentarModel;

    public function __construct()
    {
        $this->pengumumanModel = new PengumumanModel();
        $this->komentarModel   = new KomentarPengumumanModel();
    }

    /**
     * Tampilkan daftar pengumuman
     *
     * VULN-XSS-002: Reflected XSS
     * Parameter GET 'q' digunakan untuk search dan akan ditampilkan
     * langsung ke view tanpa sanitasi.
     */
    public function index()
    {
        // VULN-XSS-002: Reflected XSS
        // Input parameter q dari user diterima langsung
        $q = $this->request->getGet('q');

        if (!empty($q)) {
            // Pencarian sederhana (raw concatenation untuk search juga bisa jadi VULN-SQLI,
            // tapi framework CI4 builder menangani binding otomatis jika pakai like)
            $pengumumanList = $this->pengumumanModel
                ->select('pengumuman.*, users.full_name as author_name')
                ->join('users', 'users.id = pengumuman.author_id')
                ->groupStart()
                    ->like('judul', $q)
                    ->orLike('isi', $q)
                ->groupEnd()
                ->orderBy('created_at', 'DESC')
                ->findAll();
        } else {
            // Tampilkan yang sudah publish jika bukan admin/dosen
            $role = session()->get('role');
            if ($role === 'admin' || $role === 'dosen') {
                $pengumumanList = $this->pengumumanModel->getAllWithAuthor();
            } else {
                $pengumumanList = $this->pengumumanModel->getPublished();
            }
        }

        // Tampilkan view
        return view('pengumuman/index', [
            'title'          => 'Pengumuman',
            'pengumumanList' => $pengumumanList,
            'q'              => $q, // Diteruskan ke view untuk direflect tanpa escape!
        ]);
    }

    /**
     * Tampilkan form tambah pengumuman (Hanya Admin & Dosen)
     */
    public function create()
    {
        $role = session()->get('role');
        if (!in_array($role, ['admin', 'dosen'])) {
            return redirect()->to('/pengumuman')->with('error', 'Anda tidak memiliki akses.');
        }

        return view('pengumuman/create', ['title' => 'Tambah Pengumuman']);
    }

    /**
     * Simpan pengumuman baru (Hanya Admin & Dosen)
     */
    public function store()
    {
        $role = session()->get('role');
        if (!in_array($role, ['admin', 'dosen'])) {
            return redirect()->to('/pengumuman')->with('error', 'Anda tidak memiliki akses.');
        }

        $data = [
            'judul'        => $this->request->getPost('judul'),
            'isi'          => $this->request->getPost('isi'),
            'author_id'    => session()->get('user_id'),
            'is_published' => $this->request->getPost('is_published') ? 1 : 0,
        ];

        $this->pengumumanModel->insert($data);

        return redirect()->to('/pengumuman')->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    /**
     * Detail pengumuman + Komentar
     */
    public function detail($id)
    {
        $pengumuman = $this->pengumumanModel->getWithCommentCount($id);

        if (!$pengumuman) {
            return redirect()->to('/pengumuman')->with('error', 'Pengumuman tidak ditemukan.');
        }

        // Ambil semua komentar
        $komentarList = $this->komentarModel->getByPengumuman($id);

        return view('pengumuman/detail', [
            'title'        => $pengumuman['judul'],
            'pengumuman'   => $pengumuman,
            'komentarList' => $komentarList,
        ]);
    }

    /**
     * Simpan komentar
     *
     * VULN-XSS-001: Stored XSS
     * Input komentar disave mentah ke DB. Saat ditampilkan nanti, tidak diescape.
     */
    public function storeKomentar($id)
    {
        $isi_komentar = $this->request->getPost('isi_komentar');

        if (empty(trim($isi_komentar))) {
            return redirect()->to('/pengumuman/' . $id)->with('error', 'Komentar tidak boleh kosong.');
        }

        $data = [
            'pengumuman_id' => $id,
            'user_id'       => session()->get('user_id'),
            'isi_komentar'  => $isi_komentar,
        ];

        $this->komentarModel->insert($data);

        return redirect()->to('/pengumuman/' . $id)->with('success', 'Komentar berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit pengumuman
     */
    public function edit($id)
    {
        $pengumuman = $this->pengumumanModel->find($id);

        if (!$pengumuman) {
            return redirect()->to('/pengumuman')->with('error', 'Pengumuman tidak ditemukan.');
        }

        // Cek akses: hanya admin atau pembuat pengumuman
        $userId = session()->get('user_id');
        $role   = session()->get('role');
        if ($role !== 'admin' && $pengumuman['author_id'] != $userId) {
            return redirect()->to('/pengumuman')->with('error', 'Anda tidak memiliki akses untuk mengedit pengumuman ini.');
        }

        return view('pengumuman/edit', [
            'title'      => 'Edit Pengumuman',
            'pengumuman' => $pengumuman,
        ]);
    }

    /**
     * Update pengumuman
     */
    public function update($id)
    {
        $pengumuman = $this->pengumumanModel->find($id);

        if (!$pengumuman) {
            return redirect()->to('/pengumuman')->with('error', 'Pengumuman tidak ditemukan.');
        }

        // Cek akses
        $userId = session()->get('user_id');
        $role   = session()->get('role');
        if ($role !== 'admin' && $pengumuman['author_id'] != $userId) {
            return redirect()->to('/pengumuman')->with('error', 'Anda tidak memiliki akses untuk mengedit pengumuman ini.');
        }

        $data = [
            'judul'        => $this->request->getPost('judul'),
            'isi'          => $this->request->getPost('isi'),
            'is_published' => $this->request->getPost('is_published') ? 1 : 0,
        ];

        $this->pengumumanModel->update($id, $data);

        return redirect()->to('/pengumuman/' . $id)->with('success', 'Pengumuman berhasil diperbarui.');
    }

    /**
     * Hapus pengumuman
     */
    public function delete($id)
    {
        $pengumuman = $this->pengumumanModel->find($id);

        if (!$pengumuman) {
            return redirect()->to('/pengumuman')->with('error', 'Pengumuman tidak ditemukan.');
        }

        // Cek akses
        $userId = session()->get('user_id');
        $role   = session()->get('role');
        if ($role !== 'admin' && $pengumuman['author_id'] != $userId) {
            return redirect()->to('/pengumuman')->with('error', 'Anda tidak memiliki akses untuk menghapus pengumuman ini.');
        }

        $this->pengumumanModel->delete($id);

        return redirect()->to('/pengumuman')->with('success', 'Pengumuman berhasil dihapus.');
    }

    /**
     * Hapus komentar
     */
    public function deleteKomentar($komentarId)
    {
        $komentar = $this->komentarModel->find($komentarId);

        if (!$komentar) {
            return redirect()->back()->with('error', 'Komentar tidak ditemukan.');
        }

        // Cek akses: Admin atau penulis komentar
        $userId = session()->get('user_id');
        $role   = session()->get('role');
        if ($role !== 'admin' && $komentar['user_id'] != $userId) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus komentar ini.');
        }

        $this->komentarModel->delete($komentarId);

        return redirect()->back()->with('success', 'Komentar berhasil dihapus.');
    }
}
