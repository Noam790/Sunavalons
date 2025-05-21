<?php if (!$error && isset($ville)): ?>
    <form method="post" action="plantator.php" class="redirect-plantator">
        <input type="hidden" name="ville" value="<?= htmlspecialchars($_POST["ville"]) ?>">
        <button type="submit">
            Voir les
            <input type="number" name="nb_arbres" value="5" min="1" max="20">
            arbres recommandés pour <?= htmlspecialchars($ville) ?>
        </button>
    </form>
<?php endif; ?>
