<?php
$trees = null;
$error = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ville = urlencode($_POST["ville"]);
    $nb_arbres = intval($_POST["nb_arbres"]);

    $url = "http://127.0.0.1:5000/api?ville=$ville&nb_arbres=$nb_arbres";
    $response = file_get_contents($url);

    if ($response !== false) {
        $data = json_decode($response, true);

        if (isset($data["error"])) {
            $error = $data["error"];
        } else {
            $trees = $data;
        }
    } else {
        $error = "Erreur lors de la communication avec le serveur Flask.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Recommandation d'Arbres</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <h1>Choisissez une ville et un nombre d'arbres</h1>

    <form method="post">
        <label for="ville">Ville :</label>
        <input type="text" id="ville" name="ville" required><br><br>

        <label for="nb_arbres">Nombre d'arbres :</label>
        <input type="number" id="nb_arbres" name="nb_arbres" required><br><br>

        <button type="submit">Soumettre</button>
    </form>

    <?php if ($error): ?>
        <p style="color:red;">Erreur : <?= htmlspecialchars($error) ?></p>
    <?php elseif (is_array($trees)): ?>
        <h2>Arbres recommandés pour <?= htmlspecialchars($_POST["ville"]) ?> :</h2>
        <div class="tree-container">
            <?php foreach ($trees as $tree): ?>
                <div class="tree-card">
                    <h3><?= htmlspecialchars($tree) ?></h3>
                    <img src="assets/trees/<?=$tree ;?>.jpg">
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</body>
</html>
