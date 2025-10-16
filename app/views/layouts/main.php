<?php
// Layout principal : inclut header, flash, contenu, footer
require_once __DIR__ . '/partials/header.php';
$flashPartial = __DIR__ . '/../partials/flash.php';
if (file_exists($flashPartial)) {
    require $flashPartial;
}
echo $content ?? '';

require_once __DIR__ . '/partials/footer.php';
