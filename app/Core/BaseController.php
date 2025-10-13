<?php

namespace App\Core;

use PDO;
use Exception;
use App\Core\Database;
use App\Core\Auth;

/**
 * Contrôleur de base
 */

abstract class BaseController
{
    protected PDO $db;

    /**
     * Initialise la connexion base de données et la session
     */
    public function __construct()
    {
        $this->db = Database::getInstance();
        Auth::initSession();
    }

    /**
     * Rend une vue avec les données et le layout spécifiés
     */
    protected function render(string $view, array $data = [], ?string $layout = 'layouts/main'): void
    {
        $currentUser = Auth::getUser();
        $isLoggedIn = Auth::isLoggedIn();
        $csrfToken = Auth::generateCsrfToken();
        $flashMessages = $this->getFlashMessages();
        extract($data);
        ob_start();
        require __DIR__ . "/../views/{$view}.php";
        $content = ob_get_clean();
        if ($layout) {
            require __DIR__ . "/../views/{$layout}.php";
        } else {
            echo $content;
        }
    }

    /**
     * Redirige vers une URL
     */
    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }

    /**
     * Retourne une réponse JSON avec le code de statut
     */
    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Valide une requête POST avec vérification CSRF et champs requis
     */
    protected function validatePostRequest(array $requiredFields = []): array
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            throw new Exception('Méthode non autorisée');
        }
        $token = $_POST[\AppConfig::CSRF_TOKEN_NAME] ?? '';
        if (!Auth::verifyCsrfToken($token)) {
            throw new Exception('Token CSRF invalide');
        }
        $data = [];
        foreach ($requiredFields as $field) {
            if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
                throw new Exception("Le champ '{$field}' est requis");
            }
            $data[$field] = trim($_POST[$field]);
        }
        return $data;
    }

    /**
     * Ajoute un message flash pour l'affichage suivant
     */
    protected function addFlashMessage(string $type, string $message): void
    {
        if (!isset($_SESSION['flash_messages'])) {
            $_SESSION['flash_messages'] = [];
        }
        $_SESSION['flash_messages'][] = ['type' => $type, 'message' => $message];
    }

    /**
     * Récupère et supprime les messages flash de la session
     */
    protected function getFlashMessages(): array
    {
        $messages = $_SESSION['flash_messages'] ?? [];
        unset($_SESSION['flash_messages']);
        return $messages;
    }
}
