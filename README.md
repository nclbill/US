# Projet basé sur UserSpice 5.8.1 – Collaboration avec ChatGPT

## 🎯 Objectif du dépôt

Ce dépôt sert de **base de travail avec ChatGPT**.  
Il est construit sur le framework **UserSpice 5.8.1** (non modifié dans sa logique de gestion des utilisateurs) et contient des **ajouts et adaptations spécifiques** pour un projet de gestion de commandes.

> 💬 Le but n'est **pas de documenter UserSpice**, mais de fournir un contexte clair pour que ChatGPT puisse m'assister efficacement dans le développement.

---

## 📦 Contenu

### 📁 Pages personnalisées ajoutées

Le projet comprend plusieurs **nouvelles pages PHP** créées pour la saisie et la gestion des commandes :

- `saisie.php` – Saisie des informations d’un produit à réserver
- `modifs.php` – Modification des données existantes
- `commandes.php` – Page de création d'une commande
- `commandes_traitement.php` – Traitement des commandes
- `commandes_suivi.php` – Suivi des commandes en cours
- `collaborateurs.php` – Gestion des collaborateurs autorisés

---

## 🗄️ Base de données

Un fichier SQL d’export de la base est inclus, contenant **seulement la structure** (aucune donnée personnelle).

### 📊 Tables personnalisées ajoutées au schéma :

- `produits` – Produits commandables
- `commandes` – Enregistrements des commandes effectuées
- `acheteurs` – Données client associées aux commandes
- `collaborateurs` – Gestion des utilisateurs autorisés

---

## 🔐 Sécurité

- **Aucune donnée sensible** n’est incluse
- La base de données contient **la structure uniquement**
- L'intégration Git ignore les fichiers temporaires ou sensibles

---

## 🧠 Pourquoi ce dépôt ?

Ce projet sert uniquement à **fournir un environnement de travail clair à ChatGPT**, afin de faciliter :
- Le débogage
- L’écriture de code adapté à la structure réelle
- L’évolution de pages spécifiques au projet

---
