<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Dashboard Dosen</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">
            Selamat datang, <?= esc(session()->get('full_name')) ?>
            <?php if ($dosen): ?>
                &middot; NIP: <?= esc($dosen['nip']) ?>
            <?php endif; ?>
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
    <div class="col-md-6">
        <div class="card vs-stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Mata Kuliah Diampu</div>
                        <div class="stat-value mt-1"><?= count($mataKuliah) ?></div>
                    </div>
                    <div class="stat-icon" style="background:rgba(26,35,126,0.1);color:var(--vs-primary);">
                        <i class="bi bi-book-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card vs-stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Total Mahasiswa</div>
                        <div class="stat-value mt-1"><?= esc($totalMahasiswa) ?></div>
                    </div>
                    <div class="stat-icon" style="background:rgba(0,191,165,0.1);color:var(--vs-accent);">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Row -->
<div class="row g-3">
    <!-- Mata Kuliah Diampu -->
    <div class="col-lg-7">
        <div class="card vs-card">
            <div class="card-header">
                <span><i class="bi bi-book me-2"></i>Mata Kuliah yang Diampu</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table vs-table mb-0">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Mata Kuliah</th>
                                <th class="text-center">SKS</th>
                                <th class="text-center">Semester</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($mataKuliah)): ?>
                                <tr><td colspan="4" class="text-center text-muted py-3">Belum ada mata kuliah yang diampu</td></tr>
                            <?php else: ?>
                                <?php foreach ($mataKuliah as $mk): ?>
                                    <tr>
                                        <td><code class="text-dark"><?= esc($mk['kode_mk']) ?></code></td>
                                        <td><?= esc($mk['nama_mk']) ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-primary bg-opacity-10 text-primary"><?= esc($mk['sks']) ?></span>
                                        </td>
                                        <td class="text-center"><?= esc($mk['semester']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Pengumuman Terbaru -->
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
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if (!$dosen): ?>
    <div class="alert alert-warning mt-4">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <strong>Perhatian:</strong> Profil dosen Anda belum terdaftar di sistem.
        Hubungi administrator untuk menambahkan data profil dosen.
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
