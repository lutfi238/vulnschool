<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\NilaiModel;

/**
 * Controller: Admin/Nilai
 * Admin view — read-only daftar semua nilai
 */
class Nilai extends BaseController
{
    protected NilaiModel $nilaiModel;

    public function __construct()
    {
        $this->nilaiModel = new NilaiModel();
    }

    /**
     * Daftar semua nilai (read-only)
     */
    public function index()
    {
        $nilaiList = $this->nilaiModel->getAllNilaiWithDetail();

        return view('nilai/admin/index', [
            'title'     => 'Daftar Nilai Mahasiswa',
            'nilaiList' => $nilaiList,
        ]);
    }
}
