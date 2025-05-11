<div class="error">
    <p><?= htmlspecialchars($error) ?></p>
    <?php
    if (
        isset($error, $ville)
        && ($error === "Votre commune n'a pas fourni la liste de ses arbres."
            || $error === "Le csv de votre ville est introuvable")
        && !empty($ville)
    ) {
        include 'components/upload_ville.php';
    }
    ?>
</div>
