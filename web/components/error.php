<div class="error">
    <p><?= htmlspecialchars($error) ?></p>
    <?php
    if (
        isset($error, $ville)
        && !empty($ville)
        && (
            $error === "Votre commune n'a pas fourni la liste de ses arbres."
            || $error === "Le csv de votre ville est introuvable"
            || $error === "Erreur lors de la mise à jour du fichier."
        )
    ) {
        include 'components/upload_ville.php';
    }
    ?>
</div>
