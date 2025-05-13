<form method="post" class="search-form">
    <div class="form-row">
        <label for="ville">Ville :</label>
        <input type="text" id="ville" name="ville" required placeholder="Ex: Paris, Lyon, Bordeaux...">
        <input type="hidden" name="redirect" value="comparator.php">
    </div>
    <div class="form-buttons">
        <button type="submit">Analyser</button>
        <?php if (isset($ville) && validate_ville($ville) && has_csv_file($ville)): ?>
            <button><a href="upload.php?ville=<?= urlencode($ville) ?>" class="header-logo-link">Mettre à jour</a></button>
        <?php endif; ?>
    </div>
</form>
