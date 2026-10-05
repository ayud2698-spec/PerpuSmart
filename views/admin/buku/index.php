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
                    <h1 class="ps-page-title">Kelola Buku</h1>
                    <p class="ps-page-subtitle">Manajemen data koleksi perpustakaan</p>
                </div>
            </div>
            <a href="<?= BASE_URL ?>/index.php?page=admin/buku/create" class="ps-btn-accent">
                <i data-lucide="plus"></i> Tambah Buku
            </a>
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

            <div class="ps-card">
                <div class="ps-card-header">
                    <h3 class="ps-card-title">
                        <i data-lucide="library"></i>
                        Daftar Buku
                        <span class="ps-badge ps-badge-primary ms-2"><?= count($buku) ?></span>
                    </h3>
                    <!-- Search -->
                    <form action="" method="GET" class="ps-search-form">
                        <input type="hidden" name="page" value="admin/buku">
                        <div class="ps-search-wrap">
                            <i data-lucide="search" class="ps-search-icon"></i>
                            <input type="text" name="search" class="ps-search-input"
                                   placeholder="Cari judul, penulis, kategori..."
                                   value="<?= e($_GET['search'] ?? '') ?>">
                            <?php if (!empty($_GET['search'])): ?>
                                <a href="<?= BASE_URL ?>/index.php?page=admin/buku" class="ps-search-clear">
                                    <i data-lucide="x"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                        <button type="submit" class="ps-btn-primary">
                            <i data-lucide="search"></i> Cari
                        </button>
                    </form>
                </div>

                <div class="ps-card-body p-0">
                    <?php if (empty($buku)): ?>
                        <div class="ps-empty-state">
                            <i data-lucide="search-x"></i>
                            <h4>Tidak ada buku ditemukan</h4>
                            <p>
                                <?php if (!empty($_GET['search'])): ?>
                                    Tidak ada hasil untuk "<strong><?= e($_GET['search']) ?></strong>"
                                <?php else: ?>
                                    Belum ada data buku. Tambahkan buku pertama!
                                <?php endif; ?>
                            </p>
                            <?php if (!empty($_GET['search'])): ?>
                                <a href="<?= BASE_URL ?>/index.php?page=admin/buku" class="ps-btn-primary">
                                    Tampilkan Semua
                                </a>
                            <?php else: ?>
                                <a href="<?= BASE_URL ?>/index.php?page=admin/buku/create" class="ps-btn-accent">
                                    <i data-lucide="plus"></i> Tambah Buku
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="ps-table-wrap">
                            <table class="table table-hover align-middle mb-0 ps-table" id="bukuTable">
                                <thead>
                                    <tr>
                                        <th style="width: 110px;">Kode</th>
                                        <th>Judul & Penulis</th>
                                        <th style="width: 160px;">Kategori</th>
                                        <th style="width: 90px;">Tahun</th>
                                        <th style="width: 80px;">Stok</th>
                                        <th style="width: 130px;">Status</th>
                                        <th style="width: 130px;" class="text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($buku as $b): ?>
                                        <tr>
                                            <td>
                                                <span class="ps-code-badge"><?= e($b['kode_buku']) ?></span>
                                            </td>
                                            <td>
                                                <div class="ps-book-cell">
                                                    <div class="ps-book-icon">
                                                        <i data-lucide="book"></i>
                                                    </div>
                                                    <div>
                                                        <div class="ps-book-title"><?= e($b['judul']) ?></div>
                                                        <div class="ps-book-author">
                                                            <i data-lucide="user" style="width:12px;height:12px"></i>
                                                            <?= e($b['penulis']) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="ps-kategori-badge"><?= e($b['kategori']) ?></span>
                                            </td>
                                            <td><?= e($b['tahun_terbit']) ?></td>
                                            <td>
                                                <span class="ps-stok-num <?= $b['stok'] <= 2 ? 'text-danger fw-bold' : '' ?>">
                                                    <?= $b['stok'] ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($b['stok'] > 0): ?>
                                                    <span class="ps-badge ps-badge-success">
                                                        <i data-lucide="check" style="width:12px;height:12px"></i>
                                                        Tersedia
                                                    </span>
                                                <?php else: ?>
                                                    <span class="ps-badge ps-badge-danger">
                                                        <i data-lucide="x" style="width:12px;height:12px"></i>
                                                        Habis
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end">
                                                <div class="ps-action-btns justify-content-end">
                                                    <a href="<?= BASE_URL ?>/index.php?page=admin/buku/show&id=<?= $b['id'] ?>"
                                                       class="ps-action-btn ps-action-view" title="Lihat Detail">
                                                        <i data-lucide="eye"></i>
                                                    </a>
                                                    <a href="<?= BASE_URL ?>/index.php?page=admin/buku/edit&id=<?= $b['id'] ?>"
                                                       class="ps-action-btn ps-action-edit" title="Edit Buku">
                                                        <i data-lucide="pencil"></i>
                                                    </a>
                                                    <form method="POST"
                                                          action="<?= BASE_URL ?>/index.php?page=admin/buku/delete"
                                                          class="d-inline"
                                                          onsubmit="return confirm('Hapus buku &quot;<?= e(addslashes($b['judul'])) ?>&quot;?\nAksi ini tidak dapat dibatalkan!')">
                                                        <?= csrfField() ?>
                                                        <input type="hidden" name="id" value="<?= $b['id'] ?>">
                                                        <button type="submit" class="ps-action-btn ps-action-delete" title="Hapus Buku">
                                                            <i data-lucide="trash-2"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>