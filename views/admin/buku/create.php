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
                    <h1 class="ps-page-title">Tambah Buku</h1>
                    <p class="ps-page-subtitle">
                        <a href="<?= BASE_URL ?>/index.php?page=admin/buku" class="ps-breadcrumb-link">
                            Kelola Buku
                        </a>
                        <i data-lucide="chevron-right" style="width:14px;height:14px"></i> Tambah Buku
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
                        <i data-lucide="book-plus"></i>
                    </div>
                    <div>
                        <h3>Data Buku Baru</h3>
                        <p>Isi semua field yang diperlukan</p>
                    </div>
                </div>

                <form action="<?= BASE_URL ?>/index.php?page=admin/buku/create" method="POST" class="ps-form" novalidate>
                    <?= csrfField() ?>

                    <div class="ps-form-grid-2">
                        <div class="ps-field-group">
                            <label for="kode_buku" class="ps-field-label">
                                <i data-lucide="hash"></i> Kode Buku <span class="required">*</span>
                            </label>
                            <input type="text" id="kode_buku" name="kode_buku" class="ps-field-input"
                                   placeholder="cth: BK-011" required maxlength="20"
                                   value="<?= e($formData['kode_buku'] ?? '') ?>"
                                   style="text-transform:uppercase">
                            <small class="ps-field-hint">Format: BK-XXX atau sesuai kebijakan perpustakaan</small>
                        </div>

                        <div class="ps-field-group">
                            <label for="kategori" class="ps-field-label">
                                <i data-lucide="tag"></i> Kategori <span class="required">*</span>
                            </label>
                            <input type="text" id="kategori" name="kategori" class="ps-field-input"
                                   placeholder="cth: Pemrograman, Basis Data..."
                                   list="kategoriSuggest" required maxlength="100"
                                   value="<?= e($formData['kategori'] ?? '') ?>">
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
                               placeholder="Judul lengkap buku..." required maxlength="255"
                               value="<?= e($formData['judul'] ?? '') ?>">
                    </div>

                    <div class="ps-form-grid-2">
                        <div class="ps-field-group">
                            <label for="penulis" class="ps-field-label">
                                <i data-lucide="user-pen"></i> Penulis <span class="required">*</span>
                            </label>
                            <input type="text" id="penulis" name="penulis" class="ps-field-input"
                                   placeholder="Nama penulis / editor..." required maxlength="150"
                                   value="<?= e($formData['penulis'] ?? '') ?>">
                        </div>

                        <div class="ps-field-group">
                            <label for="penerbit" class="ps-field-label">
                                <i data-lucide="building-2"></i> Penerbit <span class="required">*</span>
                            </label>
                            <input type="text" id="penerbit" name="penerbit" class="ps-field-input"
                                   placeholder="Nama penerbit..." required maxlength="150"
                                   value="<?= e($formData['penerbit'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="ps-form-grid-2">
                        <div class="ps-field-group">
                            <label for="tahun_terbit" class="ps-field-label">
                                <i data-lucide="calendar"></i> Tahun Terbit <span class="required">*</span>
                            </label>
                            <input type="number" id="tahun_terbit" name="tahun_terbit" class="ps-field-input"
                                   placeholder="<?= date('Y') ?>" min="1900" max="<?= date('Y') + 1 ?>"
                                   required value="<?= e($formData['tahun_terbit'] ?? '') ?>">
                        </div>

                        <div class="ps-field-group">
                            <label for="stok" class="ps-field-label">
                                <i data-lucide="package"></i> Jumlah Stok <span class="required">*</span>
                            </label>
                            <input type="number" id="stok" name="stok" class="ps-field-input"
                                   placeholder="0" min="0" required
                                   value="<?= e($formData['stok'] ?? '0') ?>">
                        </div>
                    </div>

                    <div class="ps-field-group">
                        <label for="deskripsi" class="ps-field-label">
                            <i data-lucide="align-left"></i> Deskripsi
                        </label>
                        <textarea id="deskripsi" name="deskripsi" class="ps-field-input ps-textarea"
                                  rows="4" placeholder="Deskripsi singkat isi buku..."><?= e($formData['deskripsi'] ?? '') ?></textarea>
                    </div>

                    <div class="ps-form-actions">
                        <a href="<?= BASE_URL ?>/index.php?page=admin/buku" class="ps-btn-ghost">
                            <i data-lucide="x"></i>
                            <span>Batal</span>
                        </a>
                        <button type="reset" class="ps-btn-ghost text-warning-emphasis">
                            <i data-lucide="rotate-ccw"></i>
                            <span>Reset Form</span>
                        </button>
                        <button type="submit" class="ps-btn-primary" id="btnSubmit">
                            <i data-lucide="save"></i>
                            <span>Simpan Buku</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>