<?php
// Mini-routeur basique via ?r=controller/action

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Core/Auth.php';
require_once __DIR__ . '/../app/Core/BaseController.php';

// Charge les modèles nécessaires
require_once __DIR__ . '/../app/models/BaseModel.php';
require_once __DIR__ . '/../app/models/UserModel.php';
require_once __DIR__ . '/../app/models/ListModel.php';
require_once __DIR__ . '/../app/models/CategoryModel.php';
require_once __DIR__ . '/../app/models/ItemModel.php';

// Charge les contrôleurs
require_once __DIR__ . '/../app/controllers/HomeController.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/ListController.php';

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
