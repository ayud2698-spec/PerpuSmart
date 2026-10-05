<?php
class User {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /** Cari user berdasarkan username (login) */
    public function findByUsername(string $username): array|false {
        $stmt = $this->db->prepare(
            'SELECT * FROM users WHERE username = :username LIMIT 1'
        );
        $stmt->execute([':username' => $username]);
        return $stmt->fetch();
    }

    /** Cari user berdasarkan email */
    public function findByEmail(string $email): array|false {
        $stmt = $this->db->prepare(
            'SELECT * FROM users WHERE email = :email LIMIT 1'
        );
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    /** Cari user berdasarkan ID */
    public function findById(int $id): array|false {
        $stmt = $this->db->prepare(
            'SELECT id, nama, username, email, role, created_at FROM users WHERE id = :id LIMIT 1'
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /** Buat user baru — password otomatis di-hash */
    public function create(string $nama, string $username, string $email, string $password, string $role = 'user'): bool {
        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        $stmt = $this->db->prepare(
            'INSERT INTO users (nama, username, email, password, role) 
             VALUES (:nama, :username, :email, :password, :role)'
        );
        return $stmt->execute([
            ':nama'     => $nama,
            ':username' => $username,
            ':email'    => $email,
            ':password' => $hash,
            ':role'     => $role,
        ]);
    }

    /** Ambil semua user */
    public function getAll(): array {
        $stmt = $this->db->query(
            'SELECT id, nama, username, email, role, created_at FROM users ORDER BY created_at DESC'
        );
        return $stmt->fetchAll();
    }

    /** Hitung total user */
    public function countAll(): int {
        return (int) $this->db->query('SELECT COUNT(*) FROM users')->fetchColumn();
    }

    /** Hitung total user berdasarkan role */
    public function countByRole(string $role): int {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM users WHERE role = :role');
        $stmt->execute([':role' => $role]);
        return (int) $stmt->fetchColumn();
    }

    /** Cek apakah username sudah ada */
    public function usernameExists(string $username, ?int $excludeId = null): bool {
        if ($excludeId !== null) {
            $stmt = $this->db->prepare('SELECT COUNT(*) FROM users WHERE username = :u AND id != :id');
            $stmt->execute([':u' => $username, ':id' => $excludeId]);
        } else {
            $stmt = $this->db->prepare('SELECT COUNT(*) FROM users WHERE username = :u');
            $stmt->execute([':u' => $username]);
        }
        return (int) $stmt->fetchColumn() > 0;
    }

    /** Cek apakah email sudah ada */
    public function emailExists(string $email, ?int $excludeId = null): bool {
        if ($excludeId !== null) {
            $stmt = $this->db->prepare('SELECT COUNT(*) FROM users WHERE email = :e AND id != :id');
            $stmt->execute([':e' => $email, ':id' => $excludeId]);
        } else {
            $stmt = $this->db->prepare('SELECT COUNT(*) FROM users WHERE email = :e');
            $stmt->execute([':e' => $email]);
        }
        return (int) $stmt->fetchColumn() > 0;
    }
}