<?php
// fonction pour récupérer les catégories de liste dans la base de données 
function getCategories(PDO $pdo): array
{
    // on prépare la requête pour récupérer les catégories de liste                               
    $query = $pdo->prepare('SELECT * FROM category');
    // on exécute la requête
    $query->execute();
    // on récupère le résultat de la requête sous forme de tableau associatif (FETCH_ASSOC)
    return $query->fetchALL(PDO::FETCH_ASSOC);
}