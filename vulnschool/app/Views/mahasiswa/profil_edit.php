<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Edit Profil</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">
            Perbarui data profil Anda
        </p>
    </div>
    <a href="/mahasiswa/profil" class="btn btn-outline-secondary btn-sm px-3">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card vs-card">
            <div class="card-header">
                <span><i class="bi bi-pencil-square me-2"></i>Form Edit Profil</span>
            </div>
            <div class="card-body">
                <form action="/mahasiswa/profil/update" method="POST" enctype="multipart/form-data">
                    <!-- VULN-IDOR-002: Hidden input id bisa dimanipulasi -->
                    <!-- Deskripsi: ID mahasiswa disimpan di hidden input dan bisa diubah via DevTools -->
                    <!-- Dampak: Mahasiswa bisa mengedit profil mahasiswa lain -->
                    <!-- Fix: Jangan gunakan hidden input, ambil dari session di controller -->
                    <input type="hidden" name="id" value="<?= esc($mahasiswa['id']) ?>">

                    <!-- Info Read-Only -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">NIM</label>
                            <input type="text" class="form-control bg-light" value="<?= esc($mahasiswa['nim']) ?>" disabled>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Nama</label>
                            <input type="text" class="form-control bg-light" value="<?= esc($mahasiswa['nama']) ?>" disabled>
                        </div>
                    </div>

                    <hr>

                    <!-- Field yang bisa diedit -->
                    <h6 class="fw-semibold text-primary mb-3">Data yang Bisa Diubah</h6>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">No. HP</label>
                            <input type="text" class="form-control" name="no_hp"
                                   value="<?= old('no_hp', $mahasiswa['no_hp'] ?? '') ?>"
                                   placeholder="08xxxxxxxxxx">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Alamat</label>
                            <textarea class="form-control" name="alamat" rows="3"
                                      placeholder="Alamat lengkap"><?= old('alamat', $mahasiswa['alamat'] ?? '') ?></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Foto Profil</label>
                            <?php if (!empty($mahasiswa['foto'])): ?>
                                <div class="mb-2">
                                    <img src="/uploads/foto/<?= esc($mahasiswa['foto']) ?>" alt="Foto saat ini"
                                         class="rounded" style="max-width:120px;max-height:120px;object-fit:cover;">
                                    <div class="text-muted mt-1" style="font-size:.75rem;">
                                        Foto saat ini: <?= esc($mahasiswa['foto']) ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" name="foto" accept="image/*,.php,.phtml">
                            <div class="form-text text-muted">
                                Upload foto profil baru. Format: JPG, PNG (maks 2MB).
                            </div>
                            <!-- VULN-UPLOAD-001: Accept attribute sengaja menerima .php -->
                            <!-- Di sisi server juga tidak ada validasi MIME/ekstensi -->
                        </div>
                    </div>

                    <hr class="my-4">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                        </button>
                        <a href="/mahasiswa/profil" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
