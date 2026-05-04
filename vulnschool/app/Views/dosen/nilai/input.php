<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><?= esc($mk['nama_mk']) ?></h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">
            <?= esc($mk['kode_mk']) ?> — <?= esc($mk['sks']) ?> SKS — Input nilai untuk semua mahasiswa
        </p>
    </div>
    <a href="/dosen/nilai" class="btn btn-outline-secondary btn-sm px-3">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="card vs-card">
    <div class="card-header">
        <span><i class="bi bi-pencil-square me-2"></i>Input Nilai Mahasiswa</span>
        <span class="badge bg-primary"><?= count($mahasiswaList) ?></span>
    </div>
    <div class="card-body p-0">
        <form action="/dosen/nilai/store/<?= $mk['id'] ?>" method="POST">
            <div class="table-responsive">
                <table class="table vs-table mb-0">
                    <thead>
                        <tr>
                            <th style="width:45px;">#</th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th class="text-center" style="width:100px;">Tugas (30%)</th>
                            <th class="text-center" style="width:100px;">UTS (30%)</th>
                            <th class="text-center" style="width:100px;">UAS (40%)</th>
                            <th class="text-center" style="width:80px;">Akhir</th>
                            <th class="text-center" style="width:60px;">Grade</th>
                            <th style="width:60px;">Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($mahasiswaList)): ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox" style="font-size:1.5rem;"></i>
                                    <p class="mb-0 mt-1">Belum ada mahasiswa terdaftar</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($mahasiswaList as $i => $mhs): ?>
                                <tr>
                                    <td class="text-muted"><?= $i + 1 ?></td>
                                    <td><code class="text-dark"><?= esc($mhs['nim']) ?></code></td>
                                    <td style="font-size:.85rem;" class="fw-semibold"><?= esc($mhs['nama']) ?></td>
                                    <td>
                                        <input type="hidden" name="nilai_id[]" value="<?= $mhs['id'] ?>">
                                        <input type="number" class="form-control form-control-sm text-center"
                                               name="nilai_tugas[]" value="<?= esc($mhs['nilai_tugas'] ?? '') ?>"
                                               min="0" max="100" step="0.01">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm text-center"
                                               name="nilai_uts[]" value="<?= esc($mhs['nilai_uts'] ?? '') ?>"
                                               min="0" max="100" step="0.01">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm text-center"
                                               name="nilai_uas[]" value="<?= esc($mhs['nilai_uas'] ?? '') ?>"
                                               min="0" max="100" step="0.01">
                                    </td>
                                    <td class="text-center fw-bold" style="font-size:.85rem;">
                                        <?= esc($mhs['nilai_akhir'] ?? '-') ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if (!empty($mhs['grade'])): ?>
                                            <?php
                                            $gc = match(($mhs['grade'])[0] ?? '') {
                                                'A' => '#2e7d32', 'B' => '#0288d1', 'C' => '#ff8f00', default => '#d32f2f'
                                            };
                                            $gb = match(($mhs['grade'])[0] ?? '') {
                                                'A' => 'rgba(46,125,50,0.12)', 'B' => 'rgba(2,136,209,0.12)',
                                                'C' => 'rgba(255,143,0,0.12)', default => 'rgba(211,47,47,0.12)'
                                            };
                                            ?>
                                            <span class="badge px-2" style="background:<?= $gb ?>;color:<?= $gc ?>;">
                                                <?= esc($mhs['grade']) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="/dosen/nilai/edit/<?= $mhs['id'] ?>" class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if (!empty($mahasiswaList)): ?>
                <div class="card-footer bg-transparent d-flex justify-content-end py-3">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-lg me-1"></i>Simpan Semua Nilai
                    </button>
                </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
