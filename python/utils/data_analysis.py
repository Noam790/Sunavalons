import math
import os

import pandas as pd


def load_trees_data(city=None):
    base_name = "arbres_conditions"
    if city is not None:
        base_name = f"arbres_{city.lower()}"

    data_dir = "data"
    extensions = [".csv", ".ods", ".xlsx"]
    file_path = None

    for ext in extensions:
        candidate_path = os.path.join(data_dir, base_name + ext)
        if os.path.isfile(candidate_path):
            file_path = candidate_path
            print(file_path)
            break

    if not file_path:
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
    except Exception as e:
        print(f"Erreur lors du chargement : {e}")
        return None

    return df.to_dict(orient="records")


def euclidean_distance(tree, city_data):
    return math.sqrt((tree["eau"] - city_data["eau"])**2 +
                     (tree["sol"] - city_data["sol"])**2 +
                     (tree["climat"] - city_data["climat"])**2 +
                     (tree["exposition"] - city_data["exposition"])**2)


def find_closest_trees(city, trees_data, k=5): # K plus proches arbres
    distances = [(tree["genre_francais"], euclidean_distance(tree, city))for tree in trees_data]
    distances.sort(key=lambda x: x[1])

    return distances[:k]
