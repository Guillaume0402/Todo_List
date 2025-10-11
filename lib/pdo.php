<?php

require_once __DIR__ . "/../.env.php"; // <-- remonte d’un seul dossier, pas deux

try {
    // Connexion PDO
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

} catch (Exception $e) {
    die('❌ Erreur SQL : ' . $e->getMessage());
}
