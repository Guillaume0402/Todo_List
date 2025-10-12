<?php
// Mini-routeur basique via ?r=controller/action

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\ListController;

$r = $_GET['r'] ?? 'home/index';
[$controller, $action] = array_pad(explode('/', $r, 2), 2, 'index');

try {
    switch ($controller) {
        case 'home':
            $ctrl = new HomeController();
            if ($action === 'about') $ctrl->about();
            else $ctrl->index();
            break;
        case 'auth':
            $ctrl = new AuthController();
            if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') $ctrl->login();
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
            (new HomeController())->index();
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo '<pre style="color:#fff;background:#222;padding:1rem;border-radius:8px">';
    echo 'Erreur: ' . htmlspecialchars($e->getMessage());
    echo "\n\n" . htmlspecialchars($e->getTraceAsString());
    echo '</pre>';
}
