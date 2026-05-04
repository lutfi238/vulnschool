<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Backup Database</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">Manajemen cadangan data sistem</p>
    </div>
    <a href="/admin/backup/generate" class="btn btn-primary btn-sm px-3">
        <i class="bi bi-database-down me-1"></i>Generate Backup Baru
    </a>
</div>

<div class="card vs-card">
    <div class="card-header">
        <span><i class="bi bi-folder-check me-2"></i>Riwayat Backup</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table vs-table mb-0">
                <thead>
                    <tr>
                        <th style="width:45px;">#</th>
                        <th>Nama File</th>
                        <th>Ukuran</th>
                        <th>Waktu Dibuat</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($backups)): ?>
                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada file backup</td></tr>
                    <?php else: ?>
                        <?php foreach ($backups as $i => $b): ?>
                            <tr>
                                <td class="text-muted"><?= $i + 1 ?></td>
                                <td><code class="text-dark"><?= esc($b['name']) ?></code></td>
                                <td><?= esc($b['size']) ?></td>
                                <td><?= esc($b['time']) ?></td>
                                <td class="text-end">
                                    <a href="<?= $b['url'] ?>" class="btn btn-sm btn-outline-primary" target="_blank">
                                        <i class="bi bi-download"></i> Unduh
                                    </a>
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
