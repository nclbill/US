# UserSpice – Base Technique Réutilisable

Ce dépôt contient la **version stable et documentée** de [UserSpice 5.8.1](https://userspice.com), conçue pour servir de **socle technique** à la création d’assistants intelligents, de plateformes sécurisées, ou de systèmes de gestion sur mesure.

---

## 📁 Structure du dépôt

```
US/
├── users/                 → Noyau UserSpice
├── usersc/                → Personnalisations locales (persistantes)
├── docs/                  → Documentation complète
│   ├── USERSPICE_CORE_REFERENCE.md
│   ├── USERSPICE_STRUCTURE.pdf
│   ├── USERSPICE_REFERENCE.html
│   └── README.md          ← Ce fichier
└── ...
```

---

## 🎯 Objectif

Ce dépôt a pour but de fournir une **base solide, claire et extensible** à tous les projets basés sur UserSpice, y compris :

- 🔐 Plateformes sécurisées avec authentification multi-rôle  
- 🧠 Assistants intelligents comme **SpiceDev**  
- ⚙️ Back-offices modulaires  
- 🧱 Bases prêtes à l’emploi pour développeurs  
---

## 🚀 Démarrage rapide

1. **Cloner le dépôt**

```bash
git clone https://github.com/votre-utilisateur/US.git
cd US
```

2. **Configurer l’environnement**

- Copier `.env.example` vers `.env` si présent
- Configurer les identifiants de base de données dans `/users/init.php`
- Importer la base via `users/install/sql`

3. **Lancer le projet dans un navigateur**

```bash
http://localhost/US/users/
```

---

## 📚 Documentation incluse

| Fichier                          | Description                                               |
|----------------------------------|-----------------------------------------------------------|
| `USERSPICE_CORE_REFERENCE.md`   | Documentation technique complète en Markdown              |
| `USERSPICE_STRUCTURE.pdf`       | Version imprimable PDF (français + anglais)               |
| `USERSPICE_REFERENCE.html`      | Navigation locale interactive de la documentation         |
| `README.md`                     | Présentation du dépôt et guide rapide                     |

---

## 🧠 Assistant SpiceDev

Ce dépôt est la base officielle de **SpiceDev**, un assistant intelligent capable de :

- Comprendre la structure complète de UserSpice
- Répondre à toutes les questions sur les fichiers internes
- Générer du code PHP compatible avec UserSpice
- Automatiser les tâches courantes

Utilisable dans [ChatGPT Custom GPTs](https://chat.openai.com/gpts) ou via l’API OpenAI.

---
## 🏗 Structure du dépôt

```
US/
├── users/                    # Dossier principal UserSpice (core)
├── usersc/                   # Dossier personnalisé non écrasé par les MAJ
├── docs/                     # Documentation complète (générée)
│   ├── USERSPICE_CORE_REFERENCE.md
│   ├── USERSPICE_STRUCTURE.pdf
│   └── USERSPICE_REFERENCE.html
├── README.md                 # Présentation du projet (ce fichier)
├── .gitignore
└── ...
```

---

## 🎯 Objectif du dépôt

Ce dépôt est conçu pour servir de **base technique universelle** pour :

- Développer des projets clients variés à partir d’un socle stable
- Maintenir la compatibilité avec UserSpice 5.8.1
- Isoler la logique cœur de UserSpice des personnalisations projets
- Documenter proprement tous les composants internes de UserSpice

---

## 🔒 Confidentialité

Ce projet est **privé**. Ne pas republier sans autorisation.

---

## 🧪 Statut

✅ Base UserSpice 5.8.1 analysée et documentée  
🔄 Ajout progressif d’assistants personnalisés client  
📦 Prêt pour intégration dans ChatGPT via Custom GPTs

---
## ⚙️ Utilisation locale

### 1. Cloner le dépôt

```bash
git clone https://github.com/nclbill/US.git
cd US
```

### 2. Consulter la documentation

Tous les fichiers se trouvent dans le dossier `docs/`.  
Vous pouvez :

- Lire `USERSPICE_CORE_REFERENCE.md` dans un éditeur de texte
- Ouvrir `USERSPICE_STRUCTURE.pdf` avec un lecteur PDF
- Lancer `USERSPICE_REFERENCE.html` dans un navigateur

---

## 🧠 Utilisation dans un assistant IA (ChatGPT)

### Préparation

1. Se rendre sur [https://chat.openai.com/gpts/editor](https://chat.openai.com/gpts/editor)
2. Créer un nouvel assistant
3. Donner un nom comme **SpiceDev**
4. Ajouter les fichiers suivants dans l’onglet **"Fichiers"** :
   - `USERSPICE_CORE_REFERENCE.md`
   - `USERSPICE_STRUCTURE.pdf` *(facultatif)*
   - `USERSPICE_REFERENCE.html` *(facultatif)*

### Conseils

- Choisir un **ton technique** (expert PHP, documentation)
- Ajouter un comportement : *« Répond de manière précise sur la base UserSpice 5.8.1 »*
- Définir des instructions personnalisées pour éviter les erreurs liées aux projets clients

---

## 🧩 Intégration dans des projets

Créer une branche par projet client :

```bash
git checkout -b client-<nom>
```

Y intégrer uniquement :
- Les fichiers de personnalisation dans `usersc/`
- Les nouvelles pages, hooks, ou adaptations
- Les assistants IA spécifiques si besoin

---
## 🛠️ Déploiement recommandé

### Pré-requis

- PHP 7.4+
- MySQL 5.7+ ou MariaDB
- Serveur Apache ou Nginx
- Extensions : PDO, mbstring, openssl, fileinfo

### Étapes

1. Copier les fichiers sur le serveur :
   ```bash
   cp -r US/* /var/www/html/
   ```

2. Créer la base de données :
   ```sql
   CREATE DATABASE userspice CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

3. Lancer le site et suivre l’installateur via navigateur :
   ```
   http://localhost/users/install/
   ```

4. Supprimer le dossier `install/` après installation.

---

## 🔒 Bonnes pratiques de sécurité

- Mettre `.htaccess` dans tous les dossiers sensibles
- Changer le préfixe des tables
- Ne jamais exposer `/users/init.php` directement
- Protéger l’accès à `/admin.php` par permission
- Ne pas modifier le noyau dans `/users/`, utiliser `/usersc/`

---

## 📚 Ressources complémentaires

- Documentation officielle : [https://userspice.com/documentation](https://userspice.com/documentation)
- Forum de la communauté : [https://userspice.com/forums/](https://userspice.com/forums/)
- Dépôt GitHub : [https://github.com/nclbill/US](https://github.com/nclbill/US)

---

## 🔖 Licence

Ce socle UserSpice modifié est soumis à la licence [Open Source MIT](https://opensource.org/licenses/MIT).  
Vous pouvez l’utiliser, le modifier et le distribuer librement avec attribution.

---
