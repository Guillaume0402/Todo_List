<?php
// Vue: authentification - inscription
?>
<section class="container my-5">
    <div class="card bg-dark text-light p-3 m-5">
        <h2 class="mb-3 text-primary">Créer un compte</h2>

        <?php if (!empty($flashMessages)) : ?>
            <?php foreach ($flashMessages as $fm) : ?>
                <div class="alert alert-<?= htmlspecialchars($fm['type']) ?>">
                    <?= htmlspecialchars($fm['message']) ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <form method="post" action="<?= AppConfig::BASE_PATH ?>?r=auth/register" autocomplete="off">
            <input type="hidden" name="<?= htmlspecialchars(AppConfig::CSRF_TOKEN_NAME) ?>" value="<?= htmlspecialchars($csrfToken) ?>" />

            <div class="mb-3">
                <label class="form-label">Nom d'affichage</label>
                <input type="text" name="name" class="form-control" required value="<?= isset($prefillName) ? htmlspecialchars($prefillName) : '' ?>" />
            </div>

            <div class="mb-3">
                <label class="form-label">Adresse email</label>
                <input type="email" name="email" class="form-control" required value="<?= isset($prefillEmail) ? htmlspecialchars($prefillEmail) : '' ?>" />
            </div>

            <div class="mb-3">
                <label class="form-label">Mot de passe</label>
                <input type="password" name="password" class="form-control" required minlength="<?= (int)AppConfig::PASSWORD_MIN_LENGTH ?>" />
            </div>

            <div class="mb-3">
                <label class="form-label">Confirmation du mot de passe</label>
                <input type="password" name="password_confirm" class="form-control" required minlength="<?= (int)AppConfig::PASSWORD_MIN_LENGTH ?>" />
            </div>

            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-primary">Inscription</button>
                <a class="link-light" href="<?= AppConfig::BASE_PATH ?>?r=auth/login">Déjà inscrit ? Se connecter</a>
            </div>
        </form>
    </div>
</section>