<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Edit Pengumuman</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">Perbarui informasi pengumuman yang sudah ada</p>
    </div>
    <a href="/pengumuman/<?= $pengumuman['id'] ?>" class="btn btn-outline-secondary btn-sm px-3">
        <i class="bi bi-arrow-left me-1"></i>Batal
    </a>
</div>

<div class="card vs-card" style="max-width: 800px;">
    <div class="card-body p-4">
        <form action="/pengumuman/update/<?= $pengumuman['id'] ?>" method="POST">
            <div class="mb-3">
                <label class="form-label fw-semibold">Judul Pengumuman</label>
                <input type="text" class="form-control" name="judul" value="<?= esc($pengumuman['judul']) ?>" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-semibold">Isi Pengumuman</label>
                <!-- Editor sederhana textarea biasa sesuai instruksi (bukan WYSIWYG) -->
                <textarea class="form-control" name="isi" rows="10" required><?= esc($pengumuman['isi']) ?></textarea>
                <div class="form-text mt-2"><i class="bi bi-info-circle me-1"></i>Gunakan baris baru (Enter) untuk memisahkan paragraf.</div>
            </div>
            
            <div class="mb-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="is_published" name="is_published" value="1" <?= $pengumuman['is_published'] ? 'checked' : '' ?>>
                    <label class="form-check-label ms-2" for="is_published">
                        <span class="fw-semibold">Publikasikan</span><br>
                        <span class="text-muted" style="font-size:.8rem;">Jika dinonaktifkan, pengumuman akan disimpan sebagai draft.</span>
                    </label>
                </div>
            </div>
            
            <hr class="mb-4">
            
            <div class="d-flex justify-content-end gap-2">
                <a href="/pengumuman/<?= $pengumuman['id'] ?>" class="btn btn-light px-4">Batal</a>
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-2"></i>Update Pengumuman</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
