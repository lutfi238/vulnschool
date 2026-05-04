<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Dashboard Admin</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">
            Selamat datang kembali, <?= esc(session()->get('full_name')) ?>
        </p>
    </div>
    <div>
        <span class="badge bg-light text-dark border px-3 py-2" style="font-size:.78rem;">
            <i class="bi bi-calendar3 me-1"></i><?= date('l, d F Y') ?>
        </span>
    </div>
</div>

<!-- Statistik Cards -->
<div class="row g-3 mb-4">
    <!-- Total User -->
    <div class="col-xl-3 col-md-6">
        <div class="card vs-stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Total User</div>
                        <div class="stat-value mt-1"><?= esc($stats['total_users']) ?></div>
                    </div>
                    <div class="stat-icon" style="background:rgba(26,35,126,0.1);color:var(--vs-primary);">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Mahasiswa -->
    <div class="col-xl-3 col-md-6">
        <div class="card vs-stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Total Mahasiswa</div>
                        <div class="stat-value mt-1"><?= esc($stats['total_mahasiswa']) ?></div>
                    </div>
                    <div class="stat-icon" style="background:rgba(0,191,165,0.1);color:var(--vs-accent);">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Dosen -->
    <div class="col-xl-3 col-md-6">
        <div class="card vs-stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Total Dosen</div>
                        <div class="stat-value mt-1"><?= esc($stats['total_dosen']) ?></div>
                    </div>
                    <div class="stat-icon" style="background:rgba(2,136,209,0.1);color:var(--vs-info);">
                        <i class="bi bi-person-workspace"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Mata Kuliah -->
    <div class="col-xl-3 col-md-6">
        <div class="card vs-stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Total Mata Kuliah</div>
                        <div class="stat-value mt-1"><?= esc($stats['total_matakuliah']) ?></div>
                    </div>
                    <div class="stat-icon" style="background:rgba(255,143,0,0.1);color:var(--vs-warning);">
                        <i class="bi bi-book-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tables Row -->
<div class="row g-3">
    <!-- 5 User Terbaru -->
    <div class="col-lg-7">
        <div class="card vs-card">
            <div class="card-header">
                <span><i class="bi bi-people me-2"></i>5 User Terbaru</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table vs-table mb-0">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Nama Lengkap</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Dibuat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recentUsers)): ?>
                                <tr><td colspan="5" class="text-center text-muted py-3">Belum ada data user</td></tr>
                            <?php else: ?>
                                <?php foreach ($recentUsers as $user): ?>
                                    <tr>
                                        <td>
                                            <code class="text-dark"><?= esc($user['username']) ?></code>
                                        </td>
                                        <td><?= esc($user['full_name']) ?></td>
                                        <td>
                                            <?php
                                            $badgeClass = match($user['role']) {
                                                'admin' => 'badge-role-admin',
                                                'dosen' => 'badge-role-dosen',
                                                'mahasiswa' => 'badge-role-mahasiswa',
                                                default => 'bg-secondary',
                                            };
                                            ?>
                                            <span class="badge <?= $badgeClass ?>"><?= esc(ucfirst($user['role'])) ?></span>
                                        </td>
                                        <td>
                                            <?php if ($user['is_active']): ?>
                                                <span class="badge bg-success bg-opacity-10 text-success">Aktif</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger bg-opacity-10 text-danger">Nonaktif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-muted" style="font-size:.78rem;">
                                            <?= date('d M Y', strtotime($user['created_at'])) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- 5 Pengumuman Terbaru -->
    <div class="col-lg-5">
        <div class="card vs-card">
            <div class="card-header">
                <span><i class="bi bi-megaphone me-2"></i>Pengumuman Terbaru</span>
            </div>
            <div class="card-body">
                <?php if (empty($recentPengumuman)): ?>
                    <p class="text-muted text-center py-3 mb-0">Belum ada pengumuman</p>
                <?php else: ?>
                    <?php foreach ($recentPengumuman as $p): ?>
                        <div class="pengumuman-item">
                            <div class="title"><?= esc($p['judul']) ?></div>
                            <div class="meta mt-1">
                                <i class="bi bi-person me-1"></i><?= esc($p['author_name']) ?>
                                <span class="mx-1">&middot;</span>
                                <i class="bi bi-clock me-1"></i><?= date('d M Y', strtotime($p['created_at'])) ?>
                                <?php if ($p['is_published']): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success ms-1" style="font-size:.65rem;">Publish</span>
                                <?php else: ?>
                                    <span class="badge bg-warning bg-opacity-10 text-warning ms-1" style="font-size:.65rem;">Draft</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
