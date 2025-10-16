<?php

namespace App\Core;

use function hash_equals;


/**
 * Gestionnaire de session et d'authentification
 */

class Auth
{
    /**
     * Initialise la session avec les paramètres de sécurité
     */
    public static function initSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            $params = [
                'lifetime' => \AppConfig::SESSION_LIFETIME,
                'path' => \AppConfig::APP_URL,
                'httponly' => true,
                'secure' => isset($_SERVER['HTTPS']),
                'samesite' => 'Lax'
            ];
            // N'ajouter le domaine que s'il est défini, sinon laisser le host par défaut (localhost, 127.0.0.1, etc.)
            if (!empty(\AppConfig::SESSION_DOMAIN)) {
                $params['domain'] = \AppConfig::SESSION_DOMAIN;
            }
            session_set_cookie_params($params);
            session_start();
        }
    }

    /**
     * Vérifie si l'utilisateur est connecté
     */
    public static function isLoggedIn(): bool
    {
        self::initSession();
        return isset($_SESSION['user']) && !empty($_SESSION['user']['id']);
    }

    /**
     * Connecte un utilisateur et stocke ses données en session
     */
    public static function login(array $user): void
    {
        self::initSession();
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => (int)$user['id'],
            'email' => $user['email'],
            'name' => $user['display_name'] ?? null,
            'is_admin' => (bool)$user['is_admin'],
        ];
    }

    /**
     * Déconnecte l'utilisateur et détruit la session
     */
    public static function logout(): void
    {
        self::initSession();
        session_regenerate_id(true);
        session_destroy();
        unset($_SESSION);
    }

    /**
     * Récupère les données de l'utilisateur connecté
     */
    public static function getUser(): ?array
    {
        self::initSession();
        return $_SESSION['user'] ?? null;
    }

    /**
     * Récupère l'ID de l'utilisateur connecté
     */
    public static function getUserId(): ?int
    {
        $user = self::getUser();
        return $user ? (int)$user['id'] : null;
    }

    /**
     * Force la connexion (redirige vers login si non connecté)
     */
    public static function requireAuth(): void
    {
        if (!self::isLoggedIn()) {
            header('Location: ' . \AppConfig::BASE_PATH . '?r=auth/login');
            exit;
        }
    }

    /**
     * Génère un token CSRF unique pour la protection des formulaires
     */
    public static function generateCsrfToken(): string
    {
        self::initSession();
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        // Déposer aussi un cookie CSRF (double-submit cookie) pour plus de robustesse en local
        $cookieOptions = [
            'expires' => time() + \AppConfig::SESSION_LIFETIME,
            'path' => \AppConfig::APP_URL,
            'secure' => isset($_SERVER['HTTPS']),
            'httponly' => false,
            'samesite' => 'Lax',
        ];
        if (!empty(\AppConfig::SESSION_DOMAIN)) {
            $cookieOptions['domain'] = \AppConfig::SESSION_DOMAIN;
        }
        setcookie('XSRF-TOKEN', $_SESSION['csrf_token'], $cookieOptions);
        return $_SESSION['csrf_token'];
    }

    /**
     * Vérifie la validité d'un token CSRF (session + cookie de secours)
     */
    public static function verifyCsrfToken(string $token): bool
    {
        self::initSession();
        // Vérification via la session
        if (isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token)) {
            return true;
        }
        // Fallback: double-submit cookie (utile si la session ne persiste pas en local)
        if (isset($_COOKIE['XSRF-TOKEN']) && hash_equals($_COOKIE['XSRF-TOKEN'], $token)) {
            return true;
        }
        return false;
    }

    /**
     * Vérifie si l'utilisateur connecté est un administrateur
     * (à adapter selon votre logique métier)
     */
    public static function isAdmin(): bool
{
    return isset($_SESSION['user']['is_admin']) && $_SESSION['user']['is_admin'] == 1;
}

}
