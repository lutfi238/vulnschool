<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>

<div class="auth-card">
    <div class="auth-card-header">
        <div class="icon-circle">
            <i class="bi bi-question-circle"></i>
        </div>
        <h4>Lupa Password</h4>
        <p>Masukkan username untuk mendapatkan token reset</p>
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

        <!-- Token result (setelah submit) -->
        <?php if (isset($resetToken)): ?>
            <div class="alert alert-warning">
                <i class="bi bi-key me-1"></i><strong>Token Reset Password:</strong>
                <div class="mt-2">
                    <code class="d-block p-2 bg-light rounded" style="word-break: break-all;">
                        <?= esc($resetToken) ?>
                    </code>
                </div>
                <hr>
                <a href="/reset-password?token=<?= esc($resetToken) ?>" class="btn btn-sm btn-warning">
                    <i class="bi bi-arrow-right me-1"></i>Gunakan Token Ini
                </a>
            </div>
        <?php endif; ?>

        <form action="/forgot-password" method="POST" id="forgotForm">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="username" name="username"
                       placeholder="Username" required
                       value="<?= old('username') ?>">
                <label for="username"><i class="bi bi-person me-1"></i>Username</label>
            </div>

            <button type="submit" class="btn btn-auth mb-3">
                <i class="bi bi-key me-1"></i>Dapatkan Token Reset
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
