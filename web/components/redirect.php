<?php if (!$error && isset($ville)): ?>
    <form method="post" action="index.php" style="margin-top:2em; display:inline; ">
        <input type="hidden" name="ville" value="<?= htmlspecialchars($_POST["ville"]) ?>">
        <button type="submit" style="display:inline-flex; align-items:center; gap:6px; font-size:1em; padding:8px 8px; cursor:pointer;">
            Voir les
            <input type="number" name="nb_arbres" value="5" min="1" max="20" ">
            arbres recommandés pour <?= htmlspecialchars($ville) ?>
        </button>
    </form>
<?php endif; ?>
