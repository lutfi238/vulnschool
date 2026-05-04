<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Dashboard Mahasiswa</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">
            Selamat datang, <?= esc(session()->get('full_name')) ?>
            <?php if ($mahasiswa): ?>
                &middot; NIM: <?= esc($mahasiswa['nim']) ?>
                &middot; <?= esc($mahasiswa['jurusan']) ?>
            <?php endif; ?>
        </p>
    </div>
    <div>
        <span class="badge bg-light text-dark border px-3 py-2" style="font-size:.78rem;">
            <i class="bi bi-calendar3 me-1"></i><?= date('l, d F Y') ?>
        </span>
    </div>
</div>

<!-- Statistik Cards -->
<div class="row g-3 mb-4">
    <!-- IPK -->
    <div class="col-xl-3 col-md-6">
        <div class="card vs-stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">IPK Sementara</div>
                        <div class="stat-value mt-1"><?= number_format($ipk, 2) ?></div>
                    </div>
                    <div class="stat-icon" style="background:rgba(46,125,50,0.1);color:var(--vs-success);">
                        <i class="bi bi-trophy-fill"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="progress" style="height:4px;">
                        <div class="progress-bar bg-success" style="width:<?= ($ipk / 4) * 100 ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total SKS -->
    <div class="col-xl-3 col-md-6">
        <div class="card vs-stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Total SKS</div>
                        <div class="stat-value mt-1"><?= esc($totalSks) ?></div>
                    </div>
                    <div class="stat-icon" style="background:rgba(26,35,126,0.1);color:var(--vs-primary);">
                        <i class="bi bi-journal-bookmark-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Mata Kuliah -->
    <div class="col-xl-3 col-md-6">
        <div class="card vs-stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Total Mata Kuliah</div>
                        <div class="stat-value mt-1"><?= esc($totalMk) ?></div>
                    </div>
                    <div class="stat-icon" style="background:rgba(2,136,209,0.1);color:var(--vs-info);">
                        <i class="bi bi-book-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Persentase Kehadiran -->
    <div class="col-xl-3 col-md-6">
        <div class="card vs-stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-label">Kehadiran</div>
                        <div class="stat-value mt-1"><?= $avgKehadiran ?>%</div>
                    </div>
                    <div class="stat-icon" style="background:rgba(255,143,0,0.1);color:var(--vs-warning);">
                        <i class="bi bi-calendar-check-fill"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="progress" style="height:4px;">
                        <?php
                        $barColor = $avgKehadiran >= 80 ? 'bg-success' : ($avgKehadiran >= 60 ? 'bg-warning' : 'bg-danger');
                        ?>
                        <div class="progress-bar <?= $barColor ?>" style="width:<?= $avgKehadiran ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Row -->
<div class="row g-3">
    <!-- Grafik Nilai + Tabel Nilai -->
    <div class="col-lg-8">
        <!-- Grafik Nilai -->
        <?php if (!empty($nilaiList)): ?>
        <div class="card vs-card mb-3">
            <div class="card-header">
                <span><i class="bi bi-bar-chart-line me-2"></i>Grafik Nilai Akhir per Mata Kuliah</span>
            </div>
            <div class="card-body">
                <canvas id="nilaiChart" height="200"></canvas>
            </div>
        </div>
        <?php endif; ?>

        <!-- Tabel Nilai -->
        <div class="card vs-card">
            <div class="card-header">
                <span><i class="bi bi-card-checklist me-2"></i>Daftar Nilai</span>
                <span class="badge bg-primary bg-opacity-10 text-primary"><?= $totalMk ?> MK</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table vs-table mb-0">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Mata Kuliah</th>
                                <th class="text-center">SKS</th>
                                <th class="text-center">Tugas</th>
                                <th class="text-center">UTS</th>
                                <th class="text-center">UAS</th>
                                <th class="text-center">Akhir</th>
                                <th class="text-center">Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($nilaiList)): ?>
                                <tr><td colspan="8" class="text-center text-muted py-3">Belum ada data nilai</td></tr>
                            <?php else: ?>
                                <?php foreach ($nilaiList as $n): ?>
                                    <tr>
                                        <td><code class="text-dark"><?= esc($n['kode_mk']) ?></code></td>
                                        <td><?= esc($n['nama_mk']) ?></td>
                                        <td class="text-center"><?= esc($n['sks']) ?></td>
                                        <td class="text-center"><?= number_format($n['nilai_tugas'], 1) ?></td>
                                        <td class="text-center"><?= number_format($n['nilai_uts'], 1) ?></td>
                                        <td class="text-center"><?= number_format($n['nilai_uas'], 1) ?></td>
                                        <td class="text-center fw-semibold"><?= number_format($n['nilai_akhir'], 1) ?></td>
                                        <td class="text-center">
                                            <?php
                                            $gradeClass = 'grade-' . $n['grade'][0]; // A, B, C, D, E
                                            ?>
                                            <span class="<?= $gradeClass ?>"><?= esc($n['grade']) ?></span>
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

    <!-- Pengumuman Terbaru -->
    <div class="col-lg-4">
        <div class="card vs-card">
            <div class="card-header">
                <span><i class="bi bi-megaphone me-2"></i>Pengumuman</span>
            </div>
            <div class="card-body">
                <?php if (empty($recentPengumuman)): ?>
                    <p class="text-muted text-center py-3 mb-0">Belum ada pengumuman</p>
                <?php else: ?>
                    <?php foreach ($recentPengumuman as $p): ?>
                        <div class="pengumuman-item">
                            <div class="title"><?= esc($p['judul']) ?></div>
                            <div class="meta mt-1">
                                <i class="bi bi-person me-1"></i><?= esc($p['author_name']) ?>
                                <span class="mx-1">&middot;</span>
                                <i class="bi bi-clock me-1"></i><?= date('d M Y', strtotime($p['created_at'])) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($mahasiswa): ?>
        <!-- Info Mahasiswa -->
        <div class="card vs-card mt-3">
            <div class="card-header">
                <span><i class="bi bi-person-vcard me-2"></i>Info Profil</span>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0" style="font-size:.83rem;">
                    <tr>
                        <td class="text-muted" style="width:100px;">NIM</td>
                        <td><code><?= esc($mahasiswa['nim']) ?></code></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Nama</td>
                        <td><?= esc($mahasiswa['nama']) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Jurusan</td>
                        <td><?= esc($mahasiswa['jurusan']) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Angkatan</td>
                        <td><?= esc($mahasiswa['angkatan']) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">No. HP</td>
                        <td><?= esc($mahasiswa['no_hp'] ?? '-') ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php if (!$mahasiswa): ?>
    <div class="alert alert-warning mt-4">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <strong>Perhatian:</strong> Profil mahasiswa Anda belum terdaftar di sistem.
        Hubungi administrator untuk menambahkan data profil mahasiswa.
    </div>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?php if (!empty($nilaiList)): ?>
<script>
    // Chart.js — Grafik Nilai Akhir per Mata Kuliah
    const ctx = document.getElementById('nilaiChart').getContext('2d');

    const labels = <?= json_encode(array_map(fn($n) => $n['kode_mk'] . ' - ' . $n['nama_mk'], $nilaiList)) ?>;
    const nilaiAkhir = <?= json_encode(array_map(fn($n) => (float) $n['nilai_akhir'], $nilaiList)) ?>;
    const grades = <?= json_encode(array_column($nilaiList, 'grade')) ?>;

    // Warna berdasarkan grade
    const colors = nilaiAkhir.map(val => {
        if (val >= 85) return 'rgba(46, 125, 50, 0.8)';   // A - hijau
        if (val >= 75) return 'rgba(2, 136, 209, 0.8)';    // B - biru
        if (val >= 60) return 'rgba(255, 143, 0, 0.8)';    // C - oranye
        return 'rgba(211, 47, 47, 0.8)';                   // D/E - merah
    });

    const borderColors = nilaiAkhir.map(val => {
        if (val >= 85) return 'rgba(46, 125, 50, 1)';
        if (val >= 75) return 'rgba(2, 136, 209, 1)';
        if (val >= 60) return 'rgba(255, 143, 0, 1)';
        return 'rgba(211, 47, 47, 1)';
    });

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Nilai Akhir',
                data: nilaiAkhir,
                backgroundColor: colors,
                borderColor: borderColors,
                borderWidth: 1,
                borderRadius: 6,
                barPercentage: 0.6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        afterLabel: function(context) {
                            return 'Grade: ' + grades[context.dataIndex];
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    grid: { color: 'rgba(0,0,0,0.04)' },
                    ticks: { font: { size: 11 } }
                },
                x: {
                    grid: { display: false },
                    ticks: {
                        font: { size: 10 },
                        maxRotation: 45,
                        minRotation: 0,
                        callback: function(value) {
                            const label = this.getLabelForValue(value);
                            return label.length > 20 ? label.substr(0, 18) + '...' : label;
                        }
                    }
                }
            }
        }
    });
</script>
<?php endif; ?>
<?= $this->endSection() ?>
