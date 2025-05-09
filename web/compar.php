<?php
$best_trees = null;
$worst_trees = null;
$error = null;
$ville = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ville = $_POST["ville"] ?? null;
    $image_path = "assets/trees/";

    if (isset($_FILES["csv_file"]) && $ville) {
        $upload_dir = __DIR__ . "/../data/";

        // Obtenir l'extension du fichier original
        $original_filename = $_FILES["csv_file"]["name"];
        $file_extension = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));

        // Vérifier que l'extension est autorisée
        $allowed_extensions = ['csv', 'ods', 'xlsx'];
        if (!in_array($file_extension, $allowed_extensions)) {
            $error = "Format de fichier non supporté. Seuls les fichiers CSV, ODS ou XLSX sont acceptés.";
        } else {
            $upload_name = "arbres_" . strtolower($ville) . "." . $file_extension;
            $upload_path = $upload_dir . $upload_name;

            if (move_uploaded_file($_FILES["csv_file"]["tmp_name"], $upload_path)) {
                $success = "Le fichier a bien été envoyé ! Vous pouvez relancer l'analyse.";
            } else {
                $error = "Erreur lors de l'envoi du fichier.";
            }
        }
    } else {
        $url = "http://127.0.0.1:5000/api/city_trees?ville=" . urlencode($ville);
        $response = @file_get_contents($url);

        if ($response !== false) {
            $data = json_decode($response, true);

            if (isset($data["error"])) {
                $error = $data["error"];
            } else {
                $best_trees = $data["best_trees"];
                $worst_trees = $data["worst_trees"] ?? null;
            }
        } else {
            $error = "Votre commune n'a pas fourni la liste de ses arbres.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Recommandation d'Arbres</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Sunavalons</h1>
            <p>Recommandations intelligentes d'arbres pour un environnement urbain durable</p>
        </div>
    </header>

    <main class="container">
        <form method="post" class="search-form">
            <div class="form-row">
                <label for="ville">Ville :</label>
                <input type="text" id="ville" name="ville" required placeholder="Ex: Paris, Lyon, Bordeaux...">
            </div>
            <button type="submit">Analyser</button>
        </form>

        <?php if ($error === "Votre commune n'a pas fourni la liste de ses arbres." && $ville): ?>
            <div class="error">
                <p><?= htmlspecialchars($error) ?></p>
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
                <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="ville" value="<?= htmlspecialchars($ville) ?>">
                    <input type="file" name="csv_file" accept=".csv,.xlsx,.xls,.ods" required>
                    <button type="submit">Envoyer le CSV</button>
                </form>
            </div>
        <?php endif; ?>

        <?php if ($error && $error !== "Votre commune n'a pas fourni la liste de ses arbres."): ?>
            <div class="error">
                <p>Erreur : <?= htmlspecialchars($error) ?></p>
            </div>
        <?php elseif (isset($success)): ?>
            <div class="success">
                <p><?= htmlspecialchars($success) ?></p>
            </div>
        <?php elseif (isset($best_trees) && isset($_POST["ville"]) && !$error): ?>
            <h2>Arbres les mieux adaptés présents dans la ville de <?= htmlspecialchars($_POST["ville"]) ?></h2>

            <div class="tree-container">
                <?php foreach ($best_trees as $tree): ?>
                    <?php
                        $note = $tree[1];
                        $fill_ratio = max(0, min(1, 1 - ($note / 8)));
                        $fill_percent = intval($fill_ratio * 100);
                    ?>
                    <div class="tree-card">
                        <span class="eco-badge">
                            <span class="eco-fill" style="width: <?= $fill_percent ?>%;"></span>
                            <span class="eco-text">Score éco-compatible</span>
                        </span>
                        <img src=<?=$image_path.htmlspecialchars($tree[0]).".jpg";?> class="tree-image">
                        <div class="tree-content">
                            <h3 class="tree-name"><?= str_replace("_"," ",htmlspecialchars($tree[0]))?></h3>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($worst_trees)): ?>
            <h2>Arbres les moins adaptés</h2>
            <div class="tree-container">
            <?php foreach ($worst_trees as $tree): ?>
                <div class="tree-card">
                    <span class="eco-badge">
                        <span class="eco-fill" style="width: <?= $fill_percent ?>%;"></span>
                        <span class="eco-text">Score éco-compatible</span>
                    </span>
                    <img src="<?= $image_path . htmlspecialchars($tree[0]) ?>.jpg" class="tree-image">
                    <div class="tree-content">
                        <h3 class="tree-name"><?= str_replace("_", " ", htmlspecialchars($tree[0])) ?></h3>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!$error && isset($ville)): ?>
            <form method="post" action="index.php" style="margin-top:2em; display:inline; ">
                <input type="hidden" name="ville" value="<?= htmlspecialchars($_POST["ville"]) ?>">
                <button type="submit" style="display:inline-flex; align-items:center; gap:6px; font-size:1em; padding:8px 8px; cursor:pointer;">
                    Voir les
                    <input type="number" name="nb_arbres" value="5" min="1" max="20" style="width:40px; font-size:1em; padding:0; text-align:center; margin:0; line-height:1;">
                    arbres recommandés pour <?= htmlspecialchars($ville) ?>
                </button>
            </form>
        <?php endif; ?>
    </main>

    <footer>
        <p>Sunavalons © 2025 - Pour un environnement urbain plus adapté</p>
    </footer>
</body>
</html>
