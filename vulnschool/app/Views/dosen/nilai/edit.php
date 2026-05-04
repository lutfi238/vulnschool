<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Edit Nilai</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">
            <?= esc($nilai['nama_mahasiswa']) ?> (<?= esc($nilai['nim']) ?>) — <?= esc($nilai['nama_mk']) ?>
        </p>
    </div>
    <a href="/dosen/nilai/input/<?= $nilai['mata_kuliah_id'] ?>" class="btn btn-outline-secondary btn-sm px-3">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card vs-card">
            <div class="card-header">
                <span><i class="bi bi-pencil me-2"></i>Edit Nilai Mahasiswa</span>
            </div>
            <div class="card-body">
                <!-- Info Mahasiswa -->
                <div class="d-flex gap-3 align-items-center mb-4 p-3 rounded-3" style="background:rgba(26,35,126,0.04);">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center"
                         style="width:48px;height:48px;font-size:1.2rem;color:var(--vs-primary);font-weight:700;">
                        <?= strtoupper(substr($nilai['nama_mahasiswa'], 0, 1)) ?>
                    </div>
                    <div>
                        <div class="fw-bold"><?= esc($nilai['nama_mahasiswa']) ?></div>
                        <div class="text-muted" style="font-size:.8rem;"><?= esc($nilai['nim']) ?> — <?= esc($nilai['nama_mk']) ?> (<?= esc($nilai['kode_mk']) ?>)</div>
                    </div>
                </div>

                <form action="/dosen/nilai/update/<?= $nilai['id'] ?>" method="POST">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Nilai Tugas (30%)</label>
                            <input type="number" class="form-control" name="nilai_tugas"
                                   value="<?= esc($nilai['nilai_tugas'] ?? '') ?>" min="0" max="100" step="0.01" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Nilai UTS (30%)</label>
                            <input type="number" class="form-control" name="nilai_uts"
                                   value="<?= esc($nilai['nilai_uts'] ?? '') ?>" min="0" max="100" step="0.01" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Nilai UAS (40%)</label>
                            <input type="number" class="form-control" name="nilai_uas"
                                   value="<?= esc($nilai['nilai_uas'] ?? '') ?>" min="0" max="100" step="0.01" required>
                        </div>
                    </div>

                    <?php if (!empty($nilai['nilai_akhir'])): ?>
                        <div class="mt-4 p-3 rounded-3 text-center" style="background:rgba(26,35,126,0.04);">
                            <span class="text-muted" style="font-size:.8rem;">Nilai saat ini:</span>
                            <span class="fw-bold ms-2" style="font-size:1.2rem;"><?= esc($nilai['nilai_akhir']) ?></span>
                            <span class="badge ms-2 px-2" style="background:rgba(46,125,50,0.12);color:#2e7d32;"><?= esc($nilai['grade']) ?></span>
                        </div>
                    <?php endif; ?>

                    <hr class="my-4">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-lg me-1"></i>Simpan Nilai
                        </button>
                        <a href="/dosen/nilai/input/<?= $nilai['mata_kuliah_id'] ?>" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
