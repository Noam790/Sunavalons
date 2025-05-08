import math
import os

import pandas as pd


def load_trees_data(city=None):
    csv_path = os.path.join("data", f"arbres_conditions.csv")
    if(city is not None):
        csv_path = os.path.join("data", f"arbres_{city.lower()}.csv")
    try:
        trees_df = pd.read_csv(csv_path)
    except:
        return None
    return trees_df.to_dict(orient="records")

def euclidean_distance(tree, city_data):
    return math.sqrt((tree["eau"] - city_data["eau"])**2 +
                     (tree["sol"] - city_data["sol"])**2 +
                     (tree["climat"] - city_data["climat"])**2 +
                     (tree["exposition"] - city_data["exposition"])**2)


def find_closest_trees(city, trees_data, k=5): # K plus proches arbres
    distances = [(tree["genre_francais"], euclidean_distance(tree, city))for tree in trees_data]
    distances.sort(key=lambda x: x[1])

    return distances[:k]
