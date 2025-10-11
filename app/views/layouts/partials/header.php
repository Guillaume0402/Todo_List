<?php
// Partiel header
// Note: la session est initialisée par BaseController via Auth, pas besoin d'inclure lib/session.php
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TyckyList - Gestionnaire de listes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <!-- MENU MOBILE EN DEHORS DU HEADER POUR Z-INDEX MAXIMAL -->
    <div class="mobile-nav" id="mobileNav">
        <div class="mobile-nav-content">
            <button class="mobile-close" id="mobileClose" aria-label="Fermer le menu" type="button">
                <span aria-hidden="true">&times;</span>
            </button>
            <nav class="mobile-nav-links">
                <a href="<?= AppConfig::BASE_PATH ?>?r=home/index" class="mobile-nav-link active">
                    <i class="bi bi-house-door"></i>
                    <span>Accueil</span>
                </a>
                <a href="<?= AppConfig::BASE_PATH ?>?r=lists/index" class="mobile-nav-link">
                    <i class="bi bi-list-check"></i>
                    <span>Mes listes</span>
                </a>
                <a href="#" class="mobile-nav-link">
                    <i class="bi bi-tags"></i>
                    <span>Pricing</span>
                </a>
                <a href="#" class="mobile-nav-link">
                    <i class="bi bi-question-circle"></i>
                    <span>FAQs</span>
                </a>
                <a href="<?= AppConfig::BASE_PATH ?>?r=home/about" class="mobile-nav-link">
                    <i class="bi bi-info-circle"></i>
                    <span>À propos</span>
                </a>
            </nav>
            <div class="mobile-actions">
                <?php if (isset($_SESSION['user'])) { ?>
                    <span class="mobile-welcome">Bonjour, <?= htmlspecialchars($_SESSION['user']['name'] ?? 'Utilisateur') ?></span>
                    <a href="<?= AppConfig::BASE_PATH ?>?r=auth/logout" class="btn btn-outline-danger btn-modern">
                        <i class="bi bi-box-arrow-right"></i>
                        Déconnexion
                    </a>
                <?php } else { ?>
                    <a href="<?= AppConfig::BASE_PATH ?>?r=auth/login" class="btn btn-outline-light btn-modern">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Connexion
                    </a>
                    <a href="#" class="btn btn-primary btn-modern">
                        <i class="bi bi-person-plus"></i>
                        S'inscrire
                    </a>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="content-wrapper">
        <!-- Header moderne avec design métallique -->
        <header class="modern-header mb-5">
            <div class="container-fluid">
                <nav class="custom-navbar">
                    <!-- Logo Section -->
                    <div class="brand-section">
                        <a href="<?= AppConfig::BASE_PATH ?>?r=home/index" class="brand-link">
                            <div class="logo-container">
                                <img src="/assets/images/logotickylist.png" alt="TyckyList Logo" class="logo-img">
                                <div class="brand-text">
                                    <span class="brand-name">TyckyList</span>
                                    <span class="brand-tagline">Organise tes listes</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- Mobile Menu Toggle -->
                    <button class="mobile-toggle" type="button" id="mobileToggle" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="toggle-line"></span>
                        <span class="toggle-line"></span>
                        <span class="toggle-line"></span>
                    </button>
                    <!-- Navigation Links (Desktop) -->
                    <div class="nav-section">
                        <nav class="main-nav">
                            <a href="<?= AppConfig::BASE_PATH ?>?r=home/index" class="nav-link active">
                                <i class="bi bi-house-door"></i>
                                <span>Accueil</span>
                            </a>
                            <a href="<?= AppConfig::BASE_PATH ?>?r=lists/index" class="nav-link">
                                <i class="bi bi-list-check"></i>
                                <span>Mes listes</span>
                            </a>
                            <a href="#" class="nav-link">
                                <i class="bi bi-tags"></i>
                                <span>Pricing</span>
                            </a>
                            <a href="#" class="nav-link">
                                <i class="bi bi-question-circle"></i>
                                <span>FAQs</span>
                            </a>
                            <a href="<?= AppConfig::BASE_PATH ?>?r=home/about" class="nav-link">
                                <i class="bi bi-info-circle"></i>
                                <span>À propos</span>
                            </a>
                        </nav>
                    </div>
                    <!-- User Actions (Desktop) -->
                    <div class="actions-section">
                        <?php if (isset($_SESSION['user'])) { ?>
                            <div class="user-menu">
                                <span class="user-welcome">Bonjour, <?= htmlspecialchars($_SESSION['user']['name'] ?? 'Utilisateur') ?></span>
                                <a href="<?= AppConfig::BASE_PATH ?>?r=auth/logout" class="btn btn-outline-danger btn-modern">
                                    <i class="bi bi-box-arrow-right"></i>
                                    Déconnexion
                                </a>
                            </div>
                        <?php } else { ?>
                            <div class="auth-buttons">
                                <a href="<?= AppConfig::BASE_PATH ?>?r=auth/login" class="btn btn-outline-light btn-modern">
                                    <i class="bi bi-box-arrow-in-right"></i>
                                    Connexion
                                </a>
                                <a href="#" class="btn btn-primary btn-modern">
                                    <i class="bi bi-person-plus"></i>
                                    S'inscrire
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                </nav>
            </div>
            <!-- Ligne de séparation avec effet lumineux -->
            <div class="header-separator"></div>
        </header>
        <!-- JavaScript personnalisé pour le menu mobile -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const burger = document.getElementById('mobileToggle');
                const menu = document.getElementById('mobileNav');
                const closeBtn = document.getElementById('mobileClose');
                if (burger && menu) {
                    burger.addEventListener('click', function() {
                        if (menu.classList.contains('active')) {
                            menu.classList.remove('active');
                            burger.setAttribute('aria-expanded', 'false');
                        } else {
                            menu.classList.add('active');
                            burger.setAttribute('aria-expanded', 'true');
                        }
                    });
                }
                if (closeBtn && menu && burger) {
                    closeBtn.addEventListener('click', function() {
                        menu.classList.remove('active');
                        burger.setAttribute('aria-expanded', 'false');
                    });
                }
            });
        </script>