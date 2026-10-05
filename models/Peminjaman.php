<?php
/**
 * Model: Peminjaman
 * Mengelola data transaksi peminjaman & pengembalian buku.
 */

class Peminjaman {
    private PDO $db;
    public const DENDA_PER_HARI = 1000; // Rp 1.000 per hari keterlambatan
    public const MAX_PINJAM_USER = 3;   // Maksimal 3 buku aktif per anggota

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Update otomatis status 'terlambat' jika melewati batas_kembali
     */
    public function refreshStatusTerlambat(): void {
        $stmt = $this->db->prepare("
            UPDATE peminjaman 
            SET status = 'terlambat' 
            WHERE status = 'dipinjam' AND batas_kembali < CURDATE()
        ");
        $stmt->execute();
    }

    /**
     * Buat peminjaman baru oleh user
     */
    public function pinjam(int $userId, int $bukuId, int $durasiHari = 7, ?string $catatan = null): array {
        $this->refreshStatusTerlambat();

        // 1. Cek kuota peminjaman aktif user
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM peminjaman 
            WHERE user_id = :uid AND status IN ('dipinjam', 'terlambat')
        ");
        $stmt->execute([':uid' => $userId]);
        if ((int)$stmt->fetchColumn() >= self::MAX_PINJAM_USER) {
            return [
                'success' => false,
                'message' => 'Anda telah mencapai batas maksimal peminjaman aktif (' . self::MAX_PINJAM_USER . ' buku). Kembalikan buku yang dipinjam terlebih dahulu.'
            ];
        }

        // 2. Cek apakah user sedang meminjam buku yang sama
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM peminjaman 
            WHERE user_id = :uid AND buku_id = :bid AND status IN ('dipinjam', 'terlambat')
        ");
        $stmt->execute([':uid' => $userId, ':bid' => $bukuId]);
        if ((int)$stmt->fetchColumn() > 0) {
            return [
                'success' => false,
                'message' => 'Anda sedang meminjam buku ini. Tidak dapat meminjam buku yang sama secara bersamaan.'
            ];
        }

        // 3. Cek stok buku
        $stmt = $this->db->prepare("SELECT stok, judul FROM buku WHERE id = :bid");
        $stmt->execute([':bid' => $bukuId]);
        $buku = $stmt->fetch();
        if (!$buku) {
            return ['success' => false, 'message' => 'Buku tidak ditemukan.'];
        }
        if ((int)$buku['stok'] <= 0) {
            return ['success' => false, 'message' => 'Maaf, stok buku "' . e($buku['judul']) . '" sedang habis.'];
        }

        // 4. Generate Kode Pinjam unik
        $kodePinjam = 'PJ-' . date('Ym') . '-' . strtoupper(bin2hex(random_bytes(3)));

        // 5. Simpan transaksi peminjaman & kurangi stok (Database Transaction)
        try {
            $this->db->beginTransaction();

            $tanggalPinjam = date('Y-m-d');
            $batasKembali  = date('Y-m-d', strtotime("+{$durasiHari} days"));

            $insertStmt = $this->db->prepare("
                INSERT INTO peminjaman (kode_pinjam, user_id, buku_id, tanggal_pinjam, batas_kembali, status, catatan)
                VALUES (:kode, :uid, :bid, :tgl_pinjam, :batas, 'dipinjam', :catatan)
            ");
            $insertStmt->execute([
                ':kode'       => $kodePinjam,
                ':uid'        => $userId,
                ':bid'        => $bukuId,
                ':tgl_pinjam' => $tanggalPinjam,
                ':batas'      => $batasKembali,
                ':catatan'    => $catatan
            ]);

            $updateStokStmt = $this->db->prepare("UPDATE buku SET stok = stok - 1 WHERE id = :bid AND stok > 0");
            $updateStokStmt->execute([':bid' => $bukuId]);

            $this->db->commit();
            return [
                'success'     => true,
                'message'     => 'Peminjaman berhasil diajukan! Batas pengembalian: ' . date('d M Y', strtotime($batasKembali)),
                'kode_pinjam' => $kodePinjam
            ];
        } catch (Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()];
        }
    }

    /**
     * Proses Pengembalian Buku oleh Admin
     */
    public function prosesPengembalian(int $peminjamanId, ?int $dendaManual = null, ?string $catatanAdmin = null): array {
        $peminjaman = $this->getById($peminjamanId);
        if (!$peminjaman) {
            return ['success' => false, 'message' => 'Data peminjaman tidak ditemukan.'];
        }
        if ($peminjaman['status'] === 'dikembalikan') {
            return ['success' => false, 'message' => 'Buku ini sudah dikembalikan sebelumnya.'];
        }

        // Hitung denda otomatis jika tidak ditentukan manual
        $denda = $dendaManual;
        if ($denda === null) {
            $hariTerlambat = $this->hitungHariTerlambat($peminjaman['batas_kembali']);
            $denda = $hariTerlambat > 0 ? $hariTerlambat * self::DENDA_PER_HARI : 0;
        }

        $catatanBaru = $peminjaman['catatan'];
        if (!empty($catatanAdmin)) {
            $catatanBaru = trim(($catatanBaru ? $catatanBaru . ' | ' : '') . 'Petugas: ' . $catatanAdmin);
        }

        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("
                UPDATE peminjaman 
                SET status = 'dikembalikan', 
                    tanggal_kembali = CURDATE(), 
                    denda = :denda,
                    catatan = :catatan
                WHERE id = :id
            ");
            $stmt->execute([
                ':denda'   => $denda,
                ':catatan' => $catatanBaru,
                ':id'      => $peminjamanId
            ]);

            // Kembalikan stok buku (+1)
            $stmtStok = $this->db->prepare("UPDATE buku SET stok = stok + 1 WHERE id = :bid");
            $stmtStok->execute([':bid' => $peminjaman['buku_id']]);

            $this->db->commit();
            return [
                'success' => true,
                'message' => 'Pengembalian buku berhasil diproses.' . ($denda > 0 ? ' Denda: Rp ' . number_format($denda, 0, ',', '.') : ''),
                'denda'   => $denda
            ];
        } catch (Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'message' => 'Gagal memproses pengembalian: ' . $e->getMessage()];
        }
    }

    /**
     * Ambil data peminjaman by ID
     */
    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("
            SELECT p.*, 
                   u.nama AS user_nama, u.email AS user_email, u.username AS user_username,
                   b.judul AS buku_judul, b.kode_buku, b.penulis AS buku_penulis, b.cover_url, b.kategori AS buku_kategori
            FROM peminjaman p
            JOIN users u ON p.user_id = u.id
            JOIN buku b ON p.buku_id = b.id
            WHERE p.id = :id
        ");
        $stmt->execute([':id' => $id]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    /**
     * Ambil daftar peminjaman berdasarkan User ID
     */
    public function getByUserId(int $userId): array {
        $this->refreshStatusTerlambat();
        $stmt = $this->db->prepare("
            SELECT p.*, 
                   b.judul AS buku_judul, b.kode_buku, b.penulis AS buku_penulis, b.cover_url, b.kategori AS buku_kategori,
                   DATEDIFF(CURDATE(), p.batas_kembali) AS hari_lewat
            FROM peminjaman p
            JOIN buku b ON p.buku_id = b.id
            WHERE p.user_id = :uid
            ORDER BY p.id DESC
        ");
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll();
    }

    /**
     * Ambil semua transaksi peminjaman untuk Admin
     */
    public function getAll(?string $status = null, ?string $search = null): array {
        $this->refreshStatusTerlambat();
        $sql = "
            SELECT p.*, 
                   u.nama AS user_nama, u.email AS user_email, u.username AS user_username,
                   b.judul AS buku_judul, b.kode_buku, b.penulis AS buku_penulis, b.cover_url,
                   DATEDIFF(CURDATE(), p.batas_kembali) AS hari_lewat
            FROM peminjaman p
            JOIN users u ON p.user_id = u.id
            JOIN buku b ON p.buku_id = b.id
            WHERE 1=1
        ";
        $params = [];

        if (!empty($status)) {
            $sql .= " AND p.status = :status";
            $params[':status'] = $status;
        }

        if (!empty($search)) {
            $sql .= " AND (p.kode_pinjam LIKE :s OR u.nama LIKE :s OR u.email LIKE :s OR b.judul LIKE :s OR b.kode_buku LIKE :s)";
            $params[':s'] = "%{$search}%";
        }

        $sql .= " ORDER BY p.id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Statistik peminjaman
     */
    public function getStats(): array {
        $this->refreshStatusTerlambat();
        $res = [
            'total'        => 0,
            'dipinjam'     => 0,
            'dikembalikan' => 0,
            'terlambat'    => 0,
            'total_denda'  => 0
        ];

        $stmt = $this->db->query("
            SELECT 
                COUNT(*) AS total,
                SUM(CASE WHEN status = 'dipinjam' THEN 1 ELSE 0 END) AS dipinjam,
                SUM(CASE WHEN status = 'dikembalikan' THEN 1 ELSE 0 END) AS dikembalikan,
                SUM(CASE WHEN status = 'terlambat' THEN 1 ELSE 0 END) AS terlambat,
                SUM(denda) AS total_denda
            FROM peminjaman
        ");
        $row = $stmt->fetch();
        if ($row) {
            $res['total']        = (int)($row['total'] ?? 0);
            $res['dipinjam']     = (int)($row['dipinjam'] ?? 0);
            $res['dikembalikan'] = (int)($row['dikembalikan'] ?? 0);
            $res['terlambat']    = (int)($row['terlambat'] ?? 0);
            $res['total_denda']  = (int)($row['total_denda'] ?? 0);
        }
        return $res;
    }

    /**
     * Hitung selisih hari keterlambatan
     */
    public function hitungHariTerlambat(string $batasKembali): int {
        $today = new DateTime();
        $batas = new DateTime($batasKembali);
        if ($today > $batas) {
            $diff = $today->diff($batas);
            return (int)$diff->days;
        }
        return 0;
    }
}
