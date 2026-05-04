<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">System Information</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">Informasi detail tentang server dan perangkat lunak</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card vs-card h-100">
            <div class="card-header">
                <span><i class="bi bi-server me-2"></i>Server & PHP</span>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <tbody>
                        <tr><td class="text-muted" style="width:150px;">Operating System</td><td class="fw-semibold"><?= esc($info['server_os']) ?></td></tr>
                        <tr><td class="text-muted">PHP Version</td><td class="fw-semibold"><?= esc($info['php_version']) ?></td></tr>
                        <tr><td class="text-muted">Server API</td><td class="fw-semibold"><?= esc($info['server_sapi']) ?></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card vs-card h-100">
            <div class="card-header">
                <span><i class="bi bi-database me-2"></i>Database & Framework</span>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <tbody>
                        <tr><td class="text-muted" style="width:150px;">Database Driver</td><td class="fw-semibold"><?= esc($info['db_driver']) ?></td></tr>
                        <tr><td class="text-muted">Database Version</td><td class="fw-semibold"><?= esc($info['db_version']) ?></td></tr>
                        <tr><td class="text-muted">CodeIgniter</td><td class="fw-semibold"><?= esc($info['ci_version']) ?></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
