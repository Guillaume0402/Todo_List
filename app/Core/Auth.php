<?php

namespace App\Core;

use function hash_equals;

/**
 * Gestionnaire de session et d'authentification
 */

class Auth
{
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

    public static function isLoggedIn(): bool
    {
        self::initSession();
        return isset($_SESSION['user']) && !empty($_SESSION['user']['id']);
    }

    public static function login(array $user): void
    {
        self::initSession();
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => (int)$user['id'],
            'email' => $user['email'],
            'name' => $user['display_name'] ?? null,
        ];
    }

    public static function logout(): void
    {
        self::initSession();
        session_regenerate_id(true);
        session_destroy();
        unset($_SESSION);
    }

    public static function getUser(): ?array
    {
        self::initSession();
        return $_SESSION['user'] ?? null;
    }

    public static function getUserId(): ?int
    {
        $user = self::getUser();
        return $user ? (int)$user['id'] : null;
    }

    public static function requireAuth(): void
    {
        if (!self::isLoggedIn()) {
            header('Location: ' . \AppConfig::BASE_PATH . '?r=auth/login');
            exit;
        }
    }

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
}
