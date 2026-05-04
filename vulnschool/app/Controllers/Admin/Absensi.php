<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AbsensiModel;
use App\Models\MataKuliahModel;
use App\Models\MahasiswaModel;

/**
 * Controller: Admin/Absensi
 * Admin view — daftar semua absensi dengan filter
 */
class Absensi extends BaseController
{
    protected AbsensiModel $absensiModel;
    protected MataKuliahModel $mkModel;
    protected MahasiswaModel $mahasiswaModel;

    public function __construct()
    {
        $this->absensiModel   = new AbsensiModel();
        $this->mkModel        = new MataKuliahModel();
        $this->mahasiswaModel = new MahasiswaModel();
    }

    /**
     * View all absensi — filter by MK & mahasiswa
     */
    public function index()
    {
        $filterMk  = $this->request->getGet('mk');
        $filterMhs = $this->request->getGet('mhs');

        $builder = $this->absensiModel
            ->select('absensi.*, mahasiswa.nim, mahasiswa.nama as nama_mahasiswa,
                      mata_kuliah.kode_mk, mata_kuliah.nama_mk')
            ->join('mahasiswa', 'mahasiswa.id = absensi.mahasiswa_id')
            ->join('mata_kuliah', 'mata_kuliah.id = absensi.mata_kuliah_id');

        if (!empty($filterMk)) {
            $builder->where('absensi.mata_kuliah_id', $filterMk);
        }
        if (!empty($filterMhs)) {
            $builder->where('absensi.mahasiswa_id', $filterMhs);
        }

        $absensiList = $builder
            ->orderBy('absensi.tanggal', 'DESC')
            ->orderBy('mahasiswa.nim', 'ASC')
            ->findAll();

        // Data untuk dropdown filter
        $mataKuliahAll = $this->mkModel->findAll();
        $mahasiswaAll  = $this->mahasiswaModel->findAll();

        return view('absensi/admin/index', [
            'title'         => 'Rekap Absensi',
            'absensiList'   => $absensiList,
            'mataKuliahAll' => $mataKuliahAll,
            'mahasiswaAll'  => $mahasiswaAll,
            'filterMk'      => $filterMk,
            'filterMhs'     => $filterMhs,
        ]);
    }
}
