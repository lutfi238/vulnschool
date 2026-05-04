<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserModel;

/**
 * Controller: Api/Users
 * API Endpoint untuk demonstrasi P5
 * 
 * VULN-AUTH-006: Endpoint API Tanpa Authentication
 */
class Users extends BaseController
{
    /**
     * VULN-AUTH-006: Endpoint API Tanpa Authentication
     * 
     * Mengembalikan data semua user dalam format JSON.
     * Termasuk password hash dan tidak ada validasi token/session.
     */
    public function index()
    {
        $userModel = new UserModel();
        $users = $userModel->findAll();

        // Sengaja me-return JSON berisi password hash untuk recon/OSINT
        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $users,
            'note'   => 'Dev note: TODO - add JWT auth before release'
        ]);
    }
}
