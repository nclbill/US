#!/bin/bash

# Se rendre dans le dossier du projet
cd /Applications/XAMPP/xamppfiles/htdocs/espacemoto || exit 1

# Générer la date au format "YYYY-DDD-HH-MM-SS" (DDD = jour de l'année)
commit_date=$(date +"%Y-%j-%H-%M-%S")

# Ajouter tous les fichiers
git add .

# Commit avec message contenant la date
git commit -m "Sauvegarde automatique : $commit_date"

# Pousser sur la branche main
git push origin main
