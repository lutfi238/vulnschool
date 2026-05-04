<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Kelola Dosen</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">
            Total: <?= esc($total ?? count($dosenList)) ?> dosen terdaftar
        </p>
    </div>
    <a href="/admin/dosen/create" class="btn btn-primary btn-sm px-3">
        <i class="bi bi-plus-lg me-1"></i>Tambah Dosen
    </a>
</div>

<!-- Search -->
<div class="card vs-card mb-3">
    <div class="card-body py-2">
        <form action="/admin/dosen" method="GET" class="d-flex gap-2">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control border-start-0" name="q"
                       placeholder="Cari nama, NIP, atau email..." value="<?= esc($keyword ?? '') ?>">
            </div>
            <button type="submit" class="btn btn-primary btn-sm px-3">Cari</button>
            <?php if (!empty($keyword)): ?>
                <a href="/admin/dosen" class="btn btn-outline-secondary btn-sm px-3">Reset</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<?php if (!empty($keyword)): ?>
    <div class="alert alert-info py-2" style="font-size:.85rem;">
        <i class="bi bi-info-circle me-1"></i>Menampilkan hasil pencarian untuk: <strong>"<?= esc($keyword) ?>"</strong>
        — <?= count($dosenList) ?> data ditemukan
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
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Bidang Keahlian</th>
                        <th>No. HP</th>
                        <th style="width:160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($dosenList)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size:2rem;"></i>
                                <p class="mb-0 mt-2">Tidak ada data dosen</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($dosenList as $i => $dsn): ?>
                            <tr>
                                <td class="text-muted"><?= $i + 1 ?></td>
                                <td><code class="text-dark"><?= esc($dsn['nip']) ?></code></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-indigo bg-opacity-10 d-flex align-items-center justify-content-center"
                                             style="width:32px;height:32px;font-size:.75rem;font-weight:700;color:var(--vs-primary);background:rgba(57,73,171,0.12);">
                                            <?= strtoupper(substr($dsn['nama'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div class="fw-semibold" style="font-size:.85rem;"><?= esc($dsn['nama']) ?></div>
                                            <div class="text-muted" style="font-size:.72rem;"><?= esc($dsn['email'] ?? '') ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td style="font-size:.85rem;"><?= esc($dsn['bidang_keahlian'] ?? '-') ?></td>
                                <td style="font-size:.85rem;"><?= esc($dsn['no_hp'] ?? '-') ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="/admin/dosen/detail/<?= $dsn['id'] ?>" class="btn btn-outline-primary" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="/admin/dosen/edit/<?= $dsn['id'] ?>" class="btn btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="/admin/dosen/delete/<?= $dsn['id'] ?>"
                                           class="btn btn-outline-danger" title="Hapus"
                                           onclick="return confirm('Yakin ingin menghapus data dosen ini?')">
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

    <?php if (!empty($pager)): ?>
        <div class="card-footer bg-transparent d-flex justify-content-center py-3">
            <?= $pager->links() ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
