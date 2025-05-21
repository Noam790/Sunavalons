from concurrent.futures import ThreadPoolExecutor
from datetime import datetime

import requests

COMMON_HEADERS = {
    "User-Agent":
    "Mozilla/5.0 (Windows NT 10.0; Win64; x64) "
    "AppleWebKit/537.36 (KHTML, like Gecko) "
    "Chrome/122.0.0.0 Safari/537.36"
}


def get_coordinates(ville):
    """Obtenir les coordonnées (lat, long) de la ville en paramètre."""
    url = (f"https://nominatim.openstreetmap.org/search?"
           f"q={ville}&format=json&limit=1&email=email@example.com")

    try:
        response = requests.get(url, headers=COMMON_HEADERS)
        response.raise_for_status()  # Check les erreurs
        data = response.json()
        if data:
            lat = data[0]["lat"]
            lon = data[0]["lon"]
            return lat, lon
        else:
            return None,None
    except requests.exceptions.RequestException as e:
        return None,None


def get_precipitation(lat, lon):
    """Précipitations annuelles via Open-Meteo."""
    year = datetime.now().year - 1
    url = (
        f"https://archive-api.open-meteo.com/v1/archive?"
        f"latitude={lat}&longitude={lon}&start_date={year}-01-01&end_date={year}-12-31"
        f"&daily=precipitation_sum&timezone=Europe%2FParis")

    response = requests.get(url, headers=COMMON_HEADERS).json()
    return sum(response["daily"]["precipitation_sum"])


def get_soil_ph(lat, lon):
    """pH du sol via SoilGrids."""
    url = (f"https://rest.isric.org/soilgrids/v2.0/properties/query"
           f"?lon={lon}&lat={lat}&property=phh2o")

    try:
        r = requests.get(url, headers=COMMON_HEADERS).json()
        depths = r["properties"]["layers"][0]["depths"]
        for depth in depths:
            values = depth.get("values", {})
            raw_ph = values.get("mean") or values.get("Q0.5")
            if raw_ph is not None:
                return raw_ph / 10
        return None
    except Exception:
        return None


def get_min_temperature(lat, lon):
    """Température minimale annuelle via Open-Meteo."""
    year = datetime.now().year - 1
    url = (
        f"https://archive-api.open-meteo.com/v1/archive?"
        f"latitude={lat}&longitude={lon}&start_date={year}-01-01&end_date={year}-12-31"
        f"&daily=temperature_2m_min&timezone=Europe%2FParis")

    response = requests.get(url, headers=COMMON_HEADERS).json()
    return min(response["daily"]["temperature_2m_min"])


def get_solar_radiation(lat, lon):
    """Rayonnement solaire direct moyen l'an passé via Open-Meteo"""
    year = datetime.now().year - 1
    url = (f"https://archive-api.open-meteo.com/v1/archive?"
           f"latitude={lat}&longitude={lon}"
           f"&start_date={year}-01-01&end_date={year}-12-31"
           f"&daily=shortwave_radiation_sum"
           f"&timezone=Europe%2FParis")

    response = requests.get(url, headers=COMMON_HEADERS).json()
    radiation = response["daily"]["shortwave_radiation_sum"]

    # Conversion de MJ/m² en Wh/m²
    radiation_wh_m2 = [r * 277.78 for r in radiation]

    return sum(radiation_wh_m2) / len(
        radiation_wh_m2)  # Moyenne chaleur en juin


def extract_data_for_city(ville):
    """Fonction main regroupant les indicateurs."""
    lat, lon = get_coordinates(ville)

    if lat is None or lon is None:
        return None
    indicators = {}

    with ThreadPoolExecutor(max_workers=4) as executor: #  Paralléliser les appels API
        thread_precip = executor.submit(get_precipitation, lat, lon)
        thread_ph = executor.submit(get_soil_ph, lat, lon)
        thread_tmin = executor.submit(get_min_temperature, lat, lon)
        thread_radiation = executor.submit(get_solar_radiation, lat, lon)

        precip = thread_precip.result()
        ph = thread_ph.result()
        tmin = thread_tmin.result()
        radiation = thread_radiation.result()

    # Normalisation Eau
    if precip < 300:
        indicators["eau"] = 1
    elif precip < 500:
        indicators["eau"] = 2
    elif precip < 800:
        indicators["eau"] = 3
    elif precip < 1200:
        indicators["eau"] = 4
    else:
        indicators["eau"] = 5

    # Normalisation Sol
    if ph is None:
        indicators["sol"] = 3  # Valeur par défaut
    elif ph < 4.0 or ph > 9.5:
        indicators["sol"] = 1
    elif 4.0 <= ph < 5.0 or 9.0 < ph <= 9.5:
        indicators["sol"] = 2
    elif 5.0 <= ph < 6.0 or 8.5 < ph <= 9.0:
        indicators["sol"] = 3
    elif 6.0 <= ph < 7.0 or 7.8 < ph <= 8.5:
        indicators["sol"] = 4
    elif 7.0 <= ph <= 8.0:
        indicators["sol"] = 5

    # Normalisation Climat
    if tmin < -30:
        indicators["climat"] = 1
    elif tmin < -20:
        indicators["climat"] = 2
    elif tmin < -10:
        indicators["climat"] = 3
    elif tmin < -5:
        indicators["climat"] = 4
    else:
        indicators["climat"] = 5

    # Normalisation Exposition
    if radiation < 2000:
        indicators["exposition"] = 1
    elif radiation < 3000:
        indicators["exposition"] = 2
    elif radiation < 4000:
        indicators["exposition"] = 3
    elif radiation < 5000:
        indicators["exposition"] = 4
    else:
        indicators["exposition"] = 5

    return indicators
