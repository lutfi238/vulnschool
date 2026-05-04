<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Detail Absensi</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">
            <?= esc($mk['nama_mk']) ?> (<?= esc($mk['kode_mk']) ?>)
        </p>
    </div>
    <a href="/mahasiswa/absensi" class="btn btn-outline-secondary btn-sm px-3">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
</div>

<?php
$pctColor = $persentase >= 80 ? '#2e7d32' : ($persentase >= 60 ? '#ff8f00' : '#d32f2f');
$pctBg    = $persentase >= 80 ? 'rgba(46,125,50,0.12)' : ($persentase >= 60 ? 'rgba(255,143,0,0.12)' : 'rgba(211,47,47,0.12)');
?>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card vs-stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-value" style="color:<?= $pctColor ?>;"><?= $persentase ?>%</div>
                        <div class="stat-label">Kehadiran</div>
                    </div>
                    <div class="stat-icon" style="background:<?= $pctBg ?>;color:<?= $pctColor ?>;">
                        <i class="bi bi-pie-chart"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card vs-stat-card">
            <div class="card-body text-center">
                <div class="stat-value text-success"><?= $totalHadir ?></div>
                <div class="stat-label">Hadir</div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card vs-stat-card">
            <div class="card-body text-center">
                <div class="stat-value text-info"><?= $totalSakit ?></div>
                <div class="stat-label">Sakit</div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card vs-stat-card">
            <div class="card-body text-center">
                <div class="stat-value text-warning"><?= $totalIzin ?></div>
                <div class="stat-label">Izin</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card vs-stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-value text-danger"><?= $totalAlpha ?></div>
                        <div class="stat-label">Alpha</div>
                    </div>
                    <div class="stat-icon" style="background:rgba(211,47,47,0.12);color:#d32f2f;">
                        <i class="bi bi-x-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Absensi -->
<div class="card vs-card">
    <div class="card-header">
        <span><i class="bi bi-table me-2"></i>Riwayat Absensi</span>
        <span class="badge bg-primary"><?= $totalPertemuan ?> Pertemuan</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table vs-table mb-0">
                <thead>
                    <tr>
                        <th style="width:70px;">Pertemuan</th>
                        <th>Tanggal</th>
                        <th class="text-center">Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($absensiList)): ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size:2rem;"></i>
                                <p class="mb-0 mt-2">Belum ada data absensi</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($absensiList as $a): ?>
                            <?php
                            $statusBadge = match($a['status']) {
                                'hadir' => ['bg' => 'rgba(46,125,50,0.12)', 'color' => '#2e7d32', 'icon' => 'bi-check-circle-fill'],
                                'sakit' => ['bg' => 'rgba(2,136,209,0.12)', 'color' => '#0288d1', 'icon' => 'bi-heart-pulse-fill'],
                                'izin'  => ['bg' => 'rgba(255,143,0,0.12)', 'color' => '#ff8f00', 'icon' => 'bi-envelope-fill'],
                                'alpha' => ['bg' => 'rgba(211,47,47,0.12)', 'color' => '#d32f2f', 'icon' => 'bi-x-circle-fill'],
                                default => ['bg' => 'rgba(0,0,0,0.05)', 'color' => '#666', 'icon' => 'bi-question-circle'],
                            };
                            ?>
                            <tr>
                                <td class="text-center">
                                    <span class="badge bg-primary bg-opacity-10 text-primary fw-bold px-3"><?= esc($a['pertemuan']) ?></span>
                                </td>
                                <td>
                                    <i class="bi bi-calendar3 text-muted me-1" style="font-size:.8rem;"></i>
                                    <?= date('d M Y', strtotime($a['tanggal'])) ?>
                                    <span class="text-muted ms-1" style="font-size:.75rem;">(<?= strftime('%A', strtotime($a['tanggal'])) ?: date('l', strtotime($a['tanggal'])) ?>)</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge px-3 py-1" style="background:<?= $statusBadge['bg'] ?>;color:<?= $statusBadge['color'] ?>;">
                                        <i class="bi <?= $statusBadge['icon'] ?> me-1"></i><?= ucfirst($a['status']) ?>
                                    </span>
                                </td>
                                <td style="font-size:.85rem;" class="text-muted">
                                    <?= esc($a['keterangan'] ?? '-') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Info MK -->
<div class="card vs-card mt-4">
    <div class="card-header">
        <span><i class="bi bi-info-circle me-2"></i>Informasi Mata Kuliah</span>
    </div>
    <div class="card-body" style="font-size:.85rem;">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless table-sm mb-0">
                    <tr><td class="text-muted" style="width:120px;">Kode MK</td><td class="fw-semibold"><?= esc($mk['kode_mk']) ?></td></tr>
                    <tr><td class="text-muted">Nama MK</td><td class="fw-semibold"><?= esc($mk['nama_mk']) ?></td></tr>
                    <tr><td class="text-muted">SKS</td><td class="fw-semibold"><?= esc($mk['sks']) ?></td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless table-sm mb-0">
                    <tr><td class="text-muted" style="width:120px;">Semester</td><td class="fw-semibold"><?= esc($mk['semester']) ?></td></tr>
                    <tr><td class="text-muted">Total Pertemuan</td><td class="fw-semibold"><?= $totalPertemuan ?></td></tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td>
                            <?php if ($persentase >= 80): ?>
                                <span class="badge bg-success bg-opacity-10 text-success">✅ Memenuhi Syarat</span>
                            <?php elseif ($persentase >= 60): ?>
                                <span class="badge bg-warning bg-opacity-10 text-warning">⚠️ Perlu Perhatian</span>
                            <?php else: ?>
                                <span class="badge bg-danger bg-opacity-10 text-danger">❌ Tidak Memenuhi</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
