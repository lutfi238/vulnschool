<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Transcript Nilai Akademik</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">
            <?= esc($mahasiswa['nama'] ?? '') ?> — <?= esc($mahasiswa['nim'] ?? '') ?>
        </p>
    </div>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card vs-stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-value"><?= $ipk ?></div>
                        <div class="stat-label">IPK</div>
                    </div>
                    <div class="stat-icon" style="background:rgba(46,125,50,0.12);color:#2e7d32;">
                        <i class="bi bi-trophy"></i>
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
                        <div class="stat-value"><?= $totalSks ?></div>
                        <div class="stat-label">Total SKS</div>
                    </div>
                    <div class="stat-icon" style="background:rgba(2,136,209,0.12);color:#0288d1;">
                        <i class="bi bi-mortarboard"></i>
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
                        <div class="stat-value"><?= count($nilaiList) ?></div>
                        <div class="stat-label">Total MK</div>
                    </div>
                    <div class="stat-icon" style="background:rgba(255,143,0,0.12);color:#ff8f00;">
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
                        <?php
                        $gradeA = count(array_filter($nilaiList, fn($n) => in_array($n['grade'] ?? '', ['A', 'A-'])));
                        ?>
                        <div class="stat-value"><?= $gradeA ?></div>
                        <div class="stat-label">Grade A/A-</div>
                    </div>
                    <div class="stat-icon" style="background:rgba(26,35,126,0.12);color:#1a237e;">
                        <i class="bi bi-star"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Semester -->
<div class="card vs-card mb-3">
    <div class="card-body py-2">
        <form action="/mahasiswa/nilai" method="GET" class="d-flex gap-2 align-items-center">
            <label class="fw-semibold text-nowrap" style="font-size:.85rem;">Filter Semester:</label>
            <input type="text" class="form-control form-control-sm" name="semester"
                   value="<?= esc($semester ?? '') ?>" placeholder="Contoh: 2025/Ganjil" style="max-width:200px;">
            <button type="submit" class="btn btn-primary btn-sm px-3">Filter</button>
            <?php if (!empty($semester)): ?>
                <a href="/mahasiswa/nilai" class="btn btn-outline-secondary btn-sm px-3">Reset</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- Info Mahasiswa (KHS Header) -->
<div class="card vs-card mb-4">
    <div class="card-header">
        <span><i class="bi bi-person-badge me-2"></i>Kartu Hasil Studi</span>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless table-sm mb-0" style="font-size:.85rem;">
                    <tr><td class="text-muted" style="width:120px;">NIM</td><td class="fw-semibold"><?= esc($mahasiswa['nim'] ?? '-') ?></td></tr>
                    <tr><td class="text-muted">Nama</td><td class="fw-semibold"><?= esc($mahasiswa['nama'] ?? '-') ?></td></tr>
                    <tr><td class="text-muted">Jurusan</td><td class="fw-semibold"><?= esc($mahasiswa['jurusan'] ?? '-') ?></td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless table-sm mb-0" style="font-size:.85rem;">
                    <tr><td class="text-muted" style="width:120px;">Angkatan</td><td class="fw-semibold"><?= esc($mahasiswa['angkatan'] ?? '-') ?></td></tr>
                    <tr><td class="text-muted">Total SKS</td><td class="fw-semibold"><?= $totalSks ?> SKS</td></tr>
                    <tr><td class="text-muted">IPK</td><td class="fw-bold" style="color:#2e7d32;font-size:1.1rem;"><?= $ipk ?></td></tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Nilai -->
<div class="card vs-card">
    <div class="card-header">
        <span><i class="bi bi-table me-2"></i>Daftar Nilai</span>
        <span class="badge bg-primary"><?= count($nilaiList) ?></span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table vs-table mb-0">
                <thead>
                    <tr>
                        <th style="width:45px;">#</th>
                        <th>Kode MK</th>
                        <th>Nama Mata Kuliah</th>
                        <th class="text-center">SKS</th>
                        <th class="text-center">Tugas</th>
                        <th class="text-center">UTS</th>
                        <th class="text-center">UAS</th>
                        <th class="text-center">Akhir</th>
                        <th class="text-center">Grade</th>
                        <th class="text-center">Bobot</th>
                        <th style="width:60px;">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($nilaiList)): ?>
                        <tr>
                            <td colspan="11" class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size:2rem;"></i>
                                <p class="mb-0 mt-2">Belum ada data nilai</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($nilaiList as $i => $n): ?>
                            <?php
                            $gradeColor = match($n['grade'][0] ?? '') {
                                'A' => '#2e7d32', 'B' => '#0288d1', 'C' => '#ff8f00', default => '#d32f2f'
                            };
                            $gradeBg = match($n['grade'][0] ?? '') {
                                'A' => 'rgba(46,125,50,0.12)', 'B' => 'rgba(2,136,209,0.12)',
                                'C' => 'rgba(255,143,0,0.12)', default => 'rgba(211,47,47,0.12)'
                            };
                            $bobot = \App\Models\NilaiModel::gradeToBobot($n['grade'] ?? 'E');
                            ?>
                            <tr>
                                <td class="text-muted"><?= $i + 1 ?></td>
                                <td><code class="text-dark"><?= esc($n['kode_mk']) ?></code></td>
                                <td style="font-size:.85rem;"><?= esc($n['nama_mk']) ?></td>
                                <td class="text-center"><span class="badge bg-primary bg-opacity-10 text-primary"><?= esc($n['sks']) ?></span></td>
                                <td class="text-center" style="font-size:.85rem;"><?= esc($n['nilai_tugas'] ?? '-') ?></td>
                                <td class="text-center" style="font-size:.85rem;"><?= esc($n['nilai_uts'] ?? '-') ?></td>
                                <td class="text-center" style="font-size:.85rem;"><?= esc($n['nilai_uas'] ?? '-') ?></td>
                                <td class="text-center fw-bold" style="font-size:.85rem;"><?= esc($n['nilai_akhir'] ?? '-') ?></td>
                                <td class="text-center">
                                    <span class="badge px-2" style="background:<?= $gradeBg ?>;color:<?= $gradeColor ?>;">
                                        <?= esc($n['grade']) ?>
                                    </span>
                                </td>
                                <td class="text-center" style="font-size:.85rem;"><?= number_format($bobot, 2) ?></td>
                                <td>
                                    <a href="/mahasiswa/nilai/<?= $n['id'] ?>" class="btn btn-sm btn-outline-primary" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <?php if (!empty($nilaiList)): ?>
                <tfoot>
                    <tr class="fw-bold" style="background:rgba(26,35,126,0.04);">
                        <td colspan="3" class="text-end">Total / Rata-rata</td>
                        <td class="text-center"><?= $totalSks ?></td>
                        <td colspan="4"></td>
                        <td class="text-center" style="font-size:.9rem;color:#1a237e;">IPK: <?= $ipk ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
