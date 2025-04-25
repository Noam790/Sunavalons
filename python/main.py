from utils import extract_data_for_city, find_closest_trees


def main():
    city = input("Ville : ")
    nb = int(input("Arbres : "))

    city_data = extract_data_for_city(city)
    trees = find_closest_trees(city_data, nb)

    print(trees)


main()
