<?php

namespace App\Controllers;

use App\Models\MahasiswaModel;
use App\Models\MataKuliahModel;
use App\Models\NilaiModel;
use App\Models\DosenModel;

/**
 * Controller: MahasiswaMataKuliahController
 * Fitur mata kuliah untuk mahasiswa yang login
 */
class MahasiswaMataKuliahController extends BaseController
{
    protected MahasiswaModel $mahasiswaModel;
    protected MataKuliahModel $mkModel;
    protected NilaiModel $nilaiModel;

    public function __construct()
    {
        $this->mahasiswaModel = new MahasiswaModel();
        $this->mkModel        = new MataKuliahModel();
        $this->nilaiModel     = new NilaiModel();
    }

    /**
     * List mata kuliah yang diambil mahasiswa semester ini
     */
    public function index()
    {
        $mahasiswa = $this->mahasiswaModel->findByUserId(session()->get('user_id'));

        $mataKuliah = [];
        if ($mahasiswa) {
            // Ambil semua MK yang ada nilai-nya untuk mahasiswa ini
            $nilaiList = $this->nilaiModel->getNilaiByMahasiswa($mahasiswa['id']);

            // Ambil data MK lengkap dengan dosen
            $mkIds = array_unique(array_column($nilaiList, 'mata_kuliah_id'));
            if (!empty($mkIds)) {
                $dosenModel = new DosenModel();
                foreach ($mkIds as $mkId) {
                    $mk = $this->mkModel->find($mkId);
                    if ($mk) {
                        $dosen = $dosenModel->find($mk['dosen_id']);
                        $mk['nama_dosen'] = $dosen ? $dosen['nama'] : '-';
                        $mataKuliah[] = $mk;
                    }
                }
            }
        }

        return view('mahasiswa/matakuliah/index', [
            'title'      => 'Mata Kuliah Saya',
            'mahasiswa'  => $mahasiswa,
            'mataKuliah' => $mataKuliah,
        ]);
    }

    /**
     * Detail mata kuliah untuk mahasiswa
     */
    public function detail($id)
    {
        $mahasiswa = $this->mahasiswaModel->findByUserId(session()->get('user_id'));

        $mk = $this->mkModel->find($id);
        if (!$mk) {
            return redirect()->to('/mahasiswa/mata-kuliah')
                ->with('error', 'Mata kuliah tidak ditemukan.');
        }

        // Ambil data dosen pengampu
        $dosenModel = new DosenModel();
        $dosen = $dosenModel->find($mk['dosen_id']);

        // Ambil nilai mahasiswa untuk MK ini (jika ada)
        $nilai = null;
        if ($mahasiswa) {
            $nilaiList = $this->nilaiModel
                ->where('mahasiswa_id', $mahasiswa['id'])
                ->where('mata_kuliah_id', $id)
                ->first();
            $nilai = $nilaiList;
        }

        return view('mahasiswa/matakuliah/detail', [
            'title'     => 'Detail Mata Kuliah',
            'mk'        => $mk,
            'dosen'     => $dosen,
            'mahasiswa' => $mahasiswa,
            'nilai'     => $nilai,
        ]);
    }
}
