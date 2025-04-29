#!/bin/bash

# Utilisation : ./commit.sh "message"

echo "🔍 Exécution des vérifications pre-commit..."
pre-commit run --all-files

echo "➕ Ajout des modifications..."
git add .
pre-commit run --all-files # pour re corriger une fois tout add (normalement tout passe)

# Étape 3 : Faire le commit
echo "💾 Création du commit avec le message : $1"
git commit -m "$1"

# Étape 4 : Vérification finale
echo "✅ Commit créé avec succès !"
git show --name-status

# Étape 5 : Push les modifications ?
read -p "Voulez-vous push les modifications ? [Y/n] " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]] || [[ -z $REPLY ]]
then
    echo "🚀 Push en cours..."
    git push
    echo "✅ Push effectué avec succès !"
else
    echo "⏸️  Push annulé (les commits restent locaux)"
fi
