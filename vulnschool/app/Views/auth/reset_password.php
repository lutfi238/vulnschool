<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>

<div class="auth-card">
    <div class="auth-card-header">
        <div class="icon-circle">
            <i class="bi bi-shield-lock"></i>
        </div>
        <h4>Reset Password</h4>
        <p>Masukkan password baru Anda</p>
    </div>

    <div class="auth-card-body">
        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-1"></i><?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <i class="bi bi-check-circle me-1"></i><?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <form action="/reset-password" method="POST" id="resetForm">
            <input type="hidden" name="token" value="<?= esc($token ?? '') ?>">

            <div class="alert alert-info small">
                <i class="bi bi-info-circle me-1"></i>
                <strong>Token:</strong> <code><?= esc($token ?? 'tidak ada') ?></code>
            </div>

            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="password" name="password"
                       placeholder="Password Baru" required>
                <label for="password"><i class="bi bi-lock me-1"></i>Password Baru</label>
            </div>

            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                       placeholder="Konfirmasi Password" required>
                <label for="confirm_password"><i class="bi bi-lock-fill me-1"></i>Konfirmasi Password</label>
            </div>

            <button type="submit" class="btn btn-auth mb-3">
                <i class="bi bi-check-lg me-1"></i>Reset Password
            </button>

            <div class="text-center">
                <a href="/login" class="text-decoration-none small text-muted">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke login
                </a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
