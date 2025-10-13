<?php
// Mini-routeur basique via ?r=controller/action

// Charge l'autoload Composer pour récupérer les classes du projet
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\ListController;

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
            else $ctrl->index();
            break;
        default:
            // Route inconnue : retour à l'accueil
            (new HomeController())->index();
    }
} catch (Throwable $e) {
    // Affiche une erreur lisible en cas d'exception non gérée
    http_response_code(500);
    echo '<pre style="color:#fff;background:#222;padding:1rem;border-radius:8px">';
    echo 'Erreur: ' . htmlspecialchars($e->getMessage());
    echo "\n\n" . htmlspecialchars($e->getTraceAsString());
    echo '</pre>';
}
