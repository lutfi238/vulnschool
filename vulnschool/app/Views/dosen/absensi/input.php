<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><?= esc($mk['nama_mk']) ?></h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">
            <?= esc($mk['kode_mk']) ?> — Input absensi pertemuan ke-<?= $pertemuan ?>
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="/dosen/absensi/rekap/<?= $mk['id'] ?>" class="btn btn-outline-success btn-sm px-3">
            <i class="bi bi-bar-chart me-1"></i>Rekap
        </a>
        <a href="/dosen/absensi" class="btn btn-outline-secondary btn-sm px-3">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>
</div>

<!-- Navigasi Pertemuan -->
<div class="card vs-card mb-3">
    <div class="card-body py-2">
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="fw-semibold text-nowrap" style="font-size:.85rem;">Pertemuan:</span>
            <?php for ($p = 1; $p <= max($totalPertemuan + 1, $pertemuan, 16); $p++): ?>
                <a href="/dosen/absensi/input/<?= $mk['id'] ?>?pertemuan=<?= $p ?>"
                   class="btn btn-sm <?= $p == $pertemuan ? 'btn-primary' : 'btn-outline-secondary' ?> px-2"
                   style="min-width:36px;">
                    <?= $p ?>
                </a>
            <?php endfor; ?>
        </div>
    </div>
</div>

<!-- Form Input -->
<div class="card vs-card">
    <div class="card-header">
        <span><i class="bi bi-pencil-square me-2"></i>Absensi Pertemuan ke-<?= $pertemuan ?></span>
        <?php if (!$isNew): ?>
            <span class="badge bg-success">Sudah diisi</span>
        <?php else: ?>
            <span class="badge bg-warning text-dark">Baru</span>
        <?php endif; ?>
    </div>
    <div class="card-body p-0">
        <form action="/dosen/absensi/store/<?= $mk['id'] ?>" method="POST">
            <input type="hidden" name="pertemuan" value="<?= $pertemuan ?>">

            <div class="px-3 pt-3 pb-2">
                <div class="row g-2 align-items-center">
                    <div class="col-auto">
                        <label class="fw-semibold" style="font-size:.85rem;">Tanggal:</label>
                    </div>
                    <div class="col-auto">
                        <?php
                        $defaultDate = !$isNew && !empty($absensiList) ? $absensiList[0]['tanggal'] : date('Y-m-d');
                        ?>
                        <input type="date" class="form-control form-control-sm" name="tanggal"
                               value="<?= esc($defaultDate) ?>" required style="max-width:200px;">
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table vs-table mb-0">
                    <thead>
                        <tr>
                            <th style="width:45px;">#</th>
                            <th>NIM</th>
                            <th>Nama Mahasiswa</th>
                            <th class="text-center" style="width:200px;">Status</th>
                            <th style="width:250px;">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($isNew && !empty($enrolled)): ?>
                            <?php foreach ($enrolled as $i => $mhs): ?>
                                <tr>
                                    <td class="text-muted"><?= $i + 1 ?></td>
                                    <td><code class="text-dark"><?= esc($mhs['nim']) ?></code></td>
                                    <td style="font-size:.85rem;" class="fw-semibold"><?= esc($mhs['nama']) ?></td>
                                    <td>
                                        <input type="hidden" name="mahasiswa_id[]" value="<?= $mhs['id'] ?>">
                                        <input type="hidden" name="absensi_id[]" value="">
                                        <select name="status[]" class="form-select form-select-sm">
                                            <option value="hadir" selected>✅ Hadir</option>
                                            <option value="sakit">🏥 Sakit</option>
                                            <option value="izin">📧 Izin</option>
                                            <option value="alpha">❌ Alpha</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm"
                                               name="keterangan[]" placeholder="Opsional">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php elseif (!$isNew && !empty($absensiList)): ?>
                            <?php foreach ($absensiList as $i => $a): ?>
                                <tr>
                                    <td class="text-muted"><?= $i + 1 ?></td>
                                    <td><code class="text-dark"><?= esc($a['nim']) ?></code></td>
                                    <td style="font-size:.85rem;" class="fw-semibold"><?= esc($a['nama']) ?></td>
                                    <td>
                                        <input type="hidden" name="mahasiswa_id[]" value="<?= $a['mahasiswa_id'] ?>">
                                        <input type="hidden" name="absensi_id[]" value="<?= $a['id'] ?>">
                                        <select name="status[]" class="form-select form-select-sm">
                                            <option value="hadir" <?= $a['status'] === 'hadir' ? 'selected' : '' ?>>✅ Hadir</option>
                                            <option value="sakit" <?= $a['status'] === 'sakit' ? 'selected' : '' ?>>🏥 Sakit</option>
                                            <option value="izin" <?= $a['status'] === 'izin' ? 'selected' : '' ?>>📧 Izin</option>
                                            <option value="alpha" <?= $a['status'] === 'alpha' ? 'selected' : '' ?>>❌ Alpha</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm"
                                               name="keterangan[]" value="<?= esc($a['keterangan'] ?? '') ?>"
                                               placeholder="Opsional">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox" style="font-size:1.5rem;"></i>
                                    <p class="mb-0 mt-1">Tidak ada mahasiswa terdaftar di MK ini</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if (!empty($enrolled) || !empty($absensiList)): ?>
                <div class="card-footer bg-transparent d-flex justify-content-end py-3">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-lg me-1"></i>Simpan Absensi
                    </button>
                </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
