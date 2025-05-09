from flask import Flask, jsonify, request

from python.utils.data_analysis import find_closest_trees, load_trees_data
from python.utils.data_displaying import get_results
from python.utils.data_extraction import extract_data_for_city

app = Flask(__name__)

# Charger les arbres pour optimiser les requêtes
tree_set = load_trees_data()

@app.route("/api")

def api_get_trees():
    city = request.args.get("ville") # Paramètres du formulaire
    nb_trees = request.args.get("nb_arbres", type=int)

    if not city or not nb_trees:
        return jsonify({"error": "Paramètres manquants"}), 400

    trees = get_results(city, nb_trees, tree_set)
    print(trees)

    if isinstance(trees, str):  # Erreur retournée par get_results
        return jsonify({"error": trees}), 400

    return jsonify(trees)

@app.route("/api/city_trees")
def api_city_trees():
    city = request.args.get("ville")
    if not city:
        return jsonify({"error": "Paramètres manquants"}), 400

    # 1. Récupérer le climat de la ville
    city_data = extract_data_for_city(city)
    if not city_data:
        return jsonify({"error": "Climat de la ville introuvable"}), 400

    # 2. Charger les arbres de la ville
    city_trees = load_trees_data(city)
    if city_trees is None:
        return jsonify({"error": "Le csv de votre ville est introuvable"}), 400

    # 3. Récupérer les noms de référence
    city_tree_names = set([i["genre_francais"] for i in city_trees])

    # 4. Garder seulement les arbres communs (présents dans les deux)
    filtered_trees = [tree for tree in tree_set if tree["genre_francais"] in city_tree_names]

    if not filtered_trees:
        return jsonify({"error": "Aucun arbre commun trouvé"}), 400

    # 5. Trier par compatibilité (distance)
    sorted_trees = find_closest_trees(city_data, filtered_trees, len(filtered_trees))
    best_score = sorted_trees[0][1]
    worst_score = sorted_trees[-1][1]

    # 6. Trouver les arbres les plus et moins adaptés
    best_trees = [i for i in sorted_trees if i[1] == best_score]
    worst_trees = [i for i in sorted_trees if i[1] == worst_score]

    print(best_trees, worst_trees, best_trees == worst_trees)

    # 7. Retourner la liste triée
    return jsonify({"best_trees": best_trees, "worst_trees": worst_trees}) if best_trees != worst_trees else jsonify({"best_trees": best_trees})

app.run()
