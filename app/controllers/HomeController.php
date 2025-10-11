<?php

/**
 * Contrôleur pour la page d'accueil
 */

class HomeController extends BaseController
{
    /**
     * Page d'accueil
     */
    public function index(): void
    {
        $this->render('home/index');
    }

    /**
     * Page à propos
     */
    public function about(): void
    {
        $this->render('home/about');
    }
}
