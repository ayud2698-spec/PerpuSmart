<?php
$currentPage = $_GET['page'] ?? '';
$user = currentUser();
?>
<!-- ── Sidebar Admin ─────────────────────────────────── -->
<aside class="ps-sidebar" id="adminSidebar">
    <div class="ps-sidebar-brand">
        <a href="<?= BASE_URL ?>/index.php?page=admin/dashboard" class="ps-brand-link">
            <div class="ps-brand-icon">
                <svg class="ps-logo-mark" viewBox="0 0 32 32" width="26" height="26" aria-hidden="true"><rect x="5" y="10" width="5" height="16" rx="1.2" fill="currentColor"/><rect x="12" y="6" width="5" height="20" rx="1.2" fill="currentColor" opacity=".85"/><path d="M13.5 6h2v6l-1-.9-1 .9z" fill="#FBBF24"/><rect x="20" y="8" width="5" height="18" rx="1.2" fill="#FBBF24" transform="rotate(14 25 26)"/><rect x="3" y="27" width="26" height="2" rx="1" fill="currentColor"/></svg>
            </div>
            <div class="ps-brand-text">
                <span class="ps-brand-name">PerpuSmart</span>
                <span class="ps-brand-role">Administrator</span>
            </div>
        </a>
    </div>

    <nav class="ps-sidebar-nav">
        <div class="ps-nav-section">
            <span class="ps-nav-label">Menu Utama</span>
            <a href="<?= BASE_URL ?>/index.php?page=admin/dashboard"
               class="ps-nav-item <?= $currentPage === 'admin/dashboard' ? 'active' : '' ?>">
                <i data-lucide="layout-dashboard"></i>
                <span>Dashboard</span>
            </a>
            <a href="<?= BASE_URL ?>/index.php?page=admin/buku"
               class="ps-nav-item <?= str_starts_with($currentPage, 'admin/buku') ? 'active' : '' ?>">
                <i data-lucide="library"></i>
                <span>Kelola Buku</span>
            </a>
            <a href="<?= BASE_URL ?>/index.php?page=admin/peminjaman"
               class="ps-nav-item <?= str_starts_with($currentPage, 'admin/peminjaman') ? 'active' : '' ?>">
                <i data-lucide="arrow-left-right"></i>
                <span>Transaksi Peminjaman</span>
            </a>
            <a href="<?= BASE_URL ?>/index.php?page=admin/users"
               class="ps-nav-item <?= $currentPage === 'admin/users' ? 'active' : '' ?>">
                <i data-lucide="users"></i>
                <span>Pengguna</span>
            </a>
        </div>

        <div class="ps-nav-section mt-auto">
            <span class="ps-nav-label">Akun</span>
            <div class="ps-nav-user">
                <div class="ps-user-avatar">
                    <?= strtoupper(substr($user['nama'], 0, 1)) ?>
                </div>
                <div class="ps-user-info">
                    <span class="ps-user-name"><?= e($user['nama']) ?></span>
                    <span class="ps-user-role">Admin</span>
                </div>
            </div>
            <a href="<?= BASE_URL ?>/index.php?page=logout" class="ps-nav-item ps-nav-logout"
               onclick="return confirm('Yakin ingin logout?')">
                <i data-lucide="log-out"></i>
                <span>Logout</span>
            </a>
        </div>
    </nav>
</aside>