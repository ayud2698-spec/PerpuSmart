<?php require_once BASE_PATH . '/views/layouts/header.php'; ?>
<?php require_once BASE_PATH . '/views/layouts/navbar_user.php'; ?>

<div class="ps-user-layout">
    <div class="container-xl py-4">
        <!-- Breadcrumb -->
        <nav class="ps-breadcrumb mb-4">
            <a href="<?= BASE_URL ?>/index.php?page=user/dashboard">
                <i data-lucide="home" style="width:15px;height:15px"></i> Dashboard
            </a>
            <i data-lucide="chevron-right" style="width:14px;height:14px"></i>
            <span>Peminjaman Saya</span>
        </nav>

        <!-- Flash messages -->
        <?php if (hasFlash('flash_success')): ?>
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
                <i data-lucide="check-circle" style="width:20px;height:20px;flex-shrink:0;"></i>
                <div><?= e(flash('flash_success')) ?></div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (hasFlash('flash_error')): ?>
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
                <i data-lucide="alert-triangle" style="width:20px;height:20px;flex-shrink:0;"></i>
                <div><?= e(flash('flash_error')) ?></div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h1 class="ps-dashboard-title mb-1">Riwayat & Peminjaman Saya</h1>
                <p class="text-muted mb-0">Pantau buku yang sedang Anda pinjam dan riwayat pengembalian.</p>
            </div>
            <a href="<?= BASE_URL ?>/index.php?page=user/buku" class="ps-btn-primary d-inline-flex align-items-center gap-2">
                <i data-lucide="plus"></i> Pinjam Buku Baru
            </a>
        </div>

        <?php if (empty($peminjamanList)): ?>
            <div class="ps-card text-center py-5">
                <div class="mb-3" style="color: var(--ps-primary);">
                    <i data-lucide="bookmark" style="width: 54px; height: 54px;"></i>
                </div>
                <h4 class="fw-bold mb-2">Belum Ada Peminjaman</h4>
                <p class="text-muted mb-4">Anda belum pernah meminjam buku dari perpustakaan.</p>
                <a href="<?= BASE_URL ?>/index.php?page=user/buku" class="ps-btn-primary d-inline-flex align-items-center gap-2">
                    <i data-lucide="book-open"></i> Buka Katalog Buku Sekarang
                </a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($peminjamanList as $item): ?>
                    <?php 
                        $isTerlambat = ($item['status'] === 'terlambat') || ($item['status'] === 'dipinjam' && strtotime($item['batas_kembali']) < strtotime(date('Y-m-d')));
                        $isKembali   = ($item['status'] === 'dikembalikan');
                    ?>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="ps-card h-100 d-flex flex-column p-4" style="border-top: 4px solid <?= $isKembali ? 'var(--ps-success)' : ($isTerlambat ? 'var(--ps-danger)' : 'var(--ps-primary)') ?>;">
                            <!-- Card Header -->
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <span class="ps-kategori-badge">
                                        <?= e($item['buku_kategori']) ?>
                                    </span>
                                    <div class="text-muted small mt-2">
                                        <code class="ps-code-badge"><?= e($item['kode_pinjam']) ?></code>
                                    </div>
                                </div>
                                <div>
                                    <?php if ($isKembali): ?>
                                        <span class="badge ps-badge-success d-flex align-items-center gap-1">
                                            <i data-lucide="check" style="width:12px;height:12px"></i> Selesai
                                        </span>
                                    <?php elseif ($isTerlambat): ?>
                                        <span class="badge ps-badge-danger d-flex align-items-center gap-1">
                                            <i data-lucide="alert-circle" style="width:12px;height:12px"></i> Terlambat
                                        </span>
                                    <?php else: ?>
                                        <span class="badge" style="background: var(--ps-primary-light); color: var(--ps-primary); font-weight:700;" class="d-flex align-items-center gap-1">
                                            <i data-lucide="clock" style="width:12px;height:12px;display:inline-block;vertical-align:-2px;"></i> Dipinjam
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Book Title -->
                            <h5 class="fw-bold mb-1" style="line-height: 1.3;">
                                <a href="<?= BASE_URL ?>/index.php?page=user/buku/show&id=<?= $item['buku_id'] ?>" class="text-decoration-none text-reset">
                                    <?= e($item['buku_judul']) ?>
                                </a>
                            </h5>
                            <p class="text-muted small mb-3">Penulis: <?= e($item['buku_penulis']) ?></p>

                            <!-- Dates Info -->
                            <div class="p-3 mb-3 rounded-3" style="background: var(--ps-bg); border: 1px solid var(--ps-border); font-size: 0.85rem;">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Tanggal Pinjam:</span>
                                    <span class="fw-semibold"><?= date('d M Y', strtotime($item['tanggal_pinjam'])) ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Batas Pengembalian:</span>
                                    <span class="fw-semibold <?= $isTerlambat && !$isKembali ? 'text-danger' : '' ?>">
                                        <?= date('d M Y', strtotime($item['batas_kembali'])) ?>
                                    </span>
                                </div>
                                <?php if ($isKembali): ?>
                                    <div class="d-flex justify-content-between text-success pt-2 border-top">
                                        <span>Tanggal Kembali:</span>
                                        <span class="fw-bold"><?= date('d M Y', strtotime($item['tanggal_kembali'])) ?></span>
                                    </div>
                                    <?php if ($item['denda'] > 0): ?>
                                        <div class="d-flex justify-content-between text-danger pt-1">
                                            <span>Denda Dibayar:</span>
                                            <span class="fw-bold">Rp <?= number_format($item['denda'], 0, ',', '.') ?></span>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <?php 
                                        $hariSisa = (int)ceil((strtotime($item['batas_kembali']) - strtotime(date('Y-m-d'))) / 86400);
                                    ?>
                                    <div class="pt-2 border-top">
                                        <?php if ($hariSisa < 0): ?>
                                            <div class="text-danger fw-bold d-flex align-items-center gap-1">
                                                <i data-lucide="alert-octagon" style="width:14px;height:14px"></i>
                                                Lewat <?= abs($hariSisa) ?> hari (Estimasi Denda: Rp <?= number_format(abs($hariSisa) * 1000, 0, ',', '.') ?>)
                                            </div>
                                        <?php elseif ($hariSisa == 0): ?>
                                            <div class="text-warning fw-bold d-flex align-items-center gap-1">
                                                <i data-lucide="clock" style="width:14px;height:14px"></i>
                                                Hari ini batas terakhir!
                                            </div>
                                        <?php else: ?>
                                            <div class="text-muted d-flex align-items-center gap-1">
                                                <i data-lucide="hourglass" style="width:14px;height:14px"></i>
                                                Sisa waktu: <strong><?= $hariSisa ?> hari lagi</strong>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php if ($item['catatan']): ?>
                                <p class="small text-muted mb-3 fst-italic">
                                    "<?= e($item['catatan']) ?>"
                                </p>
                            <?php endif; ?>

                            <!-- Action -->
                            <div class="mt-auto">
                                <a href="<?= BASE_URL ?>/index.php?page=user/buku/detail&id=<?= $item['buku_id'] ?>" class="ps-btn-ghost w-100 d-flex align-items-center justify-content-center gap-2">
                                    <i data-lucide="book-open"></i> Detail Buku
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>
