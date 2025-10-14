<section class="container my-5">
    <div class="card bg-dark text-light p-3 m-5">
        <h2 class="mb-3 text-primary">Créer un compte</h2>
        <form id="registerForm" method="post" action="<?= AppConfig::BASE_PATH ?>?r=auth/register" autocomplete="off" novalidate>
            <input type="hidden" name="<?= htmlspecialchars(AppConfig::CSRF_TOKEN_NAME) ?>" value="<?= htmlspecialchars($csrfToken) ?>" />

            <div class="mb-3">
                <label for="name" class="form-label">Nom d'affichage</label>
                <input id="name" type="text" name="name" class="form-control" required
                    aria-describedby="nameError"
                    value="<?= isset($prefillName) ? htmlspecialchars($prefillName) : '' ?>" />
                <div id="nameError" class="invalid-feedback">Le nom d’affichage est requis.</div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Adresse email</label>
                <input id="email" type="email" name="email" class="form-control" required
                    aria-describedby="emailError"
                    value="<?= isset($prefillEmail) ? htmlspecialchars($prefillEmail) : '' ?>" />
                <div id="emailError" class="invalid-feedback">Veuillez entrer une adresse email valide.</div>
            </div>

            <div class="mb-3 position-relative">
                <label for="password" class="form-label">Mot de passe</label>
                <div class="input-group">
                    <input id="password" type="password" name="password" class="form-control" required
                        minlength="<?= (int)AppConfig::PASSWORD_MIN_LENGTH ?>"
                        aria-describedby="passwordError" />
                    <button type="button" class="btn btn-outline-secondary toggle-password" tabindex="-1">👁</button>
                </div>
                <div id="passwordError" class="invalid-feedback">
                    Le mot de passe doit contenir au moins <?= (int)AppConfig::PASSWORD_MIN_LENGTH ?> caractères.
                </div>
            </div>

            <div class="mb-3 position-relative">
                <label for="password_confirm" class="form-label">Confirmation du mot de passe</label>
                <div class="input-group">
                    <input id="password_confirm" type="password" name="password_confirm" class="form-control" required
                        minlength="<?= (int)AppConfig::PASSWORD_MIN_LENGTH ?>"
                        aria-describedby="confirmError" />
                    <button type="button" class="btn btn-outline-secondary toggle-password" tabindex="-1">👁</button>
                </div>
                <div id="confirmError" class="invalid-feedback">Les mots de passe ne correspondent pas.</div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-primary" type="submit">Inscription</button>
                <a class="link-light" href="<?= AppConfig::BASE_PATH ?>?r=auth/login">Déjà inscrit ? Se connecter</a>
            </div>
        </form>
    </div>
</section>