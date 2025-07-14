<?php

require_once __DIR__ . "/../.env.php";

// connexion à la base de données

// on utilise l'objet PDO pour se connecter à la base de données
try {
    // on crée une instance de la classe PDO qui représente la connexion à la base de données
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS
    );
}
// en cas d'erreur, on affiche un message et on arrête le script
catch (Exception $e) {
    // on affiche un message d'erreur
    die('Erreur SQL : ' . $e->getMessage());
}
?>