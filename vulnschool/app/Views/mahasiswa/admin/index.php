<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Kelola Mahasiswa</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">
            Total: <?= esc($total) ?> mahasiswa terdaftar
        </p>
    </div>
    <a href="/admin/mahasiswa/create" class="btn btn-primary btn-sm px-3">
        <i class="bi bi-plus-lg me-1"></i>Tambah Mahasiswa
    </a>
</div>

<!-- Search -->
<div class="card vs-card mb-3">
    <div class="card-body py-2">
        <form action="/admin/mahasiswa" method="GET" class="d-flex gap-2">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control border-start-0" name="q"
                       placeholder="Cari nama, NIM, atau email..." value="<?= esc($keyword ?? '') ?>">
            </div>
            <button type="submit" class="btn btn-primary btn-sm px-3">Cari</button>
            <?php if ($keyword): ?>
                <a href="/admin/mahasiswa" class="btn btn-outline-secondary btn-sm px-3">Reset</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<?php if ($keyword): ?>
    <div class="alert alert-info py-2" style="font-size:.85rem;">
        <i class="bi bi-info-circle me-1"></i>Menampilkan hasil pencarian untuk: <strong>"<?= esc($keyword) ?>"</strong>
        — <?= count($mahasiswa) ?> data ditemukan
    </div>
<?php endif; ?>

<!-- Table -->
<div class="card vs-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table vs-table mb-0">
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Jurusan</th>
                        <th class="text-center">Angkatan</th>
                        <th>No. HP</th>
                        <th style="width:160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($mahasiswa)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size:2rem;"></i>
                                <p class="mb-0 mt-2">Tidak ada data mahasiswa</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($mahasiswa as $i => $mhs): ?>
                            <tr>
                                <td class="text-muted"><?= $i + 1 ?></td>
                                <td><code class="text-dark"><?= esc($mhs['nim']) ?></code></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php if (!empty($mhs['foto'])): ?>
                                            <img src="/uploads/foto/<?= esc($mhs['foto']) ?>" alt=""
                                                 class="rounded-circle" style="width:32px;height:32px;object-fit:cover;">
                                        <?php else: ?>
                                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center"
                                                 style="width:32px;height:32px;font-size:.75rem;font-weight:700;color:var(--vs-primary);">
                                                <?= strtoupper(substr($mhs['nama'], 0, 1)) ?>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <div class="fw-semibold" style="font-size:.85rem;"><?= esc($mhs['nama']) ?></div>
                                            <div class="text-muted" style="font-size:.72rem;"><?= esc($mhs['email'] ?? '') ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td style="font-size:.85rem;"><?= esc($mhs['jurusan']) ?></td>
                                <td class="text-center">
                                    <span class="badge bg-primary bg-opacity-10 text-primary"><?= esc($mhs['angkatan']) ?></span>
                                </td>
                                <td style="font-size:.85rem;"><?= esc($mhs['no_hp'] ?? '-') ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="/admin/mahasiswa/detail/<?= $mhs['id'] ?>" class="btn btn-outline-primary" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="/admin/mahasiswa/edit/<?= $mhs['id'] ?>" class="btn btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="/admin/mahasiswa/delete/<?= $mhs['id'] ?>"
                                           class="btn btn-outline-danger" title="Hapus"
                                           onclick="return confirm('Yakin ingin menghapus data mahasiswa ini?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if ($pager): ?>
        <div class="card-footer bg-transparent d-flex justify-content-center py-3">
            <?= $pager->links() ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
