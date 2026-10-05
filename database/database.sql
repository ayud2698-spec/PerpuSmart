-- ============================================================
--  PerpuSmart - Database Schema & Seed Data
--  Sistem Informasi Perpustakaan Kampus
--  Workshop Sistem Informasi Web Server
-- ============================================================

CREATE DATABASE IF NOT EXISTS perpusmart
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE perpusmart;

-- ============================================================
--  Tabel: users
-- ============================================================
CREATE TABLE IF NOT EXISTS users (
    id         INT UNSIGNED         NOT NULL AUTO_INCREMENT,
    nama       VARCHAR(100)         NOT NULL,
    username   VARCHAR(50)          NOT NULL UNIQUE,
    email      VARCHAR(150)         NOT NULL UNIQUE,
    password   VARCHAR(255)         NOT NULL,
    role       ENUM('admin','user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP            NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP            NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  Tabel: buku
-- ============================================================
CREATE TABLE IF NOT EXISTS buku (
    id           INT UNSIGNED NOT NULL AUTO_INCREMENT,
    kode_buku    VARCHAR(20)  NOT NULL UNIQUE,
    judul        VARCHAR(255) NOT NULL,
    penulis      VARCHAR(150) NOT NULL,
    penerbit     VARCHAR(150) NOT NULL,
    tahun_terbit YEAR         NOT NULL,
    kategori     VARCHAR(100) NOT NULL,
    stok         INT UNSIGNED NOT NULL DEFAULT 0,
    deskripsi    TEXT,
    cover_url    VARCHAR(500) DEFAULT NULL,
    created_at   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX idx_judul    (judul),
    INDEX idx_penulis  (penulis),
    INDEX idx_kategori (kategori),
    INDEX idx_kode     (kode_buku)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  SEED DATA - Users
--  Password:
--    admin  => Admin@123
--    budi   => User@123
-- ============================================================
INSERT INTO users (nama, username, email, password, role) VALUES
(
    'Administrator',
    'admin',
    'admin@perpusmart.id',
    '$2y$12$wmpVNnXImaBQWqG/YW8fVOEKjvkUSIivnIreuBpfj.OLdBM4ogzii',
    'admin'
),
(
    'Budi Santoso',
    'budi',
    'budi@mahasiswa.id',
    '$2y$12$H6.n8KtqALM0UY8A6PO47O9QcoZ/W9Ko9tU/KLsFGmNa.vadZTgme',
    'user'
);

-- ============================================================
--  SEED DATA - Buku (10 judul)
-- ============================================================
INSERT INTO buku (kode_buku, judul, penulis, penerbit, tahun_terbit, kategori, stok, deskripsi) VALUES
(
    'BK-001',
    'Clean Code: A Handbook of Agile Software Craftsmanship',
    'Robert C. Martin',
    'Prentice Hall',
    2008,
    'Pemrograman',
    5,
    'Buku klasik tentang cara menulis kode yang bersih, mudah dibaca, dan mudah dipelihara.'
),
(
    'BK-002',
    'The Pragmatic Programmer: Your Journey to Mastery',
    'David Thomas & Andrew Hunt',
    'Addison-Wesley',
    2019,
    'Pemrograman',
    3,
    'Panduan lengkap untuk menjadi programmer yang pragmatis dan efektif.'
),
(
    'BK-003',
    'Pemrograman Web dengan PHP dan MySQL',
    'Bunafit Nugroho',
    'Gava Media',
    2020,
    'Pemrograman Web',
    7,
    'Buku panduan praktis membangun aplikasi web dinamis menggunakan PHP dan MySQL.'
),
(
    'BK-004',
    'Basis Data: Konsep dan Perancangan',
    'Fathansyah',
    'Informatika Bandung',
    2018,
    'Basis Data',
    4,
    'Penjelasan mendalam tentang konsep basis data relasional, normalisasi, dan ERD.'
),
(
    'BK-005',
    'Algoritma dan Pemrograman dalam Bahasa Pascal dan C',
    'Rinaldi Munir',
    'Informatika Bandung',
    2016,
    'Algoritma',
    6,
    'Buku fundamental tentang algoritma, struktur data, dan implementasinya dalam Pascal dan C.'
),
(
    'BK-006',
    'Kecerdasan Buatan: Teknik dan Aplikasinya',
    'Sri Kusumadewi',
    'Graha Ilmu',
    2017,
    'Kecerdasan Buatan',
    2,
    'Membahas teknik kecerdasan buatan: logika fuzzy, jaringan saraf tiruan, dan sistem pakar.'
),
(
    'BK-007',
    'Sistem Operasi: Konsep dan Teknik Implementasi',
    'Abraham Silberschatz',
    'Erlangga',
    2019,
    'Sistem Operasi',
    5,
    'Referensi standar tentang konsep sistem operasi: manajemen proses, memori, dan file.'
),
(
    'BK-008',
    'Jaringan Komputer dan Internet',
    'Forouzan & Mosharraf',
    'McGraw-Hill',
    2021,
    'Jaringan Komputer',
    3,
    'Buku lengkap tentang protokol jaringan, arsitektur TCP/IP, routing, dan keamanan jaringan.'
),
(
    'BK-009',
    'Rekayasa Perangkat Lunak: Pendekatan Praktisi',
    'Roger S. Pressman',
    'Andi Offset',
    2015,
    'Rekayasa Perangkat Lunak',
    4,
    'Panduan komprehensif tentang proses rekayasa perangkat lunak dan manajemen proyek IT.'
),
(
    'BK-010',
    'Pemrograman Berorientasi Objek dengan Java',
    'Deitel & Deitel',
    'Pearson Education',
    2020,
    'Pemrograman',
    8,
    'Buku terlengkap tentang OOP menggunakan Java, mencakup GUI dan penanganan exception.'
);

-- ============================================================
--  Tabel: peminjaman
-- ============================================================
CREATE TABLE IF NOT EXISTS peminjaman (
    id             INT UNSIGNED NOT NULL AUTO_INCREMENT,
    kode_pinjam    VARCHAR(30)  NOT NULL UNIQUE,
    user_id        INT UNSIGNED NOT NULL,
    buku_id        INT UNSIGNED NOT NULL,
    tanggal_pinjam DATE         NOT NULL,
    batas_kembali  DATE         NOT NULL,
    tanggal_kembali DATE        NULL,
    status         ENUM('dipinjam', 'dikembalikan', 'terlambat') NOT NULL DEFAULT 'dipinjam',
    denda          INT UNSIGNED NOT NULL DEFAULT 0,
    catatan        TEXT         NULL,
    created_at     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    CONSTRAINT fk_peminjaman_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_peminjaman_buku FOREIGN KEY (buku_id) REFERENCES buku(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_buku_id (buku_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample peminjaman
INSERT INTO peminjaman (kode_pinjam, user_id, buku_id, tanggal_pinjam, batas_kembali, status, catatan)
VALUES
('PJ-202610-0001', 2, 1, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 7 DAY), 'dipinjam', 'Peminjaman untuk tugas kuliah');