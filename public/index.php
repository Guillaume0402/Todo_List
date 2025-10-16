<?php
// Mini-routeur basique via ?r=controller/action

// Charge l'autoload Composer pour récupérer les classes du projet
require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Http;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\ListController;


// Charge la configuration de l'application
if (!defined('APP_DEBUG')) {
    define('APP_DEBUG', \AppConfig::DEBUG_MODE);
}

// Configure l'affichage des erreurs en fonction du mode debug
ini_set('session.gc_maxlifetime', (string)\AppConfig::SESSION_LIFETIME);

// Démarre la session avec des options sécurisées
$secure = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
session_start([
    'cookie_lifetime' => \AppConfig::SESSION_LIFETIME,
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax',
    'cookie_domain'   => \AppConfig::SESSION_DOMAIN,
    'use_strict_mode' => true,
    'cookie_secure'   => $secure, // 👈 ajouté
]);





// Parse le paramètre ?r=controller/action et applique home/index par défaut
$r = $_GET['r'] ?? 'home/index';
[$controller, $action] = array_pad(explode('/', $r, 2), 2, 'index');

try {
    // Oriente vers le bon contrôleur puis l'action demandée
    switch ($controller) {
        case 'home':
            $ctrl = new HomeController();
            if ($action === 'about') $ctrl->about();
            else $ctrl->index();
            break;
        case 'auth':
            $ctrl = new AuthController();
            if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') $ctrl->login();
            elseif ($action === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') $ctrl->register();
            elseif ($action === 'register') $ctrl->showRegister();
            elseif ($action === 'logout') $ctrl->logout();
            else $ctrl->showLogin();
            break;
        case 'lists':
            $ctrl = new ListController();
            if ($action === 'index') $ctrl->index();
            elseif ($action === 'form') $ctrl->form();
            elseif ($action === 'saveList') $ctrl->saveList();
            elseif ($action === 'saveItem') $ctrl->saveItem();
            elseif ($action === 'deleteItem') $ctrl->deleteItem();
            elseif ($action === 'updateItemStatus') $ctrl->updateItemStatus();
            else Http::abort(404);                 
            break;
        case 'admin':
            $ctrl = new \App\Controllers\AdminDashboardController();
            if ($action === 'dashboard') {
                $ctrl->index();
            } else {
                Http::abort(404);
            }
            break;


        default:
            Http::abort(404);
    }
} catch (\Throwable $e) {
    // En dev : afficher l'erreur
    if (\AppConfig::DEBUG_MODE) {
        throw $e;
    }
    // En prod : log + page 500
    error_log($e);
    Http::abort(500);
}



/* 
Note : pour une vraie appli, utiliser un vrai routeur (FastRoute, Symfony Routing, etc.)
et un vrai framework MVC (Laravel, Symfony, etc.) pour plus de sécurité et de fonctionnalités. 
Ceci est un exemple très simplifié pour illustrer le concept.
Ne pas oublier de protéger les pages privées (ex: listes) via un middleware ou dans les contrôleurs.
Ne pas oublier de valider/sanitizer les entrées utilisateurs (ex: via filter_input, htmlspecialchars, etc.)
Ne pas oublier de protéger contre CSRF (ex: via un token dans les formulaires)
Ne pas oublier de protéger contre les injections SQL (ex: via des requêtes préparées PDO)
Ne pas oublier de gérer les sessions de manière sécurisée (ex: session_start avec des options sécurisées)
Ne pas oublier de gérer les erreurs et exceptions de manière appropriée (ex: try/catch, logging, etc.)   
Ne pas oublier de configurer correctement le serveur web (ex: Apache, Nginx) pour la réécriture d'URL et la sécurité
Ne pas oublier de configurer correctement les permissions des fichiers et dossiers (ex: chmod, chown) pour la sécurité
Ne pas oublier de tester l'application de manière approfondie (ex: tests unitaires, tests fonctionnels, tests de sécurité, etc.)
Ne pas oublier de documenter le code et l'architecture de l'application pour faciliter la maintenance et l'évolution future.
Ne pas oublier de suivre les bonnes pratiques de développement (ex: PSR, SOLID, DRY, KISS, YAGNI, etc.)
Ne pas oublier de mettre en place un système de déploiement et de mise à jour (ex: CI/CD, Docker, etc.)
Ne pas oublier de surveiller et d'analyser les performances et la sécurité de l'application en production (ex: logs, monitoring, alerting, etc.)
Ne pas oublier de sauvegarder régulièrement les données importantes (ex: base de données, fichiers, etc.)
Ne pas oublier de respecter les lois et régulations en vigueur (ex: RGPD, CCPA, etc.) concernant la vie privée et la protection des données utilisateurs.
Ne pas oublier de consulter la documentation officielle et les ressources en ligne pour approfondir ses connaissances et rester à jour avec les évolutions technologiques.
Ne pas oublier de demander de l'aide ou des conseils à la communauté ou à des experts si nécessaire.
Ne pas oublier de prendre du recul et de réfléchir à l'architecture globale et aux choix technologiques pour garantir la pérennité et la scalabilité de l'application. 
*/
