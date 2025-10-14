<div class="container col-xxl-8 ">
    <h1>Liste</h1>
</div>

<div class="accordion container" id="accordionExample">
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button <?= ($editMode) ? 'collapsed' : '' ?>" type="button"
                data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded=<?= ($editMode) ? 'false' : 'true' ?> aria-controls="collapseOne">
                <?= ($editMode) ? htmlspecialchars($list['title'] ?? '') : 'Ajouter une liste' ?>
            </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse <?= ($editMode) ? '' : 'show' ?>"
            data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <form action="<?= AppConfig::BASE_PATH ?>?r=lists/saveList<?= $editMode ? '&id=' . (int)($list['id'] ?? 0) : '' ?>" method="post">
                    <input type="hidden" name="<?= htmlspecialchars(AppConfig::CSRF_TOKEN_NAME) ?>" value="<?= htmlspecialchars($csrfToken) ?>" />
                    <div class="mb-3">
                        <label for="title" class="form-label">Titre</label>
                        <input type="text" value="<?= htmlspecialchars($list['title'] ?? '') ?>" name="title" id="title" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Catégorie</label>
                        <select name="category_id" id="category_id" class="form-control">
                            <?php foreach ($categories as $category) { ?>
                                <option <?= ((int)($category['id'] ?? 0) === (int)($list['category_id'] ?? 0)) ? 'selected="selected"' : '' ?> value="<?= (int)$category['id'] ?>"><?= htmlspecialchars($category['name'] ?? '') ?></option>
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
            <form method="post" class="d-flex" action="<?= AppConfig::BASE_PATH ?>?r=lists/saveItem&id=<?= (int)($list['id'] ?? 0) ?>">
                <input type="hidden" name="<?= htmlspecialchars(AppConfig::CSRF_TOKEN_NAME) ?>" value="<?= htmlspecialchars($csrfToken) ?>" />
                <input type="checkbox" name="status" id="status" value="1">
                <input type="text" name="name" id="name" placeholder="Ajouter un item" class="form-control mx-2">
                <input type="submit" name="saveListItem" class="btn btn-primary" value="Enregistrer">
            </form>
            <div class="row m-4 border rounded p-2 ">
                <?php foreach ($items as $item) { ?>
                    <?php $itemStatus = isset($item['status']) ? (int)$item['status'] : (int)($item['is_done'] ?? 0); ?>
                    <div class="accordion mb-2">
                        <div class="accordion-item" id="accordion-parent-<?= (int)($item['id'] ?? 0) ?>">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse-item-<?= (int)($item['id'] ?? 0) ?>" aria-expanded='false'
                                    aria-controls="collapse-item-<?= (int)($item['id'] ?? 0) ?>">
                                    <a class="me-2"
                                        href="<?= AppConfig::BASE_PATH ?>?r=lists/updateItemStatus&id=<?= (int)($list['id'] ?? 0) ?>&item_id=<?= (int)($item['id'] ?? 0) ?>&status=<?= $itemStatus ? 0 : 1 ?>"
                                        data-toggle-status="1"
                                        data-item-id="<?= (int)($item['id'] ?? 0) ?>"
                                        data-list-id="<?= (int)($list['id'] ?? 0) ?>"
                                        data-current-status="<?= $itemStatus ? 1 : 0 ?>">
                                        <i class="bi bi-check-circle<?= ($itemStatus ? '-fill' : '') ?>"></i></a>
                                    <?= htmlspecialchars($item['name'] ?? '') ?>
                                </button>
                            </h2>
                            <div id="collapse-item-<?= (int)($item['id'] ?? 0) ?>" class="accordion-collapse collapse"
                                data-bs-parent="#accordion-parent-<?= (int)($item['id'] ?? 0) ?>">
                                <div class="accordion-body">
                                    <form action="<?= AppConfig::BASE_PATH ?>?r=lists/saveItem&id=<?= (int)($list['id'] ?? 0) ?>" method="post">
                                        <input type="hidden" name="<?= htmlspecialchars(AppConfig::CSRF_TOKEN_NAME) ?>" value="<?= htmlspecialchars($csrfToken) ?>" />
                                        <div class="mb-3 d-flex">
                                            <input type="text" value="<?= htmlspecialchars($item['name'] ?? '') ?>" name="name" class="form-control">
                                            <input type="hidden" name="item_id" value="<?= (int)($item['id'] ?? 0) ?>">
                                            <input type="submit" value="Enregistrer" name="saveListItem" class="btn btn-primary">
                                        </div>
                                    </form>
                                    <a class="btn btn-outline-primary"
                                        href="<?= AppConfig::BASE_PATH ?>?r=lists/deleteItem&id=<?= (int)($list['id'] ?? 0) ?>&item_id=<?= (int)($item['id'] ?? 0) ?>"
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