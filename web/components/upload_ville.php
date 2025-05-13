<?php
$file_path = __DIR__ . "/../../data/arbres_" . strtolower($ville) . ".csv";
$file_exists = file_exists($file_path);
?>

<div class="error">
    <p>
        <?php if ($file_exists): ?>
            Un fichier existe déjà pour <?= htmlspecialchars($ville) ?>.
            Vous pouvez le mettre à jour en envoyant un nouveau fichier.
        <?php else: ?>
            Vous pouvez fournir un fichier CSV contenant la liste des arbres de votre commune.
        <?php endif; ?>
    </p>

    <p>
        <strong>Format attendu :</strong> un nom d'arbre par ligne, première lettre en majuscule,<br>
        utilisez des <b>_</b> à la place des espaces ou des tirets.<br>
        Exemple :<br>
        <code>
            Platane<br>
            Arbre_de_judee<br>
            Faux_cypres<br>
            ...
        </code>
    </p>

    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="ville" value="<?= htmlspecialchars($ville) ?>">
        <div>
            <input type="file" name="csv_file" id="csv_file" accept=".csv,.ods,.xlsx" required>
            <button type="submit">
                <?= $file_exists ? 'Mettre à jour' : 'Envoyer le fichier' ?>
            </button>
        </div>
    </form>
</div>
