<form method="post" class="search-form">
    <div class="form-row">
        <label for="ville">Ville :</label>
        <input type="text" id="ville" name="ville" required placeholder="Ex: Paris, Lyon, Bordeaux...">
    </div>
    <div class="form-row">
        <label for="nb_arbres">Nombre d'arbres :</label>
        <input type="number" id="nb_arbres" name="nb_arbres" required min="1" max="200" placeholder="Entre 1 et 200">
    </div>
    <button type="submit">Rechercher</button>
</form>
