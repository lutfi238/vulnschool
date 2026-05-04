<?php
/**
 * Partial: Sidebar
 * Menu navigasi samping kiri — dinamis berdasarkan role user
 */
$role = session()->get('role');
$currentUrl = current_url(true)->getPath();

// Helper: cek apakah menu aktif
function isActive(string $path, string $current): string {
    // Exact match atau prefix match
    $path = '/' . ltrim($path, '/');
    if ($current === $path) return 'active';
    if ($path !== '/dashboard' && str_starts_with($current, $path)) return 'active';
    return '';
}
?>

<aside class="vs-sidebar" id="sidebar">
    <!-- Brand -->
    <div class="vs-sidebar-brand">
        <div class="brand-icon">
            <i class="bi bi-mortarboard-fill"></i>
        </div>
        <div>
            <div class="brand-text">VulnSchool</div>
            <div class="brand-sub">Sistem Akademik Polnep</div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="vs-sidebar-nav">

        <!-- Menu Utama (semua role) -->
        <div class="vs-nav-label">Menu Utama</div>
        <a href="/dashboard" class="vs-nav-item <?= isActive('/dashboard', $currentUrl) ?>">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <?php if ($role === 'admin'): ?>
            <!-- ============ MENU ADMIN ============ -->
            <div class="vs-nav-label">Kelola Data</div>
            <a href="/admin/users" class="vs-nav-item <?= isActive('/admin/users', $currentUrl) ?>">
                <i class="bi bi-people"></i> Kelola User
            </a>
            <a href="/admin/mahasiswa" class="vs-nav-item <?= isActive('/admin/mahasiswa', $currentUrl) ?>">
                <i class="bi bi-person-badge"></i> Kelola Mahasiswa
            </a>
            <a href="/admin/dosen" class="vs-nav-item <?= isActive('/admin/dosen', $currentUrl) ?>">
                <i class="bi bi-person-workspace"></i> Kelola Dosen
            </a>
            <a href="/admin/mata-kuliah" class="vs-nav-item <?= isActive('/admin/mata-kuliah', $currentUrl) ?>">
                <i class="bi bi-book"></i> Kelola Mata Kuliah
            </a>
            <a href="/admin/nilai" class="vs-nav-item <?= isActive('/admin/nilai', $currentUrl) ?>">
                <i class="bi bi-card-checklist"></i> Rekap Nilai
            </a>
            <a href="/admin/absensi" class="vs-nav-item <?= isActive('/admin/absensi', $currentUrl) ?>">
                <i class="bi bi-calendar-check"></i> Rekap Absensi
            </a>

            <div class="vs-nav-label">Sistem & Log</div>
            <a href="/admin/backup" class="vs-nav-item <?= isActive('/admin/backup', $currentUrl) ?>">
                <i class="bi bi-folder-check"></i> Backup Database
            </a>
            <a href="/admin/system-info" class="vs-nav-item <?= isActive('/admin/system-info', $currentUrl) ?>">
                <i class="bi bi-server"></i> System Info
            </a>
            <a href="/admin/logs" class="vs-nav-item <?= isActive('/admin/logs', $currentUrl) ?>">
                <i class="bi bi-terminal"></i> System Logs
            </a>

            <div class="vs-nav-label">Informasi</div>
            <a href="/pengumuman" class="vs-nav-item <?= isActive('/pengumuman', $currentUrl) ?>">
                <i class="bi bi-megaphone"></i> Pengumuman
            </a>

        <?php elseif ($role === 'dosen'): ?>
            <!-- ============ MENU DOSEN ============ -->
            <div class="vs-nav-label">Akademik</div>
            <a href="/dosen/mata-kuliah" class="vs-nav-item <?= isActive('/dosen/mata-kuliah', $currentUrl) ?>">
                <i class="bi bi-book"></i> Mata Kuliah Saya
            </a>
            <a href="/dosen/mahasiswa" class="vs-nav-item <?= isActive('/dosen/mahasiswa', $currentUrl) ?>">
                <i class="bi bi-person-badge"></i> Mahasiswa
            </a>
            <a href="/dosen/nilai" class="vs-nav-item <?= isActive('/dosen/nilai', $currentUrl) ?>">
                <i class="bi bi-card-checklist"></i> Input Nilai
            </a>
            <a href="/dosen/absensi" class="vs-nav-item <?= isActive('/dosen/absensi', $currentUrl) ?>">
                <i class="bi bi-calendar-check"></i> Absensi
            </a>

            <div class="vs-nav-label">Profil</div>
            <a href="/dosen/profil" class="vs-nav-item <?= isActive('/dosen/profil', $currentUrl) ?>">
                <i class="bi bi-person-circle"></i> Profil Saya
            </a>

            <div class="vs-nav-label">Informasi</div>
            <a href="/pengumuman" class="vs-nav-item <?= isActive('/pengumuman', $currentUrl) ?>">
                <i class="bi bi-megaphone"></i> Pengumuman
            </a>

        <?php elseif ($role === 'mahasiswa'): ?>
            <!-- ============ MENU MAHASISWA ============ -->
            <div class="vs-nav-label">Akademik</div>
            <a href="/mahasiswa/profil" class="vs-nav-item <?= isActive('/mahasiswa/profil', $currentUrl) ?>">
                <i class="bi bi-person-circle"></i> Profil Saya
            </a>
            <a href="/mahasiswa/mata-kuliah" class="vs-nav-item <?= isActive('/mahasiswa/mata-kuliah', $currentUrl) ?>">
                <i class="bi bi-journal-text"></i> Mata Kuliah
            </a>
            <a href="/mahasiswa/nilai" class="vs-nav-item <?= isActive('/mahasiswa/nilai', $currentUrl) ?>">
                <i class="bi bi-bar-chart-line"></i> Nilai Saya
            </a>
            <a href="/mahasiswa/absensi" class="vs-nav-item <?= isActive('/mahasiswa/absensi', $currentUrl) ?>">
                <i class="bi bi-calendar-check"></i> Absensi Saya
            </a>

            <div class="vs-nav-label">Informasi</div>
            <a href="/pengumuman" class="vs-nav-item <?= isActive('/pengumuman', $currentUrl) ?>">
                <i class="bi bi-megaphone"></i> Pengumuman
            </a>
        <?php endif; ?>

        <!-- Logout (semua role) -->
        <div class="vs-nav-label">Akun</div>
        <a href="/logout" class="vs-nav-item text-danger-light">
            <i class="bi bi-box-arrow-left"></i> Logout
        </a>
    </nav>

    <!-- Sidebar Footer: User Info -->
    <div class="vs-sidebar-footer">
        <div class="avatar">
            <?= strtoupper(substr(session()->get('full_name') ?? 'U', 0, 1)) ?>
        </div>
        <div class="user-info">
            <div class="user-name"><?= esc(session()->get('full_name')) ?></div>
            <div class="user-role"><?= esc(ucfirst(session()->get('role'))) ?></div>
        </div>
    </div>
</aside>
