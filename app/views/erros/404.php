<?php $title = "Page introuvable (404)"; ?>
<main class="container py-5" role="main" aria-labelledby="e404-title">
  <div class="card bg-dark text-light p-4">
    <h1 id="e404-title" class="h3 text-primary mb-3">404 — Page introuvable</h1>
    <p>La page demandée n’existe pas ou a été déplacée.</p>
    <div class="d-flex gap-2 mt-3">
      <a class="btn btn-primary" href="<?= AppConfig::BASE_PATH ?>?r=home/index">Retour à l’accueil</a>
      <a class="btn btn-outline-primary" href="<?= AppConfig::BASE_PATH ?>?r=lists/index">Mes listes</a>
    </div>
  </div>
</main>
