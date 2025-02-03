<?php

// fonction pour vérifier si l'utilisateur existe et si le mot de passe est correct
function verifyUserLoginPassword(PDO $pdo, string $email, string $password)
{
    // on prépare la requête pour récupérer l'utilisateur
    $query = $pdo->prepare('SELECT * FROM user WHERE email = :email');
    // on exécute la requête en passant le paramètre email à la place du :email dans la requête SQL 
    $query->bindValue(':email', $email, PDO::PARAM_STR);
    // on exécute la requête
    $query->execute();
    // on récupère le résultat de la requête sous forme de tableau associatif (FETCH_ASSOC)
    $user = $query->fetch(PDO::FETCH_ASSOC);
    // on vérifie si l'utilisateur existe et si le mot de passe est correct
    if ($user && password_verify($password, $user['password'])) { 
       // verif ok      
        return $user;
    } else {
        // email ou mdp incorrect: on retourne false
        return false;
    }
}