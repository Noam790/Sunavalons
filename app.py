from flask import Flask, jsonify, request

from python.utils.data_analysis import find_closest_trees, load_trees_data
from python.utils.data_displaying import get_results
from python.utils.data_extraction import extract_data_for_city, get_coordinates

app = Flask(__name__)

# Charger les arbres pour optimiser les requêtes
tree_set = load_trees_data()

@app.route("/api")
def api_get_trees():
    city = request.args.get("ville") # Paramètres du formulaire (requis)
    nb_trees = request.args.get("nb_arbres", type=int)

    trees = get_results(city, nb_trees, tree_set)

    if isinstance(trees, str):
        return jsonify({"error": trees}), 400

    return jsonify(trees)

@app.route("/api/city_trees")
def api_city_trees():
    city = request.args.get("ville")
    if not city:
        return jsonify({"error": "Paramètres manquants"}), 400

    # 1. Récupérer le climat de la ville
    lat, lon = get_coordinates(city)  # Ajoute cette ligne
    if lat is None or lon is None:
        return jsonify({"error": "Ville introuvable"}), 400

    city_data = extract_data_for_city(city)
    if not city_data:
        return jsonify({"error": "Votre commune n'a pas fourni la liste de ses arbres."}), 400

    # Charger les arbres de la ville
    city_trees = load_trees_data(city)
    if city_trees is None:
        return jsonify({"error": "Le csv de votre ville est introuvable"}), 400

    # Filtrer les arbres communs à la ville et notre reference
    filtered_trees = tree_set[tree_set["genre_francais"].isin(city_trees["genre_francais"])]

    if filtered_trees.empty:
        return jsonify({"error": "Aucun arbre commun trouvé"}), 400

    # Utiliser get_results pour trier les arbres
    trees = get_results(city, len(filtered_trees), filtered_trees)

    if isinstance(trees, str):
        return jsonify({"error": trees}), 400


    # Meilleur et pire score de distance
    best_score = trees[0][1]
    worst_score = trees[-1][1]

    # Séparation des meilleurs et pires arbres
    best_trees = [tree for tree in trees if tree[1] == best_score]
    worst_trees = [tree for tree in trees if tree[1] == worst_score]

    if best_trees != worst_trees:
        return jsonify({"best_trees": best_trees, "worst_trees": worst_trees})
    else:
        return jsonify({"best_trees": best_trees})

app.run()
