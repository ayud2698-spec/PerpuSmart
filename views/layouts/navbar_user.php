<?php
$currentPage = $_GET['page'] ?? '';
$user = currentUser();
?>
<nav class="ps-navbar navbar navbar-expand-lg">
    <div class="container-xl">
        <a class="ps-navbar-brand" href="<?= BASE_URL ?>/index.php?page=user/dashboard">
            <div class="ps-brand-icon-sm">
                <svg class="ps-logo-mark" viewBox="0 0 32 32" width="26" height="26" aria-hidden="true"><rect x="5" y="10" width="5" height="16" rx="1.2" fill="currentColor"/><rect x="12" y="6" width="5" height="20" rx="1.2" fill="currentColor" opacity=".85"/><path d="M13.5 6h2v6l-1-.9-1 .9z" fill="#FBBF24"/><rect x="20" y="8" width="5" height="18" rx="1.2" fill="#FBBF24" transform="rotate(14 25 26)"/><rect x="3" y="27" width="26" height="2" rx="1" fill="currentColor"/></svg>
            </div>
            <span>PerpuSmart</span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#userNav">
            <i data-lucide="menu"></i>
        </button>

        <div class="collapse navbar-collapse" id="userNav">
            <ul class="navbar-nav mx-auto gap-1">
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>/index.php?page=user/dashboard"
                       class="ps-nav-link <?= $currentPage === 'user/dashboard' ? 'active' : '' ?>">
                        <i data-lucide="home"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>/index.php?page=user/buku"
                       class="ps-nav-link <?= str_starts_with($currentPage, 'user/buku') ? 'active' : '' ?>">
                        <i data-lucide="library"></i> Katalog Buku
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= BASE_URL ?>/index.php?page=user/peminjaman"
                       class="ps-nav-link <?= str_starts_with($currentPage, 'user/peminjaman') ? 'active' : '' ?>">
                        <i data-lucide="bookmark"></i> Peminjaman Saya
                    </a>
                </li>
            </ul>

            <div class="ps-navbar-user">
                <div class="ps-user-badge">
                    <div class="ps-user-avatar-sm"><?= strtoupper(substr($user['nama'], 0, 1)) ?></div>
                    <span class="ps-user-nm"><?= e($user['nama']) ?></span>
                    <span class="ps-user-chip">Pengguna</span>
                </div>
                <a href="<?= BASE_URL ?>/index.php?page=logout" class="ps-btn-logout"
                   onclick="return confirm('Yakin ingin logout?')">
                    <i data-lucide="log-out"></i>
                </a>
            </div>
        </div>
    </div>
</nav>