from utils import find_closest_trees, extract_data_for_city

def main():
    city = str(input("Dans quelle ville souhaitez vous ajouter des arbres ? : "))
    nb_trees = int(input("Combien d'arbres différents voulez vous voir ? : "))
    city_data = extract_data_for_city(city) #climat de la ville
    best_trees = find_closest_trees(city_data,nb_trees) #meilleurs arbres pour ce climat

    print(f"\n{nb_trees} Arbres les mieux adaptés aux conditions climatiques de {city} : ")
    for tree, dist in best_trees:
        print(f"{tree}, distance avec caractéristiques de la ville : {dist}")
    return

main()
