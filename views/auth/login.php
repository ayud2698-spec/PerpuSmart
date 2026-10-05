<?php require_once BASE_PATH . '/views/layouts/header.php'; ?>

<div class="ps-auth-wrapper">
    <!-- Left Panel -->
    <div class="ps-auth-panel ps-auth-hero">
        <div class="ps-auth-hero-content">
            <div class="ps-auth-logo">
                <div class="ps-auth-logo-icon">
                    <svg class="ps-logo-mark" viewBox="0 0 32 32" width="26" height="26" aria-hidden="true"><rect x="5" y="10" width="5" height="16" rx="1.2" fill="currentColor"/><rect x="12" y="6" width="5" height="20" rx="1.2" fill="currentColor" opacity=".85"/><path d="M13.5 6h2v6l-1-.9-1 .9z" fill="#FBBF24"/><rect x="20" y="8" width="5" height="18" rx="1.2" fill="#FBBF24" transform="rotate(14 25 26)"/><rect x="3" y="27" width="26" height="2" rx="1" fill="currentColor"/></svg>
                </div>
                <h1 class="ps-auth-app-name">PerpuSmart</h1>
            </div>
            <h2 class="ps-auth-tagline">Perpustakaan Kampus<br>Digital & Modern</h2>
            <p class="ps-auth-desc">
                Temukan ribuan koleksi buku berkualitas, kelola peminjaman,
                dan nikmati pengalaman literasi yang menyenangkan.
            </p>
            <div class="ps-auth-features">
                <div class="ps-auth-feature">
                    <i data-lucide="search"></i>
                    <span>Pencarian Cepat</span>
                </div>
                <div class="ps-auth-feature">
                    <i data-lucide="layers"></i>
                    <span>Katalog Lengkap</span>
                </div>
                <div class="ps-auth-feature">
                    <i data-lucide="shield-check"></i>
                    <span>Akses Aman</span>
                </div>
            </div>
        </div>
        <div class="ps-auth-hero-deco">
            <div class="ps-deco-circle ps-deco-1"></div>
            <div class="ps-deco-circle ps-deco-2"></div>
            <div class="ps-deco-circle ps-deco-3"></div>
        </div>
    </div>

    <!-- Right Panel -->
    <div class="ps-auth-panel ps-auth-form-panel">
        <div class="ps-auth-form-wrap">
            <div class="ps-auth-form-header">
                <h2 class="ps-auth-form-title">Selamat Datang</h2>
                <p class="ps-auth-form-sub">Masuk ke akun PerpuSmart Anda</p>
            </div>

            <?php if ($error): ?>
                <div class="ps-alert ps-alert-danger">
                    <i data-lucide="circle-alert"></i>
                    <div><?= $error ?></div>
                </div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="ps-alert ps-alert-success">
                    <i data-lucide="circle-check"></i>
                    <div><?= $success ?></div>
                </div>
            <?php endif; ?>
            <?php if ($warning): ?>
                <div class="ps-alert ps-alert-warning">
                    <i data-lucide="triangle-alert"></i>
                    <div><?= $warning ?></div>
                </div>
            <?php endif; ?>
            <?php if (isset($_GET['logout'])): ?>
                <div class="ps-alert ps-alert-success mb-4 d-flex align-items-center gap-3 p-3 rounded-3 shadow-xs" style="border-left: 4px solid var(--ps-success);">
                    <div style="background: var(--ps-success); color: #fff; border-radius: var(--ps-radius-full); width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i data-lucide="check" style="width: 16px; height: 16px;"></i>
                    </div>
                    <div>
                        <strong class="d-block text-dark" style="font-size: 0.9rem;">Logout Berhasil</strong>
                        <span class="text-muted" style="font-size: 0.825rem;">Sesi akun Anda telah berakhir dengan aman.</span>
                    </div>
                </div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>/index.php?page=login" method="POST" class="ps-auth-form" novalidate>
                <?= csrfField() ?>

                <div class="ps-field-group">
                    <label for="username" class="ps-field-label">
                        <i data-lucide="user"></i> Username
                    </label>
                    <input type="text" id="username" name="username" class="ps-field-input"
                           placeholder="Masukkan username..." autocomplete="username" required
                           value="<?= e($_POST['username'] ?? '') ?>">
                </div>

                <div class="ps-field-group">
                    <label for="password" class="ps-field-label">
                        <i data-lucide="lock"></i> Password
                    </label>
                    <div class="ps-field-password">
                        <input type="password" id="password" name="password" class="ps-field-input"
                               placeholder="Masukkan password..." autocomplete="current-password" required>
                        <button type="button" class="ps-password-toggle" id="togglePassword"
                                title="Tampilkan/sembunyikan password">
                            <i data-lucide="eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="ps-btn-primary w-100" id="btnLogin">
                    <i data-lucide="log-in"></i>
                    <span>Masuk</span>
                </button>
            </form>

            <div class="ps-auth-footer">
                <p>Belum punya akun?
                    <a href="<?= BASE_URL ?>/index.php?page=register" class="ps-auth-link">Daftar sekarang</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('togglePassword').addEventListener('click', function () {
    const pwd = document.getElementById('password');
    const icon = document.getElementById('eyeIcon');
    if (pwd.type === 'password') {
        pwd.type = 'text';
        icon.setAttribute('data-lucide', 'eye-off');
    } else {
        pwd.type = 'password';
        icon.setAttribute('data-lucide', 'eye');
    }
    lucide.createIcons();
});
</script>

<?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>