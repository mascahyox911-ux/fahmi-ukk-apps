<?php
function env($key, $default = '') {
    $val = getenv($key);
    if ($val !== false && $val !== '') return $val;
    if (isset($_ENV[$key]) && $_ENV[$key] !== '') return $_ENV[$key];
    if (isset($_SERVER[$key]) && $_SERVER[$key] !== '') return $_SERVER[$key];
    return $default;
}

$db_url = env('DATABASE_URL') ?: env('MYSQL_URL');
if ($db_url) {
    $parsed_url = parse_url($db_url);
    $host = $parsed_url['host'] ?? 'localhost';
    $port = $parsed_url['port'] ?? 3306;
    $db   = isset($parsed_url['path']) ? ltrim($parsed_url['path'], '/') : 'pengaduan_sekolah';
    $user = isset($parsed_url['user']) ? urldecode($parsed_url['user']) : 'root';
    $pass = isset($parsed_url['pass']) ? urldecode($parsed_url['pass']) : '';
} else {
    $host = env('DB_HOST') ?: env('MYSQL_HOST') ?: 'localhost';
    $port = env('DB_PORT') ?: env('MYSQL_PORT') ?: '3306';
    $db   = env('DB_NAME') ?: env('MYSQL_DATABASE') ?: 'pengaduan_sekolah';
    $user = env('DB_USER') ?: env('MYSQL_USER') ?: 'root';
    $pass = env('DB_PASSWORD') ?: env('DB_PASS') ?: env('MYSQL_PASSWORD') ?: env('PASSWORD') ?: '';
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
