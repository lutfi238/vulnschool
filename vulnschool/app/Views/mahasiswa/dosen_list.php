<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Daftar Mahasiswa</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">
            Mahasiswa yang mengambil mata kuliah Anda — Total: <?= count($mahasiswa) ?> mahasiswa
        </p>
    </div>
</div>

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
                        <th>Email</th>
                        <th style="width:80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($mahasiswa)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size:2rem;"></i>
                                <p class="mb-0 mt-2">Belum ada mahasiswa yang terdaftar di mata kuliah Anda</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($mahasiswa as $i => $mhs): ?>
                            <tr>
                                <td class="text-muted"><?= $i + 1 ?></td>
                                <td><code class="text-dark"><?= esc($mhs['nim']) ?></code></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center"
                                             style="width:32px;height:32px;font-size:.75rem;font-weight:700;color:var(--vs-primary);">
                                            <?= strtoupper(substr($mhs['nama'], 0, 1)) ?>
                                        </div>
                                        <span class="fw-semibold" style="font-size:.85rem;"><?= esc($mhs['nama']) ?></span>
                                    </div>
                                </td>
                                <td style="font-size:.85rem;"><?= esc($mhs['jurusan']) ?></td>
                                <td class="text-center">
                                    <span class="badge bg-primary bg-opacity-10 text-primary"><?= esc($mhs['angkatan']) ?></span>
                                </td>
                                <td style="font-size:.85rem;"><?= esc($mhs['email'] ?? '-') ?></td>
                                <td>
                                    <a href="/dosen/mahasiswa/detail/<?= $mhs['id'] ?>" class="btn btn-outline-primary btn-sm" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
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
