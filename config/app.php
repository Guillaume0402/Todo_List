<?php

/**
 * Configuration de l'application
 */

class AppConfig
{
    public const APP_NAME = 'TyckyList';
    public const APP_VERSION = '1.0.0';
    public const APP_URL = '/';
    // Base des routes (pour éviter de répéter /public/index.php)
    public const BASE_PATH = '/public/index.php';

    // Configuration de session
    public const SESSION_LIFETIME = 3600; // 1 heure
    // Laisser vide en local pour que le cookie s'applique au host courant (ex: localhost)
    public const SESSION_DOMAIN = '';

    // Configuration de sécurité
    public const CSRF_TOKEN_NAME = '_token';
    public const PASSWORD_MIN_LENGTH = 6;

    // Pages publiques (accessibles sans authentification)
    public const PUBLIC_PAGES = [
        'home',
        'about',
        'login',
        'register'
    ];

    // Configuration des erreurs
    public const DEBUG_MODE = true;
    public const LOG_ERRORS = true;
}
