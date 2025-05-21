import math
import os

import pandas as pd


def load_trees_data(city=None):
    """Charger les arbres du Comparator dans un Dataframe Pandas en fonction de la ville en paramètre
    diponible dans un fichier .csv, .xlsx ou .ods."""

    base_name = "arbres_conditions"
    if city is not None:
        base_name = f"arbres_{city.lower()}"

    data_dir = "data"
    extensions = [".csv", ".ods", ".xlsx"]
    file_path = None

    for ext in extensions: # Boucle pour trouver l'extension du fichier dans la liste d'extensions
        candidate_path = os.path.join(data_dir, base_name + ext)
        if os.path.isfile(candidate_path):
            file_path = candidate_path
            break

    if not file_path: # Renvoie None s'il ne trouve pas le fichier
        return None

    try:
        if file_path.endswith(".csv"):
            df = pd.read_csv(file_path)
        elif file_path.endswith(".ods"):
            df = pd.read_excel(file_path, engine="odf")
        elif file_path.endswith(".xlsx"):
            df = pd.read_excel(file_path)
        else:
            return None
    except Exception as e: #Si la lecture échoue, print l'erreur et renvoie None
        print(f"Erreur lors du chargement : {e}")
        return None
    return df

def match_city_trees_to_ref(city_trees, ref_tree_names):
    """Chercher l'arbre global avec ses sous classes -> "Erable de ..." -> "Erable"."""
    def find_ref_name(name):
        for ref in ref_tree_names:
            if name.lower().startswith(ref.lower()):
                return ref
        return name  # Sinon, on garde le nom original

    return city_trees.apply(find_ref_name)


def euclidean_distance(tree, city_data):
    """Distance euclidienne entre toutes les variables de la ville et des arbres."""
    return math.sqrt((tree["eau"] - city_data["eau"])**2 +
                     (tree["sol"] - city_data["sol"])**2 +
                     (tree["climat"] - city_data["climat"])**2 +
                     (tree["exposition"] - city_data["exposition"])**2)


def find_closest_trees(city, trees_data, k=5):
    """K plus proches arbres."""
    distances = [
    (tree["genre_francais"], euclidean_distance(tree, city))for _, tree in trees_data.iterrows()]
    distances.sort(key=lambda x: x[1])

    return distances[:k]
