<?php
class BukuController {
    private Buku $bukuModel;

    public function __construct() {
        requireAdmin();
        $this->bukuModel = new Buku();
    }

    // ── Daftar buku (dengan pencarian) ───────────────────
    public function index(): void {
        $search   = trim($_GET['search'] ?? '');
        $buku     = $search ? $this->bukuModel->search($search) : $this->bukuModel->getAll();
        $pageTitle = 'Kelola Buku — ' . APP_NAME;
        $error     = flash('flash_error');
        $success   = flash('flash_success');
        require_once BASE_PATH . '/views/admin/buku/index.php';
    }

    // ── Form tambah buku ──────────────────────────────────
    public function create(): void {
        $pageTitle = 'Tambah Buku — ' . APP_NAME;
        $error     = flash('flash_error');
        $formData  = $_SESSION['form_data'] ?? [];
        unset($_SESSION['form_data']);
        require_once BASE_PATH . '/views/admin/buku/create.php';
    }

    // ── Simpan buku baru ──────────────────────────────────
    public function store(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/buku');
        }

        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash_error'] = 'Token keamanan tidak valid.';
            redirect('admin/buku/create');
        }

        $data = $this->validateInput($_POST);

        if (isset($data['_errors'])) {
            $_SESSION['flash_error'] = implode('<br>', $data['_errors']);
            $_SESSION['form_data']   = $_POST;
            redirect('admin/buku/create');
        }

        if ($this->bukuModel->kodeExists($data[':kode_buku'])) {
            $_SESSION['flash_error'] = 'Kode buku "' . e($data[':kode_buku']) . '" sudah digunakan.';
            $_SESSION['form_data']   = $_POST;
            redirect('admin/buku/create');
        }

        // Mapping untuk create (tanpa prefix ':')
        $createData = [
            'kode_buku'    => $data[':kode_buku'],
            'judul'        => $data[':judul'],
            'penulis'      => $data[':penulis'],
            'penerbit'     => $data[':penerbit'],
            'tahun_terbit' => $data[':tahun_terbit'],
            'kategori'     => $data[':kategori'],
            'stok'         => $data[':stok'],
            'deskripsi'    => $data[':deskripsi'],
        ];

        $this->bukuModel->create($createData);
        $_SESSION['flash_success'] = 'Buku "' . e($data[':judul']) . '" berhasil ditambahkan.';
        redirect('admin/buku');
    }

    // ── Form edit buku ────────────────────────────────────
    public function edit(): void {
        $id   = (int)($_GET['id'] ?? 0);
        $buku = $this->bukuModel->findById($id);
        if (!$buku) {
            $_SESSION['flash_error'] = 'Buku tidak ditemukan.';
            redirect('admin/buku');
        }
        $pageTitle = 'Edit Buku — ' . APP_NAME;
        $error     = flash('flash_error');
        $formData  = $_SESSION['form_data'] ?? [];
        unset($_SESSION['form_data']);
        require_once BASE_PATH . '/views/admin/buku/edit.php';
    }

    // ── Update buku ───────────────────────────────────────
    public function update(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/buku');
        }

        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash_error'] = 'Token keamanan tidak valid.';
            redirect('admin/buku');
        }

        $id   = (int)($_POST['id'] ?? 0);
        $buku = $this->bukuModel->findById($id);
        if (!$buku) {
            $_SESSION['flash_error'] = 'Buku tidak ditemukan.';
            redirect('admin/buku');
        }

        $data = $this->validateInput($_POST);

        if (isset($data['_errors'])) {
            $_SESSION['flash_error'] = implode('<br>', $data['_errors']);
            $_SESSION['form_data']   = $_POST;
            redirect('admin/buku/edit', ['id' => $id]);
        }

        if ($this->bukuModel->kodeExists($data[':kode_buku'], $id)) {
            $_SESSION['flash_error'] = 'Kode buku "' . e($data[':kode_buku']) . '" sudah digunakan buku lain.';
            redirect('admin/buku/edit', ['id' => $id]);
        }

        $updateData = [
            'kode_buku'    => $data[':kode_buku'],
            'judul'        => $data[':judul'],
            'penulis'      => $data[':penulis'],
            'penerbit'     => $data[':penerbit'],
            'tahun_terbit' => $data[':tahun_terbit'],
            'kategori'     => $data[':kategori'],
            'stok'         => $data[':stok'],
            'deskripsi'    => $data[':deskripsi'],
        ];

        $this->bukuModel->update($id, $updateData);
        $_SESSION['flash_success'] = 'Data buku "' . e($data[':judul']) . '" berhasil diperbarui.';
        redirect('admin/buku');
    }

    // ── Hapus buku ────────────────────────────────────────
    public function delete(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/buku');
        }

        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['flash_error'] = 'Token keamanan tidak valid.';
            redirect('admin/buku');
        }

        $id   = (int)($_POST['id'] ?? 0);
        $buku = $this->bukuModel->findById($id);
        if (!$buku) {
            $_SESSION['flash_error'] = 'Buku tidak ditemukan.';
            redirect('admin/buku');
        }

        $this->bukuModel->delete($id);
        $_SESSION['flash_success'] = 'Buku "' . e($buku['judul']) . '" berhasil dihapus.';
        redirect('admin/buku');
    }

    // ── Detail buku (admin view) ──────────────────────────
    public function show(): void {
        $id   = (int)($_GET['id'] ?? 0);
        $buku = $this->bukuModel->findById($id);
        if (!$buku) {
            $_SESSION['flash_error'] = 'Buku tidak ditemukan.';
            redirect('admin/buku');
        }
        $pageTitle = e($buku['judul']) . ' — ' . APP_NAME;
        require_once BASE_PATH . '/views/admin/buku/show.php';
    }

    // ── Validasi input buku ───────────────────────────────
    private function validateInput(array $post): array {
        $errors = [];

        $kode     = strtoupper(trim($post['kode_buku']    ?? ''));
        $judul    = trim($post['judul']                   ?? '');
        $penulis  = trim($post['penulis']                 ?? '');
        $penerbit = trim($post['penerbit']                ?? '');
        $tahun    = (int)($post['tahun_terbit']           ?? 0);
        $kategori = trim($post['kategori']                ?? '');
        $stok     = (int)($post['stok']                   ?? 0);
        $deskripsi = trim($post['deskripsi']              ?? '');

        if (empty($kode))         $errors[] = 'Kode buku wajib diisi.';
        if (strlen($kode) > 20)   $errors[] = 'Kode buku maks 20 karakter.';
        if (empty($judul))        $errors[] = 'Judul buku wajib diisi.';
        if (empty($penulis))      $errors[] = 'Penulis wajib diisi.';
        if (empty($penerbit))     $errors[] = 'Penerbit wajib diisi.';
        if ($tahun < 1900 || $tahun > (int)date('Y') + 1)
                                  $errors[] = 'Tahun terbit tidak valid (1900–' . ((int)date('Y') + 1) . ').';
        if (empty($kategori))     $errors[] = 'Kategori wajib diisi.';
        if ($stok < 0)            $errors[] = 'Stok tidak boleh negatif.';

        if (!empty($errors)) return ['_errors' => $errors];

        return [
            ':kode_buku'    => $kode,
            ':judul'        => $judul,
            ':penulis'      => $penulis,
            ':penerbit'     => $penerbit,
            ':tahun_terbit' => $tahun,
            ':kategori'     => $kategori,
            ':stok'         => $stok,
            ':deskripsi'    => $deskripsi,
        ];
    }
}