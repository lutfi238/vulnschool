<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="/pengumuman" class="btn btn-outline-secondary btn-sm px-3">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
    <?php if (session()->get('role') === 'admin' || session()->get('user_id') == $pengumuman['author_id']): ?>
        <div class="d-flex gap-2">
            <a href="/pengumuman/edit/<?= $pengumuman['id'] ?>" class="btn btn-outline-primary btn-sm px-3">
                <i class="bi bi-pencil-square me-1"></i>Edit
            </a>
            <a href="/pengumuman/delete/<?= $pengumuman['id'] ?>" class="btn btn-outline-danger btn-sm px-3" onclick="return confirm('Yakin ingin menghapus pengumuman ini?')">
                <i class="bi bi-trash me-1"></i>Hapus
            </a>
        </div>
    <?php endif; ?>
</div>

<div class="card vs-card mb-4">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <h3 class="fw-bold mb-0 text-primary"><?= esc($pengumuman['judul']) ?></h3>
            <?php if (!$pengumuman['is_published']): ?>
                <span class="badge bg-warning text-dark px-3 py-2">DRAFT</span>
            <?php endif; ?>
        </div>
        
        <div class="d-flex align-items-center gap-3 text-muted mb-4 pb-3 border-bottom" style="font-size:.85rem;">
            <span><i class="bi bi-person-circle me-1"></i><?= esc($pengumuman['author_name']) ?></span>
            <span><i class="bi bi-calendar3 me-1"></i><?= date('l, d F Y - H:i', strtotime($pengumuman['created_at'])) ?></span>
            <span><i class="bi bi-chat-dots me-1"></i><?= $pengumuman['jumlah_komentar'] ?? 0 ?> Komentar</span>
        </div>
        
        <div class="pengumuman-content" style="white-space: pre-wrap; font-size:.95rem; line-height:1.6;">
            <?= esc($pengumuman['isi']) ?>
        </div>
    </div>
</div>

<!-- Section Komentar -->
<div class="card vs-card">
    <div class="card-header bg-transparent border-bottom-0 pt-4 pb-2">
        <h6 class="fw-bold mb-0"><i class="bi bi-chat-right-text me-2"></i>Komentar (<?= count($komentarList) ?>)</h6>
    </div>
    <div class="card-body">
        
        <!-- Form Tambah Komentar -->
        <div class="d-flex gap-3 mb-4 p-3 bg-light rounded-3 border">
            <div class="avatar-sm rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width:36px;height:36px;">
                <?= strtoupper(substr(session()->get('full_name') ?? 'U', 0, 1)) ?>
            </div>
            <div class="flex-grow-1">
                <form action="/pengumuman/<?= $pengumuman['id'] ?>/komentar" method="POST">
                    <div class="form-floating mb-2">
                        <textarea class="form-control" name="isi_komentar" id="isiKomentar" placeholder="Tulis komentar..." style="height: 80px" required></textarea>
                        <label for="isiKomentar">Tambahkan komentar sebagai <?= esc(session()->get('full_name')) ?>...</label>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary btn-sm px-4">Kirim</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Daftar Komentar -->
        <div class="komentar-list mt-2">
            <?php if (empty($komentarList)): ?>
                <div class="text-center text-muted py-3" style="font-size:.85rem;">
                    Belum ada komentar. Jadilah yang pertama berkomentar!
                </div>
            <?php else: ?>
                <?php foreach ($komentarList as $k): ?>
                    <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                        <div class="avatar-sm rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width:36px;height:36px;">
                            <?= strtoupper(substr($k['full_name'], 0, 1)) ?>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <div>
                                    <span class="fw-bold" style="font-size:.9rem;"><?= esc($k['full_name']) ?></span>
                                    <span class="text-muted ms-2" style="font-size:.75rem;"><i class="bi bi-clock me-1"></i><?= date('d M Y H:i', strtotime($k['created_at'])) ?></span>
                                </div>
                                <?php if (session()->get('role') === 'admin' || session()->get('user_id') == $k['user_id']): ?>
                                    <a href="/pengumuman/komentar/delete/<?= $k['id'] ?>" class="text-danger" style="font-size:.8rem;" onclick="return confirm('Hapus komentar ini?')">
                                        <i class="bi bi-trash"></i> Hapus
                                    </a>
                                <?php endif; ?>
                            </div>
                            
                            <!-- VULN-XSS-001: Stored Cross-Site Scripting -->
                            <!-- Deskripsi: Komentar pengumuman ditampilkan tanpa output encoding. -->
                            <!--            Attacker bisa inject JavaScript yang dieksekusi di browser -->
                            <!--            setiap user yang membuka halaman. -->
                            <!-- Dampak: Cookie stealing, session hijacking, defacement, redirect ke phishing -->
                            <!-- Reproduksi: Login user manapun -> buka pengumuman -> komentar dengan payload: -->
                            <!--             <script>alert('XSS by '+document.cookie)</script> -->
                            <!--             Lalu logout, login user lain -> buka pengumuman yang sama -->
                            <!--             -> script tereksekusi di browser user lain -->
                            <!-- Fix: Gunakan esc() helper CodeIgniter: -->
                            <!--      <?= esc($k['isi_komentar']) ?> -->
                            <!--      Atau aktifkan auto-escape di config -->
                            
                            <div class="text-dark" style="font-size:.9rem; white-space: pre-wrap;">
                                <?= $k['isi_komentar'] ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
    </div>
</div>

<?= $this->endSection() ?>
