<?php
/**
 * PerpuSmart — Front Controller
 * Semua request diproses melalui file ini.
 */

require_once __DIR__ . '/config/config.php';

// ─── Session ─────────────────────────────────────────────────
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_start();
}

// ─── Autoload ────────────────────────────────────────────────
require_once BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/middleware/auth.php';
require_once BASE_PATH . '/models/User.php';
require_once BASE_PATH . '/models/Buku.php';
require_once BASE_PATH . '/models/Peminjaman.php';
require_once BASE_PATH . '/controllers/AuthController.php';
require_once BASE_PATH . '/controllers/AdminController.php';
require_once BASE_PATH . '/controllers/BukuController.php';
require_once BASE_PATH . '/controllers/UserController.php';
require_once BASE_PATH . '/controllers/PeminjamanController.php';

// ─── Router ──────────────────────────────────────────────────
$page = trim($_GET['page'] ?? '');

switch ($page) {

    // ── Auth ─────────────────────────────────────────────────
    case 'login':
        $c = new AuthController();
        $_SERVER['REQUEST_METHOD'] === 'POST' ? $c->processLogin() : $c->showLogin();
        break;

    case 'register':
        $c = new AuthController();
        $_SERVER['REQUEST_METHOD'] === 'POST' ? $c->processRegister() : $c->showRegister();
        break;

    case 'logout':
        (new AuthController())->logout();
        break;

    // ── Admin ────────────────────────────────────────────────
    case 'admin/dashboard':
        (new AdminController())->dashboard();
        break;

    case 'admin/users':
        (new AdminController())->users();
        break;

    case 'admin/buku':
        (new BukuController())->index();
        break;

    case 'admin/buku/create':
        $c = new BukuController();
        $_SERVER['REQUEST_METHOD'] === 'POST' ? $c->store() : $c->create();
        break;

    case 'admin/buku/edit':
        $c = new BukuController();
        $_SERVER['REQUEST_METHOD'] === 'POST' ? $c->update() : $c->edit();
        break;

    case 'admin/buku/delete':
        (new BukuController())->delete();
        break;

    case 'admin/buku/show':
        (new BukuController())->show();
        break;

    case 'admin/peminjaman':
        (new PeminjamanController())->indexAdmin();
        break;

    case 'admin/peminjaman/kembali':
        (new PeminjamanController())->prosesKembali();
        break;

    // ── User ─────────────────────────────────────────────────
    case 'user/dashboard':
        (new UserController())->dashboard();
        break;

    case 'user/buku':
        (new UserController())->buku();
        break;

    case 'user/buku/show':
    case 'user/buku/detail':
        (new UserController())->showBuku();
        break;

    case 'user/peminjaman':
        (new PeminjamanController())->riwayatUser();
        break;

    case 'user/peminjaman/pinjam':
        (new PeminjamanController())->pinjam();
        break;

    // ── Default ──────────────────────────────────────────────
    default:
        if (isLoggedIn()) {
            redirect(isAdmin() ? 'admin/dashboard' : 'user/dashboard');
        } else {
            redirect('login');
        }
        break;
}