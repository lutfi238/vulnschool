<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

/**
 * Controller: Auth
 * Menangani autentikasi pengguna (login, register, logout, reset password)
 *
 * ⚠️ PERINGATAN: Controller ini SENGAJA mengandung banyak kerentanan keamanan
 * untuk tujuan pendidikan. JANGAN gunakan kode ini di production!
 *
 * Daftar kerentanan:
 * - VULN-SQLI-001: SQL Injection di login
 * - VULN-AUTH-001: Password plain text
 * - VULN-AUTH-002: Reset token lemah (md5 time)
 * - VULN-AUTH-003: Tidak ada rate limiting
 * - VULN-AUTH-004: Session ID tidak di-regenerate
 * - VULN-AUTH-005: Username enumeration via pesan error
 * - VULN-MASS-001: Mass assignment di register
 */
class Auth extends Controller
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Tampilkan halaman login
     */
    public function showLogin()
    {
        // Jika sudah login, redirect ke dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login', ['title' => 'Login']);
    }

    /**
     * Proses login
     *
     * VULN-SQLI-001: SQL Injection pada Login
     * VULN-AUTH-003: Tidak ada Rate Limiting
     * VULN-AUTH-004: Session ID tidak di-regenerate
     * VULN-AUTH-005: Username Enumeration via pesan error
     */
    public function login()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Validasi input dasar
        if (empty($username) || empty($password)) {
            return redirect()->to('/login')
                ->with('error', 'Username dan password harus diisi.');
        }

        // VULN-SQLI-001: SQL Injection pada Login
        // Deskripsi: Input username dan password langsung dimasukkan ke dalam query string
        //            tanpa sanitasi atau parameter binding
        // Dampak: Attacker bisa bypass autentikasi dengan payload seperti:
        //         username: admin' --
        //         password: (apa saja)
        //         Atau melakukan UNION-based injection untuk ekstrak data:
        //         username: ' UNION SELECT 1,2,3,4,5,6,7,8,9,10,11,12 --
        // Fix: Gunakan Query Builder dengan parameter binding:
        //      $user = $this->userModel->where('username', $username)->first();
        //      if ($user && password_verify($password, $user['password'])) { ... }
        $db = \Config\Database::connect();
        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password' AND is_active = 1";
        $query = $db->query($sql);
        $user = $query->getRowArray();

        // VULN-AUTH-003: Tidak ada Rate Limiting di Login
        // Deskripsi: Tidak ada batasan jumlah percobaan login yang gagal
        // Dampak: Attacker bisa melakukan brute-force attack menggunakan tools
        //         seperti Hydra, Burp Suite Intruder, atau script custom
        //         untuk menebak password secara otomatis
        // Fix: Implementasikan throttle/rate limiting:
        //      - Gunakan CI4 Throttler: service('throttler')->check($key, $maxAttempts, $seconds)
        //      - Atau catat failed attempts di database dan lock akun setelah N kali gagal
        //      - Tambahkan CAPTCHA setelah 3x gagal login

        if (!$user) {
            // VULN-AUTH-005: Username Enumeration via Pesan Error
            // Deskripsi: Pesan error yang berbeda untuk username tidak ditemukan vs password salah
            //            memungkinkan attacker mengetahui username mana yang valid
            // Dampak: Attacker bisa melakukan enumerasi username yang terdaftar di sistem
            //         dengan mencoba berbagai username dan melihat respon yang berbeda
            // Fix: Gunakan pesan error yang sama untuk kedua kasus:
            //      "Username atau password salah."

            // Cek apakah username ada tapi password salah
            $checkUser = $db->query("SELECT * FROM users WHERE username = '$username'");
            $existingUser = $checkUser->getRowArray();

            if ($existingUser) {
                // Username ada tapi password salah → pesan spesifik (VULN!)
                return redirect()->to('/login')
                    ->with('error', 'Password salah.')
                    ->withInput();
            } else {
                // Username tidak ada → pesan berbeda (VULN!)
                return redirect()->to('/login')
                    ->with('error', 'Username tidak ditemukan.')
                    ->withInput();
            }
        }

        // VULN-AUTH-004: Session ID Tidak Di-Regenerate Setelah Login
        // Deskripsi: Setelah login berhasil, session ID tidak di-regenerate
        //            sehingga session ID lama (pre-authentication) tetap digunakan
        // Dampak: Rentan terhadap Session Fixation Attack — attacker bisa
        //         menetapkan session ID korban sebelum login, lalu hijack session
        //         setelah korban berhasil login
        // Fix: Tambahkan session()->regenerate() sebelum set session data:
        //      session()->regenerate();

        // Set session data (tanpa regenerate — VULN!)
        session()->set([
            'user_id'    => $user['id'],
            'username'   => $user['username'],
            'email'      => $user['email'],
            'full_name'  => $user['full_name'],
            'role'       => $user['role'],
            'is_admin'   => $user['is_admin'],
            'isLoggedIn' => true,
        ]);

        return redirect()->to('/dashboard')
            ->with('success', 'Login berhasil! Selamat datang, ' . $user['full_name']);
    }

    /**
     * Proses logout
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')
            ->with('info', 'Anda telah berhasil logout.');
    }

    /**
     * Tampilkan halaman registrasi
     */
    public function showRegister()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/register', ['title' => 'Daftar']);
    }

    /**
     * Proses registrasi
     *
     * VULN-AUTH-001: Password plain text
     * VULN-MASS-001: Mass assignment
     */
    public function register()
    {
        // Validasi dasar
        $password = $this->request->getPost('password');
        $confirmPassword = $this->request->getPost('confirm_password');

        if ($password !== $confirmPassword) {
            return redirect()->to('/register')
                ->with('error', 'Konfirmasi password tidak cocok.')
                ->withInput();
        }

        // VULN-MASS-001: Mass Assignment
        // Deskripsi: Semua field dari POST request diteruskan langsung ke insert()
        //            tanpa whitelist. Karena UserModel.$allowedFields berisi 'is_admin'
        //            dan 'role', attacker bisa mengirim field tambahan via POST
        // Dampak: Attacker bisa mendaftarkan diri sebagai admin dengan menambahkan
        //         parameter is_admin=1&role=admin di POST body menggunakan curl/Burp:
        //         curl -X POST http://localhost:8080/register \
        //              -d "username=hacker&password=hack123&email=h@h.com&full_name=Hacker&confirm_password=hack123&is_admin=1&role=admin"
        // Fix: Whitelist field yang diizinkan:
        //      $allowed = ['username', 'password', 'email', 'full_name'];
        //      $data = array_intersect_key($this->request->getPost(), array_flip($allowed));
        //      $data['role'] = 'mahasiswa';  // Force role mahasiswa
        //      $data['is_admin'] = 0;        // Force bukan admin
        //      $userModel->insert($data);
        $data = $this->request->getPost();

        // Hapus confirm_password karena tidak ada di database
        unset($data['confirm_password']);

        // Set default role jika tidak ada (tapi VULN: bisa di-override dari POST!)
        if (!isset($data['role'])) {
            $data['role'] = 'mahasiswa';
        }

        // VULN-AUTH-001: Password Plain Text
        // Deskripsi: Password disimpan langsung tanpa hashing
        // Dampak: Jika database bocor, semua password langsung terbaca
        // Fix: Gunakan password_hash():
        //      $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        // Coba insert user baru
        try {
            $this->userModel->insert($data);
        } catch (\Exception $e) {
            return redirect()->to('/register')
                ->with('error', 'Gagal mendaftar: ' . $e->getMessage())
                ->withInput();
        }

        return redirect()->to('/login')
            ->with('success', 'Registrasi berhasil! Silakan login dengan akun baru Anda.');
    }

    /**
     * Tampilkan halaman lupa password
     */
    public function showForgotPassword()
    {
        return view('auth/forgot_password', ['title' => 'Lupa Password']);
    }

    /**
     * Proses lupa password — generate token reset
     *
     * VULN-AUTH-002: Reset Token Lemah
     */
    public function forgotPassword()
    {
        $username = $this->request->getPost('username');

        if (empty($username)) {
            return redirect()->to('/forgot-password')
                ->with('error', 'Username harus diisi.');
        }

        $user = $this->userModel->findByUsername($username);

        if (!$user) {
            return redirect()->to('/forgot-password')
                ->with('error', 'Username tidak ditemukan.');
        }

        // VULN-AUTH-002: Reset Token Lemah (Predictable)
        // Deskripsi: Token reset password dibuat menggunakan md5(time()),
        //            yang mudah diprediksi karena berbasis waktu server
        // Dampak: Attacker yang mengetahui waktu server bisa menghitung token
        //         dan mereset password user lain tanpa akses email
        //         Contoh: jika request dikirim jam 10:00:00, attacker bisa
        //         generate md5(timestamp) untuk range waktu tersebut
        // Fix: Gunakan token yang kuat dan tidak bisa diprediksi:
        //      $token = bin2hex(random_bytes(32));
        //      Dan tambahkan batas waktu (expiry):
        //      'reset_token_expires_at' => date('Y-m-d H:i:s', strtotime('+1 hour'))
        $token = md5(time());

        // Simpan token ke database
        $this->userModel->update($user['id'], [
            'reset_token' => $token,
        ]);

        // Tampilkan token langsung (untuk demo, tidak kirim email)
        return view('auth/forgot_password', [
            'title'      => 'Lupa Password',
            'resetToken' => $token,
        ]);
    }

    /**
     * Tampilkan halaman reset password
     */
    public function showResetPassword()
    {
        $token = $this->request->getGet('token');

        if (empty($token)) {
            return redirect()->to('/forgot-password')
                ->with('error', 'Token reset tidak valid.');
        }

        return view('auth/reset_password', [
            'title' => 'Reset Password',
            'token' => $token,
        ]);
    }

    /**
     * Proses reset password
     */
    public function resetPassword()
    {
        $token = $this->request->getPost('token');
        $password = $this->request->getPost('password');
        $confirmPassword = $this->request->getPost('confirm_password');

        if (empty($token)) {
            return redirect()->to('/forgot-password')
                ->with('error', 'Token reset tidak valid.');
        }

        if ($password !== $confirmPassword) {
            return redirect()->to('/reset-password?token=' . $token)
                ->with('error', 'Konfirmasi password tidak cocok.');
        }

        // Cari user berdasarkan token
        $user = $this->userModel->findByResetToken($token);

        if (!$user) {
            return redirect()->to('/forgot-password')
                ->with('error', 'Token reset tidak valid atau sudah digunakan.');
        }

        // VULN-AUTH-001: Password disimpan plain text (tanpa hashing)
        // Fix: $password = password_hash($password, PASSWORD_DEFAULT);
        $this->userModel->update($user['id'], [
            'password'    => $password,
            'reset_token' => null, // Hapus token setelah digunakan
        ]);

        return redirect()->to('/login')
            ->with('success', 'Password berhasil direset! Silakan login dengan password baru.');
    }
}
