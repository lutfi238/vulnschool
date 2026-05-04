<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Absensi Saya</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">
            <?= esc($mahasiswa['nama'] ?? '') ?> — <?= esc($mahasiswa['nim'] ?? '') ?>
        </p>
    </div>
</div>

<?php
$grandTotal = 0; $grandHadir = 0;
foreach ($mataKuliahList as $mk) {
    $grandTotal += (int) $mk['total_pertemuan'];
    $grandHadir += (int) $mk['total_hadir'];
}
$overallPct = $grandTotal > 0 ? round(($grandHadir / $grandTotal) * 100, 2) : 0;
?>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card vs-stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-value"><?= $overallPct ?>%</div>
                        <div class="stat-label">Kehadiran</div>
                    </div>
                    <div class="stat-icon" style="background:rgba(46,125,50,0.12);color:#2e7d32;">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card vs-stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-value"><?= count($mataKuliahList) ?></div>
                        <div class="stat-label">Mata Kuliah</div>
                    </div>
                    <div class="stat-icon" style="background:rgba(2,136,209,0.12);color:#0288d1;">
                        <i class="bi bi-book"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card vs-stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-value"><?= $grandTotal ?></div>
                        <div class="stat-label">Total Pertemuan</div>
                    </div>
                    <div class="stat-icon" style="background:rgba(255,143,0,0.12);color:#ff8f00;">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card vs-stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-value"><?= $grandHadir ?></div>
                        <div class="stat-label">Hadir</div>
                    </div>
                    <div class="stat-icon" style="background:rgba(26,35,126,0.12);color:#1a237e;">
                        <i class="bi bi-person-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- List MK -->
<div class="row g-3">
    <?php if (empty($mataKuliahList)): ?>
        <div class="col-12">
            <div class="card vs-card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-calendar-x text-muted" style="font-size:3rem;"></i>
                    <h5 class="mt-3 text-muted">Belum Ada Data Absensi</h5>
                    <p class="text-muted">Data absensi belum tersedia.</p>
                </div>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($mataKuliahList as $mk): ?>
            <?php
            $pct = (float) $mk['persentase'];
            $barColor = $pct >= 80 ? '#2e7d32' : ($pct >= 60 ? '#ff8f00' : '#d32f2f');
            $barBg    = $pct >= 80 ? 'rgba(46,125,50,0.12)' : ($pct >= 60 ? 'rgba(255,143,0,0.12)' : 'rgba(211,47,47,0.12)');
            ?>
            <div class="col-md-6">
                <div class="card vs-card h-100" style="transition:transform .2s;cursor:pointer;"
                     onmouseover="this.style.transform='translateY(-4px)'"
                     onmouseout="this.style.transform='translateY(0)'"
                     onclick="location.href='/mahasiswa/absensi/<?= $mk['id'] ?>'">
                    <div class="card-body">
                        <div class="d-flex align-items-start gap-3 mb-3">
                            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width:48px;height:48px;font-size:1.2rem;background:<?= $barBg ?>;color:<?= $barColor ?>;">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1" style="font-size:.9rem;"><?= esc($mk['nama_mk']) ?></h6>
                                <div class="d-flex gap-2 flex-wrap">
                                    <span class="badge bg-primary bg-opacity-10 text-primary" style="font-size:.7rem;"><?= esc($mk['kode_mk']) ?></span>
                                    <span class="badge bg-success bg-opacity-10 text-success" style="font-size:.7rem;"><?= esc($mk['sks']) ?> SKS</span>
                                    <span class="badge bg-warning bg-opacity-10 text-warning" style="font-size:.7rem;">Sem <?= esc($mk['semester']) ?></span>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold" style="font-size:1.3rem;color:<?= $barColor ?>;"><?= $pct ?>%</div>
                                <div class="text-muted" style="font-size:.7rem;">Kehadiran</div>
                            </div>
                        </div>
                        <!-- Progress bar -->
                        <div class="progress" style="height:6px;border-radius:3px;">
                            <div class="progress-bar" style="width:<?= $pct ?>%;background:<?= $barColor ?>;border-radius:3px;"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2" style="font-size:.75rem;">
                            <span class="text-muted">
                                <i class="bi bi-check-circle text-success me-1"></i><?= $mk['total_hadir'] ?> Hadir
                                <i class="bi bi-heart-pulse text-info ms-2 me-1"></i><?= $mk['total_sakit'] ?> Sakit
                                <i class="bi bi-envelope text-warning ms-2 me-1"></i><?= $mk['total_izin'] ?> Izin
                                <i class="bi bi-x-circle text-danger ms-2 me-1"></i><?= $mk['total_alpha'] ?> Alpha
                            </span>
                            <span class="fw-semibold"><?= $mk['total_pertemuan'] ?> pertemuan</span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
