<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Edit Profil</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">
            Perbarui data profil Anda
        </p>
    </div>
    <a href="/dosen/profil" class="btn btn-outline-secondary btn-sm px-3">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card vs-card">
            <div class="card-header">
                <span><i class="bi bi-pencil me-2"></i>Edit Profil Dosen</span>
            </div>
            <div class="card-body">
                <form action="/dosen/profil/update" method="POST">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">NIP</label>
                            <input type="text" class="form-control bg-light" value="<?= esc($dosen['nip']) ?>" readonly>
                            <small class="text-muted">NIP tidak bisa diubah</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Nama Lengkap</label>
                            <input type="text" class="form-control bg-light" value="<?= esc($dosen['nama']) ?>" readonly>
                            <small class="text-muted">Hubungi admin untuk ubah nama</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Bidang Keahlian</label>
                            <input type="text" class="form-control" name="bidang_keahlian"
                                   value="<?= old('bidang_keahlian', $dosen['bidang_keahlian'] ?? '') ?>"
                                   placeholder="Contoh: Jaringan Komputer">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">No. HP</label>
                            <input type="text" class="form-control" name="no_hp"
                                   value="<?= old('no_hp', $dosen['no_hp'] ?? '') ?>" placeholder="08xxxx">
                        </div>
                    </div>

                    <hr class="my-4">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                        </button>
                        <a href="/dosen/profil" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
