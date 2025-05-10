<div class="error">
    <p><?= htmlspecialchars($error) ?></p>
    <?php
    if (
        isset($error, $ville)
        && $error === "Votre commune n'a pas fourni la liste de ses arbres."
        && !empty($ville)
    ) {
        include 'components/upload_ville.php';
    }
    ?>
</div>
