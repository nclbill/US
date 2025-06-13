#!/bin/bash
# Script pour rendre ton dépôt GitHub "US" public

REPO="nclbill/US"

echo "🔓 Passage du dépôt $REPO en mode public..."
gh repo edit "$REPO" --visibility public --accept-visibility-change-consequences

if [ $? -eq 0 ]; then
    echo "✅ Dépôt maintenant public."
else
    echo "❌ Échec de la mise à jour."
fi
