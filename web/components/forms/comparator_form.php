<form method="post" class="search-form">
    <div class="form-row">
        <label for="ville">Entrez une ville:</label>
        <input type="text" id="ville" name="ville" required placeholder="Ex: Paris, Lyon, Bordeaux...">
    </div>
    <div class="form-buttons">
        <button type="submit">Analyser</button>
        <?php if (isset($ville) && validate_ville($ville) && has_csv_file($ville)): ?>
            <button><a href="upload.php?ville=<?= urlencode($ville) ?>" class="header-logo-link">Mettre à jour</a></button>
        <?php endif; ?>
    </div>
</form>
