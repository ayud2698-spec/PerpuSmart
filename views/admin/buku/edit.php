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
                    <h1 class="ps-page-title">Edit Buku</h1>
                    <p class="ps-page-subtitle">
                        <a href="<?= BASE_URL ?>/index.php?page=admin/buku" class="ps-breadcrumb-link">Kelola Buku</a>
                        <i data-lucide="chevron-right" style="width:14px;height:14px"></i> Edit
                    </p>
                </div>
            </div>
            <a href="<?= BASE_URL ?>/index.php?page=admin/buku" class="ps-btn-ghost">
                <i data-lucide="arrow-left"></i> Kembali
            </a>
        </header>

        <div class="ps-admin-content">
            <?php if ($error): ?>
                <div class="ps-alert ps-alert-danger mb-3">
                    <i data-lucide="circle-alert"></i><div><?= $error ?></div>
                </div>
            <?php endif; ?>

            <div class="ps-form-card">
                <div class="ps-form-card-header">
                    <div class="ps-form-card-icon">
                        <i data-lucide="pencil"></i>
                    </div>
                    <div>
                        <h3>Edit Data Buku</h3>
                        <p>ID: #<?= $buku['id'] ?> &bull; Kode: <?= e($buku['kode_buku']) ?></p>
                    </div>
                </div>

                <form action="<?= BASE_URL ?>/index.php?page=admin/buku/edit" method="POST" class="ps-form" novalidate>
                    <?= csrfField() ?>
                    <input type="hidden" name="id" value="<?= $buku['id'] ?>">

                    <?php
                    // Gunakan form data jika ada (redirect setelah error), fallback ke data buku
                    $fd = !empty($formData) ? $formData : $buku;
                    ?>

                    <div class="ps-form-grid-2">
                        <div class="ps-field-group">
                            <label for="kode_buku" class="ps-field-label">
                                <i data-lucide="hash"></i> Kode Buku <span class="required">*</span>
                            </label>
                            <input type="text" id="kode_buku" name="kode_buku" class="ps-field-input"
                                   required maxlength="20" style="text-transform:uppercase"
                                   value="<?= e($fd['kode_buku']) ?>">
                        </div>

                        <div class="ps-field-group">
                            <label for="kategori" class="ps-field-label">
                                <i data-lucide="tag"></i> Kategori <span class="required">*</span>
                            </label>
                            <input type="text" id="kategori" name="kategori" class="ps-field-input"
                                   list="kategoriSuggest" required maxlength="100"
                                   value="<?= e($fd['kategori']) ?>">
                            <datalist id="kategoriSuggest">
                                <option value="Pemrograman">
                                <option value="Pemrograman Web">
                                <option value="Basis Data">
                                <option value="Algoritma">
                                <option value="Jaringan Komputer">
                                <option value="Sistem Operasi">
                                <option value="Kecerdasan Buatan">
                                <option value="Rekayasa Perangkat Lunak">
                            </datalist>
                        </div>
                    </div>

                    <div class="ps-field-group">
                        <label for="judul" class="ps-field-label">
                            <i data-lucide="book-open"></i> Judul Buku <span class="required">*</span>
                        </label>
                        <input type="text" id="judul" name="judul" class="ps-field-input"
                               required maxlength="255" value="<?= e($fd['judul']) ?>">
                    </div>

                    <div class="ps-form-grid-2">
                        <div class="ps-field-group">
                            <label for="penulis" class="ps-field-label">
                                <i data-lucide="user-pen"></i> Penulis <span class="required">*</span>
                            </label>
                            <input type="text" id="penulis" name="penulis" class="ps-field-input"
                                   required maxlength="150" value="<?= e($fd['penulis']) ?>">
                        </div>

                        <div class="ps-field-group">
                            <label for="penerbit" class="ps-field-label">
                                <i data-lucide="building-2"></i> Penerbit <span class="required">*</span>
                            </label>
                            <input type="text" id="penerbit" name="penerbit" class="ps-field-input"
                                   required maxlength="150" value="<?= e($fd['penerbit']) ?>">
                        </div>
                    </div>

                    <div class="ps-form-grid-2">
                        <div class="ps-field-group">
                            <label for="tahun_terbit" class="ps-field-label">
                                <i data-lucide="calendar"></i> Tahun Terbit <span class="required">*</span>
                            </label>
                            <input type="number" id="tahun_terbit" name="tahun_terbit" class="ps-field-input"
                                   min="1900" max="<?= date('Y') + 1 ?>" required
                                   value="<?= e($fd['tahun_terbit']) ?>">
                        </div>

                        <div class="ps-field-group">
                            <label for="stok" class="ps-field-label">
                                <i data-lucide="package"></i> Jumlah Stok <span class="required">*</span>
                            </label>
                            <input type="number" id="stok" name="stok" class="ps-field-input"
                                   min="0" required value="<?= e($fd['stok']) ?>">
                        </div>
                    </div>

                    <div class="ps-field-group">
                        <label for="deskripsi" class="ps-field-label">
                            <i data-lucide="align-left"></i> Deskripsi
                        </label>
                        <textarea id="deskripsi" name="deskripsi" class="ps-field-input ps-textarea"
                                  rows="4"><?= e($fd['deskripsi'] ?? '') ?></textarea>
                    </div>

                    <div class="ps-form-actions">
                        <a href="<?= BASE_URL ?>/index.php?page=admin/buku" class="ps-btn-ghost">
                            <i data-lucide="x"></i>
                            <span>Batal</span>
                        </a>
                        <button type="submit" class="ps-btn-primary">
                            <i data-lucide="save"></i>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>