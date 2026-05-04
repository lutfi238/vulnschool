<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Kelola Pengguna</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">Manajemen semua akun pengguna sistem</p>
    </div>
</div>

<div class="card vs-card mb-3">
    <div class="card-body py-2">
        <form action="/admin/users" method="GET" class="d-flex gap-2 align-items-center flex-wrap">
            <label class="fw-semibold text-nowrap" style="font-size:.85rem;">Filter:</label>
            <select name="role" class="form-select form-select-sm" style="max-width:150px;">
                <option value="">— Semua Role —</option>
                <option value="admin" <?= $filterRole === 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="dosen" <?= $filterRole === 'dosen' ? 'selected' : '' ?>>Dosen</option>
                <option value="mahasiswa" <?= $filterRole === 'mahasiswa' ? 'selected' : '' ?>>Mahasiswa</option>
            </select>
            <select name="status" class="form-select form-select-sm" style="max-width:150px;">
                <option value="">— Semua Status —</option>
                <option value="1" <?= $filterStatus === '1' ? 'selected' : '' ?>>Aktif</option>
                <option value="0" <?= $filterStatus === '0' ? 'selected' : '' ?>>Nonaktif</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm px-3">Filter</button>
            <?php if (!empty($filterRole) || $filterStatus !== null): ?>
                <a href="/admin/users" class="btn btn-outline-secondary btn-sm px-3">Reset</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<div class="card vs-card">
    <div class="card-header">
        <span><i class="bi bi-people me-2"></i>Data Pengguna</span>
        <span class="badge bg-primary"><?= count($users) ?></span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table vs-table mb-0">
                <thead>
                    <tr>
                        <th style="width:45px;">#</th>
                        <th>Username</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th class="text-center">Role</th>
                        <th class="text-center">Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada data pengguna</td></tr>
                    <?php else: ?>
                        <?php foreach ($users as $i => $u): ?>
                            <tr>
                                <td class="text-muted"><?= $i + 1 ?></td>
                                <td><code class="text-dark"><?= esc($u['username']) ?></code></td>
                                <td><?= esc($u['full_name']) ?></td>
                                <td style="font-size:.85rem;"><?= esc($u['email']) ?></td>
                                <td class="text-center">
                                    <?php
                                    $badge = match($u['role']) {
                                        'admin' => 'bg-danger',
                                        'dosen' => 'bg-info',
                                        'mahasiswa' => 'bg-success',
                                        default => 'bg-secondary'
                                    };
                                    ?>
                                    <span class="badge <?= $badge ?>"><?= ucfirst($u['role']) ?></span>
                                </td>
                                <td class="text-center">
                                    <?php if ($u['is_active']): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger bg-opacity-10 text-danger">Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex gap-1 justify-content-end">
                                        <a href="/admin/users/toggle/<?= $u['id'] ?>" class="btn btn-sm <?= $u['is_active'] ? 'btn-outline-warning' : 'btn-outline-success' ?>" title="<?= $u['is_active'] ? 'Nonaktifkan' : 'Aktifkan' ?>">
                                            <i class="bi <?= $u['is_active'] ? 'bi-lock' : 'bi-unlock' ?>"></i>
                                        </a>
                                        <a href="/admin/users/delete/<?= $u['id'] ?>" class="btn btn-sm btn-outline-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus user ini?')">
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
