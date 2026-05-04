<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Detail Mata Kuliah</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">
            Informasi lengkap mata kuliah dan daftar mahasiswa
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="/admin/mata-kuliah/edit/<?= $mk['id'] ?>" class="btn btn-warning btn-sm px-3">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
        <a href="/admin/mata-kuliah" class="btn btn-outline-secondary btn-sm px-3">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Info MK -->
    <div class="col-lg-5">
        <div class="card vs-card mb-4">
            <div class="card-header">
                <span><i class="bi bi-book me-2"></i>Informasi MK</span>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="rounded-3 bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center"
                         style="width:64px;height:64px;font-size:1.5rem;font-weight:800;color:var(--vs-primary);">
                        <i class="bi bi-book"></i>
                    </div>
                    <h5 class="fw-bold mt-3 mb-1"><?= esc($mk['nama_mk']) ?></h5>
                    <span class="badge bg-primary bg-opacity-10 text-primary"><?= esc($mk['kode_mk']) ?></span>
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
                    <div class="mt-3">
                        <label class="text-muted fw-semibold" style="font-size:.75rem;">DESKRIPSI</label>
                        <p class="mb-0" style="font-size:.85rem;"><?= esc($mk['deskripsi']) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Statistik -->
        <div class="card vs-card">
            <div class="card-header">
                <span><i class="bi bi-bar-chart me-2"></i>Statistik</span>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span style="font-size:.85rem;">Total Mahasiswa</span>
                    <span class="badge bg-primary"><?= $laporan['total_mahasiswa'] ?? 0 ?></span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span style="font-size:.85rem;">Sudah Dinilai</span>
                    <span class="badge bg-success"><?= count($mahasiswaList) ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Mahasiswa -->
    <div class="col-lg-7">
        <div class="card vs-card">
            <div class="card-header">
                <span><i class="bi bi-people me-2"></i>Daftar Mahasiswa</span>
                <span class="badge bg-primary"><?= count($mahasiswaList) ?></span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table vs-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th class="text-center">Tugas</th>
                                <th class="text-center">UTS</th>
                                <th class="text-center">UAS</th>
                                <th class="text-center">Akhir</th>
                                <th class="text-center">Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($mahasiswaList)): ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox" style="font-size:1.5rem;"></i>
                                        <p class="mb-0 mt-1" style="font-size:.85rem;">Belum ada mahasiswa</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($mahasiswaList as $i => $mhs): ?>
                                    <tr>
                                        <td class="text-muted"><?= $i + 1 ?></td>
                                        <td><code class="text-dark"><?= esc($mhs['nim']) ?></code></td>
                                        <td style="font-size:.85rem;"><?= esc($mhs['nama']) ?></td>
                                        <td class="text-center" style="font-size:.85rem;"><?= esc($mhs['nilai_tugas'] ?? '-') ?></td>
                                        <td class="text-center" style="font-size:.85rem;"><?= esc($mhs['nilai_uts'] ?? '-') ?></td>
                                        <td class="text-center" style="font-size:.85rem;"><?= esc($mhs['nilai_uas'] ?? '-') ?></td>
                                        <td class="text-center fw-semibold" style="font-size:.85rem;"><?= esc($mhs['nilai_akhir'] ?? '-') ?></td>
                                        <td class="text-center">
                                            <?php if (!empty($mhs['grade'])): ?>
                                                <span class="badge grade-<?= $mhs['grade'][0] ?>"
                                                      style="background:<?= match($mhs['grade'][0] ?? '') {
                                                          'A' => 'rgba(46,125,50,0.12)',
                                                          'B' => 'rgba(2,136,209,0.12)',
                                                          'C' => 'rgba(255,143,0,0.12)',
                                                          default => 'rgba(211,47,47,0.12)'
                                                      } ?>;">
                                                    <?= esc($mhs['grade']) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
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
