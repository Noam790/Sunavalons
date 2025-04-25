import math

import pandas as pd


def euclidean_distance(tree, city_data):
    return math.sqrt((tree["eau"] - city_data["eau"])**2 +
                     (tree["sol"] - city_data["sol"])**2 +
                     (tree["climat"] - city_data["climat"])**2 +
                     (tree["exposition"] - city_data["exposition"])**2)


def find_closest_trees(
        city, k=5):  # 5 plus proches arbres selon les conditions d'une ville
    trees_df = pd.read_csv("../data/arbres_conditions.csv")
    trees = trees_df.to_dict(orient="records")
    distances = [(tree["genre_francais"], euclidean_distance(tree, city))
                 for tree in trees]
    distances.sort(key=lambda x: x[1])

    return [tree[0]
            for tree in distances[:k]]  # Ne renvoyer que les noms des arbres
