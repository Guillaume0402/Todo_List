<div class="container py-5">
    <h1 class="mb-4">Tableau de bord Admin</h1>

    <div class="row g-4">
        <div class="col-md-6 col-lg-4">
            <div class="card text-white bg-primary h-100">
                <div class="card-body">
                    <h5 class="card-title">Utilisateurs inscrits</h5>
                    <p class="card-text fs-3"><?= $usersCount ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card text-white bg-success h-100">
                <div class="card-body">
                    <h5 class="card-title">Listes créées</h5>
                    <p class="card-text fs-3"><?= $listsCount ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card text-white bg-warning h-100">
                <div class="card-body">
                    <h5 class="card-title">Items</h5>
                    <p class="card-text fs-4"><?= $itemsCount ?> (<?= $itemsDone ?> terminés)</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card text-white bg-dark h-100">
                <div class="card-body">
                    <h5 class="card-title">Logs Mongo</h5>
                    <p class="card-text fs-3"><?= $logsCount ?></p>
                </div>
            </div>
        </div>

        <?php if ($lastLog): ?>
        <div class="col-md-12">
            <div class="card border-secondary">
                <div class="card-body">
                    <h5 class="card-title">Dernière action enregistrée</h5>
                    <p class="card-text">
                        Action : <strong><?= htmlspecialchars($lastLog['action']) ?></strong><br>
                        À : <strong>
                            <?= isset($lastLog['created_at']) && is_object($lastLog['created_at']) && method_exists($lastLog['created_at'], 'toDateTime')
                                ? $lastLog['created_at']->toDateTime()->format('d/m/Y H:i:s')
                                : htmlspecialchars($lastLog['created_at'] ?? 'N/A') ?>
                        </strong>
                    </p>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
