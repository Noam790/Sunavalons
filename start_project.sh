#!/bin/bash

# Trouver la version de python qui fonctionne
echo "Recherche de la version de python"
if command -v python &> /dev/null && python --version &> /dev/null; then
    PYTHON_CMD=python
elif command -v python3 &> /dev/null && python3 --version &> /dev/null; then
    PYTHON_CMD=python3
else
    echo "Python n'est pas installé ou la commande ne fonctionne pas correctement."
    exit 1
fi

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
    $PYTHON_CMD app.py & # arrière plan
    cd web || exit
    php -S localhost:8080 &
elif [[ "$OS_TYPE" == "CYGWIN"* || "$OS_TYPE" == "MINGW"* || "$OS_TYPE" == "MSYS"* ]]; then
    # Pour Windows
    echo "Lancement du serveur sur Windows..."
    start "" $PYTHON_CMD app.py
    cd web || exit
    php -S localhost:8080
else
    echo "Système d'exploitation non supporté."
    exit 1
fi
