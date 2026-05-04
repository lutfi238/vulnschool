<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><?= esc($title) ?></h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">
            <?= $isEdit ? 'Perbarui data dosen' : 'Isi data dosen baru' ?>
        </p>
    </div>
    <a href="/admin/dosen" class="btn btn-outline-secondary btn-sm px-3">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card vs-card">
            <div class="card-header">
                <span><i class="bi bi-person-workspace me-2"></i><?= esc($title) ?></span>
            </div>
            <div class="card-body">
                <form action="<?= $isEdit ? '/admin/dosen/update/' . $dosen['id'] : '/admin/dosen/store' ?>"
                      method="POST">

                    <?php if (!$isEdit): ?>
                    <!-- Data Akun (hanya saat create) -->
                    <h6 class="fw-semibold text-primary mb-3"><i class="bi bi-key me-1"></i>Data Akun</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Username</label>
                            <input type="text" class="form-control" name="username" required
                                   value="<?= old('username') ?>" placeholder="dosenXXX">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Password</label>
                            <input type="text" class="form-control" name="password" required
                                   value="<?= old('password', 'dosen123') ?>" placeholder="Password">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Email</label>
                            <input type="email" class="form-control" name="email" required
                                   value="<?= old('email') ?>" placeholder="email@vulnschool.ac.id">
                        </div>
                    </div>
                    <hr class="my-3">
                    <?php endif; ?>

                    <!-- Data Dosen -->
                    <h6 class="fw-semibold text-primary mb-3"><i class="bi bi-person me-1"></i>Data Dosen</h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">NIP</label>
                            <input type="text" class="form-control" name="nip" required
                                   value="<?= old('nip', $dosen['nip'] ?? '') ?>" placeholder="19XX...">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Nama Lengkap</label>
                            <input type="text" class="form-control" name="nama" required
                                   value="<?= old('nama', $dosen['nama'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Bidang Keahlian</label>
                            <input type="text" class="form-control" name="bidang_keahlian"
                                   value="<?= old('bidang_keahlian', $dosen['bidang_keahlian'] ?? '') ?>"
                                   placeholder="Contoh: Jaringan Komputer">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">No. HP</label>
                            <input type="text" class="form-control" name="no_hp"
                                   value="<?= old('no_hp', $dosen['no_hp'] ?? '') ?>" placeholder="08xxxx">
                        </div>

                        <?php if ($isEdit): ?>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Email</label>
                            <input type="email" class="form-control" name="email"
                                   value="<?= old('email', $dosen['email'] ?? '') ?>">
                        </div>
                        <?php endif; ?>
                    </div>

                    <hr class="my-4">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-lg me-1"></i><?= $isEdit ? 'Simpan Perubahan' : 'Tambah Dosen' ?>
                        </button>
                        <a href="/admin/dosen" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
