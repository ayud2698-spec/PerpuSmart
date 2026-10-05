<?php require_once BASE_PATH . '/views/layouts/header.php'; ?>

<div class="ps-admin-layout">
    <?php require_once BASE_PATH . '/views/layouts/sidebar_admin.php'; ?>

    <main class="ps-admin-main">
        <!-- Topbar -->
        <header class="ps-topbar">
            <div class="ps-topbar-left">
                <button class="ps-sidebar-toggle" id="sidebarToggle">
                    <i data-lucide="panel-left-close"></i>
                </button>
                <div>
                    <h1 class="ps-page-title">Dashboard</h1>
                    <p class="ps-page-subtitle">Selamat datang, <?= e(currentUser()['nama']) ?></p>
                </div>
            </div>
            <div class="ps-topbar-right">
                <span class="ps-topbar-date"><?= date('l, d F Y') ?></span>
            </div>
        </header>

        <div class="ps-admin-content">
            <?php if ($success): ?>
                <div class="ps-alert ps-alert-success mb-3">
                    <i data-lucide="circle-check"></i><div><?= $success ?></div>
                </div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="ps-alert ps-alert-danger mb-3">
                    <i data-lucide="circle-alert"></i><div><?= $error ?></div>
                </div>
            <?php endif; ?>

            <!-- Stat Cards -->
            <div class="ps-stats-grid">
                <div class="ps-stat-card ps-stat-primary">
                    <div class="ps-stat-icon">
                        <i data-lucide="library"></i>
                    </div>
                    <div class="ps-stat-body">
                        <div class="ps-stat-number"><?= number_format($totalBuku) ?></div>
                        <div class="ps-stat-label">Total Koleksi Buku</div>
                    </div>
                    <div class="ps-stat-deco"></div>
                </div>

                <div class="ps-stat-card ps-stat-success">
                    <div class="ps-stat-icon">
                        <i data-lucide="check-circle"></i>
                    </div>
                    <div class="ps-stat-body">
                        <div class="ps-stat-number"><?= number_format($bukuTersedia) ?></div>
                        <div class="ps-stat-label">Buku Tersedia</div>
                    </div>
                    <div class="ps-stat-deco"></div>
                </div>

                <div class="ps-stat-card ps-stat-danger">
                    <div class="ps-stat-icon">
                        <i data-lucide="x-circle"></i>
                    </div>
                    <div class="ps-stat-body">
                        <div class="ps-stat-number"><?= number_format($stokHabis) ?></div>
                        <div class="ps-stat-label">Stok Habis</div>
                    </div>
                    <div class="ps-stat-deco"></div>
                </div>

                <div class="ps-stat-card ps-stat-accent">
                    <div class="ps-stat-icon">
                        <i data-lucide="users"></i>
                    </div>
                    <div class="ps-stat-body">
                        <div class="ps-stat-number"><?= number_format($totalUsers) ?></div>
                        <div class="ps-stat-label">Total Pengguna</div>
                    </div>
                    <div class="ps-stat-deco"></div>
                </div>
            </div>

            <!-- Quick Actions + Recent Books -->
            <div class="ps-dashboard-grid">
                <!-- Quick Actions -->
                <div class="ps-card">
                    <div class="ps-card-header">
                        <h3 class="ps-card-title">
                            <i data-lucide="zap" style="color:var(--ps-warning)"></i> Aksi Cepat
                        </h3>
                    </div>
                    <div class="ps-card-body">
                        <div class="ps-quick-actions">
                            <a href="<?= BASE_URL ?>/index.php?page=admin/buku/create" class="ps-quick-action-btn">
                                <div class="ps-qa-icon" style="background: var(--ps-primary-light); color: var(--ps-primary);">
                                    <i data-lucide="book-plus"></i>
                                </div>
                                <div class="ps-qa-text">
                                    <strong class="d-block">Tambah Buku</strong>
                                    <small class="text-muted">Input judul baru</small>
                                </div>
                            </a>
                            <a href="<?= BASE_URL ?>/index.php?page=admin/buku" class="ps-quick-action-btn">
                                <div class="ps-qa-icon" style="background: var(--ps-success-light); color: var(--ps-success);">
                                    <i data-lucide="list"></i>
                                </div>
                                <div class="ps-qa-text">
                                    <strong class="d-block">Kelola Buku</strong>
                                    <small class="text-muted">Katalog & stok</small>
                                </div>
                            </a>
                            <a href="<?= BASE_URL ?>/index.php?page=admin/peminjaman" class="ps-quick-action-btn">
                                <div class="ps-qa-icon" style="background: var(--ps-warning-light); color: var(--ps-warning);">
                                    <i data-lucide="arrow-left-right"></i>
                                </div>
                                <div class="ps-qa-text">
                                    <strong class="d-block">Peminjaman</strong>
                                    <small class="text-muted">Sirkulasi buku</small>
                                </div>
                            </a>
                            <a href="<?= BASE_URL ?>/index.php?page=admin/users" class="ps-quick-action-btn">
                                <div class="ps-qa-icon" style="background: #EFF6FF; color: #2563EB;">
                                    <i data-lucide="users"></i>
                                </div>
                                <div class="ps-qa-text">
                                    <strong class="d-block">Data User</strong>
                                    <small class="text-muted">Daftar anggota</small>
                                </div>
                            </a>
                        </div>

                        <!-- Availability Bar -->
                        <div class="ps-availability-bar mt-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="ps-label-sm">Ketersediaan Buku</span>
                                <span class="ps-label-sm">
                                    <?= $totalBuku > 0 ? round($bukuTersedia / $totalBuku * 100) : 0 ?>%
                                </span>
                            </div>
                            <div class="ps-progress">
                                <div class="ps-progress-fill ps-progress-success"
                                     style="width: <?= $totalBuku > 0 ? round($bukuTersedia / $totalBuku * 100) : 0 ?>%">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-success">Tersedia: <?= $bukuTersedia ?></small>
                                <small class="text-danger">Habis: <?= $stokHabis ?></small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Books -->
                <div class="ps-card">
                    <div class="ps-card-header">
                        <h3 class="ps-card-title">
                            <i data-lucide="clock"></i> Buku Terbaru
                        </h3>
                        <a href="<?= BASE_URL ?>/index.php?page=admin/buku" class="ps-btn-link">
                            Lihat semua <i data-lucide="arrow-right"></i>
                        </a>
                    </div>
                    <div class="ps-card-body p-0">
                        <?php if (empty($bukuTerbaru)): ?>
                            <div class="ps-empty-state p-4">
                                <i data-lucide="inbox"></i>
                                <p>Belum ada buku</p>
                            </div>
                        <?php else: ?>
                            <div class="ps-recent-list">
                                <?php foreach ($bukuTerbaru as $b): ?>
                                    <div class="ps-recent-item">
                                        <div class="ps-recent-icon">
                                            <i data-lucide="book"></i>
                                        </div>
                                        <div class="ps-recent-info">
                                            <span class="ps-recent-title"><?= e($b['judul']) ?></span>
                                            <span class="ps-recent-meta">
                                                <?= e($b['penulis']) ?> &bull; <?= e($b['kategori']) ?>
                                            </span>
                                        </div>
                                        <div class="ps-recent-badge">
                                            <?php if ($b['stok'] > 0): ?>
                                                <span class="ps-badge ps-badge-success"><?= $b['stok'] ?> stok</span>
                                            <?php else: ?>
                                                <span class="ps-badge ps-badge-danger">Habis</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>