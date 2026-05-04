<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Detail Nilai</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">
            Informasi lengkap nilai mata kuliah
        </p>
    </div>
    <a href="/mahasiswa/nilai" class="btn btn-outline-secondary btn-sm px-3">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="row g-4 justify-content-center">
    <!-- Info Nilai -->
    <div class="col-lg-6">
        <div class="card vs-card">
            <div class="card-header">
                <span><i class="bi bi-bar-chart-line me-2"></i>Informasi Nilai</span>
            </div>
            <div class="card-body">
                <!-- MK Info -->
                <div class="text-center mb-4">
                    <div class="rounded-3 bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center"
                         style="width:56px;height:56px;font-size:1.3rem;color:var(--vs-primary);">
                        <i class="bi bi-book"></i>
                    </div>
                    <h5 class="fw-bold mt-3 mb-1"><?= esc($nilai['nama_mk']) ?></h5>
                    <span class="badge bg-primary bg-opacity-10 text-primary"><?= esc($nilai['kode_mk']) ?></span>
                    <span class="badge bg-success bg-opacity-10 text-success"><?= esc($nilai['sks']) ?> SKS</span>
                </div>

                <table class="table table-borderless" style="font-size:.85rem;">
                    <tr>
                        <td class="text-muted" style="width:130px;">Mahasiswa</td>
                        <td class="fw-semibold"><?= esc($nilai['nama_mahasiswa']) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">NIM</td>
                        <td class="fw-semibold"><?= esc($nilai['nim']) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Dosen Pengampu</td>
                        <td class="fw-semibold"><?= esc($nilai['nama_dosen'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">NIP Dosen</td>
                        <td class="fw-semibold"><?= esc($nilai['nip_dosen'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Semester</td>
                        <td class="fw-semibold"><?= esc($nilai['semester_tahun'] ?? '-') ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Komponen Nilai -->
    <div class="col-lg-6">
        <div class="card vs-card mb-4">
            <div class="card-header">
                <span><i class="bi bi-clipboard-data me-2"></i>Komponen Nilai</span>
            </div>
            <div class="card-body">
                <div class="row g-3 text-center mb-4">
                    <div class="col-4">
                        <div class="p-3 rounded-3" style="background:rgba(2,136,209,0.08);">
                            <div class="fw-bold" style="font-size:1.8rem;color:#0288d1;"><?= esc($nilai['nilai_tugas'] ?? '-') ?></div>
                            <div class="text-muted" style="font-size:.75rem;">Tugas (30%)</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 rounded-3" style="background:rgba(255,143,0,0.08);">
                            <div class="fw-bold" style="font-size:1.8rem;color:#ff8f00;"><?= esc($nilai['nilai_uts'] ?? '-') ?></div>
                            <div class="text-muted" style="font-size:.75rem;">UTS (30%)</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-3 rounded-3" style="background:rgba(46,125,50,0.08);">
                            <div class="fw-bold" style="font-size:1.8rem;color:#2e7d32;"><?= esc($nilai['nilai_uas'] ?? '-') ?></div>
                            <div class="text-muted" style="font-size:.75rem;">UAS (40%)</div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="text-center">
                    <div class="mb-2">
                        <label class="text-muted" style="font-size:.7rem;text-transform:uppercase;">Nilai Akhir</label>
                        <div class="fw-bold" style="font-size:2rem;color:#1a237e;"><?= esc($nilai['nilai_akhir'] ?? '-') ?></div>
                    </div>

                    <?php
                    $gradeColor = match(($nilai['grade'] ?? '')[0] ?? '') {
                        'A' => '#2e7d32', 'B' => '#0288d1', 'C' => '#ff8f00', default => '#d32f2f'
                    };
                    $gradeBg = match(($nilai['grade'] ?? '')[0] ?? '') {
                        'A' => 'rgba(46,125,50,0.12)', 'B' => 'rgba(2,136,209,0.12)',
                        'C' => 'rgba(255,143,0,0.12)', default => 'rgba(211,47,47,0.12)'
                    };
                    $bobot = \App\Models\NilaiModel::gradeToBobot($nilai['grade'] ?? 'E');
                    ?>

                    <div class="mb-3">
                        <span class="badge px-4 py-2" style="font-size:1.8rem;background:<?= $gradeBg ?>;color:<?= $gradeColor ?>;">
                            <?= esc($nilai['grade'] ?? '-') ?>
                        </span>
                    </div>

                    <div class="text-muted" style="font-size:.85rem;">
                        Bobot: <strong><?= number_format($bobot, 2) ?></strong> ×
                        <?= esc($nilai['sks']) ?> SKS =
                        <strong><?= number_format($bobot * (int)$nilai['sks'], 2) ?></strong> angka mutu
                    </div>
                </div>
            </div>
        </div>

        <!-- Keterangan Grade -->
        <div class="card vs-card">
            <div class="card-header">
                <span><i class="bi bi-info-circle me-2"></i>Keterangan Grade</span>
            </div>
            <div class="card-body" style="font-size:.82rem;">
                <div class="row">
                    <div class="col-6">
                        <div class="d-flex justify-content-between mb-1"><span>A (≥85)</span><span class="fw-bold">4.00</span></div>
                        <div class="d-flex justify-content-between mb-1"><span>A- (≥80)</span><span class="fw-bold">3.75</span></div>
                        <div class="d-flex justify-content-between mb-1"><span>B+ (≥75)</span><span class="fw-bold">3.50</span></div>
                        <div class="d-flex justify-content-between mb-1"><span>B (≥70)</span><span class="fw-bold">3.00</span></div>
                        <div class="d-flex justify-content-between mb-1"><span>B- (≥65)</span><span class="fw-bold">2.75</span></div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex justify-content-between mb-1"><span>C+ (≥60)</span><span class="fw-bold">2.50</span></div>
                        <div class="d-flex justify-content-between mb-1"><span>C (≥55)</span><span class="fw-bold">2.00</span></div>
                        <div class="d-flex justify-content-between mb-1"><span>D (≥50)</span><span class="fw-bold">1.00</span></div>
                        <div class="d-flex justify-content-between mb-1"><span>E (<50)</span><span class="fw-bold text-danger">0.00</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
