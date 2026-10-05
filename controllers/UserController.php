<?php
class UserController {
    private Buku $bukuModel;

    public function __construct() {
        requireLogin(); // Hanya perlu login, tidak perlu role 'user' khusus
        if (isAdmin()) redirect('admin/dashboard'); // Admin diarahkan ke admin
        $this->bukuModel = new Buku();
    }

    // ── Dashboard User ────────────────────────────────────
    public function dashboard(): void {
        $userId      = (int)$_SESSION['user_id'];
        $peminjamanModel = new Peminjaman();
        $myLoans     = $peminjamanModel->getByUserId($userId);
        $activeLoans = array_filter($myLoans, fn($p) => in_array($p['status'], ['dipinjam', 'terlambat']));

        $totalBuku   = $this->bukuModel->countAll();
        $tersedia    = $this->bukuModel->countAvailable();
        $stokHabis   = $this->bukuModel->countUnavailable();
        $bukuTerbaru = $this->bukuModel->getAll(6);
        $pageTitle   = 'Dashboard — ' . APP_NAME;
        $success     = flash('flash_success');
        $error       = flash('flash_error');
        require_once BASE_PATH . '/views/user/dashboard.php';
    }

    // ── Daftar & Pencarian Buku ───────────────────────────
    public function buku(): void {
        $search   = trim($_GET['search']   ?? '');
        $kategori = trim($_GET['kategori'] ?? '');

        if (!empty($search)) {
            $buku   = $this->bukuModel->search($search);
        } elseif (!empty($kategori)) {
            $buku   = $this->bukuModel->searchByKategori($kategori);
        } else {
            $buku   = $this->bukuModel->getAll();
        }

        $kategoris  = $this->bukuModel->getKategori();
        $pageTitle  = 'Daftar Buku — ' . APP_NAME;
        require_once BASE_PATH . '/views/user/buku/index.php';
    }

    // ── Detail Buku ───────────────────────────────────────
    public function showBuku(): void {
        $id   = (int)($_GET['id'] ?? 0);
        $buku = $this->bukuModel->findById($id);
        if (!$buku) {
            $_SESSION['flash_error'] = 'Buku tidak ditemukan.';
            redirect('user/buku');
        }
        $pageTitle = e($buku['judul']) . ' — ' . APP_NAME;
        require_once BASE_PATH . '/views/user/buku/show.php';
    }
}