<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Pengumuman</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">
            Pusat informasi dan pengumuman kampus
        </p>
    </div>
    <?php if (in_array(session()->get('role'), ['admin', 'dosen'])): ?>
        <a href="/pengumuman/create" class="btn btn-primary btn-sm px-3">
            <i class="bi bi-plus-lg me-1"></i>Buat Pengumuman
        </a>
    <?php endif; ?>
</div>

<!-- Form Search -->
<div class="card vs-card mb-4">
    <div class="card-body py-2">
        <form action="/pengumuman" method="GET" class="d-flex gap-2">
            <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari pengumuman..." value="<?= esc($q ?? '') ?>">
            <button type="submit" class="btn btn-primary btn-sm px-3"><i class="bi bi-search"></i></button>
            <?php if (!empty($q)): ?>
                <a href="/pengumuman" class="btn btn-outline-secondary btn-sm px-3">Clear</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<?php if (!empty($q)): ?>
    <div class="alert alert-info py-2" style="font-size:.9rem;">
        <i class="bi bi-info-circle me-2"></i>
        <!-- VULN-XSS-002: Reflected XSS -->
        <!-- Deskripsi: Query pencarian ($q) ditampilkan langsung tanpa escape. -->
        <!-- Attacker bisa membagikan URL dengan payload XSS di parameter ?q= -->
        <!-- Fix: Gunakan esc() -> Hasil pencarian untuk: "<?= esc($q) ?>" -->
        Hasil pencarian untuk: "<b><?= $q ?></b>"
    </div>
<?php endif; ?>

<div class="row g-4">
    <?php if (empty($pengumumanList)): ?>
        <div class="col-12">
            <div class="card vs-card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-megaphone text-muted" style="font-size:3rem;"></i>
                    <h5 class="mt-3 text-muted">Belum Ada Pengumuman</h5>
                    <p class="text-muted">Pusat informasi saat ini masih kosong.</p>
                </div>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($pengumumanList as $p): ?>
            <div class="col-12">
                <div class="card vs-card h-100" style="transition:transform .2s;cursor:pointer;"
                     onmouseover="this.style.transform='translateY(-2px)'"
                     onmouseout="this.style.transform='translateY(0)'"
                     onclick="location.href='/pengumuman/<?= $p['id'] ?>'">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="fw-bold text-primary mb-0"><?= esc($p['judul']) ?></h5>
                            <?php if (!$p['is_published']): ?>
                                <span class="badge bg-warning text-dark"><i class="bi bi-eye-slash me-1"></i>Draft</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="d-flex align-items-center gap-3 text-muted mb-3" style="font-size:.8rem;">
                            <span><i class="bi bi-person-circle me-1"></i><?= esc($p['author_name']) ?></span>
                            <span><i class="bi bi-calendar3 me-1"></i><?= date('d M Y, H:i', strtotime($p['created_at'])) ?></span>
                        </div>
                        
                        <p class="card-text text-muted mb-0" style="font-size:.9rem;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                            <?= esc($p['isi']) ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
