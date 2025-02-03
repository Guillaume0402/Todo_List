<?php
require_once __DIR__ . "/lib/session.php";
// Prévient les attaques de fixation de session
session_regenerate_id(true);

// Supprime les données de session
session_destroy();

// Supprime les données du tableau de session $_SESSION
unset($_SESSION);

header('Location: login.php');