<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">System Logs</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">Viewer log aplikasi (Recon Target)</p>
    </div>
</div>

<div class="row g-4">
    <!-- Sidebar Logs List -->
    <div class="col-md-3">
        <div class="card vs-card">
            <div class="card-header">
                <span><i class="bi bi-list-ul me-2"></i>Daftar Log</span>
            </div>
            <div class="list-group list-group-flush">
                <?php if (empty($logs)): ?>
                    <div class="list-group-item text-muted text-center py-4">Belum ada log</div>
                <?php else: ?>
                    <?php foreach ($logs as $log): ?>
                        <a href="/admin/logs?file=<?= urlencode($log) ?>" class="list-group-item list-group-item-action <?= $selectedFile === $log ? 'active' : '' ?>">
                            <i class="bi bi-file-earmark-text me-2"></i><?= esc($log) ?>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Log Viewer -->
    <div class="col-md-9">
        <div class="card vs-card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>
                    <i class="bi bi-terminal me-2"></i>Isi Log: 
                    <span class="text-primary fw-bold"><?= esc($selectedFile ?? 'Pilih file log') ?></span>
                </span>
            </div>
            <div class="card-body bg-dark text-light p-0" style="min-height: 400px; max-height: 600px; overflow-y: auto;">
                <?php if ($selectedFile): ?>
                    <pre class="m-0 p-3" style="font-size: .85rem; font-family: 'Courier New', Courier, monospace; white-space: pre-wrap;"><?= htmlspecialchars($logContent) ?></pre>
                <?php else: ?>
                    <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                        Pilih file log di panel kiri untuk melihat isinya
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
