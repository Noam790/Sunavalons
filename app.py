from flask import Flask, jsonify, request

from python.utils import get_results

app = Flask(__name__)


@app.route("/api")
def api_get_trees():
    city = request.args.get("ville")
    nb_trees = request.args.get("nb_arbres", type=int)

    if not city or not nb_trees:
        return jsonify({"error": "Paramètres manquants"}), 400

    trees = get_results(city, nb_trees)

    if isinstance(trees, str):  # Erreur retournée par get_results
        return jsonify({"error": trees}), 400

    return jsonify(trees)


app.run(debug=True)
