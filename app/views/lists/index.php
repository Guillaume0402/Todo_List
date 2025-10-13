<?php
// Vue: listes index
?>
<div class='container'>
    <div class="d-flex justify-content-between align-items-center">
        <h1>Mes listes</h1>
        <?php if ($isLoggedIn) { ?>
            <a href="<?= AppConfig::BASE_PATH ?>?r=lists/form" class="btn btn-primary">Ajouter une liste</a>
        <?php } ?>
        <form method="get" action="<?= AppConfig::BASE_PATH ?>">
            <input type="hidden" name="r" value="lists/index" />
            <label for="category" class="form-label">Catégorie</label>
            <select name="category" id="category" onchange="this.form.submit()">
                <option value="">Toutes</option>
                <?php foreach ($categories as $category) { ?>
                    <option <?= ((int)($category['id'] ?? 0) === (int)($selectedCategoryId ?? 0) ? 'selected="selected"' : '') ?>
                        value="<?= (int)$category['id'] ?>">
                        <?= htmlspecialchars($category['name'] ?? '') ?>
                    </option>
                <?php } ?>
            </select>
        </form>
    </div>
    <div class="row">
        <?php if ($isLoggedIn) {
            if (!empty($lists)) {
                foreach ($lists as $list) { ?>
                    <div class="col-md-4 my-2">
                        <div class="card w-100">
                            <div class="card-header d-flex align-items-center justify-content-evenly">
                                <i class="bi bi-card-checklist"></i>
                                <h3 class="card-title"><?= htmlspecialchars($list['title'] ?? '') ?></h3>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <?php $items = $list['items'] ?? null;
                                if ($items === null) {
                                    $items = [];
                                } ?>
                                <?php if (!empty($items)) : ?>
                                    <ul class="list-group">
                                        <?php foreach ($items as $item) : ?>
                                            <?php $status = isset($item['status']) ? (int)$item['status'] : (int)($item['is_done'] ?? 0); ?>
                                            <li class="list-group-item d-flex align-items-center">
                                                <a class="me-2"
                                                    href="<?= AppConfig::BASE_PATH ?>?r=lists/updateItemStatus&id=<?= (int)($list['id'] ?? 0) ?>&item_id=<?= (int)($item['id'] ?? 0) ?>&status=<?= $status ? 0 : 1 ?>&redirect=list">
                                                    <i class="bi bi-check-circle<?= $status ? '-fill' : '' ?>"></i>
                                                </a>
                                                <?= htmlspecialchars($item['name'] ?? '') ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else : ?>
                                    <p class="text-muted m-0">Aucun item pour cette liste.</p>
                                <?php endif; ?>

                                <div class="d-flex justify-content-between align-items-end mt-2">
                                    <a href="<?= AppConfig::BASE_PATH ?>?r=lists/form&id=<?= (int)($list['id'] ?? 0) ?>" class="btn btn-primary">Voir la liste</a>
                                    <span class="badge rounded-pill text-bg-primary">
                                        <i class="bi <?= htmlspecialchars($list['category_icon'] ?? 'bi-card-checklist') ?>"></i>
                                        <?= htmlspecialchars($list['category_name'] ?? 'Sans catégorie') ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p>Aucune liste</p>
            <?php } ?>
        <?php } else { ?>
            <p>Pour consulter vos listes, vous devez être connecté</p>
            <a href="<?= AppConfig::BASE_PATH ?>?r=auth/login" class="btn btn-outline-primary me-2">Connexion</a>
        <?php } ?>
    </div>
</div>