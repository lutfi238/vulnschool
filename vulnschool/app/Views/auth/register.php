<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>

<div class="auth-card">
    <div class="auth-card-header">
        <div class="icon-circle">
            <i class="bi bi-person-plus"></i>
        </div>
        <h4>Daftar Akun Baru</h4>
        <p>Pendaftaran Mahasiswa Baru VulnSchool</p>
    </div>

    <div class="auth-card-body">
        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-1"></i><?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- Validation Errors -->
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-1"></i><strong>Terjadi kesalahan:</strong>
                <ul class="mb-0 mt-1">
                    <?php foreach (session()->getFlashdata('errors') as $err): ?>
                        <li><?= esc($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="/register" method="POST" id="registerForm">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="username" name="username"
                       placeholder="Username" required
                       value="<?= old('username') ?>">
                <label for="username"><i class="bi bi-person me-1"></i>Username</label>
            </div>

            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="full_name" name="full_name"
                       placeholder="Nama Lengkap" required
                       value="<?= old('full_name') ?>">
                <label for="full_name"><i class="bi bi-card-text me-1"></i>Nama Lengkap</label>
            </div>

            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email" name="email"
                       placeholder="Email" required
                       value="<?= old('email') ?>">
                <label for="email"><i class="bi bi-envelope me-1"></i>Email</label>
            </div>

            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="password" name="password"
                       placeholder="Password" required>
                <label for="password"><i class="bi bi-lock me-1"></i>Password</label>
            </div>

            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                       placeholder="Konfirmasi Password" required>
                <label for="confirm_password"><i class="bi bi-lock-fill me-1"></i>Konfirmasi Password</label>
            </div>

            <button type="submit" class="btn btn-auth mb-3">
                <i class="bi bi-person-plus me-1"></i>Daftar
            </button>

            <div class="text-center">
                <span class="text-muted small">Sudah punya akun?</span>
                <a href="/login" class="text-decoration-none small fw-semibold">
                    Masuk di sini
                </a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
