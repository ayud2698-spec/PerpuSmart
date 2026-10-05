<?php require_once BASE_PATH . '/views/layouts/header.php'; ?>

<div class="ps-admin-layout">
    <?php require_once BASE_PATH . '/views/layouts/sidebar_admin.php'; ?>

    <div class="ps-main-content">
        <!-- Topbar -->
        <div class="ps-topbar">
            <div>
                <h2 class="ps-page-title mb-0">Kelola Transaksi Peminjaman</h2>
                <small class="text-muted">Pantau sirkulasi peminjaman dan proses pengembalian buku</small>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="ps-user-badge">
                    <i data-lucide="shield-check" style="color:var(--ps-amber)"></i>
                    <strong><?= e($_SESSION['user_nama'] ?? 'Admin') ?></strong>
                </span>
            </div>
        </div>

        <div class="ps-content-body">
            <!-- Flash Messages -->
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

            <!-- Stat Cards -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="ps-stat-card">
                        <div class="ps-stat-icon" style="background: var(--ps-warning-light); color: var(--ps-warning);">
                            <i data-lucide="clock"></i>
                        </div>
                        <div>
                            <div class="ps-stat-value"><?= number_format($stats['dipinjam']) ?></div>
                            <div class="ps-stat-label">Sedang Dipinjam</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="ps-stat-card">
                        <div class="ps-stat-icon" style="background: var(--ps-danger-light); color: var(--ps-danger);">
                            <i data-lucide="alert-octagon"></i>
                        </div>
                        <div>
                            <div class="ps-stat-value text-danger"><?= number_format($stats['terlambat']) ?></div>
                            <div class="ps-stat-label">Terlambat Kembali</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="ps-stat-card">
                        <div class="ps-stat-icon" style="background: var(--ps-success-light); color: var(--ps-success);">
                            <i data-lucide="check-circle-2"></i>
                        </div>
                        <div>
                            <div class="ps-stat-value text-success"><?= number_format($stats['dikembalikan']) ?></div>
                            <div class="ps-stat-label">Sudah Dikembalikan</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="ps-stat-card">
                        <div class="ps-stat-icon" style="background: var(--ps-primary-light); color: var(--ps-primary);">
                            <i data-lucide="coins"></i>
                        </div>
                        <div>
                            <div class="ps-stat-value" style="font-size: 1.35rem;">Rp <?= number_format($stats['total_denda'], 0, ',', '.') ?></div>
                            <div class="ps-stat-label">Total Denda Terkumpul</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="ps-card">
                <div class="p-3 border-bottom d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <!-- Status Filter Tabs -->
                    <div class="ps-tab-pills">
                        <a href="<?= BASE_URL ?>/index.php?page=admin/peminjaman" class="ps-tab-pill <?= empty($status) ? 'active' : '' ?>">
                            <i data-lucide="layers" style="width:14px;height:14px"></i>
                            <span>Semua</span>
                            <span class="ps-pill-count"><?= $stats['total'] ?></span>
                        </a>
                        <a href="<?= BASE_URL ?>/index.php?page=admin/peminjaman&status=dipinjam" class="ps-tab-pill <?= $status === 'dipinjam' ? 'active' : '' ?>">
                            <i data-lucide="clock" style="width:14px;height:14px;color:var(--ps-warning)"></i>
                            <span>Dipinjam</span>
                            <span class="ps-pill-count"><?= $stats['dipinjam'] ?></span>
                        </a>
                        <a href="<?= BASE_URL ?>/index.php?page=admin/peminjaman&status=terlambat" class="ps-tab-pill <?= $status === 'terlambat' ? 'active' : '' ?>">
                            <i data-lucide="alert-circle" style="width:14px;height:14px;color:var(--ps-danger)"></i>
                            <span>Terlambat</span>
                            <span class="ps-pill-count" style="<?= $stats['terlambat'] > 0 ? 'background: var(--ps-danger-light); color: var(--ps-danger); font-weight:700;' : '' ?>"><?= $stats['terlambat'] ?></span>
                        </a>
                        <a href="<?= BASE_URL ?>/index.php?page=admin/peminjaman&status=dikembalikan" class="ps-tab-pill <?= $status === 'dikembalikan' ? 'active' : '' ?>">
                            <i data-lucide="check-circle" style="width:14px;height:14px;color:var(--ps-success)"></i>
                            <span>Dikembalikan</span>
                            <span class="ps-pill-count"><?= $stats['dikembalikan'] ?></span>
                        </a>
                    </div>

                    <!-- Search Box -->
                    <form action="<?= BASE_URL ?>/index.php" method="GET" class="ps-search-form">
                        <input type="hidden" name="page" value="admin/peminjaman">
                        <?php if (!empty($status)): ?>
                            <input type="hidden" name="status" value="<?= e($status) ?>">
                        <?php endif; ?>
                        <div class="ps-search-wrap">
                            <i data-lucide="search" class="ps-search-icon"></i>
                            <input type="text" name="search" class="ps-search-input" placeholder="Cari kode/peminjam/buku..." value="<?= e($search) ?>">
                            <?php if ($search): ?>
                                <a href="<?= BASE_URL ?>/index.php?page=admin/peminjaman<?= $status ? '&status=' . $status : '' ?>" class="ps-search-clear">
                                    <i data-lucide="x"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                        <button type="submit" class="ps-btn-primary">
                            <i data-lucide="search"></i> Cari
                        </button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 ps-table">
                        <thead>
                            <tr>
                                <th>Kode & Peminjam</th>
                                <th>Buku Dipinjam</th>
                                <th>Tgl Pinjam</th>
                                <th>Batas Kembali</th>
                                <th>Status</th>
                                <th>Denda</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($peminjamanList)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i data-lucide="inbox" style="width:40px;height:40px;margin-bottom:8px;opacity:0.5;"></i>
                                        <div>Tidak ada data transaksi peminjaman ditemukan.</div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($peminjamanList as $p): ?>
                                    <?php 
                                        $isTerlambat = ($p['status'] === 'terlambat') || ($p['status'] === 'dipinjam' && strtotime($p['batas_kembali']) < strtotime(date('Y-m-d')));
                                        $isKembali   = ($p['status'] === 'dikembalikan');
                                        $hariLewat   = (int)($p['hari_lewat'] ?? 0);
                                        $estimasiDenda = ($hariLewat > 0 && !$isKembali) ? $hariLewat * 1000 : 0;
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold"><code class="ps-code-badge"><?= e($p['kode_pinjam']) ?></code></div>
                                            <div class="small fw-semibold mt-1"><?= e($p['user_nama']) ?></div>
                                            <div class="text-muted" style="font-size:0.75rem;"><?= e($p['user_email']) ?></div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-truncate" style="max-width: 250px;" title="<?= e($p['buku_judul']) ?>">
                                                <?= e($p['buku_judul']) ?>
                                            </div>
                                            <small class="text-muted">Kode: <?= e($p['kode_buku']) ?> | <?= e($p['buku_penulis']) ?></small>
                                        </td>
                                        <td>
                                            <span class="small"><?= date('d/m/Y', strtotime($p['tanggal_pinjam'])) ?></span>
                                        </td>
                                        <td>
                                            <span class="small fw-semibold <?= ($isTerlambat && !$isKembali) ? 'text-danger' : '' ?>">
                                                <?= date('d/m/Y', strtotime($p['batas_kembali'])) ?>
                                            </span>
                                            <?php if ($isTerlambat && !$isKembali): ?>
                                                <div class="badge ps-badge-danger" style="font-size:0.7rem; margin-top:2px;">
                                                    +<?= $hariLewat ?> hari
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($isKembali): ?>
                                                <span class="ps-badge ps-badge-success d-inline-flex align-items-center gap-1">
                                                    <i data-lucide="check" style="width:12px;height:12px"></i> Dikembalikan
                                                </span>
                                                <div class="text-muted" style="font-size:0.725rem; margin-top: 2px;"><?= date('d/m/Y', strtotime($p['tanggal_kembali'])) ?></div>
                                            <?php elseif ($isTerlambat): ?>
                                                <span class="ps-badge ps-badge-danger d-inline-flex align-items-center gap-1">
                                                    <i data-lucide="alert-circle" style="width:12px;height:12px"></i> Terlambat
                                                </span>
                                            <?php else: ?>
                                                <span class="ps-badge" style="background: var(--ps-warning-light); color: var(--ps-warning); font-weight:700;" class="d-inline-flex align-items-center gap-1">
                                                    <i data-lucide="clock" style="width:12px;height:12px"></i> Dipinjam
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($isKembali): ?>
                                                <?= $p['denda'] > 0 ? '<span class="text-danger fw-semibold">Rp ' . number_format($p['denda'], 0, ',', '.') . '</span>' : '<span class="text-muted">-</span>' ?>
                                            <?php else: ?>
                                                <?php if ($estimasiDenda > 0): ?>
                                                    <span class="text-danger fw-bold" title="Estimasi denda keterlambatan">
                                                        Rp <?= number_format($estimasiDenda, 0, ',', '.') ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end">
                                            <?php if (!$isKembali): ?>
                                                <button type="button" class="ps-btn-primary btn-sm d-inline-flex align-items-center gap-1"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#modalKembali"
                                                        data-id="<?= $p['id'] ?>"
                                                        data-kode="<?= e($p['kode_pinjam']) ?>"
                                                        data-peminjam="<?= e($p['user_nama']) ?>"
                                                        data-buku="<?= e($p['buku_judul']) ?>"
                                                        data-batas="<?= date('d M Y', strtotime($p['batas_kembali'])) ?>"
                                                        data-harilewat="<?= $hariLewat ?>"
                                                        data-denda="<?= $estimasiDenda ?>"
                                                        onclick="openModalKembali(this)">
                                                    <i data-lucide="rotate-ccw" style="width:13px;height:13px"></i>
                                                    <span>Kembalikan</span>
                                                </button>
                                            <?php else: ?>
                                                <span class="ps-badge ps-badge-success" title="Sudah dikembalikan">
                                                    <i data-lucide="check-check" style="width:13px;height:13px"></i> Selesai
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Pengembalian Buku -->
<div class="modal fade" id="modalKembali" tabindex="-1" aria-labelledby="modalKembaliLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: var(--ps-radius-lg);">
            <div class="modal-header border-bottom py-3" style="background: var(--ps-dark); color: #fff;">
                <h5 class="modal-title d-flex align-items-center gap-2" id="modalKembaliLabel">
                    <i data-lucide="rotate-ccw" style="width:20px;height:20px;color:var(--ps-accent)"></i>
                    Proses Pengembalian Buku
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= BASE_URL ?>/index.php?page=admin/peminjaman/kembali" method="POST">
                <?= csrfField() ?>
                <input type="hidden" name="peminjaman_id" id="kembali_peminjaman_id" value="">

                <div class="modal-body p-4">
                    <!-- Ringkasan info -->
                    <div class="p-3 mb-3 rounded-3" style="background: var(--ps-off-white); border: 1px solid var(--ps-border);">
                        <div class="mb-1"><strong id="kembali_kode" class="text-primary"></strong></div>
                        <div class="mb-1">Peminjam: <strong id="kembali_peminjam"></strong></div>
                        <div class="mb-1">Judul Buku: <strong id="kembali_buku"></strong></div>
                        <div>Batas Kembali: <span id="kembali_batas" class="fw-semibold"></span></div>
                    </div>

                    <!-- Keterlambatan Alert -->
                    <div id="kembali_alert_terlambat" class="alert alert-warning d-none">
                        <div class="d-flex align-items-center gap-2 fw-bold text-danger mb-1">
                            <i data-lucide="alert-triangle" style="width:18px;height:18px"></i>
                            Terlambat <span id="kembali_hari_lewat">0</span> Hari!
                        </div>
                        <div class="small">Perhitungan Denda Otomatis (Rp 1.000 / hari): <strong>Rp <span id="kembali_estimasi_denda">0</span></strong></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah Denda (Rp)</label>
                        <input type="number" name="denda_manual" id="kembali_denda_input" class="form-control" min="0" step="500" value="0">
                        <div class="form-text">Bisa diubah jika denda dimaafkan atau ada biaya penggantian kerusakan.</div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold">Catatan Petugas (Opsional)</label>
                        <textarea name="catatan_petugas" class="form-control" rows="2" placeholder="Kondisi buku baik / ada catatan khusus"></textarea>
                    </div>
                </div>

                <div class="modal-footer border-top bg-light p-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success d-flex align-items-center gap-2">
                        <i data-lucide="check"></i> Konfirmasi Pengembalian
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openModalKembali(btn) {
    const id = btn.getAttribute('data-id');
    const kode = btn.getAttribute('data-kode');
    const peminjam = btn.getAttribute('data-peminjam');
    const buku = btn.getAttribute('data-buku');
    const batas = btn.getAttribute('data-batas');
    const hariLewat = parseInt(btn.getAttribute('data-harilewat')) || 0;
    const denda = parseInt(btn.getAttribute('data-denda')) || 0;

    document.getElementById('kembali_peminjaman_id').value = id;
    document.getElementById('kembali_kode').textContent = kode;
    document.getElementById('kembali_peminjam').textContent = peminjam;
    document.getElementById('kembali_buku').textContent = buku;
    document.getElementById('kembali_batas').textContent = batas;
    document.getElementById('kembali_denda_input').value = denda;

    const alertBox = document.getElementById('kembali_alert_terlambat');
    if (hariLewat > 0) {
        document.getElementById('kembali_hari_lewat').textContent = hariLewat;
        document.getElementById('kembali_estimasi_denda').textContent = denda.toLocaleString('id-ID');
        alertBox.classList.remove('d-none');
    } else {
        alertBox.classList.add('d-none');
    }
}
</script>

<?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>
