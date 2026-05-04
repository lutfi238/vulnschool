<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Detail Mahasiswa</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">
            <?= esc($mahasiswa['nim']) ?> — <?= esc($mahasiswa['nama']) ?>
        </p>
    </div>
    <a href="/dosen/mahasiswa" class="btn btn-outline-secondary btn-sm px-3">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="row g-3">
    <!-- Profil Card -->
    <div class="col-lg-4">
        <div class="card vs-card">
            <div class="card-body text-center py-4">
                <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3"
                     style="width:80px;height:80px;font-size:2rem;font-weight:700;color:var(--vs-primary);">
                    <?= strtoupper(substr($mahasiswa['nama'], 0, 1)) ?>
                </div>
                <h5 class="fw-bold mb-1"><?= esc($mahasiswa['nama']) ?></h5>
                <p class="text-muted mb-2" style="font-size:.85rem;"><?= esc($mahasiswa['nim']) ?></p>
                <span class="badge badge-role-mahasiswa px-3"><?= esc($mahasiswa['jurusan']) ?></span>
            </div>
            <div class="card-body border-top">
                <table class="table table-sm table-borderless mb-0" style="font-size:.83rem;">
                    <tr>
                        <td class="text-muted"><i class="bi bi-calendar me-1"></i>Angkatan</td>
                        <td><?= esc($mahasiswa['angkatan']) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted"><i class="bi bi-envelope me-1"></i>Email</td>
                        <td><?= esc($mahasiswa['email'] ?? '-') ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Nilai -->
    <div class="col-lg-8">
        <div class="card vs-card">
            <div class="card-header">
                <span><i class="bi bi-card-checklist me-2"></i>Data Nilai</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table vs-table mb-0">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Mata Kuliah</th>
                                <th class="text-center">Tugas</th>
                                <th class="text-center">UTS</th>
                                <th class="text-center">UAS</th>
                                <th class="text-center">Akhir</th>
                                <th class="text-center">Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($nilaiList)): ?>
                                <tr><td colspan="7" class="text-center text-muted py-3">Belum ada data nilai</td></tr>
                            <?php else: ?>
                                <?php foreach ($nilaiList as $n): ?>
                                    <tr>
                                        <td><code class="text-dark"><?= esc($n['kode_mk']) ?></code></td>
                                        <td><?= esc($n['nama_mk']) ?></td>
                                        <td class="text-center"><?= number_format($n['nilai_tugas'], 1) ?></td>
                                        <td class="text-center"><?= number_format($n['nilai_uts'], 1) ?></td>
                                        <td class="text-center"><?= number_format($n['nilai_uas'], 1) ?></td>
                                        <td class="text-center fw-semibold"><?= number_format($n['nilai_akhir'], 1) ?></td>
                                        <td class="text-center">
                                            <span class="grade-<?= $n['grade'][0] ?>"><?= esc($n['grade']) ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
