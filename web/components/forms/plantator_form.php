<form class="selection-plantator" method="post" id="plantator">
            <section class="input-boxes-plantator">
                <section class="input-box-plantator" id="ville">
                    <label for="ville">Entrez une ville: </label>
                    <input type="text" name="ville" required placeholder="Ex: Paris, Lyon, Bordeaux..."/>
                </section>
                <section class="input-box-plantator" id="nb">
                    <label for="nb_arbres">Choisissez le nombre d'arbres à afficher :</label>
                    <input class="input-plantator" type="number" name="nb_arbres" required min="1" max="200" placeholder="Entre 1 et 200"/>
                </section>
            </section>
            <section class="validation-box-plantator">
                <input class="validation-button-plantator" type="submit" value="Valider">
            </section>
    </form>
