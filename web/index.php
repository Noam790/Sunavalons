<?php
$trees = null;
$error = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ville = urlencode($_POST["ville"]);
    $nb_arbres = intval($_POST["nb_arbres"]);

    $url = "http://127.0.0.1:5000/api?ville=$ville&nb_arbres=$nb_arbres";
    $image_path = "assets/trees/";
    $response = file_get_contents($url);

    if ($response !== false) {
        $data = json_decode($response, true);
        print_r($data);

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

            <div class="form-row">
                <label for="nb_arbres">Nombre d'arbres :</label>
                <input type="number" id="nb_arbres" name="nb_arbres" required min="1" max="200" placeholder="Entre 1 et 200">
            </div>

            <button type="submit">Rechercher</button>
        </form>

        <?php if ($error): ?>
            <div class="error">
                <p>Erreur : <?= htmlspecialchars($error) ?></p>
            </div>
        <?php elseif (is_array($trees)): ?>
            <h2>Arbres recommandés pour <?= htmlspecialchars($_POST["ville"]) ?></h2>

            <div class="tree-container">
                <?php foreach ($trees as $tree): ?>
                    <?php $note = $tree[1];?>
                    <div class="tree-card">
                        <span class="eco-badge">Éco-compatible</span>
                        <img src=<?=$image_path.htmlspecialchars($tree[0]).".jpg";?> class="tree-image">
                        <?=$image_path.htmlspecialchars($tree[0]).".jpg" ;?>
                        <div class="tree-content">
                            <h3 class="tree-name"><?= htmlspecialchars($tree[0]) ?></h3>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="eco-tips">
                <h3>Conseils pour une plantation durable</h3>
                <p>Planter des arbres en ville offre de nombreux bénéfices écologiques et améliore la qualité de vie des citadins.</p>

                <div class="tips-container">
                    <div class="tip-card">
                        <p class="tip-title">Îlots de fraîcheur</p>
                        <p>Les arbres réduisent l'effet d'îlot de chaleur urbain en créant de l'ombre et en rafraîchissant l'air jusqu'à 8°C.</p>
                    </div>

                    <div class="tip-card">
                        <p class="tip-title">Qualité de l'air</p>
                        <p>Un arbre adulte peut absorber jusqu'à 22kg de CO₂ par an et filtrer les particules fines de l'atmosphère.</p>
                    </div>

                    <div class="tip-card">
                        <p class="tip-title">Biodiversité</p>
                        <p>Les arbres urbains constituent des habitats et des sources de nourriture pour la faune, favorisant la biodiversité locale.</p>
                    </div>

                    <div class="tip-card">
                        <p class="tip-title">Visuel</p>
                        <p>Une ville fleurie et colorée sera toujours plus agréable qu'une ville industrielle</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <footer>
        <p>Sunavalons © 2025 - Pour un environnement urbain plus adapté</p>
    </footer>
</body>
</html>
