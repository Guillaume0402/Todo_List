<?php
// Vue d'accueil (contenu déplacé depuis index.php)
?>
<main>
    <!-- Section Hero -->
    <section class="hero-section mt-5">
        <div class="container col-xxl-8 px-4 py-5">
            <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
                <div class="col-10 col-sm-8 col-lg-6">
                    <img src="/assets/images/logotickylist.png" class="d-block mx-lg-auto img-fluid" alt="Logo TyckyList"
                        width="500" loading="lazy">
                </div>
                <div class="col-lg-6">
                    <h1 class="display-5 fw-bold lh-1 mb-3">Gardez vos listes avec vous !</h1>
                    <p class="lead">Bienvenue sur TyckyList, votre nouvelle plateforme de création de listes de tâches en
                        ligne. Avec TyckyList, vous pouvez facilement créer des listes de choses à faire pour tous les aspects
                        de votre vie. Que vous planifiez votre prochain voyage, que vous organisiez votre travail ou que
                        vous fassiez des courses, TyckyList vous aide à rester organisé et à suivre vos tâches en toute
                        simplicité.</p>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                        <a href="<?= AppConfig::BASE_PATH ?>?r=lists/index" class="btn btn-primary btn-lg px-4 me-md-2">Commencer</a>
                        <a href="<?= AppConfig::BASE_PATH ?>?r=home/about" class="btn btn-outline-secondary btn-lg px-4">En savoir plus</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Fonctionnalités -->
    <section class="features-section">
        <div class="container col-xxl-8 px-4 py-5">
            <div class="row text-center">
                <header class="section-header mb-5">
                    <h2>Découvrez les fonctionnalités principales</h2>
                </header>
                <div class="col-md-4 my-2">
                    <article class="card w-100 h-100">
                        <div class="card-header">
                            <i class="bi bi-card-checklist" aria-hidden="true"></i>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h3 class="card-title">Créer un nombre illimité de listes</h3>
                            <p class="card-text flex-grow-1">Organisez vos tâches sans limite avec notre système de listes personnalisables.</p>
                            <a href="<?= AppConfig::BASE_PATH ?>?r=auth/login" class="btn btn-primary mt-auto">S'inscrire</a>
                        </div>
                    </article>
                </div>
                <div class="col-md-4 my-2">
                    <article class="card w-100 h-100">
                        <div class="card-header">
                            <i class="bi bi-tags-fill" aria-hidden="true"></i>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h3 class="card-title">Classer les listes par catégories</h3>
                            <p class="card-text flex-grow-1">Triez et organisez vos listes avec un système de catégories intuitif.</p>
                            <a href="<?= AppConfig::BASE_PATH ?>?r=auth/login" class="btn btn-primary mt-auto">S'inscrire</a>
                        </div>
                    </article>
                </div>
                <div class="col-md-4 my-2">
                    <article class="card w-100 h-100">
                        <div class="card-header">
                            <i class="bi bi-search" aria-hidden="true"></i>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h3 class="card-title">Retrouver facilement vos listes</h3>
                            <p class="card-text flex-grow-1">Recherchez et accédez rapidement à vos listes grâce à notre moteur de recherche.</p>
                            <a href="<?= AppConfig::BASE_PATH ?>?r=auth/login" class="btn btn-primary mt-auto">S'inscrire</a>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>
</main>