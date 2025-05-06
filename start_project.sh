#!/bin/bash

# Installer les dépendances
echo "Vérification des dépendances Python..."
if ! python -m pip install -r requirements.txt; then
    echo "Erreur lors de l'installation des dépendances Python"
    exit 1
fi

# Détecter le système d'exploitation
OS_TYPE=$(uname)

if [[ "$OS_TYPE" == "Linux" || "$OS_TYPE" == "Darwin" ]]; then
    # Pour Linux ou MacOS
    echo "Lancement du serveur sur Linux/MacOS..."
    python app.py & # arrière plan
    cd web || exit
    php -S localhost:8080 &
elif [[ "$OS_TYPE" == "CYGWIN"* || "$OS_TYPE" == "MINGW"* || "$OS_TYPE" == "MSYS"* ]]; then
    # Pour Windows
    echo "Lancement du serveur sur Windows..."
    start python app.py
    cd web || exit
    php -S localhost:8080
else
    echo "Système d'exploitation non supporté."
    exit 1
fi
