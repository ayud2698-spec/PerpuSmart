<?php
class AuthController {
    private User $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    // ── Tampilkan halaman login ───────────────────────────
    public function showLogin(): void {
        redirectIfLoggedIn();
        $pageTitle = 'Login — ' . APP_NAME;
        $error     = flash('flash_error');
        $success   = flash('flash_success');
        $warning   = flash('flash_warning');
        require_once BASE_PATH . '/views/auth/login.php';
    }

    // ── Proses form login ─────────────────────────────────
    public function processLogin(): void {
        redirectIfLoggedIn();

        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash_error'] = 'Token keamanan tidak valid. Silakan coba lagi.';
            redirect('login');
        }

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            $_SESSION['flash_error'] = 'Username dan password wajib diisi.';
            redirect('login');
        }

        $user = $this->userModel->findByUsername($username);

        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['flash_error'] = 'Username atau password salah. Silakan coba lagi.';
            redirect('login');
        }

        // Regenerasi session ID (cegah session fixation)
        session_regenerate_id(true);

        $_SESSION['user_id']       = (int) $user['id'];
        $_SESSION['user_nama']     = $user['nama'];
        $_SESSION['user_username'] = $user['username'];
        $_SESSION['user_role']     = $user['role'];

        // Reset CSRF setelah login
        unset($_SESSION['csrf_token']);

        $_SESSION['flash_success'] = 'Selamat datang, ' . e($user['nama']) . '!';

        if ($user['role'] === 'admin') {
            redirect('admin/dashboard');
        } else {
            redirect('user/dashboard');
        }
    }

    // ── Tampilkan halaman register ────────────────────────
    public function showRegister(): void {
        redirectIfLoggedIn();
        $pageTitle = 'Registrasi — ' . APP_NAME;
        $error     = flash('flash_error');
        $success   = flash('flash_success');
        $formData  = $_SESSION['form_data'] ?? [];
        unset($_SESSION['form_data']);
        require_once BASE_PATH . '/views/auth/register.php';
    }

    // ── Proses form register ──────────────────────────────
    public function processRegister(): void {
        redirectIfLoggedIn();

        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash_error'] = 'Token keamanan tidak valid.';
            redirect('register');
        }

        $nama     = trim($_POST['nama']             ?? '');
        $username = trim($_POST['username']         ?? '');
        $email    = trim($_POST['email']            ?? '');
        $password = $_POST['password']              ?? '';
        $confirm  = $_POST['password_confirm']      ?? '';

        $errors = [];

        if (empty($nama))                                       $errors[] = 'Nama lengkap wajib diisi.';
        if (strlen($nama) > 100)                                $errors[] = 'Nama terlalu panjang (maks 100 karakter).';
        if (empty($username))                                   $errors[] = 'Username wajib diisi.';
        if (strlen($username) < 4)                              $errors[] = 'Username minimal 4 karakter.';
        if (strlen($username) > 50)                             $errors[] = 'Username terlalu panjang (maks 50 karakter).';
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $username))        $errors[] = 'Username hanya boleh huruf, angka, dan underscore.';
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Format email tidak valid.';
        if (strlen($password) < 6)                              $errors[] = 'Password minimal 6 karakter.';
        if ($password !== $confirm)                             $errors[] = 'Konfirmasi password tidak cocok.';
        if ($this->userModel->usernameExists($username))        $errors[] = 'Username "' . e($username) . '" sudah digunakan.';
        if ($this->userModel->emailExists($email))              $errors[] = 'Email "' . e($email) . '" sudah terdaftar.';

        if (!empty($errors)) {
            $_SESSION['flash_error'] = implode('<br>', $errors);
            $_SESSION['form_data']   = compact('nama', 'username', 'email');
            redirect('register');
        }

        $this->userModel->create($nama, $username, $email, $password, 'user');
        unset($_SESSION['csrf_token']);
        $_SESSION['flash_success'] = 'Registrasi berhasil! Silakan login dengan akun Anda.';
        redirect('login');
    }

    // ── Logout ────────────────────────────────────────────
    public function logout(): void {
        requireLogin();

        // Hapus semua data session
        $_SESSION = [];

        // Hapus cookie session
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        // Hancurkan session
        session_destroy();

        // Redirect ke login
        header('Location: ' . BASE_URL . '/index.php?page=login&logout=1');
        exit;
    }
}