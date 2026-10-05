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
            <a href="<?= BASE_URL ?>/index.php?page=user/buku">Katalog Buku</a>
            <i data-lucide="chevron-right" style="width:14px;height:14px"></i>
            <span><?= e(mb_strimwidth($buku['judul'], 0, 40, '...')) ?></span>
        </nav>

        <div class="ps-book-detail">
            <!-- Book Cover Side -->
            <div class="ps-book-detail-cover">
                <div class="ps-book-detail-icon">
                    <i data-lucide="book-open"></i>
                </div>
                <div class="ps-book-detail-code"><?= e($buku['kode_buku']) ?></div>

                <!-- Availability Box -->
                <div class="ps-availability-box <?= $buku['stok'] > 0 ? 'available' : 'unavailable' ?>">
                    <?php if ($buku['stok'] > 0): ?>
                        <div class="ps-avail-icon">
                            <i data-lucide="check-circle"></i>
                        </div>
                        <div>
                            <strong>Tersedia</strong>
                            <span><?= $buku['stok'] ?> eksemplar</span>
                        </div>
                    <?php else: ?>
                        <div class="ps-avail-icon">
                            <i data-lucide="x-circle"></i>
                        </div>
                        <div>
                            <strong>Stok Habis</strong>
                            <span>Tidak tersedia</span>
                        </div>
                    <?php endif; ?>
                </div>

                <a href="<?= BASE_URL ?>/index.php?page=user/buku" class="ps-btn-ghost w-100 mt-3">
                    <i data-lucide="arrow-left"></i> Kembali ke Katalog
                </a>
            </div>

            <!-- Book Info Side -->
            <div class="ps-book-detail-info">
                <div class="ps-book-detail-tags">
                    <span class="ps-kategori-badge"><?= e($buku['kategori']) ?></span>
                    <span class="ps-code-badge"><?= e($buku['kode_buku']) ?></span>
                </div>

                <h1 class="ps-book-detail-title"><?= e($buku['judul']) ?></h1>

                <div class="ps-book-detail-meta">
                    <div class="ps-book-meta-item">
                        <i data-lucide="user-pen"></i>
                        <div>
                            <span class="ps-meta-label">Penulis</span>
                            <span class="ps-meta-value"><?= e($buku['penulis']) ?></span>
                        </div>
                    </div>
                    <div class="ps-book-meta-item">
                        <i data-lucide="building-2"></i>
                        <div>
                            <span class="ps-meta-label">Penerbit</span>
                            <span class="ps-meta-value"><?= e($buku['penerbit']) ?></span>
                        </div>
                    </div>
                    <div class="ps-book-meta-item">
                        <i data-lucide="calendar"></i>
                        <div>
                            <span class="ps-meta-label">Tahun Terbit</span>
                            <span class="ps-meta-value"><?= e($buku['tahun_terbit']) ?></span>
                        </div>
                    </div>
                    <div class="ps-book-meta-item">
                        <i data-lucide="package"></i>
                        <div>
                            <span class="ps-meta-label">Stok Tersedia</span>
                            <span class="ps-meta-value <?= $buku['stok'] > 0 ? 'text-success' : 'text-danger' ?>">
                                <strong><?= $buku['stok'] ?> eksemplar</strong>
                            </span>
                        </div>
                    </div>
                </div>

                <?php if ($buku['deskripsi']): ?>
                    <div class="ps-book-detail-desc">
                        <h4><i data-lucide="align-left"></i> Tentang Buku Ini</h4>
                        <p><?= e($buku['deskripsi']) ?></p>
                    </div>
                <?php endif; ?>

                <div class="ps-book-detail-status">
                    <?php if ($buku['stok'] > 0): ?>
                        <div class="ps-status-banner ps-status-available">
                            <i data-lucide="check-circle"></i>
                            <div>
                                <strong>Buku Tersedia untuk Dipinjam</strong>
                                <span>Stok tersedia <?= $buku['stok'] ?> eksemplar. Anda dapat langsung meminjam buku ini secara online.</span>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="button" class="ps-btn-primary btn-lg w-100 py-3 shadow-sm d-flex align-items-center justify-content-center gap-2" data-bs-toggle="modal" data-bs-target="#modalPinjamBuku">
                                <i data-lucide="bookmark-plus"></i>
                                <span>Pinjam Buku Ini Sekarang</span>
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="ps-status-banner ps-status-unavailable">
                            <i data-lucide="clock"></i>
                            <div>
                                <strong>Stok Sedang Habis</strong>
                                <span>Semua eksemplar buku sedang dipinjam. Silakan pantau kembali nanti atau pilih judul lain.</span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pinjam Buku -->
<?php if ($buku['stok'] > 0): ?>
<div class="modal fade" id="modalPinjamBuku" tabindex="-1" aria-labelledby="modalPinjamLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: var(--ps-radius-lg);">
            <div class="modal-header border-bottom py-3" style="background: var(--ps-dark); color: #fff;">
                <h5 class="modal-title d-flex align-items-center gap-2" id="modalPinjamLabel">
                    <i data-lucide="bookmark" style="width:20px;height:20px;color:var(--ps-accent)"></i>
                    Konfirmasi Peminjaman Buku
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= BASE_URL ?>/index.php?page=user/peminjaman/pinjam" method="POST">
                <?= csrfField() ?>
                <input type="hidden" name="buku_id" value="<?= $buku['id'] ?>">

                <div class="modal-body p-4">
                    <div class="d-flex align-items-start gap-3 p-3 mb-4 rounded-3" style="background: var(--ps-bg); border: 1px solid var(--ps-border);">
                        <div style="background: var(--ps-primary-light); color: var(--ps-primary); padding: 12px; border-radius: var(--ps-radius-md);">
                            <i data-lucide="book-open" style="width: 24px; height: 24px;"></i>
                        </div>
                        <div>
                            <span class="ps-kategori-badge mb-1"><?= e($buku['kategori']) ?></span>
                            <h6 class="fw-bold mb-1 mt-1"><?= e($buku['judul']) ?></h6>
                            <p class="text-muted small mb-0">Penulis: <?= e($buku['penulis']) ?> | Kode: <code><?= e($buku['kode_buku']) ?></code></p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="ps-form-label fw-bold mb-2">Pilih Durasi Peminjaman</label>
                        <select name="durasi_hari" id="durasi_hari" class="ps-form-control" required onchange="updateBatasKembali()">
                            <option value="3">3 Hari</option>
                            <option value="7" selected>7 Hari (Standar Perpustakaan)</option>
                            <option value="14">14 Hari (Maksimal)</option>
                        </select>
                    </div>

                    <div class="p-3 mb-3 rounded-3" style="background: var(--ps-primary-light); border: 1px dashed var(--ps-primary);">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted small">Tanggal Peminjaman:</span>
                            <strong><?= date('d M Y') ?></strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Estimasi Batas Kembali:</span>
                            <strong id="labelBatasKembali" style="color: var(--ps-primary); font-size: 1.05rem;">
                                <?= date('d M Y', strtotime('+7 days')) ?>
                            </strong>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="ps-form-label mb-1">Catatan Peminjaman (Opsional)</label>
                        <textarea name="catatan" class="ps-form-control" rows="2" placeholder="Contoh: Untuk keperluan referensi skripsi / tugas akhir"></textarea>
                    </div>

                    <div class="small text-muted mt-2">
                        <i data-lucide="info" style="width:14px;height:14px;display:inline-block;vertical-align:-2px;"></i>
                        Denda keterlambatan berlaku <strong>Rp 1.000/hari</strong> setelah melewati batas waktu.
                    </div>
                </div>

                <div class="modal-footer border-top bg-light p-3">
                    <button type="button" class="ps-btn-ghost" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="ps-btn-primary d-flex align-items-center gap-2">
                        <i data-lucide="check"></i> Konfirmasi & Pinjam
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function updateBatasKembali() {
    const days = parseInt(document.getElementById('durasi_hari').value) || 7;
    const today = new Date();
    today.setDate(today.getDate() + days);
    const options = { day: 'numeric', month: 'short', year: 'numeric' };
    document.getElementById('labelBatasKembali').textContent = today.toLocaleDateString('id-ID', options);
}
</script>
<?php endif; ?>

<?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>