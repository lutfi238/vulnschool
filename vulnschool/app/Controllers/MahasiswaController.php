<?php

namespace App\Controllers;

use App\Models\MahasiswaModel;
use App\Models\DosenModel;
use App\Models\MataKuliahModel;
use App\Models\NilaiModel;

/**
 * Controller: MahasiswaController
 * Fitur profil untuk mahasiswa + daftar mahasiswa untuk dosen
 *
 * VULN-IDOR-001: Profil mahasiswa lain bisa diakses tanpa validasi kepemilikan
 * VULN-IDOR-002: Edit profil mahasiswa lain via hidden input id
 * VULN-UPLOAD-001: Upload file tanpa validasi (bisa upload .php)
 */
class MahasiswaController extends BaseController
{
    protected MahasiswaModel $mahasiswaModel;

    public function __construct()
    {
        $this->mahasiswaModel = new MahasiswaModel();
    }

    // ========================================================================
    // FITUR MAHASISWA
    // ========================================================================

    /**
     * Lihat profil mahasiswa
     *
     * VULN-IDOR-001: Insecure Direct Object Reference
     */
    public function profil($id = null)
    {
        // VULN-IDOR-001: Insecure Direct Object Reference pada Profil Mahasiswa
        // Deskripsi: Tidak ada validasi kepemilikan — parameter $id langsung digunakan
        //            untuk mengambil data profil tanpa mengecek apakah mahasiswa yang
        //            login memiliki hak akses ke profil tersebut
        // Dampak: Mahasiswa A bisa melihat profil mahasiswa B dengan mengubah URL:
        //         /mahasiswa/profil/1 → /mahasiswa/profil/2 → /mahasiswa/profil/3
        //         Semua data pribadi (alamat, no HP, dll.) terekspos
        // Fix: Tambahkan validasi kepemilikan:
        //      $mhsLogin = $this->mahasiswaModel->findByUserId(session()->get('user_id'));
        //      if ($id !== null && (int)$id !== (int)$mhsLogin['id']) {
        //          throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        //      }
        if ($id === null) {
            // Jika tidak ada id, ambil profil sendiri
            $mahasiswa = $this->mahasiswaModel->findByUserId(session()->get('user_id'));
            if (!$mahasiswa) {
                return redirect()->to('/dashboard')
                    ->with('error', 'Profil mahasiswa Anda belum terdaftar.');
            }
            $id = $mahasiswa['id'];
        }

        // Ambil data mahasiswa berdasarkan ID (TANPA validasi kepemilikan — VULN!)
        $mahasiswa = $this->mahasiswaModel->getMahasiswaWithUser($id);

        if (!$mahasiswa) {
            return redirect()->to('/dashboard')
                ->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        // Ambil data nilai
        $nilaiModel = new NilaiModel();
        $nilaiList = $nilaiModel->getNilaiByMahasiswa($id);

        return view('mahasiswa/profil', [
            'title'     => 'Profil Mahasiswa',
            'mahasiswa' => $mahasiswa,
            'nilaiList' => $nilaiList,
        ]);
    }

    /**
     * Form edit profil mahasiswa
     *
     * VULN-IDOR-002: ID diambil dari form, bukan dari session
     */
    public function editProfil()
    {
        $mahasiswa = $this->mahasiswaModel->findByUserId(session()->get('user_id'));

        if (!$mahasiswa) {
            return redirect()->to('/dashboard')
                ->with('error', 'Profil mahasiswa Anda belum terdaftar.');
        }

        $mahasiswa = $this->mahasiswaModel->getMahasiswaWithUser($mahasiswa['id']);

        return view('mahasiswa/profil_edit', [
            'title'     => 'Edit Profil',
            'mahasiswa' => $mahasiswa,
        ]);
    }

    /**
     * Simpan perubahan profil mahasiswa
     *
     * VULN-IDOR-002: ID mahasiswa diambil dari hidden input, bukan session
     * VULN-UPLOAD-001: File upload tanpa validasi
     */
    public function updateProfil()
    {
        // VULN-IDOR-002: IDOR pada Edit Profil
        // Deskripsi: ID mahasiswa diambil dari hidden input form ($this->request->getPost('id'))
        //            bukan dari session, sehingga bisa dimanipulasi
        // Dampak: Mahasiswa A bisa mengedit profil mahasiswa B dengan mengubah
        //         nilai hidden input 'id' di form menggunakan browser DevTools
        //         atau mengirim POST request dengan id mahasiswa lain
        // Fix: Ambil ID dari session, bukan dari input:
        //      $mhsLogin = $this->mahasiswaModel->findByUserId(session()->get('user_id'));
        //      $id = $mhsLogin['id'];
        $id = $this->request->getPost('id');

        $mahasiswa = $this->mahasiswaModel->find($id);
        if (!$mahasiswa) {
            return redirect()->to('/mahasiswa/profil')
                ->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $data = [
            'alamat' => $this->request->getPost('alamat'),
            'no_hp'  => $this->request->getPost('no_hp'),
        ];

        // VULN-UPLOAD-001: Unrestricted File Upload
        // Deskripsi: File foto profil yang diupload TIDAK divalidasi:
        //            - Tidak cek MIME type (bisa upload PHP, exe, dll.)
        //            - Tidak cek ekstensi file
        //            - Tidak cek ukuran file
        //            - Tidak rename file (nama asli dipakai)
        // Dampak: Attacker bisa mengupload file PHP yang berisi web shell,
        //         lalu mengaksesnya via URL untuk menjalankan perintah di server (RCE):
        //         1. Upload shell.php berisi: [?php system($_GET['cmd']); ?]
        //         2. Akses: /uploads/foto/shell.php?cmd=whoami
        //         3. Server menjalankan perintah 'whoami' dan menampilkan hasilnya
        // Fix: Validasi file upload:
        //      $rules = ['foto' => 'uploaded[foto]|max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]'];
        //      if (!$this->validate($rules)) { return redirect()->back()->with('errors', $this->validator->getErrors()); }
        //      $newName = $foto->getRandomName();
        //      $foto->move(WRITEPATH . '../public/uploads/foto', $newName);
        $foto = $this->request->getFile('foto');
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            // Upload tanpa validasi — VULN!
            $uploadPath = FCPATH . 'uploads/foto';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $foto->move($uploadPath, $foto->getName());
            $data['foto'] = $foto->getName();

            // Hapus foto lama jika ada
            if (!empty($mahasiswa['foto']) && file_exists($uploadPath . '/' . $mahasiswa['foto'])) {
                unlink($uploadPath . '/' . $mahasiswa['foto']);
            }
        }

        try {
            $this->mahasiswaModel->update($id, $data);
            return redirect()->to('/mahasiswa/profil')
                ->with('success', 'Profil berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui profil: ' . $e->getMessage())
                ->withInput();
        }
    }

    // ========================================================================
    // FITUR DOSEN
    // ========================================================================

    /**
     * Daftar mahasiswa yang diajar oleh dosen (berdasarkan mata kuliah)
     */
    public function listForDosen()
    {
        $dosenModel      = new DosenModel();
        $mataKuliahModel = new MataKuliahModel();

        $dosen = $dosenModel->findByUserId(session()->get('user_id'));

        $mahasiswaList = [];
        if ($dosen) {
            $mataKuliah = $mataKuliahModel->getByDosenId($dosen['id']);
            $mkIds = array_column($mataKuliah, 'id');

            if (!empty($mkIds)) {
                $db = \Config\Database::connect();
                $mkIdsStr = implode(',', $mkIds);
                $mahasiswaList = $db->query("
                    SELECT DISTINCT m.*, u.username, u.email
                    FROM mahasiswa m
                    JOIN users u ON u.id = m.user_id
                    JOIN nilai n ON n.mahasiswa_id = m.id
                    WHERE n.mata_kuliah_id IN ($mkIdsStr)
                    ORDER BY m.nim ASC
                ")->getResultArray();
            }
        }

        return view('mahasiswa/dosen_list', [
            'title'     => 'Daftar Mahasiswa',
            'mahasiswa' => $mahasiswaList,
        ]);
    }

    /**
     * Detail mahasiswa untuk dosen
     */
    public function detailForDosen($id)
    {
        $mahasiswa = $this->mahasiswaModel->getMahasiswaWithUser($id);

        if (!$mahasiswa) {
            return redirect()->to('/dosen/mahasiswa')
                ->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $nilaiModel = new NilaiModel();
        $nilaiList = $nilaiModel->getNilaiByMahasiswa($id);

        return view('mahasiswa/dosen_detail', [
            'title'     => 'Detail Mahasiswa',
            'mahasiswa' => $mahasiswa,
            'nilaiList' => $nilaiList,
        ]);
    }
}
