<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Rekap Absensi</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">
            <?= esc($mk['nama_mk']) ?> (<?= esc($mk['kode_mk']) ?>) — Ringkasan kehadiran semua mahasiswa
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="/dosen/absensi/input/<?= $mk['id'] ?>" class="btn btn-primary btn-sm px-3">
            <i class="bi bi-pencil-square me-1"></i>Input
        </a>
        <a href="/dosen/absensi" class="btn btn-outline-secondary btn-sm px-3">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>
</div>

<div class="card vs-card">
    <div class="card-header">
        <span><i class="bi bi-bar-chart me-2"></i>Rekap Kehadiran</span>
        <span class="badge bg-primary"><?= count($rekapList) ?> Mahasiswa</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table vs-table mb-0">
                <thead>
                    <tr>
                        <th style="width:45px;">#</th>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th class="text-center">Pertemuan</th>
                        <th class="text-center">Hadir</th>
                        <th class="text-center">Sakit</th>
                        <th class="text-center">Izin</th>
                        <th class="text-center">Alpha</th>
                        <th class="text-center" style="width:130px;">Kehadiran</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($rekapList)): ?>
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size:2rem;"></i>
                                <p class="mb-0 mt-2">Belum ada data absensi</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($rekapList as $i => $r): ?>
                            <?php
                            $pct = (float) $r['persentase'];
                            $barColor = $pct >= 80 ? '#2e7d32' : ($pct >= 60 ? '#ff8f00' : '#d32f2f');
                            $barBg    = $pct >= 80 ? 'rgba(46,125,50,0.12)' : ($pct >= 60 ? 'rgba(255,143,0,0.12)' : 'rgba(211,47,47,0.12)');
                            ?>
                            <tr>
                                <td class="text-muted"><?= $i + 1 ?></td>
                                <td><code class="text-dark"><?= esc($r['nim']) ?></code></td>
                                <td style="font-size:.85rem;" class="fw-semibold"><?= esc($r['nama']) ?></td>
                                <td class="text-center"><span class="badge bg-primary bg-opacity-10 text-primary"><?= $r['total_pertemuan'] ?></span></td>
                                <td class="text-center"><span class="badge bg-success bg-opacity-10 text-success"><?= $r['total_hadir'] ?></span></td>
                                <td class="text-center"><span class="badge bg-info bg-opacity-10 text-info"><?= $r['total_sakit'] ?></span></td>
                                <td class="text-center"><span class="badge bg-warning bg-opacity-10 text-warning"><?= $r['total_izin'] ?></span></td>
                                <td class="text-center"><span class="badge bg-danger bg-opacity-10 text-danger"><?= $r['total_alpha'] ?></span></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height:6px;border-radius:3px;">
                                            <div class="progress-bar" style="width:<?= $pct ?>%;background:<?= $barColor ?>;border-radius:3px;"></div>
                                        </div>
                                        <span class="fw-bold" style="font-size:.8rem;color:<?= $barColor ?>;min-width:40px;"><?= $pct ?>%</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <?php if ($pct >= 80): ?>
                                        <span class="badge px-2" style="background:rgba(46,125,50,0.12);color:#2e7d32;">✅ OK</span>
                                    <?php elseif ($pct >= 60): ?>
                                        <span class="badge px-2" style="background:rgba(255,143,0,0.12);color:#ff8f00;">⚠️ Perhatian</span>
                                    <?php else: ?>
                                        <span class="badge px-2" style="background:rgba(211,47,47,0.12);color:#d32f2f;">❌ Tidak OK</span>
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

<?= $this->endSection() ?>
