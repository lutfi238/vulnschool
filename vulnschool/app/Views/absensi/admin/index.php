<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Rekap Absensi</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">
            Seluruh data kehadiran mahasiswa
        </p>
    </div>
</div>

<!-- Filter -->
<div class="card vs-card mb-3">
    <div class="card-body py-2">
        <form action="/admin/absensi" method="GET" class="d-flex gap-2 align-items-center flex-wrap">
            <label class="fw-semibold text-nowrap" style="font-size:.85rem;">Filter:</label>
            <select name="mk" class="form-select form-select-sm" style="max-width:220px;">
                <option value="">— Semua Mata Kuliah —</option>
                <?php foreach ($mataKuliahAll as $m): ?>
                    <option value="<?= $m['id'] ?>" <?= $filterMk == $m['id'] ? 'selected' : '' ?>>
                        <?= esc($m['kode_mk'] . ' — ' . $m['nama_mk']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <select name="mhs" class="form-select form-select-sm" style="max-width:220px;">
                <option value="">— Semua Mahasiswa —</option>
                <?php foreach ($mahasiswaAll as $m): ?>
                    <option value="<?= $m['id'] ?>" <?= $filterMhs == $m['id'] ? 'selected' : '' ?>>
                        <?= esc($m['nim'] . ' — ' . $m['nama']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary btn-sm px-3">Filter</button>
            <?php if (!empty($filterMk) || !empty($filterMhs)): ?>
                <a href="/admin/absensi" class="btn btn-outline-secondary btn-sm px-3">Reset</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- Tabel -->
<div class="card vs-card">
    <div class="card-header">
        <span><i class="bi bi-table me-2"></i>Data Absensi</span>
        <span class="badge bg-primary"><?= count($absensiList) ?></span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table vs-table mb-0">
                <thead>
                    <tr>
                        <th style="width:45px;">#</th>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Mata Kuliah</th>
                        <th class="text-center">Pertemuan</th>
                        <th>Tanggal</th>
                        <th class="text-center">Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($absensiList)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size:2rem;"></i>
                                <p class="mb-0 mt-2">Tidak ada data absensi</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($absensiList as $i => $a): ?>
                            <?php
                            $sb = match($a['status']) {
                                'hadir' => ['bg' => 'rgba(46,125,50,0.12)', 'c' => '#2e7d32'],
                                'sakit' => ['bg' => 'rgba(2,136,209,0.12)', 'c' => '#0288d1'],
                                'izin'  => ['bg' => 'rgba(255,143,0,0.12)', 'c' => '#ff8f00'],
                                'alpha' => ['bg' => 'rgba(211,47,47,0.12)', 'c' => '#d32f2f'],
                                default => ['bg' => 'rgba(0,0,0,0.05)', 'c' => '#666'],
                            };
                            ?>
                            <tr>
                                <td class="text-muted"><?= $i + 1 ?></td>
                                <td><code class="text-dark"><?= esc($a['nim']) ?></code></td>
                                <td style="font-size:.85rem;"><?= esc($a['nama_mahasiswa']) ?></td>
                                <td style="font-size:.85rem;">
                                    <span class="badge bg-primary bg-opacity-10 text-primary" style="font-size:.7rem;"><?= esc($a['kode_mk']) ?></span>
                                    <?= esc($a['nama_mk']) ?>
                                </td>
                                <td class="text-center"><span class="badge bg-primary bg-opacity-10 text-primary"><?= esc($a['pertemuan']) ?></span></td>
                                <td style="font-size:.85rem;"><?= date('d M Y', strtotime($a['tanggal'])) ?></td>
                                <td class="text-center">
                                    <span class="badge px-2" style="background:<?= $sb['bg'] ?>;color:<?= $sb['c'] ?>;"><?= ucfirst($a['status']) ?></span>
                                </td>
                                <td style="font-size:.85rem;" class="text-muted"><?= esc($a['keterangan'] ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
