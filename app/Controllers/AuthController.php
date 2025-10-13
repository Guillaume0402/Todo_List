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

            $user = $this->userModel->verifyLogin($data['email'], $data['password']);

            if ($user) {
                Auth::login($user);
                $this->addFlashMessage('success', 'Connexion réussie');
                $this->redirect(\AppConfig::BASE_PATH . '?r=home/index');
            } else {
                $this->addFlashMessage('error', 'Email ou mot de passe incorrect');
                $_SESSION['last_login_email'] = $data['email'];
                $this->redirect(\AppConfig::BASE_PATH . '?r=auth/login');
            }
        } catch (Exception $e) {
            $this->addFlashMessage('error', $e->getMessage());
            if (isset($_POST['email'])) {
                $_SESSION['last_login_email'] = $_POST['email'];
            }
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

            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Adresse email invalide');
            }

            $minLength = (int)\AppConfig::PASSWORD_MIN_LENGTH;
            if (strlen($data['password']) < $minLength) {
                throw new Exception("Le mot de passe doit contenir au moins {$minLength} caractères");
            }

            if ($data['password'] !== $data['password_confirm']) {
                throw new Exception('Les mots de passe ne correspondent pas');
            }

            if ($this->userModel->getByEmail($data['email'])) {
                throw new Exception('Un compte existe déjà pour cet email');
            }

            unset($data['password_confirm']);

            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

            $isSaved = $this->userModel->save([
                'email' => $data['email'],
                'password' => $hashedPassword,
                'display_name' => $data['name'],
            ]);

            if (!$isSaved) {
                throw new Exception("Impossible d'enregistrer le compte pour le moment");
            }

            $user = $this->userModel->getByEmail($data['email']);

            if (!$user) {
                throw new Exception('Utilisateur introuvable après inscription');
            }

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
