#!/bin/bash
# Script pour rendre ton dépôt GitHub "US" privé

REPO="nclbill/US"

echo "🔄 Passage du dépôt $REPO en mode privé..."
gh repo edit "$REPO" --visibility private --accept-visibility-change-consequences

if [ $? -eq 0 ]; then
    echo "✅ Dépôt maintenant privé."
else
    echo "❌ Échec de la mise à jour."
fi
