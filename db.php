<?php
// Database Configuration for SEVZO E-Commerce
$host = 'localhost';
$db   = 'sevzo';
$user = 'root';
$pass = '';

// Load production configuration if exists
if (file_exists(__DIR__ . '/config.php')) {
    include __DIR__ . '/config.php';
}
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     // If database doesn't exist yet, we can connect without db name to help initialize it if needed.
     try {
         $pdo_init = new PDO("mysql:host=$host;charset=$charset", $user, $pass, $options);
         $pdo_init->exec("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
         $pdo = new PDO($dsn, $user, $pass, $options);
     } catch (\PDOException $ex) {
         throw new \PDOException($ex->getMessage(), (int)$ex->getCode());
     }
}
?>
