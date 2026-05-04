<?php
/**
 * Partial: Topbar
 * Bar navigasi atas — breadcrumb, info user, tombol logout
 */
$role = session()->get('role');
$roleBadgeClass = match($role) {
    'admin'     => 'badge-role-admin',
    'dosen'     => 'badge-role-dosen',
    'mahasiswa' => 'badge-role-mahasiswa',
    default     => 'bg-secondary',
};
?>

<header class="vs-topbar">
    <div class="topbar-left">
        <button class="btn-sidebar-toggle" onclick="toggleSidebar()" aria-label="Toggle sidebar">
            <i class="bi bi-list"></i>
        </button>
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/dashboard"><i class="bi bi-house-door"></i></a></li>
                <?php if (isset($breadcrumb) && is_array($breadcrumb)): ?>
                    <?php foreach ($breadcrumb as $i => $crumb): ?>
                        <?php if ($i === array_key_last($breadcrumb)): ?>
                            <li class="breadcrumb-item active"><?= esc($crumb['label']) ?></li>
                        <?php else: ?>
                            <li class="breadcrumb-item">
                                <a href="<?= esc($crumb['url']) ?>"><?= esc($crumb['label']) ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="breadcrumb-item active"><?= esc($title ?? 'Dashboard') ?></li>
                <?php endif; ?>
            </ol>
        </nav>
    </div>

    <div class="topbar-right">
        <!-- Role badge -->
        <span class="badge <?= $roleBadgeClass ?> px-2 py-1" style="font-size:.72rem;">
            <?= esc(ucfirst($role)) ?>
        </span>

        <!-- User dropdown -->
        <div class="dropdown">
            <button class="btn btn-sm btn-light border-0 dropdown-toggle d-flex align-items-center gap-2"
                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <div style="width:30px;height:30px;border-radius:8px;background:var(--vs-primary);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.75rem;">
                    <?= strtoupper(substr(session()->get('full_name') ?? 'U', 0, 1)) ?>
                </div>
                <span class="d-none d-md-inline" style="font-size:.85rem;font-weight:500;">
                    <?= esc(session()->get('full_name')) ?>
                </span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="min-width:200px;">
                <li>
                    <div class="dropdown-item-text">
                        <div class="fw-semibold" style="font-size:.85rem;"><?= esc(session()->get('full_name')) ?></div>
                        <div class="text-muted" style="font-size:.75rem;"><?= esc(session()->get('email')) ?></div>
                    </div>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="/dashboard">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item text-danger" href="/logout">
                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</header>
