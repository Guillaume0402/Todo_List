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
            $this->redirect('/public/index.php?r=home/index');
        }

        $this->render('auth/login');
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
                $this->redirect('/public/index.php?r=home/index');
            } else {
                $this->addFlashMessage('error', 'Email ou mot de passe incorrect');
                $this->redirect('/public/index.php?r=auth/login');
            }
        } catch (Exception $e) {
            $this->addFlashMessage('error', $e->getMessage());
            $this->redirect('/public/index.php?r=auth/login');
        }
    }

    /**
     * Déconnexion
     */
    public function logout(): void
    {
        Auth::logout();
        $this->redirect('/public/index.php?r=auth/login');
    }
}
