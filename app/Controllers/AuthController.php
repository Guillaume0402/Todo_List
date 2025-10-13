<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Auth;
use App\Models\UserModel;
use Exception;

/**
 * Contrôleur d'authentification
 */

class AuthController extends BaseController
{
    private UserModel $userModel;

    /**
     * Initialise le modèle utilisateur
     */
    public function __construct()
    {
        parent::__construct();
        $this->userModel = new UserModel();
    }

    /**
     * Affiche la page de connexion
     */
    public function showLogin(): void
    {
        // Si déjà connecté, rediriger vers l'accueil
        if (Auth::isLoggedIn()) {
            $this->redirect(\AppConfig::BASE_PATH . '?r=home/index');
        }
        // Pré-remplir l'email si présent en flash (après échec)
        $prefillEmail = $_SESSION['last_login_email'] ?? '';
        unset($_SESSION['last_login_email']);
        $this->render('auth/login', ['prefillEmail' => $prefillEmail]);
    }

    /**
     * Affiche la page d'inscription
     */
    public function showRegister(): void
    {
        if (Auth::isLoggedIn()) {
            $this->redirect(\AppConfig::BASE_PATH . '?r=home/index');
        }
        $prefillEmail = $_SESSION['last_register_email'] ?? '';
        $prefillName = $_SESSION['last_register_name'] ?? '';
        unset($_SESSION['last_register_email'], $_SESSION['last_register_name']);
        $this->render('auth/register', [
            'prefillEmail' => $prefillEmail,
            'prefillName' => $prefillName,
        ]);
    }

    /**
     * Traite la connexion
     */
    public function login(): void
    {
        try {
            $data = $this->validatePostRequest(['email', 'password']);

            // Normaliser l’email (comme au register)
            $email = mb_strtolower(trim($data['email'] ?? ''), 'UTF-8');

            $user = $this->userModel->verifyLogin($email, $data['password']);

            if ($user) {
                // Sécuriser la session (anti-fixation)
                if (session_status() !== PHP_SESSION_ACTIVE) {
                    session_start();
                }
                session_regenerate_id(true);

                Auth::login($user);
                $this->addFlashMessage('success', 'Connexion réussie');
                $this->redirect(\AppConfig::BASE_PATH . '?r=home/index');
            } else {
                // Petite pause pour limiter le bruteforce
                $delayMs = random_int(200, 500);
                usleep($delayMs * 1000);

                $this->addFlashMessage('error', 'Identifiants invalides.');
                $_SESSION['last_login_email'] = $email; // email normalisé
                $this->redirect(\AppConfig::BASE_PATH . '?r=auth/login');
            }
        } catch (Exception $e) {
            if (isset($email)) {
                $_SESSION['last_login_email'] = $email;
            } elseif (isset($_POST['email'])) {
                $_SESSION['last_login_email'] = $_POST['email'];
            }

            $this->addFlashMessage('error', $e->getMessage());
            $this->redirect(\AppConfig::BASE_PATH . '?r=auth/login');
        }
    }


    /**
     * Traite l'inscription
     */
    public function register(): void
    {
        try {
            $data = $this->validatePostRequest(['name', 'email', 'password', 'password_confirm']);

            // Normalisation
            $data['email'] = mb_strtolower(trim($data['email'] ?? ''), 'UTF-8');
            $data['name']  = trim($data['name'] ?? '');
            $data['password'] = (string)($data['password'] ?? '');
            $data['password_confirm'] = (string)($data['password_confirm'] ?? '');

            // Validation email (après normalisation)
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Adresse email invalide');
            }

            // Règles mot de passe
            $minLength = (int)\AppConfig::PASSWORD_MIN_LENGTH;
            $maxLength = 72; // bcrypt
            if (mb_strlen($data['password'], 'UTF-8') < $minLength) {
                throw new Exception("Le mot de passe doit contenir au moins {$minLength} caractères");
            }
            if (mb_strlen($data['password'], 'UTF-8') > $maxLength) {
                throw new Exception("Le mot de passe est trop long (max {$maxLength} caractères).");
            }

            // Nom affiché (optionnel)
            if ($data['name'] !== '' && mb_strlen($data['name'], 'UTF-8') > 100) {
                throw new Exception("Le nom affiché est trop long (max 100 caractères).");
            }

            // Confirmation MDP
            if ($data['password'] !== $data['password_confirm']) {
                throw new Exception('Les mots de passe ne correspondent pas');
            }

            // Doublon email (pré-check)
            if ($this->userModel->getByEmail($data['email'])) {
                throw new Exception('Un compte existe déjà pour cet email');
            }

            unset($data['password_confirm']);
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

            try {
                $isSaved = $this->userModel->save([
                    'email'        => $data['email'],
                    'password'     => $hashedPassword,
                    'display_name' => $data['name'],
                ]);
            } catch (\PDOException $e) { // \PDOException (note le backslash)
                if ($e->getCode() === '23000') {
                    // Violation de contrainte UNIQUE (email déjà pris)
                    throw new Exception('Un compte existe déjà pour cet email');
                }
                throw $e; // autre erreur SQL -> remonter
            }


            if (!$isSaved) {
                throw new Exception("Impossible d'enregistrer le compte pour le moment");
            }

            $user = $this->userModel->getByEmail($data['email']);
            if (!$user) {
                throw new Exception('Utilisateur introuvable après inscription');
            }

            // Sécuriser la session (anti fixation)
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }
            session_regenerate_id(true);

            Auth::login($user);
            $this->addFlashMessage('success', 'Inscription réussie, bienvenue !');
            $this->redirect(\AppConfig::BASE_PATH . '?r=home/index');
        } catch (Exception $e) {
            $this->addFlashMessage('error', $e->getMessage());
            if (isset($_POST['email'])) {
                $_SESSION['last_register_email'] = $_POST['email'];
            }
            if (isset($_POST['name'])) {
                $_SESSION['last_register_name'] = $_POST['name'];
            }
            $this->redirect(\AppConfig::BASE_PATH . '?r=auth/register');
        }
    }

    /**
     * Déconnexion
     */
    public function logout(): void
    {
        Auth::logout();
        $this->redirect(\AppConfig::BASE_PATH . '?r=auth/login');
    }
}
