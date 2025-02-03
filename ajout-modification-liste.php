<?php require_once __DIR__ . "/templates/header.php";
require_once __DIR__ . "/lib/pdo.php";
require_once __DIR__ . "/lib/list.php";
require_once __DIR__ . "/lib/category.php";

// si l'utilisateur n'est pas connecté on le redirige vers la page de connexion 
if (!isUserConnected()) {
    header('Location: login.php');
}

// on récupère les catégories pour les afficher dans le formulaire de liste
$categories = getCategories($pdo);

// on initialise les variables pour les erreurs et les messages de succès 
$errorsList = [];
$errorsListItem = [];
$messagesList = [];

// on initialise un tableau vide pour les données du formulaire de liste 
$list = [
    'title' => '',
    'category_id' => ''
];

// Le formulaire d'ajout/modif de liste a été envoyé 
// On vérifie si le formulaire a été envoyé
if (isset($_POST['saveList'])) {
    // On vérifie si le titre n'est pas vide
    if (!empty($_POST['title'])) {
        // On initialise la variable id à null
        $id = null;
        // On vérifie si on a un id dans l'url
        if (isset($_GET['id'])) {
            // On récupère l'id dans l'url
            $id = $_GET['id'];
        }
        // On appelle la fonction saveList pour enregistrer la liste dans la base de données 
        $res = saveList($pdo, $_POST['title'], (int) $_SESSION['user']['id'], $_POST['category_id'], $id);
        // On vérifie si la liste a bien été enregistrée
        if ($res) {
            // on affiche un message de succès
            if ($id) {
                $messagesList[] = "La liste a bien été mise à jour";
                // on redirige l'utilisateur vers la page de la liste en cours d'édition 
            } else {
                header('Location: ajout-modification-liste.php?id=' . $res);
            }
        } else {
            // on affiche un message d'erreur 
            $errorsList[] = "La liste n'a pas été enregistrée";
        }
    } else {
        // on affiche un message d'erreur 
        $errorsList[] = "Le titre est obligatoire";
    }
}

// Le formulaire d'ajout/modif d'item a été envoyé 
if (isset($_POST['saveListItem'])) {
    // On vérifie si le nom de l'item n'est pas vide
    if (!empty($_POST['name'])) {
        // on vérifie si on a un id d'item dans le formulaire si oui on le récupère sinon on le met à null
        $item_id = (isset($_POST['item_id']) ? $_POST['item_id'] : null);
        // on appelle la fonction saveListItem pour enregistrer l'item dans la base de données 
        $res = saveListItem($pdo, $_POST['name'], (int) $_GET['id'], false, $item_id);
    } else {
        // erreur 
        $errorsListItem[] = "Le nom de l'item est obligatoire";
    }
}

if (isset($_GET['action']) && isset($_GET['item_id'])) {
    // On vérifie si l'action est deleteListItem
    if ($_GET['action'] == 'deleteListItem') {
        // On appelle la fonction deleteListItem pour supprimer l'item de la liste
        $res = deleteListItemById($pdo, (int) $_GET['item_id']);
        header('Location: ajout-modification-liste.php?id=' . $_GET['id']);
    }
    if ($_GET['action'] == 'updateStatusListItem') {
        $res = updateStatusListItem($pdo, (int) $_GET['item_id'], (bool) $_GET['status']);
        if (isset($_GET['redirect']) && $_GET['redirect'] === 'list') {
            header('Location: mes-listes.php');
        } else {
            header('Location: ajout-modification-liste.php?id=' . $_GET['id']);
        }
    }
}



// On est en mode édition de liste ?  
$editMode = false;
// On a un id dans l'url ? 
if (isset($_GET['id'])) {
    // On récupère la liste par son id pour l'afficher dans le formulaire 
    $list = getListById($pdo, (int) $_GET['id']);
    // On passe en mode édition
    $editMode = true;
    // On récupère les items de la liste
    $items = getListItems($pdo, (int) $_GET['id']);
}
?>
<div class="container col-xxl-8 ">
    <h1>Liste</h1>
    <!-- On affiche les erreurs éventuelles -->
    <?php foreach ($errorsList as $error) { ?>
        <div class="alert alert-danger">
            <?= $error ?>
        </div>
    <?php } ?>
    <!-- On affiche les messages de succès éventuels -->
    <?php foreach ($messagesList as $message) { ?>
        <div class="alert alert-success">
            <?= $message ?>
        </div>
    <?php } ?>
</div>
<div class="accordion container" id="accordionExample">
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button <?= ($editMode) ? 'collapsed' : '' ?>" type="button"
                data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded=<?= ($editMode) ? 'false' : 'true' ?> aria-controls="collapseOne">
                <!-- Si on est en mode édition on affiche le titre de la liste, sinon on affiche "Ajouter une liste" -->
                <?= ($editMode) ? $list['title'] : 'Ajouter une liste' ?>
            </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse <?= ($editMode) ? '' : 'show' ?>"
            data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <form action="" method="post">
                    <div class="mb-3">
                        <label for="title" class="form-label">Titre</label>
                        <!-- On affiche le titre de la liste si on est en mode édition -->
                        <input type="text" value="<?= $list['title']; ?>" name="title" id="title" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Titre</label>
                        <select name="category_id" id="category_id" class="form-control">
                            <!-- On boucle sur les catégories pour les afficher dans le select -->
                            <?php foreach ($categories as $category) { ?>
                                <!-- On vérifie si la catégorie est celle de la liste en cours d'édition -->
                                <option <?=
                                    ($category['id'] == $list['category_id']) ? 'selected="selected"' : '';
                                ?> value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <input type="submit" value="Enregistrer" name="saveList" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <?php if (!$editMode) { ?>
            <div class="alert alert-warning">
                Après avoir enregistré vous pourrez ajouter les Items
            </div>
        <?php } else { ?>
            <h2 class="border-top pt-3">Items</h2>
            <!-- On affiche les erreurs éventuelles -->
            <?php foreach ($errorsListItem as $error) { ?>
                <div class="alert alert-danger">
                    <?= $error ?>
                </div>
            <?php } ?>
            <form method="post" class="d-flex">
                <input type="checkbox" name="status" id="status">
                <input type="text" name="name" id="name" placeholder="Ajouter un item" class="form-control mx-2">
                <input type="submit" name="saveListItem" class="btn btn-primary" value="Enregistrer">
            </form>
            <div class="row m-4 border rounded p-2 ">
                <?php foreach ($items as $item) { ?>
                    <div class="accordion mb-2">
                        <div class="accordion-item" id="accordion-parent-<?= $item['id'] ?>">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse-item-<?= $item['id'] ?>" aria-expanded='false'
                                    aria-controls="collapse-item-<?= $item['id'] ?>">
                                    <a class="me-2"
                                        href="?id=<?= $_GET['id'] ?>&action=updateStatusListItem&item_id=<?= $item['id'] ?>&status=<?= !$item['status'] ?>"><i
                                            class="bi bi-check-circle<?= ($item['status'] ? '-fill' : '') ?>"></i></a>
                                    <?= $item['name'] ?>
                                </button>
                            </h2>
                            <div id="collapse-item-<?= $item['id'] ?>" class="accordion-collapse collapse"
                                data-bs-parent="#accordion-parent-<?= $item['id'] ?>">
                                <div class="accordion-body">
                                    <form action="" method="post">
                                        <div class="mb-3 d-flex">
                                            <input type="text" value="<?= $item['name']; ?>" name="name" class="form-control">
                                            <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                                            <input type="submit" value="Enregistrer" name="saveListItem"
                                                class="btn btn-primary">
                                        </div>
                                    </form>
                                    <a class="btn btn-outline-primary"
                                        href="?id=<?= $_GET['id'] ?>&action=deleteListItem&item_id=<?= $item['id'] ?>"
                                        onclick="return confirm('Etes-vous sur de vouloir supprimer cette item')"><i
                                            class="bi bi-trash3-fill"></i> Supprimer</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</div>


<?php require_once __DIR__ . "/templates/footer.php"; ?>