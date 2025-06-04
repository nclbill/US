# UserSpice Core Reference – v5.8.1

## Table des matières

1. [Introduction](#introduction)  
2. [Structure générale du projet](#structure-générale-du-projet)  
3. [Fichiers et dossiers clés](#fichiers-et-dossiers-clés)  
4. [Flux de connexion et authentification](#flux-de-connexion-et-authentification)  
5. [Gestion des utilisateurs](#gestion-des-utilisateurs)  
6. [Permissions et rôles](#permissions-et-rôles)  
7. [Sécurité et protections intégrées](#sécurité-et-protections-intégrées)  
8. [Moteur de pages (page management)](#moteur-de-pages-page-management)  
9. [Utilitaires et helpers](#utilitaires-et-helpers)  
10. [Fonctionnement de l'assistant SpiceDev](#fonctionnement-de-lassistant-spicedev)

---

## Introduction

Cette documentation décrit le fonctionnement interne de UserSpice 5.8.1 dans le but de servir de socle technique pour le développement d’assistants personnalisés (comme **SpiceDev**) ou d’applications sur mesure.

Elle est générée automatiquement et vérifiée manuellement pour garantir :
- **Exactitude technique**
- **Exploitation rapide**
- **Réutilisation stable sur le long terme**

---

## Structure générale du projet

UserSpice repose sur une architecture PHP modulaire, organisée autour :
- D’un noyau (`users/`, `usersc/`)
- D’un routeur de page basé sur la variable `$_GET['page']` ou `$_SERVER['REQUEST_URI']`
- D’un système d’initialisation centralisé (`init.php` + `helpers.php`)
- D’un moteur d’autorisation fine basé sur des permissions (`permission_id`, `user_permission_matches`, etc.)

Les chemins critiques sont :

| Dossier/Fichier            | Rôle principal                                        |
|---------------------------|--------------------------------------------------------|
| `/users/`                 | Noyau système, login/logout/register                  |
| `/usersc/`                | Personnalisation locale (non supprimée en mise à jour)|
| `/users/init.php`        | Chargement de la session, configuration, DB, etc.     |
| `/users/helpers/`        | Fonctions de support (validation, CSRF, log, etc.)    |
| `/users/classes/`        | Classes principales (User, DB, Token, etc.)           |
| `/users/views/`          | Templates HTML pour certaines pages                   |
| `/users/includes/`       | Scripts communs (header.php, navigation.php, etc.)    |
| `/usersc/pages/`         | Pages personnalisées accessibles via navigateur       |

---

## Fichiers et dossiers clés

### 1. `/users/init.php`

Ce fichier est **inclus en haut de toutes les pages** pour initialiser :
- L’autoload des classes
- La connexion à la base de données
- La session de l’utilisateur
- Le mode maintenance éventuel
- Les préférences globales (timezone, config site...)

### 2. `/users/classes/User.php`

Classe centrale de gestion de l’utilisateur connecté. Elle permet :
- Authentification (`login()`)
- Vérification permission (`hasPermission()`, `isAdmin()`)
- Accès aux données de l'utilisateur (`data()`)

Elle repose fortement sur la classe `DB` et les helpers du projet.

### 3. `/users/classes/DB.php`

Wrapper SQL sécurisé avec `PDO`. Permet :
- Requêtes préparées
- Transactions
- Injections SQL protégées

Fonctions principales :
- `query()`, `insert()`, `update()`, `delete()`
- `first()`, `count()`, `results()`, `error()`
### 4. Flux de connexion et authentification

UserSpice utilise un système d’authentification basé sur sessions + cookies (remember me).

#### Fichiers principaux :
- `/users/login.php` : Formulaire et traitement de la connexion
- `/users/logout.php` : Déconnexion et destruction de session
- `/users/init.php` : Initialise les variables utilisateurs si session ou cookie actif

#### Processus de connexion :

1. L'utilisateur soumet ses identifiants à `login.php`
2. La classe `User` appelle :
   - `DB::get()` pour chercher l’utilisateur
   - `password_verify()` pour comparer le mot de passe
   - `Token::generate()` pour protéger le formulaire
3. Si la case "Remember Me" est cochée :
   - Création d’un `hash` unique
   - Enregistrement dans la table `users_session`
   - Cookie `remember_me` stocké dans le navigateur
4. À chaque chargement de page :
   - Si l’utilisateur n’est pas connecté mais un cookie est présent :
     - L’entrée `users_session` est recherchée
     - Si elle existe, l’utilisateur est automatiquement reconnecté

#### Déconnexion :
- `logout.php` :
   - Supprime la session (`Session::delete()`)
   - Supprime le cookie
   - Supprime l’entrée `users_session`

#### Tables concernées :
- `users` : Utilisateurs (email, username, password hash…)
- `users_session` : Gère le Remember Me

#### Sécurité :
- Hashage par `password_hash()` (algorithme `bcrypt`)
- Jeton CSRF obligatoire sur tous les formulaires
- Tentatives limitées via `us_user_sessions` ou plugins (lockout)

---

### 5. Gestion des utilisateurs

Gérée essentiellement via la classe `User` :

```php
$user = new User();
$user->login($username, $password, $remember);
$user->logout();
$user->isLoggedIn();
$user->hasPermission($perm_id);
```

#### Méthodes utiles :
| Méthode                 | Rôle                                                                 |
|-------------------------|----------------------------------------------------------------------|
| `login($u, $p, $r)`     | Connexion utilisateur                                                 |
| `logout()`              | Déconnexion                                                          |
| `isLoggedIn()`          | Vérifie si connecté                                                   |
| `data()`                | Retourne l’objet contenant les données (`$user->data()->email`)       |
| `hasPermission(id)`     | Vérifie la permission via `user_permission_matches`                  |
| `exists()`              | Vérifie si l’utilisateur existe                                       |

#### Création manuelle d’un utilisateur :
Via `users/admin_users.php`, ou script PHP :

```php
$passwordHash = password_hash('motdepasse', PASSWORD_BCRYPT, ['cost' => 12]);
$db->insert('users', [
  'username' => 'nouveluser',
  'email' => 'email@test.com',
  'password' => $passwordHash,
  'permissions' => 1,
  'join_date' => date("Y-m-d H:i:s")
]);
```

---

### 6. Permissions et rôles

#### Tables concernées :
- `permissions` : Contient les groupes (Admin, Utilisateur, etc.)
- `user_permission_matches` : Relie `user_id` à `permission_id`

#### Attribution d’un rôle :
```php
$db->insert('user_permission_matches', [
  'user_id' => $id,
  'permission_id' => 2
]);
```

#### Vérification :
```php
$user = new User();
if ($user->hasPermission(2)) {
  echo "Administrateur général";
}
```

#### Fichier de gestion :
- `users/admin_permissions.php` : CRUD des groupes de permissions
### 7. Sécurité et protections intégrées

UserSpice inclut de nombreuses protections natives.

#### CSRF (Cross-Site Request Forgery)
- Chaque formulaire génère un `Token::generate('nom_du_formulaire')`
- La valeur est injectée dans un champ `<input type="hidden" name="csrf">`
- À la soumission : `Token::check('nom_du_formulaire')` valide l’action

#### Session fixation & hijacking
- Regénération régulière d’identifiants de session
- Configuration PHP sécurisée (via `ini_set`)
- Détection d'IP différente ou User-Agent via plugins possibles

#### Injection SQL
- Utilisation exclusive de `DB::query()` avec requêtes préparées
- Toutes les entrées utilisateurs sont filtrées et échappées

#### XSS / HTML non désiré
- Les données affichées passent par `htmlspecialchars()` dans les vues
- Certains fichiers utilisent des whitelists de balises autorisées

#### Clickjacking
- Entête HTTP `X-Frame-Options: SAMEORIGIN` envoyé par défaut

---

### 8. Moteur de pages (Page Management)

#### Fonctionnement :
UserSpice permet de gérer des pages via l’interface admin.

| Élément                    | Description                                               |
|----------------------------|-----------------------------------------------------------|
| `pages`                   | Table contenant toutes les pages disponibles              |
| `permission_page_matches` | Lie les pages aux permissions autorisées (`page_id`, `permission_id`) |

#### Ajout d’une page :
- Via `admin_pages.php`
- La page doit exister physiquement dans `/users/` ou `/usersc/pages/`
- Exemple :
  - `/usersc/pages/monmodule.php`
  - Ajout en base : `monmodule.php`, restreinte au rôle `3`

#### Contrôle d’accès :
Dans `init.php` :
```php
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off") ? "https://" : "http://";
$currentPage = basename($_SERVER['PHP_SELF']);
```
La fonction `securePage($currentPage)` :
- Cherche si la page est déclarée dans `pages`
- Cherche si l’utilisateur connecté a le droit de l’ouvrir
- Redirige vers `users/login.php` ou `users/account.php` sinon

---

### 9. Utilitaires et helpers

Les fichiers de fonctions sont dans :

| Fichier                    | Fonctions principales                                          |
|----------------------------|---------------------------------------------------------------|
| `/users/helpers/helpers.php` | `sanitize()`, `Input::get()`, `Token::generate()`...       |
| `/users/helpers/us_helpers.php` | Aides spécifiques UserSpice : `echouser()`, `currentPage()`, `checkMenu()` |
| `/users/helpers/messages.php` | Gère les flash messages (`Session::flash()`)             |

#### Exemples utiles :

```php
// Récupération sécurisée d’une variable GET ou POST
$id = Input::get('id');

// Nettoyage chaîne
$nom = sanitize($_POST['nom']);

// Message flash
Session::flash('succes', 'Opération réussie');
```

Les helpers sont automatiquement chargés via `init.php`.

---
### 10. Fonctionnement de l'assistant SpiceDev

L’assistant **SpiceDev** repose sur cette documentation et l’analyse complète du socle UserSpice. Son objectif est d’agir comme un **développeur virtuel interne** capable de :

- **Comprendre et exploiter la logique de UserSpice**
- **Décrire avec précision le rôle de chaque fichier, classe ou fonction**
- **Proposer du code immédiatement fonctionnel, conforme à la base UserSpice**
- **Adapter le socle à des projets clients tout en gardant la base propre**

#### Modèle de réponse

SpiceDev suit une logique stricte :

1. Identifie le contexte du projet
2. Applique la logique de UserSpice (auth, DB, vues, permissions)
3. Génère du code propre et commenté
4. Ne sort jamais du périmètre défini sans validation explicite

---

## Exemple d'intégration dans un projet client

**Cas** : Ajout d’une page `commandes.php` permettant à un revendeur de commander un produit, avec vérifications sécurisées.

**Étapes suivies par SpiceDev** :
- Création de la page dans `/usersc/pages/`
- Sécurisation avec `securePage()`
- Inclusion de `init.php`
- Utilisation de `Input::get()` pour lire les filtres
- Vérification des droits avec `hasPerm([x], $user->data()->id)`
- Réservation des produits avec transaction SQL (`FOR UPDATE`)
- Protection CSRF et vérification des fichiers uploadés
- Enregistrement en base avec `DB::insert()`

---

## Structure finale attendue

L’assistant SpiceDev utilise :

| Élément                        | Usage                                                   |
|-------------------------------|----------------------------------------------------------|
| `USERSPICE_CORE_REFERENCE.md` | Référence technique et logique                          |
| Fichiers Markdown clients     | Documentation projet par projet                         |
| Modèle GPT avec instructions  | Chargé dans Custom GPT (ou outil tiers compatible)      |

---
---

## Structure de la base de données principale

La base de données de UserSpice 5.8.1 repose sur un ensemble de tables relationnelles, conçues pour gérer la sécurité, les utilisateurs, les autorisations, les sessions, et les personnalisations. Voici la structure standard du fichier `users/includes/user_spice.sql`.

---

### Tables essentielles

#### `users`
- Contient les comptes utilisateurs.
- Champs clés : `id`, `username`, `email`, `password`, `permissions`, `join_date`, `last_login`, `email_verified`, etc.

#### `permissions`
- Définit les groupes d’utilisateurs (admin, modérateur, client, etc.).
- Champs : `id`, `name`, `description`.

#### `user_permission_matches`
- Relie un utilisateur (`user_id`) à un groupe (`permission_id`).
- Sert à gérer l’appartenance à plusieurs groupes.

#### `pages`
- Contient les pages déclarées dans l’interface d’administration.
- Champs : `id`, `page`, `private`, `label`, `menu`, `restricted`, etc.

#### `permission_page_matches`
- Lie des groupes à des pages (gestion des droits d’accès).

#### `logs`
- Journalise certaines actions critiques (login, logout, échecs, etc.).

#### `email`
- Gère les modèles d’e-mails système (confirmation, réinitialisation…).

#### `updates`
- Suivi des mises à jour effectuées sur le projet.

#### `settings`
- Paramètres du site (nom, configuration mail, options diverses).

#### `tokens`
- Jetons temporaires pour réinitialisation, double authentification, etc.

#### `notifications`
- Notifications utilisateurs (internes, alertes).

#### `logs_archive`
- Archivage automatisé des anciens logs.

---

### Index, contraintes et clés étrangères

- La plupart des tables disposent de clés primaires (`id`) en auto-incrément.
- `user_permission_matches.user_id` et `user_permission_matches.permission_id` sont en relation avec `users.id` et `permissions.id`.
- Des indexes sont définis pour accélérer la recherche (`email`, `username`, `token`…).
- Certains champs peuvent être `NULL` selon les besoins du système (ex. : `last_login`, `reset_token_expiry`).

---

### Recommandations pour assistants personnalisés

- Ne jamais modifier les tables `users`, `permissions`, `user_permission_matches` directement sans passer par les classes système (`User`, `DB`, etc.).
- Utiliser des tables personnalisées pour les besoins métier spécifiques.
- Respecter le schéma pour assurer la compatibilité avec les mises à jour de UserSpice.

---
## Fichiers et arborescence

Voici l’arborescence simplifiée du répertoire `users/` de UserSpice 5.8.1 :

```
users/
├── admin/               # Pages d’administration
├── assets/              # CSS, JS, images
├── includes/            # Fichiers principaux de configuration
│   ├── init.php         # Initialise UserSpice (sécurité, sessions, config)
│   ├── functions.php    # Fonctions globales (checkMenu, token, etc.)
│   ├── db.php           # Classe DB (accès base de données)
│   ├── user.php         # Classe User (authentification)
│   ├── token.php        # Classe Token (CSRF)
├── login.php            # Page de connexion
├── join.php             # Inscription
├── account.php          # Compte utilisateur
├── logout.php           # Déconnexion
```

## Classes principales

UserSpice s’appuie sur des classes PHP autonomes, placées dans `users/classes/` :

- `DB` : connexion PDO, requêtes sécurisées  
- `User` : gestion utilisateur (connexion, permissions, infos)  
- `Token` : protection CSRF  
- `Input` : récupération sécurisée de `$_POST`, `$_GET`  
- `Redirect` : redirection serveur sécurisée  
- `Hash` : hachage et vérification de mot de passe  
- `Email` : envoi d’e-mails via PHPMailer  

Ces classes sont automatiquement chargées via `init.php`.
## Sécurité intégrée

UserSpice intègre plusieurs niveaux de sécurité par défaut :

### 1. Protection CSRF
- Utilisation de jetons (`Token::generate()` et `Token::check()`) sur chaque formulaire.
- Empêche les soumissions frauduleuses de formulaires.

### 2. Hachage des mots de passe
- Mots de passe stockés via `password_hash()` avec `bcrypt`.
- Vérification via `password_verify()` dans la classe `Hash`.

### 3. Système de permissions
- Permissions attribuées via des groupes (`permissions`) et associations (`user_permission_matches`).
- Vérification via `hasPerm()` ou `$user->checkPermission()`.

### 4. Filtres d’entrée
- Classe `Input` récupère les données de manière sécurisée.
- Échappement des données lors de l'affichage (`htmlspecialchars()`).

### 5. Sessions sécurisées
- Initialisation dans `init.php`.
- Déconnexion automatique via `usersc/includes/security_headers.php` et timeout configurable.

### 6. Protection XSS et injections
- Usage de requêtes préparées (`DB::query`) pour éviter les injections SQL.
- Données filtrées à l'affichage (XSS).

### 7. Restrictions d'accès aux pages
- Définies dans la table `pages`.
- Gestion via l’administration UserSpice (accès par rôle).

---  
--
---

## Initialisation du système (`init.php`)

Le fichier `users/init.php` est inclus en début de chaque page. Il sert à initialiser le contexte du projet. Il charge :

- La configuration (`users/config.php`)
- Les classes (via `users/autoload.php`)
- La session PHP et les préférences globales
- La base de données (via `DB::getInstance()`)
- Les fonctions utilitaires (`helpers.php`)
- La gestion des erreurs personnalisée
- Le système de "site en maintenance"
- La vérification de session utilisateur

Ce fichier est **essentiel** pour garantir que tous les composants nécessaires soient disponibles avant le traitement de la page.

---

## Système d'autoload (`autoload.php`)

Situé dans `users/`, ce fichier utilise `spl_autoload_register()` pour charger automatiquement les classes contenues dans `users/classes/` :

```php
spl_autoload_register(function ($class) {
  require_once $abs_us_root . $us_url_root . 'users/classes/' . $class . '.php';
});
```

Chaque classe est ainsi accessible sans `require_once` explicite (ex. : `new User()` fonctionne automatiquement).

---

## Gestion de la session

UserSpice utilise des sessions PHP standard avec quelques particularités :

- Session démarrée via `session_start()` dans `init.php`
- Vérification d'utilisateur via `Session::exists('user')`
- Gestion CSRF (jeton stocké en session)
- Sauvegarde automatique des tentatives de connexion, préférences, et jetons

---

## Helpers essentiels

Dans `users/helpers/helpers.php`, on trouve plus de 100 fonctions utiles. Exemples clés :

- `tokenHere()` / `Token::check()` : gestion des jetons CSRF
- `Input::get()` : récupération sécurisée des données `$_POST`/`$_GET`
- `sanitize()` : nettoyage des chaînes
- `ipCheckBan()` : vérification d’IP bannies
- `usError()` / `usSuccess()` : gestion des messages utilisateur

Tous ces helpers sont disponibles globalement après inclusion de `init.php`.

---
---

## Classe User (`User.php`)

La classe `User` dans `users/classes/User.php` est le cœur de la gestion utilisateur. Elle permet :

- Authentification (login, logout)
- Vérification des permissions
- Accès aux données utilisateur (nom, email, rôle)
- Gestion de la session associée à l’utilisateur

Exemple d’utilisation :

```php
$user = new User();
if ($user->isLoggedIn()) {
    echo "Bienvenue, " . $user->data()->username;
}
```

Méthodes importantes :

- `login($username, $password, $remember = false)`
- `logout()`
- `hasPermission($permission_id)`
- `data()` : retourne un objet avec les données utilisateur

---

## Classe DB (`DB.php`)

Fichier `users/classes/DB.php`, wrapper PDO pour les interactions avec la base de données :

- Singleton accessible via `DB::getInstance()`
- Requêtes sécurisées avec `query()`, `insert()`, `update()`, `delete()`
- Gestion des transactions (`beginTransaction()`, `commit()`, `rollBack()`)

Exemple d’utilisation :

```php
$db = DB::getInstance();
$user = $db->query("SELECT * FROM users WHERE id = ?", [1])->first();
```

Cette classe évite les injections SQL et simplifie les requêtes.

---

## Gestion des permissions

UserSpice utilise une gestion fine des permissions basée sur :

- Table `permissions` : groupes ou rôles définis
- Table `user_permission_matches` : relation utilisateur/groupe
- Table `permission_page_matches` : droits d’accès aux pages selon groupe

Le contrôle d’accès dans le code se fait via :

```php
if ($user->hasPermission(2)) {
    // accès réservé aux admins
}
```

---

## Système de logs

Les actions critiques (login, erreur, modification) sont enregistrées dans la table `logs` :

- Champs : `id`, `user_id`, `log`, `log_date`, `log_ip`
- Logs d’audit possibles pour les administrateurs
- Archivage automatique dans `logs_archive`

---

## Gestion des pages et routing

Les pages accessibles sont définies dans la table `pages`, avec indication de visibilité (publique, privée) et restriction par groupe.

Le routeur central analyse `$_GET['page']` et inclut la page correspondante si l’utilisateur a les droits.

Le moteur de page est dans `users/helpers/page_helpers.php`.

---
---

## Sécurité et protections intégrées

UserSpice intègre plusieurs mécanismes de sécurité :

- **Protection CSRF** : jetons CSRF générés et vérifiés sur tous les formulaires.
- **Validation côté serveur** : tous les inputs sont filtrés et validés.
- **Hashage des mots de passe** avec `password_hash()` (bcrypt).
- **Limitation des tentatives de connexion** pour éviter le brute force.
- **Sessions sécurisées** avec regeneration régulière d’ID.
- **Vérification des permissions** avant accès aux pages et actions.
- **Protection contre injection SQL** via PDO et requêtes préparées.

---

## Helpers et utilitaires

Dans `users/helpers/` se trouvent plusieurs scripts facilitant le développement :

- `validation.php` : fonctions pour valider les champs (email, password, texte).
- `csrf.php` : gestion des tokens CSRF.
- `sanitize.php` : nettoyage des données utilisateur.
- `page_helpers.php` : fonctions de routage et inclusion de pages.
- `session_helper.php` : gestion des sessions utilisateurs.

Ces helpers sont automatiquement inclus par `init.php`.

---

## Flux de connexion

Le processus de connexion est géré via la classe `User` et la page `login.php` :

1. Formulaire soumis avec username + password.
2. Validation des champs.
3. Vérification du mot de passe hashé.
4. Création de la session utilisateur.
5. Redirection vers la page d’accueil ou dernière page visitée.

La déconnexion détruit la session et redirige vers la page de login.

---

## Configuration et fichiers de préférences

Les configurations principales sont définies dans :

- `/users/init.php` : chargement des configurations et libs.
- `/users/includes/config.php` : paramètres de connexion à la base, clés API, options diverses.
- `/users/settings.php` : paramètres stockés en base (nom site, email admin).

---

## Gestion des notifications

UserSpice gère un système simple de notifications utilisateurs dans la table `notifications`.

- Notifications affichées dans l’interface.
- Possibilité de marquer comme lues.
- Utilisation dans des modules personnalisés ou alertes système.

---

## Résumé

UserSpice 5.8.1 est un framework PHP modulaire et sécurisé pour gérer utilisateurs, permissions, pages, et sécurité.

Il permet de bâtir des applications robustes, extensibles, et faciles à maintenir.

---

```
---

## Sécurité et protections intégrées

UserSpice 5.8.1 intègre plusieurs mécanismes pour sécuriser l'application :

- **Validation des entrées** : Toutes les données utilisateurs sont filtrées et validées.
- **Protection CSRF** : Jetons CSRF pour sécuriser les formulaires.
- **Hashage des mots de passe** : Utilisation de `password_hash()` avec bcrypt.
- **Gestion des sessions** : Regénération des IDs de session, expiration et verrouillage IP.
- **Permissions fines** : Contrôle d'accès granulaire par page et action.
- **Logs d’activité** : Journalisation des connexions, erreurs et actions critiques.
- **Restriction d’accès aux pages** : Pages privées et restrictions sur les groupes d’utilisateurs.
- **Protection contre les injections SQL** : Usage systématique de requêtes préparées PDO.
- **Gestion des tokens temporaires** : Pour réinitialisation de mot de passe et double authentification.

---

## Moteur de pages (page management)

Le moteur de pages de UserSpice fonctionne sur un système de déclaration dans la base :

- Les pages sont enregistrées dans la table `pages`.
- Chaque page peut être marquée privée ou publique.
- Les permissions sur les pages sont gérées par `permission_page_matches`.
- Les menus sont générés dynamiquement selon les droits utilisateurs.
- Le système de routing utilise la variable `$_GET['page']` pour charger la bonne page.
- Les pages personnalisées sont dans `/usersc/pages/` et peuvent être surchargées sans modifier le noyau.

---

## Utilitaires et helpers

UserSpice fournit de nombreux helpers pour faciliter le développement :

- Fonctions de validation (email, URL, etc.)
- Helpers pour les formulaires (CSRF, affichage des erreurs)
- Fonctions de logging et debug
- Helpers pour gérer les dates et heures
- Gestion simplifiée des sessions utilisateurs
- Fonctions pour la pagination et l'affichage
- Gestion des erreurs HTTP et redirections

---

## Fonctionnement de l’assistant SpiceDev

L’assistant **SpiceDev** repose sur cette base technique et logicielle pour :

- Comprendre la structure du projet UserSpice.
- Aider à la création de pages personnalisées.
- Gérer les permissions et rôles.
- Automatiser les tâches répétitives.
- Faciliter la maintenance et les mises à jour.
- Proposer des bonnes pratiques de sécurité.
- Générer des rapports techniques ou documentations.

---
---

## Annexes et références complémentaires

### Fichiers importants supplémentaires

- `/users/includes/config.php` : configuration globale (BD, chemins, options).
- `/users/includes/navigation.php` : génération dynamique du menu.
- `/users/includes/header.php` et `footer.php` : templates d’en-tête et pied de page.
- `/users/helpers/CSRF.php` : gestion des tokens CSRF.
- `/users/classes/Token.php` : génération et validation des tokens.
- `/users/classes/Logger.php` : gestion des logs.
- `/usersc/custom_functions.php` : fonctions personnalisées spécifiques au projet.

### Bonnes pratiques pour personnaliser UserSpice

- Toujours créer des pages dans `/usersc/pages/` pour éviter les conflits.
- Ne jamais modifier directement le noyau `/users/`.
- Utiliser les classes `User` et `DB` pour toute interaction avec la base.
- Sauvegarder les personnalisations dans `/usersc/` pour faciliter les mises à jour.
- Documenter chaque modification pour la maintenance future.

---
## Moteur de pages (page management)

UserSpice utilise un moteur de pages basé sur une base de données, ce qui permet :
- L’ajout et la suppression dynamique de pages.
- Le contrôle d’accès par permissions.
- La génération automatique de menus.

Les pages sont stockées dans la table `pages`, avec un champ `private` qui détermine si une page nécessite une connexion.

Les liens de menu sont définis par le champ `menu` dans la table.

Le fichier `/users/includes/page_functions.php` contient des fonctions clés pour gérer ce moteur.

---

## Utilitaires et helpers

Plusieurs helpers facilitent le développement :
- `Input::get()` pour récupérer les données en GET/POST.
- `Validate` pour la validation des formulaires.
- `Redirect::to()` pour les redirections.
- `Token` pour la gestion des tokens CSRF.
- `Session` pour les sessions PHP.
- `Sanitize` pour le nettoyage des données utilisateurs.

Ces classes sont disponibles dans `/users/helpers/` et sont chargées automatiquement via l’autoload.

---

## Fonctionnement de l'assistant SpiceDev

L’assistant **SpiceDev** est conçu pour exploiter la structure UserSpice avec :
- Une connaissance complète du noyau et des personnalisations.
- La capacité à générer des réponses adaptées au contexte technique.
- La possibilité de guider dans la création, modification et débogage des projets basés sur UserSpice.

SpiceDev utilise la documentation interne, les exemples de code, et les bonnes pratiques pour fournir un support fiable et pertinent.

---
# Annexes techniques

## Installation standard

1. Télécharger UserSpice 5.8.1 depuis le dépôt officiel.
2. Importer la base de données `user_spice.sql` via phpMyAdmin ou un outil équivalent.
3. Configurer le fichier `/users/init.php` avec les accès DB.
4. Mettre les droits d’écriture nécessaires sur `/usersc/uploads/`.
5. Accéder à la page d’accueil, créer un compte administrateur.

---

## Personnalisation avancée

- Utiliser le dossier `/usersc/` pour stocker les fichiers personnalisés.
- Créer des pages dans `/usersc/pages/` pour éviter les conflits lors des mises à jour.
- Ajouter des scripts JS et CSS personnalisés via `/usersc/includes/`.
- Garder le noyau `/users/` intact pour faciliter les upgrades.

---

## Ressources utiles

- Documentation officielle UserSpice : https://userspice.com/docs
- Forum communautaire : https://userspice.com/forum
- Dépôt GitHub : https://github.com/UserSpice/UserSpice

---
# Support et contributions

## Support

- Pour toute question ou problème, consulter d’abord la documentation officielle.
- Rechercher sur les forums et issues GitHub avant d’ouvrir un ticket.
- Vérifier les logs situés dans `/usersc/logs/` pour diagnostiquer les erreurs.

## Contributions

- Forker le dépôt officiel GitHub.
- Créer une branche dédiée pour vos modifications.
- Soumettre une Pull Request avec une description claire.
- Respecter la structure du code et les conventions de UserSpice.
- Tester rigoureusement avant de proposer une contribution.

---

# Remerciements

UserSpice est un projet open source développé et maintenu par une communauté passionnée. Merci à tous les contributeurs qui rendent ce projet possible.

---

# Licence

UserSpice est distribué sous licence MIT.

---
# Annexes

## Ressources utiles

- Site officiel : https://userspice.com
- Documentation complète : https://userspice.com/docs
- Forum communautaire : https://userspice.com/forum
- Dépôt GitHub : https://github.com/UserSpice/UserSpice
- Tutoriels vidéo : https://youtube.com/user/UserSpiceTutorials

## Outils recommandés

- Serveur local : XAMPP, MAMP, Laragon
- Éditeurs de code : VSCode, Atom, Sublime Text
- Gestion de base de données : phpMyAdmin, Adminer
- Contrôle de version : Git, GitHub Desktop

---

## Glossaire

- **UserSpice** : Framework PHP open source pour la gestion d’utilisateurs.
- **Permission** : Droits d’accès assignés à un utilisateur ou groupe.
- **Page privée** : Page accessible uniquement aux utilisateurs autorisés.
- **Token** : Jeton utilisé pour l’authentification temporaire.
- **PDO** : Interface PHP Data Objects pour la gestion sécurisée des bases de données.

---

# Fin du document

## Conclusion

Ce document est une base pour comprendre et développer avec UserSpice 5.8.1.  
Pour toute question ou développement spécifique, se référer au code source commenté et à la documentation officielle.

---
## Conclusion

Cette base permet de créer un assistant fiable, évolutif, et précis.

⚠️ Elle est conçue pour **UserSpice 5.8.1**. En cas de mise à jour du socle, il faudra régénérer la documentation ou vérifier manuellement la compatibilité.

---

**Fin de la documentation principale – `USERSPICE_CORE_REFERENCE.md`**
