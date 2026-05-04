<?php
$host = 'localhost';
$db   = 'ortobase';
$user = 'root';
$pass = '';
$dsn  = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$opts = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $opts);
} catch (PDOException $e) {
    http_response_code(500);
    echo '<p style="font-family:sans-serif;color:red;padding:2rem">
            Ошибка подключения к БД: ' . htmlspecialchars($e->getMessage()) . '<br>
            Проверь, что запущен MySQL и импортирован <code>sql/ortobase.sql</code>.
          </p>';
    exit;
}
