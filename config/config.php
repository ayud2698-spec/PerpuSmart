<?php
// ─── App Constants ────────────────────────────────────────────
if (!defined('APP_NAME'))        define('APP_NAME',        'PerpuSmart');
if (!defined('APP_VERSION'))     define('APP_VERSION',     '1.0.0');
if (!defined('BASE_URL'))        define('BASE_URL',        'http://localhost/perpusmart');
if (!defined('BASE_PATH'))       define('BASE_PATH',       dirname(__DIR__));

// ─── Database ────────────────────────────────────────────────
if (!defined('DB_HOST'))         define('DB_HOST',         'localhost');
if (!defined('DB_NAME'))         define('DB_NAME',         'perpusmart');
if (!defined('DB_USER'))         define('DB_USER',         'root');
if (!defined('DB_PASS'))         define('DB_PASS',         '');
if (!defined('DB_PORT'))         define('DB_PORT',         '3306');

// ─── Session ─────────────────────────────────────────────────
if (!defined('SESSION_NAME'))     define('SESSION_NAME',     'perpusmart_sess');
if (!defined('SESSION_LIFETIME')) define('SESSION_LIFETIME', 7200); // 2 jam

// ─── CSRF Helpers ────────────────────────────────────────────
function generateCsrfToken(): string {
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrfToken(string $token): bool {
    return isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

function csrfField(): string {
    return '<input type="hidden" name="csrf_token" value="'
        . htmlspecialchars(generateCsrfToken(), ENT_QUOTES, 'UTF-8') . '">';
}

// ─── Flash Messages ──────────────────────────────────────────
function flash(string $key): string {
    if (isset($_SESSION[$key])) {
        $msg = $_SESSION[$key];
        unset($_SESSION[$key]);
        return $msg;
    }
    return '';
}

function hasFlash(string $key): bool {
    return isset($_SESSION[$key]);
}

// ─── HTML Escape ─────────────────────────────────────────────
function e(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// ─── Redirect ────────────────────────────────────────────────
function redirect(string $page, array $params = []): never {
    $url = BASE_URL . '/index.php?page=' . $page;
    foreach ($params as $k => $v) {
        $url .= '&' . urlencode($k) . '=' . urlencode($v);
    }
    header('Location: ' . $url);
    exit;
}