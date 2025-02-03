<?php

// connexion à la base de données

// on utilise l'objet PDO pour se connecter à la base de données
try {
    // on crée une instance de la classe PDO qui représente la connexion à la base de données
    $pdo = new PDO("mysql:dbname=studi_checkit;host=localhost;charset=utf8mb4", "root", "");
}
// en cas d'erreur, on affiche un message et on arrête le script
catch (Exception $e) {
    // on affiche un message d'erreur
    die('Erreur SQL : ' . $e->getMessage());
}
?>