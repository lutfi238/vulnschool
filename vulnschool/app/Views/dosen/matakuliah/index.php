<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Mata Kuliah Saya</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">
            Daftar mata kuliah yang Anda ampu
        </p>
    </div>
</div>

<?php if (!$dosen): ?>
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-circle me-2"></i>Profil dosen Anda belum terdaftar. Hubungi admin.
    </div>
<?php else: ?>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card vs-stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-value"><?= count($mataKuliah) ?></div>
                        <div class="stat-label">Total MK Diampu</div>
                    </div>
                    <div class="stat-icon" style="background:rgba(2,136,209,0.12);color:#0288d1;">
                        <i class="bi bi-book"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card vs-stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <?php $totalSks = array_sum(array_column($mataKuliah, 'sks')); ?>
                        <div class="stat-value"><?= $totalSks ?></div>
                        <div class="stat-label">Total SKS</div>
                    </div>
                    <div class="stat-icon" style="background:rgba(46,125,50,0.12);color:#2e7d32;">
                        <i class="bi bi-mortarboard"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Table -->
<div class="card vs-card">
    <div class="card-header">
        <span><i class="bi bi-book me-2"></i>Daftar Mata Kuliah</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table vs-table mb-0">
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th>Kode MK</th>
                        <th>Nama Mata Kuliah</th>
                        <th class="text-center">SKS</th>
                        <th class="text-center">Semester</th>
                        <th style="width:100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($mataKuliah)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size:2rem;"></i>
                                <p class="mb-0 mt-2">Belum ada mata kuliah yang diampu</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($mataKuliah as $i => $mk): ?>
                            <tr>
                                <td class="text-muted"><?= $i + 1 ?></td>
                                <td><code class="text-dark"><?= esc($mk['kode_mk']) ?></code></td>
                                <td>
                                    <div class="fw-semibold" style="font-size:.85rem;"><?= esc($mk['nama_mk']) ?></div>
                                    <?php if (!empty($mk['deskripsi'])): ?>
                                        <div class="text-muted" style="font-size:.72rem;"><?= esc(substr($mk['deskripsi'], 0, 60)) ?><?= strlen($mk['deskripsi']) > 60 ? '...' : '' ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary bg-opacity-10 text-primary"><?= esc($mk['sks']) ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success bg-opacity-10 text-success">Sem <?= esc($mk['semester']) ?></span>
                                </td>
                                <td>
                                    <a href="/dosen/mata-kuliah/<?= $mk['id'] ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye me-1"></i>Detail
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php endif; ?>

<?= $this->endSection() ?>
