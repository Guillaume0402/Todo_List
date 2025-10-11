<?php
// login.php

require __DIR__.'/.env.php';
require __DIR__.'/lib/pdo.php';
require __DIR__.'/lib/user.php';

session_start();

// 1) Traiter le POST AVANT tout affichage
$loginError = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $user = verifyUserLoginPassword($pdo, $email, $password); // SELECT * FROM users ...

    if ($user) {
        // Ex: stocker l’utilisateur en session
        $_SESSION['user'] = [
            'id'    => (int)$user['id'],
            'email' => $user['email'],
            'name'  => $user['display_name'] ?? null,
        ];
        // 2) Rediriger immédiatement et STOPPER le script
        header('Location: index.php');
        exit;
    } else {
        $loginError = "Email ou mot de passe incorrect";
    }
}

// 3) Seulement maintenant on affiche le HTML
require __DIR__.'/templates/header.php';
?>
<div class="container my-5">
  <div class="card bg-dark text-light p-4">
    <h2 class="mb-3 text-success">Se connecter</h2>

    <?php if ($loginError): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($loginError) ?></div>
    <?php endif; ?>

    <form method="post" action="login.php" autocomplete="off">
      <div class="mb-3">
        <label class="form-label">Adresse email</label>
        <input type="email" name="email" class="form-control" required />
      </div>
      <div class="mb-3">
        <label class="form-label">Mot de passe</label>
        <input type="password" name="password" class="form-control" required />
      </div>
      <button class="btn btn-success">Connexion</button>
    </form>
  </div>
</div>
<?php require __DIR__.'/templates/footer.php'; ?>
