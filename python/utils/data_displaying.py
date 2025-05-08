from python.utils import extract_data_for_city, find_closest_trees


def get_results(city, nb_trees, trees_data):
    city_data = extract_data_for_city(city)  # climat de la ville
    if not city_data:
        return ["Données de la ville introuvables."]
    return find_closest_trees(city_data, trees_data, nb_trees)
