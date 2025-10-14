<?php if (!empty($flashMessages)) : ?>
<script>
window.addEventListener('load', function () {
  <?php foreach ($flashMessages as $fm):
    $type = ($fm['type'] ?? 'info') === 'error' ? 'danger' : ($fm['type'] ?? 'info'); ?>
    showToast(<?= json_encode($fm['message']) ?>, <?= json_encode($type) ?>);
  <?php endforeach; ?>
});
</script>
<?php endif; ?>
