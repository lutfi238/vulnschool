<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Dashboard') ?> — VulnSchool</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Chart.js (opsional, untuk grafik) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <style>
        :root {
            --vs-primary: #1a237e;
            --vs-primary-light: #3949ab;
            --vs-primary-lighter: #5c6bc0;
            --vs-accent: #00bfa5;
            --vs-accent-light: #64ffda;
            --vs-danger: #d32f2f;
            --vs-warning: #ff8f00;
            --vs-info: #0288d1;
            --vs-success: #2e7d32;
            --vs-bg: #f0f2f5;
            --vs-card: #ffffff;
            --vs-text: #1e1e2d;
            --vs-muted: #78829d;
            --vs-border: #e8ecf1;
            --vs-sidebar-w: 260px;
            --vs-topbar-h: 64px;
        }

        * { font-family: 'Inter', -apple-system, sans-serif; }
        body { background: var(--vs-bg); color: var(--vs-text); overflow-x: hidden; }

        /* ========== SIDEBAR ========== */
        .vs-sidebar {
            position: fixed;
            top: 0; left: 0; bottom: 0;
            width: var(--vs-sidebar-w);
            background: linear-gradient(180deg, var(--vs-primary) 0%, #0d1452 100%);
            z-index: 1040;
            display: flex; flex-direction: column;
            transition: transform 0.3s cubic-bezier(.4,0,.2,1);
            box-shadow: 4px 0 24px rgba(0,0,0,0.12);
        }
        .vs-sidebar-brand {
            padding: 1.25rem 1.5rem;
            display: flex; align-items: center; gap: .75rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .vs-sidebar-brand .brand-icon {
            width: 40px; height: 40px;
            background: rgba(255,255,255,0.12);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem; color: var(--vs-accent-light);
        }
        .vs-sidebar-brand .brand-text {
            color: #fff; font-weight: 700; font-size: .95rem; line-height: 1.2;
        }
        .vs-sidebar-brand .brand-sub {
            color: rgba(255,255,255,0.5); font-size: .7rem; font-weight: 400;
        }
        .vs-sidebar-nav { flex: 1; overflow-y: auto; padding: .75rem 0; }
        .vs-sidebar-nav::-webkit-scrollbar { width: 4px; }
        .vs-sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 2px; }

        .vs-nav-label {
            padding: .75rem 1.5rem .4rem;
            font-size: .65rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 1.2px;
            color: rgba(255,255,255,0.35);
        }
        .vs-nav-item {
            display: flex; align-items: center; gap: .75rem;
            padding: .65rem 1.5rem;
            color: rgba(255,255,255,0.65);
            text-decoration: none; font-size: .85rem; font-weight: 500;
            transition: all 0.2s; border-left: 3px solid transparent;
            margin: 1px 0;
        }
        .vs-nav-item:hover {
            color: #fff; background: rgba(255,255,255,0.06);
            border-left-color: var(--vs-accent);
        }
        .vs-nav-item.active {
            color: #fff; background: rgba(255,255,255,0.1);
            border-left-color: var(--vs-accent);
        }
        .vs-nav-item i { font-size: 1.1rem; width: 22px; text-align: center; }
        .vs-nav-item.text-danger-light { color: rgba(255,100,100,0.7); }
        .vs-nav-item.text-danger-light:hover { color: #ff6b6b; background: rgba(255,100,100,0.08); border-left-color: #ff6b6b; }

        .vs-sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.08);
            display: flex; align-items: center; gap: .75rem;
        }
        .vs-sidebar-footer .avatar {
            width: 36px; height: 36px; border-radius: 8px;
            background: var(--vs-primary-lighter);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 700; font-size: .85rem;
        }
        .vs-sidebar-footer .user-info { flex: 1; min-width: 0; }
        .vs-sidebar-footer .user-name { color: #fff; font-size: .8rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .vs-sidebar-footer .user-role { color: rgba(255,255,255,0.45); font-size: .68rem; }

        /* ========== TOPBAR ========== */
        .vs-topbar {
            position: fixed;
            top: 0; right: 0;
            left: var(--vs-sidebar-w);
            height: var(--vs-topbar-h);
            background: var(--vs-card);
            border-bottom: 1px solid var(--vs-border);
            display: flex; align-items: center;
            padding: 0 1.5rem;
            z-index: 1030;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            transition: left 0.3s;
        }
        .vs-topbar .topbar-left { display: flex; align-items: center; gap: .75rem; }
        .vs-topbar .breadcrumb { margin: 0; font-size: .85rem; }
        .vs-topbar .breadcrumb-item a { color: var(--vs-muted); text-decoration: none; }
        .vs-topbar .breadcrumb-item.active { color: var(--vs-text); font-weight: 600; }
        .vs-topbar .topbar-right { margin-left: auto; display: flex; align-items: center; gap: 1rem; }
        .vs-topbar .btn-sidebar-toggle { display: none; background: none; border: none; font-size: 1.3rem; color: var(--vs-text); cursor: pointer; }

        /* ========== MAIN CONTENT ========== */
        .vs-main {
            margin-left: var(--vs-sidebar-w);
            padding-top: var(--vs-topbar-h);
            min-height: 100vh;
            display: flex; flex-direction: column;
            transition: margin-left 0.3s;
        }
        .vs-content { flex: 1; padding: 1.5rem; }

        /* ========== FOOTER ========== */
        .vs-footer {
            padding: .75rem 1.5rem;
            border-top: 1px solid var(--vs-border);
            font-size: .75rem; color: var(--vs-muted);
            display: flex; justify-content: space-between; align-items: center;
            background: var(--vs-card);
        }

        /* ========== CARDS ========== */
        .vs-stat-card {
            border: none; border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.05);
            transition: transform 0.2s, box-shadow 0.2s;
            overflow: hidden;
        }
        .vs-stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        }
        .vs-stat-card .card-body { padding: 1.25rem; }
        .vs-stat-card .stat-icon {
            width: 48px; height: 48px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
        }
        .vs-stat-card .stat-value { font-size: 1.75rem; font-weight: 800; line-height: 1; }
        .vs-stat-card .stat-label { font-size: .78rem; color: var(--vs-muted); font-weight: 500; }

        .vs-card {
            border: none; border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        .vs-card .card-header {
            background: var(--vs-card);
            border-bottom: 1px solid var(--vs-border);
            padding: 1rem 1.25rem;
            font-weight: 600; font-size: .9rem;
            display: flex; align-items: center; justify-content: space-between;
        }
        .vs-card .card-body { padding: 1.25rem; }

        /* Table styling */
        .vs-table { font-size: .85rem; }
        .vs-table thead th {
            background: #f8f9fc; font-weight: 600;
            font-size: .75rem; text-transform: uppercase;
            letter-spacing: .5px; color: var(--vs-muted);
            border-bottom: 2px solid var(--vs-border);
            padding: .75rem 1rem;
        }
        .vs-table tbody td { padding: .65rem 1rem; vertical-align: middle; border-color: var(--vs-border); }

        /* Badge styles */
        .badge-role-admin { background: #fce4ec; color: #c62828; font-weight: 600; }
        .badge-role-dosen { background: #e8eaf6; color: #283593; font-weight: 600; }
        .badge-role-mahasiswa { background: #e0f2f1; color: #00695c; font-weight: 600; }

        /* Alert styling */
        .alert { border-radius: 10px; border: none; font-size: .875rem; }

        /* Pengumuman card */
        .pengumuman-item {
            padding: .75rem 0;
            border-bottom: 1px solid var(--vs-border);
        }
        .pengumuman-item:last-child { border-bottom: none; }
        .pengumuman-item .title { font-weight: 600; font-size: .875rem; color: var(--vs-text); }
        .pengumuman-item .meta { font-size: .75rem; color: var(--vs-muted); }

        /* Grade colors */
        .grade-A { color: var(--vs-success); font-weight: 700; }
        .grade-B { color: var(--vs-info); font-weight: 700; }
        .grade-C { color: var(--vs-warning); font-weight: 700; }
        .grade-D, .grade-E { color: var(--vs-danger); font-weight: 700; }

        /* Animations */
        .fade-in { animation: fadeIn .4s ease-out; }
        @keyframes fadeIn { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 991.98px) {
            .vs-sidebar { transform: translateX(-100%); }
            .vs-sidebar.show { transform: translateX(0); }
            .vs-topbar { left: 0; }
            .vs-main { margin-left: 0; }
            .vs-topbar .btn-sidebar-toggle { display: block; }
            .vs-sidebar-overlay {
                display: none; position: fixed; inset: 0;
                background: rgba(0,0,0,0.4); z-index: 1035;
            }
            .vs-sidebar-overlay.show { display: block; }
        }
    </style>
</head>
<body>

    <!-- Sidebar Overlay (mobile) -->
    <div class="vs-sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <?= $this->include('partials/sidebar') ?>

    <!-- Topbar -->
    <?= $this->include('partials/topbar') ?>

    <!-- Main Content -->
    <div class="vs-main">
        <div class="vs-content fade-in">
            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i><?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i><?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('warning')): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i><?= session()->getFlashdata('warning') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('info')): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="bi bi-info-circle-fill me-2"></i><?= session()->getFlashdata('info') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>
        </div>

        <!-- Footer -->
        <?= $this->include('partials/footer') ?>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }
    </script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
