# TyckyList – To‑Do List MVC (PHP Vanilla) + Docker

TyckyList est une application web de gestion de listes **MVC minimaliste en PHP 8** avec **MySQL** pour les données métiers et **MongoDB** pour la journalisation (logs d’activité, stats admin). Ce README a été **entièrement revu** pour intégrer un **lancement 100% Docker** et un guide d’utilisation clair, prêt pour ton dossier **DWWM**.

---

## ✨ Fonctionnalités
- Authentification sécurisée (hash, rehash, sessions durcies, messages neutres).
- Gestion des catégories, listes et items (CRUD), tri et statut *done*.
- Logger MongoDB optionnel (non bloquant) + **Dashboard Admin** avec stats SQL/Mongo.
- Architecture **MVC** lisible (PSR‑4 via Composer).
- UI responsive (Bootstrap + overrides CSS).

---

## 🧱 Stack & versions (par défaut)
- **PHP 8.2 + Apache** (image officielle) avec extensions: `pdo_mysql`, `mongodb`, `mod_rewrite`.
- **MySQL 8.0** (recommandé) – base `tickylist` (user/password: `user`/`password`).
- **phpMyAdmin** (accès DB en 1 clic).
- **MongoDB 6.x** (pour `activity_logs`) + **Mongo Express** (console web).
- **Composer** pour l’autoload PSR‑4 et la lib `mongodb/mongodb`.

> ℹ️ Le dépôt contient un **`Dockerfile`** et un **`docker-compose.yml`**. Il existe également `init-db/tickylist.sql` avec un jeu de données de démo (utilisateur `test@example.com` / `Password123!`).

---

## 📁 Arborescence (principaux dossiers/fichiers)
```
Todo_List-main/
├── app/
│   ├── Controllers/           # Home, Auth, Lists, AdminDashboard...
│   ├── Core/                  # BaseController, Database, Auth, Http...
│   ├── Models/                # UserModel, ListModel, ItemModel...
│   ├── Services/              # ActivityLogger (MongoDB)
│   └── views/                 # Vues + layouts + partials
├── assets/                    # CSS / images (overrides Bootstrap)
├── config/
│   ├── app.php                # Constantes app (APP_NAME, DEBUG, MONGO_*...)
│   └── env.php                # DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASS
├── init-db/tickylist.sql      # Schéma + seed de démonstration
├── public/index.php           # Routeur minimal ?r=controller/action
├── composer.json              # Autoload PSR‑4, dépendances
├── Dockerfile                 # Image PHP 8.2 + Apache + pdo_mysql + mongodb
├── docker-compose.yml         # Orchestration (web, mysql, phpmyadmin, mongo...)
└── README.md                  # (ce fichier)
```

---

## 🚀 Démarrage rapide (Docker)

### 0) Prérequis
- Docker Desktop / Engine + Docker Compose v2
- (Option A) **Composer installé localement**, ou
- (Option B) utiliser le conteneur `composer` (voir plus bas).

### 1) Cloner & se placer dans le projet
```bash
git clone <ton-repo> tyckylist
cd tyckylist
```

### 2) Installer les dépendances PHP (une seule fois)
- **Option A – Local**  
  ```bash
  composer install --no-interaction --prefer-dist
  ```
- **Option B – Sans Composer local**  
  ```bash
  docker run --rm -v "$PWD":/app -w /app composer:2 \
    install --no-interaction --prefer-dist
  ```

### 3) Lancer l’environnement Docker
```bash
docker compose up -d --build
```

### 4) URLs utiles
- **App web**: http://localhost:8080  
- **phpMyAdmin**: http://localhost:8081 – serveur: `mysql`, user: `user`, pass: `password`  
- **Mongo Express**: http://localhost:8082 (console MongoDB)

> ⚠️ En première exécution, importe le schéma/seed (voir section **Base de données**).

### 5) Arrêter / Recréer
```bash
docker compose down            # stop
docker compose down -v         # stop + supprime volumes (⚠ pertes de données)
docker compose up -d --build   # reconstruit et relance
```

---

## 🧩 `docker-compose.yml` – Exemple recommandé

> Ton fichier actuel contient des « … » (placeholders). Voici une **référence complète** cohérente avec le projet et le `Dockerfile` présent :

```yaml
version: "3.8"

services:
  web:
    build: .
    container_name: tickylist_web
    restart: always
    ports:
      - "8080:80"
    volumes:
      - ./:/var/www/html
    depends_on:
      - mysql
      - mongo

  mysql:
    image: mysql:8.0
    container_name: tickylist_mysql
    restart: always
    ports:
      - "3307:3306"
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: tickylist
      MYSQL_USER: user
      MYSQL_PASSWORD: password
    volumes:
      - mysql-data:/var/lib/mysql
      - ./init-db:/docker-entrypoint-initdb.d:ro

  phpmyadmin:
    image: phpmyadmin/phpmyadmin
    container_name: tickylist_phpmyadmin
    restart: always
    ports:
      - "8081:80"
    environment:
      PMA_HOST: mysql
      PMA_PORT: 3306
    depends_on:
      - mysql

  mongo:
    image: mongo:6
    container_name: tickylist_mongo
    restart: always
    ports:
      - "27017:27017"
    volumes:
      - mongo-data:/data/db

  mongo-express:
    image: mongo-express
    container_name: tickylist_mongo_express
    restart: always
    ports:
      - "8082:8081"
    environment:
      ME_CONFIG_MONGODB_SERVER: mongo
    depends_on:
      - mongo

volumes:
  mysql-data:
  mongo-data:
```

**Pourquoi ces choix ?**
- **`web`** s’appuie sur ton `Dockerfile` (PHP 8.2 + Apache + extensions). On monte le code (`.:/var/www/html`) pour un dev réactif.
- **`mysql`** expose `3307:3306` (et non `3307:3307`) ; les scripts d’init sont chargés automatiquement au 1er démarrage.
- **`mongo`** + **`mongo-express`** pour visualiser `activity_logs`.
- **`phpmyadmin`** cible bien le service `mysql`.

---

## 🗄️ Base de données (MySQL)

### Variables de connexion utilisées par l’app
Définies dans `config/env.php` :
```php
define('DB_HOST', 'mysql');     // nom du service docker-compose
define('DB_PORT', '3306');
define('DB_NAME', 'tickylist');
define('DB_USER', 'user');
define('DB_PASS', 'password');
```

### Initialisation du schéma + données de démo
- **Méthode A (auto)** : avec le `docker-compose.yml` ci‑dessus, le dossier `init-db/` est injecté dans `/docker-entrypoint-initdb.d` → import **auto** au 1er lancement.
- **Méthode B (manuelle)** : via phpMyAdmin → *Importer* → `init-db/tickylist.sql`.

### Compte de démo
- **Email**: `test@example.com`  
- **Mot de passe**: `Password123!`

---

## 🍃 Journalisation d’activité (MongoDB)
- DSN & DB définis dans `config/app.php` :
  ```php
  public const MONGO_DSN = 'mongodb://mongo:27017';
  public const MONGO_DB  = 'tyckylist';
  ```
- Le service `App\Services\ActivityLogger` enregistre des événements (`action`, `entity`, `status`, `user_id`, route HTTP, IP, UA, `created_at`...).  
- **Tolérant aux pannes** : si l’extension MongoDB n’est pas dispo ou que Mongo est down, le logger se **désactive silencieusement** (l’app continue de fonctionner).

---

## 🧭 Routage minimal & contrôleurs
- Point d’entrée: `public/index.php` – schéma `?r=controller/action` (ex: `?r=lists/index`).
- **Contrôleurs clés** :
  - `HomeController` (accueil, about)
  - `AuthController` (login/register + sécurisation session)
  - `ListController` (catégories, listes, items; support AJAX pour toggle `done`)
  - `AdminDashboardController` (stats SQL/Mongo; accès réservé `isAdmin()`)

> Le `BaseController->render()` intègre les vues dans `views/layouts/main.php` (header/footer/flash).

---

## 🔐 Sécurité & bonnes pratiques
- `password_hash()` + `password_needs_rehash()`.
- Sessions sécurisées (`cookie_httponly`, `SameSite=Lax`, `use_strict_mode`...).
- Messages d’erreurs **neutres** pour l’auth.
- Protection CSRF recommandée sur POST (token) – à activer partout si ce n’est pas déjà fait.
- Requêtes préparées PDO, validation/sanitation des entrées.

---

## 🧪 Tests manuels « smoke test »
1. Ouvre http://localhost:8080 et parcours `home/index`, `auth/login`, `auth/register`.
2. Importe la base puis connecte‑toi avec le compte de démo.
3. Crée une liste, ajoute quelques items, utilise le **toggle AJAX** done/undone.
4. Va sur le **dashboard admin** (route contrôleur Admin) et vérifie les **stats SQL** (nb users, lists, items…) et **Mongo** (compteur + dernier log).

---

## 🛠️ Commandes utiles
```bash
# Composer via conteneur (si pas installé en local)
docker run --rm -v "$PWD":/app -w /app composer:2 install

# Exécuter un shell dans le conteneur web
docker compose exec web bash

# Logs d’un service
docker compose logs -f web
docker compose logs -f mysql
docker compose logs -f mongo
```

---

## 🐛 Dépannage rapide
- **Page blanche / autoload introuvable** → exécuter `composer install` (local ou via conteneur).
- **Connexion MySQL KO** → vérifier `DB_HOST=mysql`, `DB_PORT=3306`, mapping **`3307:3306`**, et les logs `docker compose logs -f mysql`.
- **phpMyAdmin ne voit pas la DB** → `PMA_HOST=mysql`, `PMA_PORT=3306`.
- **Mongo KO** → vérifier que l’extension PHP *mongodb* est activée (le `Dockerfile` l’installe) et que le service `mongo` tourne (`logs`).

---

## 📦 Production (pistes)
- Construire une image « runtime » (copie du code + `vendor/`, sans volumes de dev).
- Variables d’env via secrets (ne pas committer des mots de passe).
- Reverse proxy (Caddy/Traefik/Nginx) + HTTPS (Let’s Encrypt).

---

## 📄 Licence
MIT © 2025 — **Guillaume Maignaut**
