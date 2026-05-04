<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>

<div class="auth-card">
    <div class="auth-card-header">
        <div class="icon-circle">
            <i class="bi bi-box-arrow-in-right"></i>
        </div>
        <h4>Masuk ke VulnSchool</h4>
        <p>Sistem Akademik Politeknik Negeri Pontianak</p>
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
        <?php if (session()->getFlashdata('info')): ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-1"></i><?= session()->getFlashdata('info') ?>
            </div>
        <?php endif; ?>

        <form action="/login" method="POST" id="loginForm">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="username" name="username"
                       placeholder="Username" required autofocus
                       value="<?= old('username') ?>">
                <label for="username"><i class="bi bi-person me-1"></i>Username</label>
            </div>

            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="password" name="password"
                       placeholder="Password" required>
                <label for="password"><i class="bi bi-lock me-1"></i>Password</label>
            </div>

            <button type="submit" class="btn btn-auth mb-3">
                <i class="bi bi-box-arrow-in-right me-1"></i>Masuk
            </button>

            <div class="text-center">
                <a href="/forgot-password" class="text-decoration-none small text-muted">
                    <i class="bi bi-question-circle me-1"></i>Lupa password?
                </a>
            </div>

            <hr>

            <div class="text-center">
                <span class="text-muted small">Belum punya akun?</span>
                <a href="/register" class="text-decoration-none small fw-semibold">
                    Daftar di sini
                </a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
