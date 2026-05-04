<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><?= esc($title) ?></h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">
            <?= $isEdit ? 'Perbarui data mata kuliah' : 'Isi data mata kuliah baru' ?>
        </p>
    </div>
    <a href="/admin/mata-kuliah" class="btn btn-outline-secondary btn-sm px-3">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card vs-card">
            <div class="card-header">
                <span><i class="bi bi-book me-2"></i><?= esc($title) ?></span>
            </div>
            <div class="card-body">
                <form action="<?= $isEdit ? '/admin/mata-kuliah/update/' . $mk['id'] : '/admin/mata-kuliah/store' ?>"
                      method="POST">

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Kode MK</label>
                            <input type="text" class="form-control" name="kode_mk" required
                                   value="<?= old('kode_mk', $mk['kode_mk'] ?? '') ?>" placeholder="INF101">
                            <small class="text-muted">Kode unik mata kuliah</small>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Nama Mata Kuliah</label>
                            <input type="text" class="form-control" name="nama_mk" required
                                   value="<?= old('nama_mk', $mk['nama_mk'] ?? '') ?>" placeholder="Pemrograman Web">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">SKS</label>
                            <input type="number" class="form-control" name="sks" required min="1" max="6"
                                   value="<?= old('sks', $mk['sks'] ?? 3) ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Semester</label>
                            <input type="number" class="form-control" name="semester" required min="1" max="8"
                                   value="<?= old('semester', $mk['semester'] ?? 1) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Dosen Pengampu</label>
                            <select class="form-select" name="dosen_id" required>
                                <option value="">— Pilih Dosen —</option>
                                <?php foreach ($dosenList as $dsn): ?>
                                    <option value="<?= $dsn['id'] ?>"
                                        <?= old('dosen_id', $mk['dosen_id'] ?? '') == $dsn['id'] ? 'selected' : '' ?>>
                                        <?= esc($dsn['nama']) ?> (<?= esc($dsn['nip']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold" style="font-size:.85rem;">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" rows="3"
                                      placeholder="Deskripsi singkat mata kuliah"><?= old('deskripsi', $mk['deskripsi'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <hr class="my-4">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-lg me-1"></i><?= $isEdit ? 'Simpan Perubahan' : 'Tambah Mata Kuliah' ?>
                        </button>
                        <a href="/admin/mata-kuliah" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
