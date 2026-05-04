<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * Controller: Admin/SystemInfo
 * Menampilkan informasi server
 */
class SystemInfo extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        
        $info = [
            'php_version' => phpversion(),
            'server_os'   => php_uname(),
            'server_sapi' => php_sapi_name(),
            'db_driver'   => $db->DBDriver,
            'db_version'  => $db->getVersion(),
            'ci_version'  => \CodeIgniter\CodeIgniter::CI_VERSION,
        ];

        return view('admin/system_info', [
            'title' => 'System Information',
            'info'  => $info
        ]);
    }

    /**
     * Endpoint test yang "lupa dihapus"
     * Digunakan untuk expose environment/secret
     */
    public function test()
    {
        return $this->response->setJSON([
            'status' => 'Testing Mode',
            'debug'  => true,
            'env'    => ENVIRONMENT,
            'secret' => 'super_secret_key_for_jwt_auth_123!!'
        ]);
    }
}
