<?php
$host     = 'localhost';
$dbname   = 'coningen_udp252_losk';
$user     = 'coningen_udp252_losk';
$password = 'ubf59fVSk9Kdbpu3aKvz';

$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $password, $options);
} catch (PDOException $e) {
    die('Error de conexión a la BD: ' . $e->getMessage());
}
