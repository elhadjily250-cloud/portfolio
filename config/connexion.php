<?php

define('DB_HOST', 'sql103.infinityfree.com');
define('DB_NOM',  'if0_41868842_portfolio');
define('DB_USER', 'if0_41868842');
define('DB_PASS', 'agFlYDJL5932SK');

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NOM . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    error_log($e->getMessage());
    die('Erreur de connexion : ' . $e->getMessage());
}
?>