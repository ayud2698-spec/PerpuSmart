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
            <h2 class="ps-auth-tagline">Bergabung dengan<br>Komunitas Literasi</h2>
            <p class="ps-auth-desc">
                Buat akun dan mulai jelajahi ribuan koleksi buku yang tersedia
                di perpustakaan kampus kami.
            </p>
            <div class="ps-auth-steps">
                <div class="ps-auth-step">
                    <div class="ps-step-num">1</div>
                    <span>Isi formulir registrasi</span>
                </div>
                <div class="ps-auth-step">
                    <div class="ps-step-num">2</div>
                    <span>Login dengan akun Anda</span>
                </div>
                <div class="ps-auth-step">
                    <div class="ps-step-num">3</div>
                    <span>Nikmati katalog buku</span>
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
                <h2 class="ps-auth-form-title">Buat Akun Baru</h2>
                <p class="ps-auth-form-sub">Daftar sebagai pengguna perpustakaan</p>
            </div>

            <?php if ($error): ?>
                <div class="ps-alert ps-alert-danger">
                    <i data-lucide="circle-alert"></i>
                    <div><?= $error ?></div>
                </div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>/index.php?page=register" method="POST" class="ps-auth-form" novalidate>
                <?= csrfField() ?>

                <div class="ps-field-group">
                    <label for="nama" class="ps-field-label">
                        <i data-lucide="badge-check"></i> Nama Lengkap
                    </label>
                    <input type="text" id="nama" name="nama" class="ps-field-input"
                           placeholder="Nama lengkap Anda..." required maxlength="100"
                           value="<?= e($formData['nama'] ?? '') ?>">
                </div>

                <div class="ps-fields-row">
                    <div class="ps-field-group">
                        <label for="username" class="ps-field-label">
                            <i data-lucide="at-sign"></i> Username
                        </label>
                        <input type="text" id="username" name="username" class="ps-field-input"
                               placeholder="username..." required maxlength="50" minlength="4"
                               pattern="[a-zA-Z0-9_]+"
                               value="<?= e($formData['username'] ?? '') ?>">
                    </div>

                    <div class="ps-field-group">
                        <label for="email" class="ps-field-label">
                            <i data-lucide="mail"></i> Email
                        </label>
                        <input type="email" id="email" name="email" class="ps-field-input"
                               placeholder="email@domain.com" required maxlength="150"
                               value="<?= e($formData['email'] ?? '') ?>">
                    </div>
                </div>

                <div class="ps-fields-row">
                    <div class="ps-field-group">
                        <label for="password" class="ps-field-label">
                            <i data-lucide="lock"></i> Password
                        </label>
                        <div class="ps-field-password">
                            <input type="password" id="password" name="password" class="ps-field-input"
                                   placeholder="Min. 6 karakter" required minlength="6">
                            <button type="button" class="ps-password-toggle" id="togglePwd1">
                                <i data-lucide="eye" id="eye1"></i>
                            </button>
                        </div>
                    </div>

                    <div class="ps-field-group">
                        <label for="password_confirm" class="ps-field-label">
                            <i data-lucide="shield-check"></i> Konfirmasi
                        </label>
                        <div class="ps-field-password">
                            <input type="password" id="password_confirm" name="password_confirm"
                                   class="ps-field-input" placeholder="Ulangi password" required>
                            <button type="button" class="ps-password-toggle" id="togglePwd2">
                                <i data-lucide="eye" id="eye2"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="ps-password-strength" id="pwdStrength" style="display:none">
                    <div class="ps-strength-bar">
                        <div class="ps-strength-fill" id="strengthFill"></div>
                    </div>
                    <span class="ps-strength-label" id="strengthLabel"></span>
                </div>

                <button type="submit" class="ps-btn-primary w-100" id="btnRegister">
                    <i data-lucide="user-plus"></i>
                    <span>Daftar Sekarang</span>
                </button>
            </form>

            <div class="ps-auth-footer">
                <p>Sudah punya akun?
                    <a href="<?= BASE_URL ?>/index.php?page=login" class="ps-auth-link">Masuk di sini</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
function setupToggle(btnId, fieldId, iconId) {
    document.getElementById(btnId).addEventListener('click', function () {
        const f = document.getElementById(fieldId);
        const i = document.getElementById(iconId);
        f.type = f.type === 'password' ? 'text' : 'password';
        i.setAttribute('data-lucide', f.type === 'password' ? 'eye' : 'eye-off');
        lucide.createIcons();
    });
}
setupToggle('togglePwd1', 'password', 'eye1');
setupToggle('togglePwd2', 'password_confirm', 'eye2');

// Password strength indicator
document.getElementById('password').addEventListener('input', function () {
    const val = this.value;
    const wrap = document.getElementById('pwdStrength');
    const fill = document.getElementById('strengthFill');
    const label = document.getElementById('strengthLabel');
    if (!val) { wrap.style.display = 'none'; return; }
    wrap.style.display = 'flex';
    let score = 0;
    if (val.length >= 6) score++;
    if (val.length >= 10) score++;
    if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score++;
    if (/\d/.test(val)) score++;
    if (/[^a-zA-Z0-9]/.test(val)) score++;
    const levels = ['', 'Sangat Lemah', 'Lemah', 'Cukup', 'Kuat', 'Sangat Kuat'];
    const colors = ['', '#ef4444', '#f97316', '#eab308', '#22c55e', '#10b981'];
    fill.style.width = (score * 20) + '%';
    fill.style.background = colors[score];
    label.textContent = levels[score] || '';
    label.style.color = colors[score];
});
</script>

<?php require_once BASE_PATH . '/views/layouts/footer.php'; ?>