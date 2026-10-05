<?php
/**
 * Controller: PeminjamanController
 * Menangani alur peminjaman buku oleh user dan pengembalian oleh admin.
 */

class PeminjamanController {
    private Peminjaman $peminjamanModel;
    private Buku $bukuModel;

    public function __construct() {
        $this->peminjamanModel = new Peminjaman();
        $this->bukuModel       = new Buku();
    }

    /**
     * User: Mengajukan Peminjaman Buku (POST)
     */
    public function pinjam(): void {
        requireUser();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('user/buku');
        }

        $token = $_POST['csrf_token'] ?? '';
        if (!verifyCsrfToken($token)) {
            $_SESSION['flash_error'] = 'Token keamanan tidak valid. Silakan coba lagi.';
            redirect('user/buku');
        }

        $bukuId     = (int)($_POST['buku_id'] ?? 0);
        $durasiHari = (int)($_POST['durasi_hari'] ?? 7);
        $catatan    = trim($_POST['catatan'] ?? '');

        // Batasi durasi pinjam max 14 hari
        if ($durasiHari < 1 || $durasiHari > 14) {
            $durasiHari = 7;
        }

        $userId = (int)$_SESSION['user_id'];
        $result = $this->peminjamanModel->pinjam($userId, $bukuId, $durasiHari, $catatan);

        if ($result['success']) {
            $_SESSION['flash_success'] = $result['message'];
            redirect('user/peminjaman');
        } else {
            $_SESSION['flash_error'] = $result['message'];
            redirect('user/buku/detail', ['id' => $bukuId]);
        }
    }

    /**
     * User: Melihat Riwayat & Peminjaman Aktif Saya (GET)
     */
    public function riwayatUser(): void {
        requireUser();

        $userId = (int)$_SESSION['user_id'];
        $peminjamanList = $this->peminjamanModel->getByUserId($userId);

        $pageTitle = 'Peminjaman Saya';
        require_once BASE_PATH . '/views/user/peminjaman/index.php';
    }

    /**
     * Admin: Daftar Seluruh Transaksi Peminjaman (GET)
     */
    public function indexAdmin(): void {
        requireAdmin();

        $status = trim($_GET['status'] ?? '');
        $search = trim($_GET['search'] ?? '');

        $peminjamanList = $this->peminjamanModel->getAll($status ?: null, $search ?: null);
        $stats = $this->peminjamanModel->getStats();

        $pageTitle = 'Kelola Transaksi Peminjaman';
        require_once BASE_PATH . '/views/admin/peminjaman/index.php';
    }

    /**
     * Admin: Proses Pengembalian Buku (POST)
     */
    public function prosesKembali(): void {
        requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/peminjaman');
        }

        $token = $_POST['csrf_token'] ?? '';
        if (!verifyCsrfToken($token)) {
            $_SESSION['flash_error'] = 'Token keamanan tidak valid.';
            redirect('admin/peminjaman');
        }

        $peminjamanId = (int)($_POST['peminjaman_id'] ?? 0);
        $dendaManual  = isset($_POST['denda_manual']) && $_POST['denda_manual'] !== '' ? (int)$_POST['denda_manual'] : null;
        $catatanAdmin = trim($_POST['catatan_petugas'] ?? '');

        $result = $this->peminjamanModel->prosesPengembalian($peminjamanId, $dendaManual, $catatanAdmin);

        if ($result['success']) {
            $_SESSION['flash_success'] = $result['message'];
        } else {
            $_SESSION['flash_error'] = $result['message'];
        }

        redirect('admin/peminjaman');
    }
}
