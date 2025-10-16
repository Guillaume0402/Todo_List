<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Database;
use App\Services\ActivityLogger;
use App\Core\Auth;
use AppConfig;

class AdminDashboardController extends BaseController
{
    public function index()
    {
        if (!Auth::isAdmin()) {
            http_response_code(403);
            echo 'Accès refusé';
            exit;
        }

        $pdo = Database::getInstance();

        // 🐘 Stats SQL
        $usersCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $listsCount = $pdo->query("SELECT COUNT(*) FROM lists")->fetchColumn();
        $itemsCount = $pdo->query("SELECT COUNT(*) FROM items")->fetchColumn();
        $itemsDone  = $pdo->query("SELECT COUNT(*) FROM items WHERE is_done = 1")->fetchColumn();

        // 🍃 Stats Mongo
        $logger = new ActivityLogger();
        $logsCol = (new \MongoDB\Client(AppConfig::MONGO_DSN))
            ->selectCollection(AppConfig::MONGO_DB, 'activity_logs');

        $lastLog = $logsCol->findOne([], ['sort' => ['created_at' => -1]]);
        $logsCount = $logsCol->countDocuments();

        // Envoi vers la vue
        $this->render('admin/dashboard', compact('usersCount', 'listsCount', 'itemsCount', 'itemsDone', 'logsCount', 'lastLog'));
    }
}
