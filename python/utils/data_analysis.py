import math

import pandas as pd

trees_data = None


def load_trees_data():
    global trees_data
    if trees_data is None:
        trees_df = pd.read_csv("data\\arbres_conditions.csv")
        trees_data = trees_df.to_dict(orient="records")
    return trees_data


def euclidean_distance(tree, city_data):
    return math.sqrt((tree["eau"] - city_data["eau"])**2 +
                     (tree["sol"] - city_data["sol"])**2 +
                     (tree["climat"] - city_data["climat"])**2 +
                     (tree["exposition"] - city_data["exposition"])**2)


def find_closest_trees(
        city, k=5):  # 5 plus proches arbres selon les conditions d'une ville
    distances = [(tree["genre francais"], euclidean_distance(tree, city))
                 for tree in trees_data]
    distances.sort(key=lambda x: x[1])

    return [tree[0]
            for tree in distances[:k]]  # Ne renvoyer que les noms des arbres
