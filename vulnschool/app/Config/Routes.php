<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 *
 * Konfigurasi routing VulnSchool
 */

// ============================================================================
// Halaman Utama
// ============================================================================
$routes->get('/', 'Home::index');

// ============================================================================
// Autentikasi (publik — tanpa filter auth)
// ============================================================================
$routes->get('/login', 'Auth::showLogin');
$routes->post('/login', 'Auth::login');
$routes->get('/logout', 'Auth::logout');

// ========================================================================
// API Endpoints (Tanpa Autentikasi)
// VULN-AUTH-006: Endpoint API Tanpa Authentication
// ========================================================================
$routes->get('/api/users', 'Api\Users::index');
$routes->get('/register', 'Auth::showRegister');
$routes->post('/register', 'Auth::register');
$routes->get('/forgot-password', 'Auth::showForgotPassword');
$routes->post('/forgot-password', 'Auth::forgotPassword');
$routes->get('/reset-password', 'Auth::showResetPassword');
$routes->post('/reset-password', 'Auth::resetPassword');

// ============================================================================
// Halaman terproteksi (memerlukan login)
// ============================================================================
$routes->group('', ['filter' => 'auth'], function ($routes) {

    // Dashboard
    $routes->get('/dashboard', 'Dashboard::index');

    // ========================================================================
    // ADMIN: Dashboard & Kelola Data Dasar
    // ========================================================================
    $routes->get('/admin/users', 'Admin\Users::index');
    $routes->get('/admin/users/toggle/(:num)', 'Admin\Users::toggleStatus/$1');
    $routes->get('/admin/users/delete/(:num)', 'Admin\Users::delete/$1');

    $routes->get('/admin/mahasiswa', 'Admin\Mahasiswa::index');
    $routes->get('/admin/mahasiswa/create', 'Admin\Mahasiswa::create');
    $routes->post('/admin/mahasiswa/store', 'Admin\Mahasiswa::store');
    $routes->get('/admin/mahasiswa/edit/(:num)', 'Admin\Mahasiswa::edit/$1');
    $routes->post('/admin/mahasiswa/update/(:num)', 'Admin\Mahasiswa::update/$1');
    $routes->get('/admin/mahasiswa/delete/(:num)', 'Admin\Mahasiswa::delete/$1');
    $routes->get('/admin/mahasiswa/detail/(:num)', 'Admin\Mahasiswa::detail/$1');

    // ========================================================================
    // ADMIN: Kelola Dosen
    // VULN-MASS-002: Mass Assignment di update profil dosen
    // ========================================================================
    $routes->get('/admin/dosen', 'Admin\Dosen::index');
    $routes->get('/admin/dosen/create', 'Admin\Dosen::create');
    $routes->post('/admin/dosen/store', 'Admin\Dosen::store');
    $routes->get('/admin/dosen/edit/(:num)', 'Admin\Dosen::edit/$1');
    $routes->post('/admin/dosen/update/(:num)', 'Admin\Dosen::update/$1');
    $routes->get('/admin/dosen/delete/(:num)', 'Admin\Dosen::delete/$1');
    $routes->get('/admin/dosen/detail/(:num)', 'Admin\Dosen::detail/$1');

    // ========================================================================
    // ADMIN: Rekap Nilai (read-only)
    // ========================================================================
    $routes->get('/admin/nilai', 'Admin\Nilai::index');

    // ========================================================================
    // ADMIN: Rekap Absensi (filterable)
    // ========================================================================
    $routes->get('/admin/absensi', 'Admin\Absensi::index');

    // ========================================================================
    // ADMIN: Fitur Tambahan (Recon Targets)
    // ========================================================================
    $routes->get('/admin/backup', 'Admin\Backup::index');
    $routes->get('/admin/backup/generate', 'Admin\Backup::generate');
    $routes->get('/admin/system-info', 'Admin\SystemInfo::index');
    $routes->get('/admin/test', 'Admin\SystemInfo::test');
    $routes->get('/admin/logs', 'Admin\Logs::index');

    // ========================================================================
    // ADMIN: Kelola Mata Kuliah
    // VULN-SQLI-003: Second-Order SQL Injection via kode_mk
    // ========================================================================
    $routes->get('/admin/mata-kuliah', 'Admin\MataKuliah::index');
    $routes->get('/admin/mata-kuliah/create', 'Admin\MataKuliah::create');
    $routes->post('/admin/mata-kuliah/store', 'Admin\MataKuliah::store');
    $routes->get('/admin/mata-kuliah/edit/(:num)', 'Admin\MataKuliah::edit/$1');
    $routes->post('/admin/mata-kuliah/update/(:num)', 'Admin\MataKuliah::update/$1');
    $routes->get('/admin/mata-kuliah/delete/(:num)', 'Admin\MataKuliah::delete/$1');
    $routes->get('/admin/mata-kuliah/detail/(:num)', 'Admin\MataKuliah::detail/$1');

    // ========================================================================
    // MAHASISWA: Profil
    // ========================================================================
    $routes->get('/mahasiswa/profil', 'MahasiswaController::profil');
    $routes->get('/mahasiswa/profil/edit', 'MahasiswaController::editProfil');
    $routes->post('/mahasiswa/profil/update', 'MahasiswaController::updateProfil');
    // VULN-IDOR-001: Bisa akses profil mahasiswa lain dengan ganti ID
    $routes->get('/mahasiswa/profil/(:num)', 'MahasiswaController::profil/$1');

    // ========================================================================
    // MAHASISWA: Mata Kuliah
    // ========================================================================
    $routes->get('/mahasiswa/mata-kuliah', 'MahasiswaMataKuliahController::index');
    $routes->get('/mahasiswa/mata-kuliah/(:num)', 'MahasiswaMataKuliahController::detail/$1');

    // ========================================================================
    // MAHASISWA: Nilai
    // VULN-IDOR-004: IDOR pada Detail Nilai (TARGET P11 PRAKTIKUM)
    // VULN-IDOR-005: IDOR pada List Nilai (Bulk) via ?mhs_id=
    // VULN-SQLI-004: SQL Injection di Filter Nilai
    // ========================================================================
    $routes->get('/mahasiswa/nilai', 'NilaiController::index');
    $routes->get('/mahasiswa/nilai/(:num)', 'NilaiController::detail/$1');

    // ========================================================================
    // MAHASISWA: Absensi
    // VULN-IDOR-006: IDOR pada Detail Absensi
    // ========================================================================
    $routes->get('/mahasiswa/absensi', 'AbsensiController::index');
    $routes->get('/mahasiswa/absensi/(:num)', 'AbsensiController::detail/$1');

    // ========================================================================
    // DOSEN: Daftar Mahasiswa
    // ========================================================================
    $routes->get('/dosen/mahasiswa', 'MahasiswaController::listForDosen');
    $routes->get('/dosen/mahasiswa/detail/(:num)', 'MahasiswaController::detailForDosen/$1');

    // ========================================================================
    // DOSEN: Mata Kuliah
    // VULN-IDOR-003: IDOR pada detail MK dosen
    // ========================================================================
    $routes->get('/dosen/mata-kuliah', 'DosenController::mataKuliah');
    $routes->get('/dosen/mata-kuliah/(:num)', 'DosenController::detailMataKuliah/$1');

    // ========================================================================
    // DOSEN: Nilai
    // ========================================================================
    $routes->get('/dosen/nilai', 'DosenController::nilaiIndex');
    $routes->get('/dosen/nilai/input/(:num)', 'DosenController::nilaiInput/$1');
    $routes->post('/dosen/nilai/store/(:num)', 'DosenController::nilaiStore/$1');
    $routes->get('/dosen/nilai/edit/(:num)', 'DosenController::nilaiEdit/$1');
    $routes->post('/dosen/nilai/update/(:num)', 'DosenController::nilaiUpdate/$1');

    // ========================================================================
    // DOSEN: Absensi
    // VULN-MASS-003: Mass Assignment di Update Absensi
    // ========================================================================
    $routes->get('/dosen/absensi', 'DosenController::absensiIndex');
    $routes->get('/dosen/absensi/input/(:num)', 'DosenController::absensiInput/$1');
    $routes->post('/dosen/absensi/store/(:num)', 'DosenController::absensiStore/$1');
    $routes->get('/dosen/absensi/rekap/(:num)', 'DosenController::absensiRekap/$1');

    // ========================================================================
    // DOSEN: Profil
    // VULN-MASS-002: Mass Assignment di edit profil dosen
    // ========================================================================
    $routes->get('/dosen/profil', 'DosenController::profil');
    $routes->get('/dosen/profil/edit', 'DosenController::editProfil');
    $routes->post('/dosen/profil/update', 'DosenController::updateProfil');

    // ========================================================================
    // UMUM: Pengumuman
    // VULN-XSS-001: Stored XSS di Komentar
    // VULN-XSS-002: Reflected XSS di Search
    // ========================================================================
    $routes->get('/pengumuman', 'PengumumanController::index');
    $routes->get('/pengumuman/create', 'PengumumanController::create');
    $routes->post('/pengumuman/store', 'PengumumanController::store');
    $routes->get('/pengumuman/(:num)', 'PengumumanController::detail/$1');
    $routes->get('/pengumuman/edit/(:num)', 'PengumumanController::edit/$1');
    $routes->post('/pengumuman/update/(:num)', 'PengumumanController::update/$1');
    $routes->get('/pengumuman/delete/(:num)', 'PengumumanController::delete/$1');
    $routes->post('/pengumuman/(:num)/komentar', 'PengumumanController::storeKomentar/$1');
    $routes->get('/pengumuman/komentar/delete/(:num)', 'PengumumanController::deleteKomentar/$1');
});
