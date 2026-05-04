<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Filter: AuthFilter
 * Memastikan user sudah login sebelum mengakses halaman terproteksi
 *
 * Catatan: Filter ini TIDAK mengecek role/permission — hanya cek login status
 * (Sengaja sederhana untuk tujuan pembelajaran)
 */
class AuthFilter implements FilterInterface
{
    /**
     * Cek apakah user sudah login
     * Jika belum, redirect ke halaman login
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }
    }

    /**
     * After filter — tidak digunakan
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak ada proses setelah response
    }
}
