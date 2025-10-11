<?php if (!empty($flashMessages)) : ?>
    <div class="container mt-3">
        <?php foreach ($flashMessages as $fm): ?>
            <div class="alert alert-<?= htmlspecialchars($fm['type'] ?? 'info') ?>">
                <?= htmlspecialchars($fm['message'] ?? '') ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>