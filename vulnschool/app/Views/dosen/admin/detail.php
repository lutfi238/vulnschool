<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Detail Dosen</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">
            Informasi lengkap data dosen
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="/admin/dosen/edit/<?= $dosen['id'] ?>" class="btn btn-warning btn-sm px-3">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
        <a href="/admin/dosen" class="btn btn-outline-secondary btn-sm px-3">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Profil Dosen -->
    <div class="col-lg-5">
        <div class="card vs-card">
            <div class="card-header">
                <span><i class="bi bi-person-workspace me-2"></i>Profil Dosen</span>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center mx-auto"
                         style="width:80px;height:80px;font-size:2rem;font-weight:700;color:var(--vs-primary);">
                        <?= strtoupper(substr($dosen['nama'], 0, 1)) ?>
                    </div>
                    <h5 class="fw-bold mt-3 mb-1"><?= esc($dosen['nama']) ?></h5>
                    <span class="badge bg-indigo bg-opacity-10 text-primary"><?= esc($dosen['nip']) ?></span>
                </div>

                <table class="table table-borderless" style="font-size:.85rem;">
                    <tr>
                        <td class="text-muted" style="width:140px;">NIP</td>
                        <td class="fw-semibold"><?= esc($dosen['nip']) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Nama</td>
                        <td class="fw-semibold"><?= esc($dosen['nama']) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Email</td>
                        <td class="fw-semibold"><?= esc($dosen['email'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Username</td>
                        <td class="fw-semibold"><?= esc($dosen['username'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Bidang Keahlian</td>
                        <td class="fw-semibold"><?= esc($dosen['bidang_keahlian'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">No. HP</td>
                        <td class="fw-semibold"><?= esc($dosen['no_hp'] ?? '-') ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Mata Kuliah Diampu -->
    <div class="col-lg-7">
        <div class="card vs-card">
            <div class="card-header">
                <span><i class="bi bi-book me-2"></i>Mata Kuliah Diampu</span>
                <span class="badge bg-primary"><?= count($mataKuliah) ?></span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table vs-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Kode MK</th>
                                <th>Nama MK</th>
                                <th class="text-center">SKS</th>
                                <th class="text-center">Semester</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($mataKuliah)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox" style="font-size:1.5rem;"></i>
                                        <p class="mb-0 mt-1" style="font-size:.85rem;">Belum ada mata kuliah</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($mataKuliah as $i => $mk): ?>
                                    <tr>
                                        <td class="text-muted"><?= $i + 1 ?></td>
                                        <td><code class="text-dark"><?= esc($mk['kode_mk']) ?></code></td>
                                        <td style="font-size:.85rem;"><?= esc($mk['nama_mk']) ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-primary bg-opacity-10 text-primary"><?= esc($mk['sks']) ?></span>
                                        </td>
                                        <td class="text-center" style="font-size:.85rem;">Semester <?= esc($mk['semester']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
