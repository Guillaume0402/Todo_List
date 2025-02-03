<?php
// on crée une session pour stocker les données de l'utilisateur connecté 
session_set_cookie_params([
    // on définit la durée de vie de la session à 1 heure
    'lifetime' => 3600,
    // on définit le chemin de la session à la racine du serveur
    'path' => '/',
    // on définit le domaine de la session à la racine du serveur
    'domain' => '.checkit.local',
    // on définit si la session doit être transmise uniquement via le protocole HTTP    
    'httponly' => true,
]);

// on démarre la session
session_start();
// on crée une fonction pour vérifier si l'utilisateur est connecté
function isUserConnected(): bool
{
    // on vérifie si l'indice user existe dans la session
    return isset($_SESSION['user']);
}