from utils import find_closest_trees, extract_data_for_city

def main():
    city = str(input("Dans quelle ville souhaitez vous ajouter des arbres ? : "))
    city_data = extract_data_for_city(city) #climat de la ville
    best_trees = find_closest_trees(city_data,20) #meilleurs arbres pour ce climat

    print("arbres les mieux adaptés aux conditions climatiques de votre ville : ")
    for tree, dist in best_trees:
        print(f"arbre : {tree}, distance avec caractéristiques de la ville : {dist}")
    return

main()
