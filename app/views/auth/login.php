<section class="container my-5">
    <div class="card bg-dark text-light p-3 m-5">
        <h2 class="mb-3 text-success">Se connecter</h2>
        <form method="post" action="<?= AppConfig::BASE_PATH ?>?r=auth/login" autocomplete="off">
            <input type="hidden" name="<?= htmlspecialchars(AppConfig::CSRF_TOKEN_NAME) ?>" value="<?= htmlspecialchars($csrfToken) ?>" />

            <div class="mb-3">
                <label class="form-label">Adresse email</label>
                <input type="email" name="email" class="form-control" required autofocus value="<?= isset($prefillEmail) ? htmlspecialchars($prefillEmail) : '' ?>" />
            </div>

            <div class="mb-3 position-relative">
                <label class="form-label">Mot de passe</label>
                <div class="input-group">
                    <input type="password" name="password" class="form-control" required />
                    <button type="button" class="btn btn-outline-secondary toggle-password" tabindex="-1">👁</button>
                </div>
            </div>

            <button class="btn btn-success">Connexion</button>
        </form>
    </div>
</section>