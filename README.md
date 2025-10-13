# TyckyList - Structure simple (MVC)

Ce projet suit une structure MVC légère et lisible avec autoload Composer (PSR-4). Voici la carte des dossiers, le rôle de chacun et comment ajouter une page.

## Arborescence

- `public/`
  - Point d'entrée web (router): `public/index.php`
  - Les fichiers statiques sont servis depuis `assets/` (CSS, images, etc.)
- `app/`
  - `Core/` — Noyau applicatif: `BaseController`, `Auth`, `Database`
  - `Controllers/` — Contrôleurs (logique HTTP) avec namespace `App\Controllers`
  - `Models/` — Accès aux données (PDO) avec namespace `App\Models`
  - `views/` — Vues PHP (HTML) par fonctionnalité
  - `views/layouts/` — Layout global + partials (`header.php`, `footer.php`)
- `config/`
  - `env.php` — Identifiants BDD (DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASS)
  - `database.php` — DSN + options PDO
  - `app.php` — Constantes (APP_NAME, CSRF, `BASE_PATH` pour les routes)
- `vendor/` — Dépendances Composer (autoload PSR-4)
- `composer.json` — Configuration autoload: `App\` → `app/`

Supprimés (legacy): `templates/`, `lib/`, `core/` (remplacé par `app/Core`).

## Flux de requête (mental model)

1. Navigateur → `public/index.php?r=controller/action`
2. Le router choisit un contrôleur et une action (ex: `ListController@index`)
3. Le contrôleur appelle les modèles (accès BDD), prépare les données
4. Le contrôleur appelle `render('dossier/vue', $data)`
5. `BaseController` inclut la vue dans le layout (`views/layouts/main.php`)

## Ajouter une nouvelle page

1. Contrôleur: créer `app/Controllers/MontrucController.php` avec namespace `App\Controllers` et une méthode publique (ex: `show()`)
2. Vue: créer `app/views/montruc/show.php` (HTML)
3. Route: appeler `public/index.php?r=montruc/show` (ou `<?= AppConfig::BASE_PATH ?>?r=montruc/show` dans une vue)

Note: Les classes sont chargées automatiquement par Composer (PSR-4), plus besoin de `require_once`.

## Authentification et sécurité

- `Auth::requireAuth()` redirige vers `/public/index.php?r=auth/login` si l'utilisateur n'est pas connecté
- CSRF: `BaseController` fournit `$csrfToken` + `AppConfig::CSRF_TOKEN_NAME` pour les formulaires POST

## Exemples d'URL utiles

- Accueil: `/public/index.php?r=home/index`
- À propos: `/public/index.php?r=home/about`
- Connexion: `/public/index.php?r=auth/login`
- Mes listes: `/public/index.php?r=lists/index`
- Éditer une liste: `/public/index.php?r=lists/form&id=123`

## Installation (local)

1. **Dépendances**
   - Installer Composer: `composer install` (génère `vendor/autoload.php`)
2. **Base de données**
   - Crée une BDD (ex: `tickylist`).
   - Mets à jour `config/env.php` avec: DB_HOST, DB_PORT, DB_NAME (`tickylist`), DB_USER (`tickyuser`), DB_PASS (`ticky123`).
3. **Lancer le serveur PHP** (si besoin):
   - Racine web: le dossier `public/` (ex: via Apache/Nginx ou `php -S localhost:8088 -t public`).
4. **Accéder à l'app**:
   - Accueil: `/public/index.php?r=home/index`
   - Connexion: `/public/index.php?r=auth/login`
   - Mes listes: `/public/index.php?r=lists/index`

## Dossier pro (DWWM)

- Schéma simple: Router → Controller → Model → View
- Un fichier par responsabilité: contrôleur pour la logique, modèle pour la BDD, vue pour l'HTML
- Éviter les includes "magiques": centraliser dans `BaseController` (sous `app/Core`)
- Documenter chaque fonctionnalité avec sa route, son contrôleur, sa vue

## Notes

- Utilise `AppConfig::BASE_PATH` pour générer des liens internes dans les vues.
