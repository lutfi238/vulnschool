<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><?= esc($mk['nama_mk']) ?></h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">
            <?= esc($mk['kode_mk']) ?> — <?= esc($mk['sks']) ?> SKS — Semester <?= esc($mk['semester']) ?>
        </p>
    </div>
    <a href="/mahasiswa/mata-kuliah" class="btn btn-outline-secondary btn-sm px-3">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="row g-4">
    <!-- Info MK -->
    <div class="col-lg-5">
        <div class="card vs-card mb-4">
            <div class="card-header">
                <span><i class="bi bi-book me-2"></i>Info Mata Kuliah</span>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="rounded-3 bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center"
                         style="width:56px;height:56px;font-size:1.3rem;color:var(--vs-primary);">
                        <i class="bi bi-book"></i>
                    </div>
                </div>

                <table class="table table-borderless" style="font-size:.85rem;">
                    <tr>
                        <td class="text-muted" style="width:120px;">Kode MK</td>
                        <td class="fw-semibold"><?= esc($mk['kode_mk']) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Nama MK</td>
                        <td class="fw-semibold"><?= esc($mk['nama_mk']) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">SKS</td>
                        <td><span class="badge bg-primary"><?= esc($mk['sks']) ?> SKS</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Semester</td>
                        <td class="fw-semibold">Semester <?= esc($mk['semester']) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Dosen</td>
                        <td class="fw-semibold"><?= esc($dosen['nama'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">NIP Dosen</td>
                        <td class="fw-semibold"><?= esc($dosen['nip'] ?? '-') ?></td>
                    </tr>
                </table>

                <?php if (!empty($mk['deskripsi'])): ?>
                    <div class="mt-3 pt-3 border-top">
                        <label class="text-muted fw-semibold" style="font-size:.7rem;text-transform:uppercase;">Deskripsi</label>
                        <p class="mb-0" style="font-size:.85rem;"><?= esc($mk['deskripsi']) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Nilai Saya di MK Ini -->
    <div class="col-lg-7">
        <div class="card vs-card">
            <div class="card-header">
                <span><i class="bi bi-bar-chart-line me-2"></i>Nilai Saya</span>
            </div>
            <div class="card-body">
                <?php if ($nilai): ?>
                    <div class="row g-3 text-center mb-4">
                        <div class="col-3">
                            <div class="p-3 rounded-3" style="background:rgba(2,136,209,0.08);">
                                <div class="fw-bold" style="font-size:1.5rem;color:#0288d1;"><?= esc($nilai['nilai_tugas'] ?? '-') ?></div>
                                <div class="text-muted" style="font-size:.75rem;">Tugas</div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="p-3 rounded-3" style="background:rgba(255,143,0,0.08);">
                                <div class="fw-bold" style="font-size:1.5rem;color:#ff8f00;"><?= esc($nilai['nilai_uts'] ?? '-') ?></div>
                                <div class="text-muted" style="font-size:.75rem;">UTS</div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="p-3 rounded-3" style="background:rgba(46,125,50,0.08);">
                                <div class="fw-bold" style="font-size:1.5rem;color:#2e7d32;"><?= esc($nilai['nilai_uas'] ?? '-') ?></div>
                                <div class="text-muted" style="font-size:.75rem;">UAS</div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="p-3 rounded-3" style="background:rgba(26,35,126,0.08);">
                                <div class="fw-bold" style="font-size:1.5rem;color:#1a237e;"><?= esc($nilai['nilai_akhir'] ?? '-') ?></div>
                                <div class="text-muted" style="font-size:.75rem;">Akhir</div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <label class="text-muted" style="font-size:.75rem;">GRADE</label>
                        <div>
                            <?php
                            $gradeColor = match($nilai['grade'][0] ?? '') {
                                'A' => '#2e7d32',
                                'B' => '#0288d1',
                                'C' => '#ff8f00',
                                default => '#d32f2f'
                            };
                            $gradeBg = match($nilai['grade'][0] ?? '') {
                                'A' => 'rgba(46,125,50,0.12)',
                                'B' => 'rgba(2,136,209,0.12)',
                                'C' => 'rgba(255,143,0,0.12)',
                                default => 'rgba(211,47,47,0.12)'
                            };
                            ?>
                            <span class="badge px-4 py-2" style="font-size:1.5rem;background:<?= $gradeBg ?>;color:<?= $gradeColor ?>;">
                                <?= esc($nilai['grade'] ?? '-') ?>
                            </span>
                        </div>
                        <p class="text-muted mt-2" style="font-size:.8rem;">
                            Semester: <?= esc($nilai['semester_tahun'] ?? '-') ?>
                        </p>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="bi bi-clipboard-x text-muted" style="font-size:2.5rem;"></i>
                        <h6 class="mt-3 text-muted">Belum Ada Nilai</h6>
                        <p class="text-muted mb-0" style="font-size:.85rem;">Nilai untuk mata kuliah ini belum diinput oleh dosen.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
