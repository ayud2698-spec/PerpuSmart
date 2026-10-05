<?php require_once BASE_PATH . '/views/layouts/header.php'; ?>

<?php require_once BASE_PATH . '/views/layouts/navbar_user.php'; ?>

<div class="ps-user-layout">
    <div class="container-xl py-4">
        <!-- Page Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h1 class="ps-page-title d-flex align-items-center gap-2 mb-1">
                    <i data-lucide="library" style="color:var(--ps-primary)"></i> Katalog Buku Perpustakaan
                </h1>
                <p class="text-muted mb-0">Jelajahi dan temukan koleksi buku akademik, referensi, dan literasi umum.</p>
            </div>
        </div>

        <!-- Search & Filter Card -->
        <div class="ps-card p-4 mb-4">
            <form action="" method="GET" class="mb-3">
                <input type="hidden" name="page" value="user/buku">
                <?php if (!empty($_GET['kategori'])): ?>
                    <input type="hidden" name="kategori" value="<?= e($_GET['kategori']) ?>">
                <?php endif; ?>
                <div class="d-flex gap-2">
                    <div class="ps-search-wrap flex-grow-1">
                        <i data-lucide="search" class="ps-search-icon"></i>
                        <input type="text" name="search" id="searchInput" class="ps-search-input w-100 py-2"
                               placeholder="Cari judul, penulis, penerbit, atau kode buku..."
                               value="<?= e($_GET['search'] ?? '') ?>" autocomplete="off">
                        <?php if (!empty($_GET['search'])): ?>
                            <a href="<?= BASE_URL ?>/index.php?page=user/buku<?= !empty($_GET['kategori']) ? '&kategori=' . urlencode($_GET['kategori']) : '' ?>" class="ps-search-clear">
                                <i data-lucide="x"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="ps-btn-primary px-4">
                        <i data-lucide="search"></i>
                        <span>Cari</span>
                    </button>
                </div>
            </form>

            <!-- Filter Kategori Chips -->
            <div class="d-flex align-items-center gap-2 flex-wrap pt-2 border-top">
                <span class="small fw-bold text-muted me-1"><i data-lucide="filter" style="width:14px;height:14px"></i> Kategori:</span>
                <a href="<?= BASE_URL ?>/index.php?page=user/buku<?= !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>"
                   class="ps-chip <?= empty($_GET['kategori']) ? 'active' : '' ?>">
                    Semua Kategori
                </a>
                <?php foreach ($kategoris as $kat): ?>
                    <a href="<?= BASE_URL ?>/index.php?page=user/buku&kategori=<?= urlencode($kat) ?><?= !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?>"
                       class="ps-chip <?= ($_GET['kategori'] ?? '') === $kat ? 'active' : '' ?>">
                        <?= e($kat) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Results Info -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="small text-muted">
                <?php if (!empty($_GET['search'])): ?>
                    Hasil pencarian untuk <strong>"<?= e($_GET['search']) ?>"</strong>: <strong><?= count($buku) ?></strong> buku ditemukan
                <?php elseif (!empty($_GET['kategori'])): ?>
                    Kategori <strong>"<?= e($_GET['kategori']) ?>"</strong>: <strong><?= count($buku) ?></strong> buku
                <?php else: ?>
                    Menampilkan <strong><?= count($buku) ?></strong> koleksi buku
                <?php endif; ?>
            </div>
            <?php if (!empty($_GET['search']) || !empty($_GET['kategori'])): ?>
                <a href="<?= BASE_URL ?>/index.php?page=user/buku" class="small ps-btn-link">
                    <i data-lucide="rotate-ccw"></i> Reset Filter
                </a>
            <?php endif; ?>
        </div>

        <!-- Book Grid -->
        <?php if (empty($buku)): ?>
            <div class="ps-card text-center py-5">
                <div class="mb-3" style="color: var(--ps-text-sub);">
                    <i data-lucide="search-x" style="width: 54px; height: 54px;"></i>
                </div>
                <h4 class="fw-bold mb-2">Tidak Ada Buku Ditemukan</h4>
                <p class="text-muted mb-4">Coba gunakan kata kunci pencarian yang berbeda atau reset filter kategori.</p>
                <a href="<?= BASE_URL ?>/index.php?page=user/buku" class="ps-btn-primary d-inline-flex align-items-center gap-2">
                    <i data-lucide="rotate-ccw"></i> Tampilkan Semua Buku
                </a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($buku as $b): ?>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="ps-book-card h-100">
                            <div class="ps-book-card-cover">
                                <div class="ps-book-card-icon">
                                    <i data-lucide="book-open"></i>
                                </div>
                                <div class="ps-book-card-code"><?= e($b['kode_buku']) ?></div>
                                <div class="ps-book-card-category"><?= e($b['kategori']) ?></div>
                            </div>
                            <div class="ps-book-card-body">
                                <h4 class="ps-book-card-title"><?= e($b['judul']) ?></h4>
                                <p class="ps-book-card-author">
                                    <i data-lucide="user-pen"></i>
                                    <span><?= e($b['penulis']) ?></span>
                                </p>
                                <p class="ps-book-card-author" style="font-size:0.775rem; margin-top:-6px;">
                                    <i data-lucide="building-2"></i>
                                    <span><?= e($b['penerbit']) ?> &bull; <?= e($b['tahun_terbit']) ?></span>
                                </p>
                                <div class="ps-book-card-footer mt-auto">
                                    <?php if ($b['stok'] > 0): ?>
                                        <span class="ps-badge ps-badge-success">
                                            <i data-lucide="check" style="width:11px;height:11px"></i>
                                            <?= $b['stok'] ?> tersedia
                                        </span>
                                    <?php else: ?>
                                        <span class="ps-badge ps-badge-danger">
                                            <i data-lucide="x" style="width:11px;height:11px"></i>
                                            Stok Habis
                                        </span>
                                    <?php endif; ?>
                                    <a href="<?= BASE_URL ?>/index.php?page=user/buku/show&id=<?= $b['id'] ?>" class="ps-btn-ghost btn-sm py-1 px-3">
                                        Detail <i data-lucide="arrow-right" style="width:12px;height:12px"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>