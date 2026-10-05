<?php require_once BASE_PATH . '/views/layouts/header.php'; ?>

<?php require_once BASE_PATH . '/views/layouts/navbar_user.php'; ?>

<div class="ps-user-layout">
    <div class="container-xl py-4">
        <?php if ($success): ?>
            <div class="ps-alert ps-alert-success mb-4">
                <i data-lucide="circle-check"></i><div><?= $success ?></div>
            </div>
        <?php endif; ?>

        <!-- Hero Welcome -->
        <div class="ps-user-hero">
            <div class="ps-user-hero-content">
                <div class="ps-user-hero-text">
                    <h1 class="ps-user-hero-title">
                        Selamat datang, <br>
                        <span class="ps-accent-text"><?= e(currentUser()['nama']) ?></span>
                    </h1>
                    <p class="ps-user-hero-desc">
                        Jelajahi koleksi buku perpustakaan kampus. Temukan buku yang Anda butuhkan
                        dan cek ketersediaan stoknya sebelum datang.
                    </p>
                    <a href="<?= BASE_URL ?>/index.php?page=user/buku" class="ps-btn-accent">
                        <i data-lucide="search"></i> Cari Buku Sekarang
                    </a>
                </div>
                <div class="ps-user-hero-visual">
                    <div class="ps-hero-book-stack">
                        <div class="ps-hero-book ps-book-1"></div>
                        <div class="ps-hero-book ps-book-2"></div>
                        <div class="ps-hero-book ps-book-3"></div>
                        <div class="ps-hero-book-icon">
                            <i data-lucide="book-open"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="ps-user-stats mb-4">
            <div class="ps-user-stat-card">
                <div class="ps-user-stat-icon ps-stat-icon-primary">
                    <i data-lucide="library"></i>
                </div>
                <div class="ps-user-stat-info">
                    <div class="ps-user-stat-num"><?= number_format($totalBuku) ?></div>
                    <div class="ps-user-stat-label">Total Koleksi</div>
                </div>
            </div>
            <div class="ps-user-stat-card">
                <div class="ps-user-stat-icon ps-stat-icon-success">
                    <i data-lucide="check-circle"></i>
                </div>
                <div class="ps-user-stat-info">
                    <div class="ps-user-stat-num"><?= number_format($tersedia) ?></div>
                    <div class="ps-user-stat-label">Buku Tersedia</div>
                </div>
            </div>
            <div class="ps-user-stat-card">
                <div class="ps-user-stat-icon ps-stat-icon-warning">
                    <i data-lucide="bookmark"></i>
                </div>
                <div class="ps-user-stat-info">
                    <div class="ps-user-stat-num"><?= count($activeLoans ?? []) ?></div>
                    <div class="ps-user-stat-label">Sedang Saya Pinjam</div>
                </div>
            </div>
        </div>

        <?php if (!empty($activeLoans)): ?>
            <!-- Sedang Dipinjam Widget -->
            <div class="ps-card p-4 mb-4" style="border-left: 4px solid var(--ps-primary);">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="fw-bold mb-0 d-flex align-items-center gap-2">
                        <i data-lucide="clock" style="color: var(--ps-primary);"></i> Buku yang Sedang Anda Pinjam
                    </h4>
                    <a href="<?= BASE_URL ?>/index.php?page=user/peminjaman" class="ps-btn-link">
                        Lihat Semua Peminjaman <i data-lucide="arrow-right"></i>
                    </a>
                </div>
                <div class="row g-3">
                    <?php foreach ($activeLoans as $al): ?>
                        <?php 
                            $isLate = ($al['status'] === 'terlambat') || strtotime($al['batas_kembali']) < strtotime(date('Y-m-d'));
                        ?>
                        <div class="col-12 col-md-6">
                            <div class="p-3 rounded-3 d-flex justify-content-between align-items-center" style="background: var(--ps-bg); border: 1px solid var(--ps-border);">
                                <div>
                                    <span class="ps-kategori-badge mb-1"><?= e($al['buku_kategori']) ?></span>
                                    <h6 class="fw-bold mb-1 mt-1"><?= e($al['buku_judul']) ?></h6>
                                    <div class="small <?= $isLate ? 'text-danger fw-bold' : 'text-muted' ?>">
                                        Batas Kembali: <?= date('d M Y', strtotime($al['batas_kembali'])) ?>
                                        <?= $isLate ? ' (Terlambat!)' : '' ?>
                                    </div>
                                </div>
                                <a href="<?= BASE_URL ?>/index.php?page=user/buku/show&id=<?= $al['buku_id'] ?>" class="btn btn-sm btn-outline-dark">
                                    Detail
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Buku Terbaru -->
        <div class="ps-section">
            <div class="ps-section-header">
                <h2 class="ps-section-title">
                    <i data-lucide="sparkles"></i> Koleksi Terbaru
                </h2>
                <a href="<?= BASE_URL ?>/index.php?page=user/buku" class="ps-btn-link">
                    Lihat semua <i data-lucide="arrow-right"></i>
                </a>
            </div>

            <?php if (empty($bukuTerbaru)): ?>
                <div class="ps-empty-state">
                    <i data-lucide="inbox"></i>
                    <p>Belum ada koleksi buku</p>
                </div>
            <?php else: ?>
                <div class="ps-books-grid">
                    <?php foreach ($bukuTerbaru as $b): ?>
                        <a href="<?= BASE_URL ?>/index.php?page=user/buku/show&id=<?= $b['id'] ?>"
                           class="ps-book-card">
                            <div class="ps-book-card-cover">
                                <div class="ps-book-card-icon">
                                    <i data-lucide="book"></i>
                                </div>
                                <div class="ps-book-card-category"><?= e($b['kategori']) ?></div>
                            </div>
                            <div class="ps-book-card-body">
                                <h4 class="ps-book-card-title"><?= e($b['judul']) ?></h4>
                                <p class="ps-book-card-author">
                                    <i data-lucide="user" style="width:13px;height:13px"></i>
                                    <?= e($b['penulis']) ?>
                                </p>
                                <div class="ps-book-card-footer">
                                    <span class="ps-book-card-year"><?= e($b['tahun_terbit']) ?></span>
                                    <?php if ($b['stok'] > 0): ?>
                                        <span class="ps-badge ps-badge-success">
                                            <i data-lucide="check" style="width:11px;height:11px"></i>
                                            <?= $b['stok'] ?> tersedia
                                        </span>
                                    <?php else: ?>
                                        <span class="ps-badge ps-badge-danger">Habis</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>