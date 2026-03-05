<?php
// ============================================================
// config.php — Social Proof Engine
// Produção: Filess.io (MySQL remoto)
// ============================================================

define('APP_VERSION', '2.0.0');

date_default_timezone_set('America/Sao_Paulo');

// ============================================================
// BANCO DE DADOS — Filess.io
// ============================================================
define('DB_HOST', getenv('DB_HOST') ?: '91j0qi.h.filess.io');
define('DB_PORT', getenv('DB_PORT') ?: '61032');
define('DB_NAME', getenv('DB_NAME') ?: 'Db_SocialProof_partlygone');
define('DB_USER', getenv('DB_USER') ?: 'Db_SocialProof_partlygone');
define('DB_PASS', getenv('DB_PASS') ?: '3d1c730e00a2e917943ab2f1d6814a2b2d75cd4a');

// ============================================================
// Database — Singleton PDO
// ============================================================
class DB {
    private static $instance = null;

    public static function conn(): PDO {
        if (self::$instance === null) {
            try {
                self::$instance = new PDO(
                    'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4',
                    DB_USER,
                    DB_PASS,
                    [
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES   => false,
                        PDO::ATTR_TIMEOUT            => 10,
                    ]
                );
            } catch (PDOException $e) {
                http_response_code(500);
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
                exit;
            }
        }
        return self::$instance;
    }

    public static function fetch(string $sql, array $params = []): ?array {
        $stmt = self::conn()->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function fetchAll(string $sql, array $params = []): array {
        $stmt = self::conn()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function insert(string $sql, array $params = []): int {
        $stmt = self::conn()->prepare($sql);
        $stmt->execute($params);
        return (int) self::conn()->lastInsertId();
    }

    public static function query(string $sql, array $params = []): bool {
        $stmt = self::conn()->prepare($sql);
        return $stmt->execute($params);
    }

    public static function execute(string $sql, array $params = []): bool {
        return self::query($sql, $params);
    }
}

// ============================================================
// Settings helper
// ============================================================
function getSetting(string $key, string $default = ''): string {
    try {
        $row = DB::fetch('SELECT value FROM settings WHERE `key` = ?', [$key]);
        return $row ? $row['value'] : $default;
    } catch (\Exception $e) {
        return $default;
    }
}

function generateSlug(string $text): string {
    $text = mb_strtolower($text, 'UTF-8');
    $text = preg_replace('/[áàãâä]/u', 'a', $text);
    $text = preg_replace('/[éèêë]/u', 'e', $text);
    $text = preg_replace('/[íìîï]/u', 'i', $text);
    $text = preg_replace('/[óòõôö]/u', 'o', $text);
    $text = preg_replace('/[úùûü]/u', 'u', $text);
    $text = preg_replace('/[ç]/u', 'c', $text);
    $text = preg_replace('/[^a-z0-9\s-]/u', '', $text);
    $text = preg_replace('/[\s-]+/', '-', trim($text));
    return $text;
}
