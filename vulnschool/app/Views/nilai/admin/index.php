<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Daftar Nilai Mahasiswa</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">
            Seluruh data nilai mahasiswa (read-only)
        </p>
    </div>
</div>

<div class="card vs-card">
    <div class="card-header">
        <span><i class="bi bi-table me-2"></i>Rekap Nilai</span>
        <span class="badge bg-primary"><?= count($nilaiList) ?></span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table vs-table mb-0">
                <thead>
                    <tr>
                        <th style="width:45px;">#</th>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Kode MK</th>
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
                        <tr>
                            <td colspan="11" class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size:2rem;"></i>
                                <p class="mb-0 mt-2">Tidak ada data nilai</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($nilaiList as $i => $n): ?>
                            <?php
                            $gc = match(($n['grade'] ?? '')[0] ?? '') {
                                'A' => '#2e7d32', 'B' => '#0288d1', 'C' => '#ff8f00', default => '#d32f2f'
                            };
                            $gb = match(($n['grade'] ?? '')[0] ?? '') {
                                'A' => 'rgba(46,125,50,0.12)', 'B' => 'rgba(2,136,209,0.12)',
                                'C' => 'rgba(255,143,0,0.12)', default => 'rgba(211,47,47,0.12)'
                            };
                            ?>
                            <tr>
                                <td class="text-muted"><?= $i + 1 ?></td>
                                <td><code class="text-dark"><?= esc($n['nim']) ?></code></td>
                                <td style="font-size:.85rem;"><?= esc($n['nama_mahasiswa']) ?></td>
                                <td><code class="text-dark"><?= esc($n['kode_mk']) ?></code></td>
                                <td style="font-size:.85rem;"><?= esc($n['nama_mk']) ?></td>
                                <td class="text-center"><span class="badge bg-primary bg-opacity-10 text-primary"><?= esc($n['sks']) ?></span></td>
                                <td class="text-center" style="font-size:.85rem;"><?= esc($n['nilai_tugas'] ?? '-') ?></td>
                                <td class="text-center" style="font-size:.85rem;"><?= esc($n['nilai_uts'] ?? '-') ?></td>
                                <td class="text-center" style="font-size:.85rem;"><?= esc($n['nilai_uas'] ?? '-') ?></td>
                                <td class="text-center fw-bold" style="font-size:.85rem;"><?= esc($n['nilai_akhir'] ?? '-') ?></td>
                                <td class="text-center">
                                    <span class="badge px-2" style="background:<?= $gb ?>;color:<?= $gc ?>;"><?= esc($n['grade'] ?? '-') ?></span>
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
