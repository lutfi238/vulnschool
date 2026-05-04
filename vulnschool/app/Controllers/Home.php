<?php

namespace App\Controllers;

/**
 * Controller: Home
 * Redirect ke dashboard jika sudah login, atau ke login jika belum
 */
class Home extends BaseController
{
    public function index()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        return redirect()->to('/login');
    }
}

