<?php
class AdminController {
    private Buku $bukuModel;
    private User $userModel;

    public function __construct() {
        requireAdmin();
        $this->bukuModel = new Buku();
        $this->userModel = new User();
    }

    // ── Dashboard Admin ───────────────────────────────────
    public function dashboard(): void {
        $totalBuku      = $this->bukuModel->countAll();
        $totalUsers     = $this->userModel->countAll();
        $bukuTersedia   = $this->bukuModel->countAvailable();
        $stokHabis      = $this->bukuModel->countUnavailable();
        $bukuTerbaru    = $this->bukuModel->getAll(5);
        $pageTitle      = 'Dashboard Admin — ' . APP_NAME;
        $error          = flash('flash_error');
        $success        = flash('flash_success');
        require_once BASE_PATH . '/views/admin/dashboard.php';
    }

    // ── Manajemen Pengguna ────────────────────────────────
    public function users(): void {
        $users          = $this->userModel->getAll();
        $totalAdmin     = $this->userModel->countByRole('admin');
        $totalUser      = $this->userModel->countByRole('user');
        $pageTitle      = 'Manajemen Pengguna — ' . APP_NAME;
        $error          = flash('flash_error');
        $success        = flash('flash_success');
        require_once BASE_PATH . '/views/admin/users/index.php';
    }
}