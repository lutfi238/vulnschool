<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><?= esc($mk['nama_mk']) ?></h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">
            <?= esc($mk['kode_mk']) ?> — <?= esc($mk['sks']) ?> SKS — Semester <?= esc($mk['semester']) ?>
        </p>
    </div>
    <a href="/dosen/mata-kuliah" class="btn btn-outline-secondary btn-sm px-3">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="row g-4">
    <!-- Info MK -->
    <div class="col-lg-4">
        <div class="card vs-card mb-4">
            <div class="card-header">
                <span><i class="bi bi-book me-2"></i>Info Mata Kuliah</span>
            </div>
            <div class="card-body">
                <table class="table table-borderless" style="font-size:.85rem;">
                    <tr>
                        <td class="text-muted" style="width:100px;">Kode MK</td>
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
                        <td class="fw-semibold"><?= esc($dosenPengampu['nama'] ?? '-') ?></td>
                    </tr>
                </table>

                <?php if (!empty($mk['deskripsi'])): ?>
                    <div class="mt-2 pt-2 border-top">
                        <label class="text-muted fw-semibold" style="font-size:.7rem;text-transform:uppercase;">Deskripsi</label>
                        <p class="mb-0" style="font-size:.85rem;"><?= esc($mk['deskripsi']) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Statistik Cepat -->
        <div class="card vs-card">
            <div class="card-header">
                <span><i class="bi bi-bar-chart me-2"></i>Statistik</span>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span style="font-size:.85rem;">Jumlah Mahasiswa</span>
                    <span class="badge bg-primary"><?= count($mahasiswaList) ?></span>
                </div>
                <?php if (!empty($mahasiswaList)):
                    $grades = array_column($mahasiswaList, 'grade');
                    $gradeA = count(array_filter($grades, fn($g) => $g === 'A'));
                    $gradeB = count(array_filter($grades, fn($g) => in_array($g, ['B', 'B+'])));
                    $gradeC = count(array_filter($grades, fn($g) => in_array($g, ['C', 'C+'])));
                ?>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span style="font-size:.82rem;" class="text-muted">Grade A</span>
                    <span class="badge bg-success bg-opacity-10 text-success"><?= $gradeA ?></span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span style="font-size:.82rem;" class="text-muted">Grade B/B+</span>
                    <span class="badge bg-info bg-opacity-10 text-info"><?= $gradeB ?></span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span style="font-size:.82rem;" class="text-muted">Grade C/C+</span>
                    <span class="badge bg-warning bg-opacity-10 text-warning"><?= $gradeC ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Daftar Mahasiswa -->
    <div class="col-lg-8">
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
                                        <p class="mb-0 mt-1" style="font-size:.85rem;">Belum ada mahasiswa terdaftar</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($mahasiswaList as $i => $mhs): ?>
                                    <tr>
                                        <td class="text-muted"><?= $i + 1 ?></td>
                                        <td><code class="text-dark"><?= esc($mhs['nim']) ?></code></td>
                                        <td style="font-size:.85rem;">
                                            <div class="fw-semibold"><?= esc($mhs['nama']) ?></div>
                                        </td>
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
