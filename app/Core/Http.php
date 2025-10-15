<?php
namespace App\Core;

final class Http
{
    /** Affiche 404/500 avec header/footer si présents, puis stoppe. */
    public static function abort(int $code = 404, array $data = []): void
    {
        http_response_code(in_array($code, [404,500], true) ? $code : 404);

        // Extrait les variables passées (optionnel)
        if (!empty($data)) { extract($data, EXTR_OVERWRITE); }

        $base = __DIR__ . '/../../views';
        $header = $base . '/templates/header.php';
        $footer = $base . '/templates/footer.php';
        $view   = $base . '/errors/' . ($code === 500 ? '500.php' : '404.php');

        if (is_file($header)) require $header;
        if (is_file($view))   require $view;
        else                  echo ($code === 500) ? 'Erreur 500' : 'Erreur 404';
        if (is_file($footer)) require $footer;

        exit;
    }
}
