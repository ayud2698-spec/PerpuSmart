<?php require_once BASE_PATH . '/views/layouts/header.php'; ?>

<div class="ps-admin-layout">
    <?php require_once BASE_PATH . '/views/layouts/sidebar_admin.php'; ?>

    <main class="ps-admin-main">
        <header class="ps-topbar">
            <div class="ps-topbar-left">
                <button class="ps-sidebar-toggle" id="sidebarToggle">
                    <i data-lucide="panel-left-close"></i>
                </button>
                <div>
                    <h1 class="ps-page-title">Detail Buku</h1>
                    <p class="ps-page-subtitle">
                        <a href="<?= BASE_URL ?>/index.php?page=admin/buku" class="ps-breadcrumb-link">Kelola Buku</a>
                        <i data-lucide="chevron-right" style="width:14px;height:14px"></i> Detail
                    </p>
                </div>
            </div>
            <a href="<?= BASE_URL ?>/index.php?page=admin/buku" class="ps-btn-ghost">
                <i data-lucide="arrow-left"></i> Kembali
            </a>
        </header>

        <div class="ps-admin-content">
            <div class="ps-detail-card">
                <div class="ps-detail-header">
                    <div class="ps-detail-book-icon">
                        <i data-lucide="book-open"></i>
                    </div>
                    <div class="ps-detail-title-wrap">
                        <h2 class="ps-detail-title"><?= e($buku['judul']) ?></h2>
                        <div class="ps-detail-meta-row">
                            <span class="ps-code-badge"><?= e($buku['kode_buku']) ?></span>
                            <span class="ps-kategori-badge"><?= e($buku['kategori']) ?></span>
                            <?php if ($buku['stok'] > 0): ?>
                                <span class="ps-badge ps-badge-success">
                                    <i data-lucide="check-circle" style="width:13px;height:13px"></i>
                                    Tersedia (<?= $buku['stok'] ?> stok)
                                </span>
                            <?php else: ?>
                                <span class="ps-badge ps-badge-danger">
                                    <i data-lucide="x-circle" style="width:13px;height:13px"></i>
                                    Stok Habis
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="ps-detail-actions">
                        <a href="<?= BASE_URL ?>/index.php?page=admin/buku/edit&id=<?= $buku['id'] ?>"
                           class="ps-btn-accent">
                            <i data-lucide="pencil"></i> Edit
                        </a>
                        <form method="POST" action="<?= BASE_URL ?>/index.php?page=admin/buku/delete"
                              onsubmit="return confirm('Hapus buku ini?')">
                            <?= csrfField() ?>
                            <input type="hidden" name="id" value="<?= $buku['id'] ?>">
                            <button type="submit" class="ps-btn-danger">
                                <i data-lucide="trash-2"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>

                <div class="ps-detail-body">
                    <div class="ps-detail-grid">
                        <div class="ps-detail-info">
                            <div class="ps-info-row">
                                <span class="ps-info-label"><i data-lucide="user-pen"></i> Penulis</span>
                                <span class="ps-info-value"><?= e($buku['penulis']) ?></span>
                            </div>
                            <div class="ps-info-row">
                                <span class="ps-info-label"><i data-lucide="building-2"></i> Penerbit</span>
                                <span class="ps-info-value"><?= e($buku['penerbit']) ?></span>
                            </div>
                            <div class="ps-info-row">
                                <span class="ps-info-label"><i data-lucide="calendar"></i> Tahun Terbit</span>
                                <span class="ps-info-value"><?= e($buku['tahun_terbit']) ?></span>
                            </div>
                            <div class="ps-info-row">
                                <span class="ps-info-label"><i data-lucide="package"></i> Stok Tersedia</span>
                                <span class="ps-info-value">
                                    <strong class="<?= $buku['stok'] > 0 ? 'text-success' : 'text-danger' ?>">
                                        <?= $buku['stok'] ?> eksemplar
                                    </strong>
                                </span>
                            </div>
                            <div class="ps-info-row">
                                <span class="ps-info-label"><i data-lucide="clock"></i> Ditambahkan</span>
                                <span class="ps-info-value"><?= date('d F Y, H:i', strtotime($buku['created_at'])) ?></span>
                            </div>
                        </div>

                        <div class="ps-detail-desc">
                            <h4><i data-lucide="align-left"></i> Deskripsi</h4>
                            <p><?= $buku['deskripsi'] ? e($buku['deskripsi']) : '<em class="text-muted">Tidak ada deskripsi</em>' ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>