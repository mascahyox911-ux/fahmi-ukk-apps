<?php
$db_url = getenv('DATABASE_URL');
if ($db_url) {
    $parsed_url = parse_url($db_url);
    $host = $parsed_url['host'];
    $port = $parsed_url['port'] ?? 3306;
    $db   = ltrim($parsed_url['path'], '/');
    $user = $parsed_url['user'];
    $pass = $parsed_url['pass'];
} else {
    $host = getenv('DB_HOST') ?: 'localhost';
    $port = getenv('DB_PORT') ?: '3306';
    $db   = getenv('DB_NAME') ?: 'pengaduan_sekolah';
    $user = getenv('DB_USER') ?: 'root';
    $pass = getenv('DB_PASSWORD') ?: '';
}

$charset = 'utf8mb4';
$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

if (getenv('DB_SSL_MODE') === 'REQUIRED' || $db_url) {
    $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
}

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

function base_url($path = '') {
    $isVercel = getenv('VERCEL') == '1' || getenv('VERCEL_ENV');
    $base = $isVercel ? '/' : '/fahmi/';
    return $base . ltrim($path, '/');
}
?>
