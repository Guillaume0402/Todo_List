<?php $title = "Erreur serveur (500)"; ?>
<main class="container py-5" role="main" aria-labelledby="e500-title">
  <div class="card bg-dark text-light p-4">
    <h1 id="e500-title" class="h3 text-primary mb-3">500 — Erreur interne du serveur</h1>
    <p>Un problème est survenu. Réessayez plus tard.</p>
    <a class="btn btn-primary mt-3" href="<?= AppConfig::BASE_PATH ?>?r=home/index">Retour à l’accueil</a>
  </div>
</main>
