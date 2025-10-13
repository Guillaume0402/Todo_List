# TickyList – Application To-Do List MVC (PHP Vanilla)

TickyList est une application web développée en PHP Vanilla suivant une architecture MVC claire et modulaire.  
Elle permet de gérer des listes de tâches personnelles avec un système complet d’authentification sécurisé**, une interface responsive et une base de données MySQL conforme aux bonnes pratiques du développement web.

---

## Architecture du projet

```
TyckyList/
│
├── public/                 # Point d’entrée web (routeur principal)
│   ├── index.php           # Routeur : analyse ?r=controller/action
│   └── assets/             # Fichiers statiques (CSS, JS, images)
│
├── app/
│   ├── Core/               # Noyau de l’application (BaseController, Auth, Database)
│   ├── Controllers/        # Logique de contrôle HTTP
│   ├── Models/             # Accès aux données (via PDO)
│   └── views/              # Vues PHP (HTML)
│       └── layouts/        # Layout global + partials (header, footer)
│
├── config/
│   ├── env.php             # Variables d’environnement (DB_HOST, DB_USER, etc.)
│   ├── database.php        # Connexion PDO (DSN + options sécurisées)
│   └── app.php             # Constantes globales (APP_NAME, CSRF, BASE_PATH)
│
├── vendor/                 # Dépendances Composer (autoload PSR-4)
├── composer.json           # Autoload PSR-4 : "App\\" → "app/"
└── database.sql            # Script SQL complet (tables, contraintes, seed)
```

---

## Fonctionnement global (Flux MVC)

1. Le navigateur appelle une URL :  
   → `public/index.php?r=controller/action`
2. Le routeur identifie le contrôleur et l’action (ex. `ListController@index`)
3. Le contrôleur traite la requête, appelle les modèles, prépare les données
4. Le contrôleur appelle `render('dossier/vue', $data)`
5. `BaseController` intègre la vue dans le layout global (`views/layouts/main.php`)

---

## Authentification et sécurité

### Fonctionnalités principales
- **Validation complète** des entrées (email, mot de passe, nom d’affichage)
- **Normalisation** des emails (trim + lowercase)
- **Hachage** des mots de passe via `password_hash()`
- **Rehash automatique** si l’algorithme par défaut évolue (`password_needs_rehash`)
- **Protection CSRF** sur toutes les requêtes POST
- **Gestion des doublons** via `try/catch` sur le code SQL `23000`
- **Sessions sécurisées** : `session_regenerate_id(true)` après login/inscription
- **Anti brute-force léger** : délai aléatoire (200–500 ms) après échec de connexion
- **Messages d’erreur neutres** (« Identifiants invalides »)
- **UX améliorée** : bouton 👁 afficher/masquer le mot de passe, flash messages Bootstrap

---

## Base de données MySQL

### Tables principales
- **users** — Comptes utilisateurs (id, email, password, display_name, created_at)
- **categories** — Catégories de listes (ex. Courses, Travail…)
- **lists** — Listes liées à un utilisateur (FK user_id, category_id)
- **items** — Éléments d’une liste (FK list_id, état terminé ou non)

### Contraintes et index
- `users.email` **UNIQUE**
- FK avec `ON DELETE CASCADE` / `ON DELETE SET NULL`
- Index `(user_id, is_archived)` pour les requêtes fréquentes

### Jeu de données de démonstration
Le fichier `database.sql` contient :
- La création complète du schéma
- Les contraintes et index
- Un jeu de données exemple :
  - 1 utilisateur : `test@example.com` / `Password123!`
  - 4 catégories, 2 listes et 4 items

---

## Installation locale

### 1 Dépendances
Installer Composer puis exécuter :
```bash
composer install
```

### 2 Base de données
1. Crée une base `tickylist`
2. Importe `database.sql` :
   - via phpMyAdmin → Importer → `database.sql`
   - ou en ligne de commande :
     ```bash
     mysql -u root -p < database.sql
     ```
3. Configure `config/env.php` :
   ```php
   define('DB_HOST', 'localhost');
   define('DB_PORT', 3306);
   define('DB_NAME', 'tickylist');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   ```

### 3 Lancer le serveur PHP
```bash
php -S localhost:8088 -t public
```

### 4 Accéder à l’application
- Accueil : [http://localhost:8088/?r=home/index](http://localhost:8088/?r=home/index)
- Connexion : [http://localhost:8088/?r=auth/login](http://localhost:8088/?r=auth/login)
- Inscription : [http://localhost:8088/?r=auth/register](http://localhost:8088/?r=auth/register)
- Mes listes : [http://localhost:8088/?r=lists/index](http://localhost:8088/?r=lists/index)

---

## Points forts pour le Dossier Professionnel (DWWM)

| Compétence | Mise en œuvre |
|-------------|----------------|
| **AT1 – Développer la partie front-end** | Formulaires responsives, gestion CSRF, bouton 👁 afficher/masquer, flash messages Bootstrap |
| **AT2 – Développer la partie back-end sécurisée** | Authentification complète (hash, rehash, gestion d’erreurs, sessions sécurisées) |
| **Base de données relationnelle** | MySQL + contraintes FK + index + seed |
| **Architecture MVC** | Séparation claire des responsabilités, autoload PSR-4 |
| **Documentation et professionnalisme** | README complet + code commenté + schéma BDD |

---

## Exemples de routes utiles

| Fonction | URL |
|-----------|-----|
| Accueil | `?r=home/index` |
| Connexion | `?r=auth/login` |
| Inscription | `?r=auth/register` |
| Mes listes | `?r=lists/index` |
| Formulaire liste | `?r=lists/form&id=123` |

---

## Bonus UX / améliorations futures
- Toggle AJAX des items “terminé / non terminé” sans rechargement  
- Pagination des listes  
- Mode sombre (CSS)  
- Export/Import de listes (JSON)

---

© 2025 – Projet **TickyList** • Développement : **Guillaume Maignaut**
