import math
import os

import pandas as pd

trees_data = None
all_tree_names = None

def load_trees_data():
    global trees_data, all_tree_names
    if trees_data is None:
        csv_path = os.path.join("data", "arbres_conditions.csv")
        trees_df = pd.read_csv(csv_path)
        trees_data = trees_df.to_dict(orient="records")
        all_tree_names = [tree["genre francais"] for tree in trees_data]
    return trees_data


def euclidean_distance(tree, city_data):
    return math.sqrt((tree["eau"] - city_data["eau"])**2 +
                     (tree["sol"] - city_data["sol"])**2 +
                     (tree["climat"] - city_data["climat"])**2 +
                     (tree["exposition"] - city_data["exposition"])**2)


def find_closest_trees(city, k=5): # K plus proches arbres
    global all_tree_names
    if k >= len(trees_data):
        return all_tree_names
    distances = [(tree["genre francais"], euclidean_distance(tree, city))
                 for tree in trees_data]
    distances.sort(key=lambda x: x[1])

    return distances[:k]
