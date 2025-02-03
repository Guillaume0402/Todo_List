<?php
// on inclut le fichier header.php pdo.php et user.php sur la page
require_once __DIR__ . "/templates/header.php";
require_once __DIR__ . "/lib/session.php";
require_once __DIR__ . "/lib/pdo.php";
require_once __DIR__ . "/lib/user.php";

// on initialise un tableau d'erreurs
$errors = [];

// si le formulaire a été soumis
if (isset($_POST['loginUser'])) {
    // on vérifie si l'utilisateur existe et si le mot de passe est correct
    $user = verifyUserLoginPassword($pdo, $_POST['email'], $_POST['password']);
    // si l'utilisateur existe
    if ($user) {
        // on va le connecter => session
        $_SESSION['user'] = $user;
        // on redirige l'utilisateur vers la page d'accueil
        header('Location: index.php');
    } else {
        // on affiche un message d'erreur
        $errors[] = "Email ou mot de passe incorrect";
    }

}
// on affiche le contenu de la session
// echo '<pre>';
// print_r($_SESSION);
// echo '</pre>';

?>


<div class="container col-xxl-8 px-4 py-5">
    <h1>Se connecter</h1>

    <?php
    // on affiche les erreurs éventuelles sous forme de message d'alerte bootstrap 
    foreach ($errors as $error) { ?>
        <div class="alert alert-danger" role="alert">
            <?= $error; ?>
        </div>

    <?php }
    ?>

    <form action="" method="post">
        <div class="mb-3">
            <label for="email" class="form-label">Adresse email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <input type="submit" name="loginUser" class="btn btn-primary" value="Connexion"></input>
    </form>


</div>

<?php
require_once __DIR__ . "/templates/footer.php";
?>