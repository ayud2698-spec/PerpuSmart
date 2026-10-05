<?php
class Database {
    private static ?PDO $instance = null;

    private function __construct() {}
    private function __clone() {}

    public static function getInstance(): PDO {
        if (self::$instance === null) {
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
                DB_HOST, DB_PORT, DB_NAME
            );
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
            ];
            try {
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                http_response_code(500);
                die('<div style="font-family:sans-serif;padding:2rem;color:#c0392b;">
                    <h2>⚠ Koneksi Database Gagal</h2>
                    <p>' . htmlspecialchars($e->getMessage()) . '</p>
                    <p>Pastikan MySQL sudah berjalan dan konfigurasi di <code>config/config.php</code> sudah benar.</p>
                </div>');
            }
        }
        return self::$instance;
    }
}