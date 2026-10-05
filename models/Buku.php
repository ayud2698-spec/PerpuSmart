<?php
class Buku {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /** Ambil semua buku (dengan opsional limit/offset) */
    public function getAll(int $limit = 0, int $offset = 0): array {
        $sql = 'SELECT * FROM buku ORDER BY created_at DESC';
        if ($limit > 0) {
            $sql .= ' LIMIT ' . (int)$limit . ' OFFSET ' . (int)$offset;
        }
        return $this->db->query($sql)->fetchAll();
    }

    /** Hitung total buku */
    public function countAll(): int {
        return (int) $this->db->query('SELECT COUNT(*) FROM buku')->fetchColumn();
    }

    /** Hitung buku yang masih tersedia (stok > 0) */
    public function countAvailable(): int {
        return (int) $this->db->query('SELECT COUNT(*) FROM buku WHERE stok > 0')->fetchColumn();
    }

    /** Hitung buku yang stok habis */
    public function countUnavailable(): int {
        return (int) $this->db->query('SELECT COUNT(*) FROM buku WHERE stok = 0')->fetchColumn();
    }

    /** Cari buku berdasarkan ID */
    public function findById(int $id): array|false {
        $stmt = $this->db->prepare('SELECT * FROM buku WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /** Pencarian buku (judul, penulis, kategori, kode_buku) */
    public function search(string $keyword): array {
        $kw = '%' . $keyword . '%';
        $stmt = $this->db->prepare(
            'SELECT * FROM buku 
             WHERE judul LIKE :k1 OR penulis LIKE :k2 OR kategori LIKE :k3 OR kode_buku LIKE :k4
             ORDER BY judul ASC'
        );
        $stmt->execute([':k1' => $kw, ':k2' => $kw, ':k3' => $kw, ':k4' => $kw]);
        return $stmt->fetchAll();
    }

    /** Filter berdasarkan kategori */
    public function searchByKategori(string $kategori): array {
        $stmt = $this->db->prepare(
            'SELECT * FROM buku WHERE kategori = :kategori ORDER BY judul ASC'
        );
        $stmt->execute([':kategori' => $kategori]);
        return $stmt->fetchAll();
    }

    /** Ambil daftar kategori unik */
    public function getKategori(): array {
        $stmt = $this->db->query('SELECT DISTINCT kategori FROM buku ORDER BY kategori ASC');
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /** Cek apakah kode_buku sudah ada */
    public function kodeExists(string $kode, ?int $excludeId = null): bool {
        if ($excludeId !== null) {
            $stmt = $this->db->prepare('SELECT COUNT(*) FROM buku WHERE kode_buku = :k AND id != :id');
            $stmt->execute([':k' => $kode, ':id' => $excludeId]);
        } else {
            $stmt = $this->db->prepare('SELECT COUNT(*) FROM buku WHERE kode_buku = :k');
            $stmt->execute([':k' => $kode]);
        }
        return (int) $stmt->fetchColumn() > 0;
    }

    /** Tambah buku baru — menggunakan Prepared Statement */
    public function create(array $data): bool {
        $stmt = $this->db->prepare(
            'INSERT INTO buku (kode_buku, judul, penulis, penerbit, tahun_terbit, kategori, stok, deskripsi)
             VALUES (:kode_buku, :judul, :penulis, :penerbit, :tahun_terbit, :kategori, :stok, :deskripsi)'
        );
        return $stmt->execute($data);
    }

    /** Update buku — menggunakan Prepared Statement */
    public function update(int $id, array $data): bool {
        $data[':id'] = $id;
        $stmt = $this->db->prepare(
            'UPDATE buku SET
                kode_buku    = :kode_buku,
                judul        = :judul,
                penulis      = :penulis,
                penerbit     = :penerbit,
                tahun_terbit = :tahun_terbit,
                kategori     = :kategori,
                stok         = :stok,
                deskripsi    = :deskripsi
             WHERE id = :id'
        );
        return $stmt->execute($data);
    }

    /** Hapus buku */
    public function delete(int $id): bool {
        $stmt = $this->db->prepare('DELETE FROM buku WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}