<p>
    Vous pouvez fournir un fichier CSV contenant la liste des arbres de votre commune.<br>
    <strong>Format attendu :</strong> Nom de la colonne  : "genre_francais", un nom d'arbre par ligne, première lettre en majuscule, <br>
    utilisez des <b>_</b> à la place des espaces ou des tirets.<br>
    Exemple :<br>
    <code>
        genre_francais<br>
        Platane<br>
        Arbre_de_judee<br>
        Faux_cypres<br>
        ...
    </code>
</p>
<form method="post" enctype="multipart/form-data" class="upload-form" style="margin-top:2em;">
    <input type="hidden" name="ville" value="<?= htmlspecialchars($ville) ?>">
    <input type="file" name="csv_file" id="csv_file" accept=".csv,.ods,.xlsx" required>
    <button type="submit">Envoyer le fichier</button>
</form>
