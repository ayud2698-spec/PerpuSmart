<?php
/**
 * Middleware Authentication & Authorization
 * PerpuSmart — Sistem Informasi Perpustakaan
 */

function isLoggedIn(): bool {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function isAdmin(): bool {
    return isLoggedIn() && ($_SESSION['user_role'] ?? '') === 'admin';
}

function isUser(): bool {
    return isLoggedIn() && ($_SESSION['user_role'] ?? '') === 'user';
}

/**
 * Wajib login — jika belum login, redirect ke halaman login.
 */
function requireLogin(): void {
    if (!isLoggedIn()) {
        $_SESSION['flash_warning'] = 'Silakan login terlebih dahulu untuk mengakses halaman ini.';
        redirect('login');
    }
}

/**
 * Wajib Admin — jika bukan admin, tolak akses.
 */
function requireAdmin(): void {
    requireLogin();
    if (!isAdmin()) {
        $_SESSION['flash_error'] = 'Akses ditolak. Halaman ini hanya untuk Administrator.';
        redirect('user/dashboard');
    }
}

/**
 * Wajib User biasa — jika bukan user, redirect ke admin.
 */
function requireUser(): void {
    requireLogin();
    if (isAdmin()) {
        redirect('admin/dashboard');
    }
}

/**
 * Redirect jika sudah login (untuk halaman login/register).
 */
function redirectIfLoggedIn(): void {
    if (isLoggedIn()) {
        if (isAdmin()) {
            redirect('admin/dashboard');
        } else {
            redirect('user/dashboard');
        }
    }
}

/**
 * Ambil data session user saat ini.
 */
function currentUser(): array {
    return [
        'id'       => $_SESSION['user_id']       ?? 0,
        'nama'     => $_SESSION['user_nama']     ?? '',
        'username' => $_SESSION['user_username'] ?? '',
        'role'     => $_SESSION['user_role']     ?? '',
    ];
}