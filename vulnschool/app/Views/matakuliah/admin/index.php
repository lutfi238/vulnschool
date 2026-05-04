<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Kelola Mata Kuliah</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">
            Total: <?= count($mataKuliah) ?> mata kuliah terdaftar
        </p>
    </div>
    <a href="/admin/mata-kuliah/create" class="btn btn-primary btn-sm px-3">
        <i class="bi bi-plus-lg me-1"></i>Tambah Mata Kuliah
    </a>
</div>

<!-- Table -->
<div class="card vs-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table vs-table mb-0">
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th>Kode MK</th>
                        <th>Nama Mata Kuliah</th>
                        <th class="text-center">SKS</th>
                        <th>Dosen Pengampu</th>
                        <th class="text-center">Semester</th>
                        <th style="width:160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($mataKuliah)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size:2rem;"></i>
                                <p class="mb-0 mt-2">Tidak ada data mata kuliah</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($mataKuliah as $i => $mk): ?>
                            <tr>
                                <td class="text-muted"><?= $i + 1 ?></td>
                                <td><code class="text-dark"><?= esc($mk['kode_mk']) ?></code></td>
                                <td>
                                    <div class="fw-semibold" style="font-size:.85rem;"><?= esc($mk['nama_mk']) ?></div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary bg-opacity-10 text-primary"><?= esc($mk['sks']) ?></span>
                                </td>
                                <td style="font-size:.85rem;">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                                             style="width:28px;height:28px;font-size:.65rem;font-weight:700;color:#283593;background:rgba(57,73,171,0.12);">
                                            <?= strtoupper(substr($mk['nama_dosen'] ?? 'D', 0, 1)) ?>
                                        </div>
                                        <div>
                                            <span class="fw-medium"><?= esc($mk['nama_dosen'] ?? '-') ?></span>
                                            <div class="text-muted" style="font-size:.7rem;"><?= esc($mk['nip'] ?? '') ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success bg-opacity-10 text-success">Sem <?= esc($mk['semester']) ?></span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="/admin/mata-kuliah/detail/<?= $mk['id'] ?>" class="btn btn-outline-primary" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="/admin/mata-kuliah/edit/<?= $mk['id'] ?>" class="btn btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="/admin/mata-kuliah/delete/<?= $mk['id'] ?>"
                                           class="btn btn-outline-danger" title="Hapus"
                                           onclick="return confirm('Yakin ingin menghapus mata kuliah ini?')">
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
</div>

<?= $this->endSection() ?>
