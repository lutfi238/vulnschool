<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Absensi</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">
            Pilih mata kuliah untuk input atau rekap absensi
        </p>
    </div>
</div>

<?php if (!$dosen): ?>
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-circle me-2"></i>Profil dosen Anda belum terdaftar.
    </div>
<?php else: ?>

<div class="row g-3">
    <?php if (empty($mataKuliah)): ?>
        <div class="col-12">
            <div class="card vs-card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-book text-muted" style="font-size:3rem;"></i>
                    <h5 class="mt-3 text-muted">Belum Ada Mata Kuliah</h5>
                    <p class="text-muted">Anda belum mengampu mata kuliah apapun.</p>
                </div>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($mataKuliah as $mk): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card vs-card h-100" style="transition:transform .2s;"
                     onmouseover="this.style.transform='translateY(-4px)'"
                     onmouseout="this.style.transform='translateY(0)'">
                    <div class="card-body">
                        <div class="d-flex align-items-start gap-3 mb-3">
                            <div class="rounded-3 bg-success bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width:48px;height:48px;font-size:1.2rem;color:#2e7d32;">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1" style="font-size:.9rem;"><?= esc($mk['nama_mk']) ?></h6>
                                <div class="d-flex gap-2">
                                    <span class="badge bg-primary bg-opacity-10 text-primary" style="font-size:.7rem;"><?= esc($mk['kode_mk']) ?></span>
                                    <span class="badge bg-success bg-opacity-10 text-success" style="font-size:.7rem;"><?= esc($mk['sks']) ?> SKS</span>
                                    <span class="badge bg-warning bg-opacity-10 text-warning" style="font-size:.7rem;">Sem <?= esc($mk['semester']) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="/dosen/absensi/input/<?= $mk['id'] ?>" class="btn btn-sm btn-primary px-3 flex-grow-1">
                                <i class="bi bi-pencil-square me-1"></i>Input
                            </a>
                            <a href="/dosen/absensi/rekap/<?= $mk['id'] ?>" class="btn btn-sm btn-outline-success px-3 flex-grow-1">
                                <i class="bi bi-bar-chart me-1"></i>Rekap
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php endif; ?>

<?= $this->endSection() ?>
