<?php
require_once 'includes/tree_card.php';
require_once 'includes/validate.php';
require_once 'includes/logic.php';
$trees = null;
$error = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ville = urlencode($_POST["ville"]);
    $nb_arbres = intval($_POST["nb_arbres"]);

    if (!validate_ville($ville)) {
        $error = "Nom de ville invalide.";
    }

    $url = "http://127.0.0.1:5000/api?ville=" . urlencode($ville) . "&nb_arbres=" . $nb_arbres;
    $api_result = call_flask_api($url);
    if (isset($api_result['error'])) {
        $error = $api_result['error'];
    } else {
        $trees = $api_result['data'];
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
    <link rel="stylesheet" href="style/stylePlantator.css">
</head>
<body>
    <?php include 'components/header.php'; ?>
    <main class="container">
        <h1>Plantator</h1>
        <p>
        Plantator est l'outil principal du projet Sunavalons et constituait le 
        MVP du projet. Cet outil vous permet de trouver quel arbre serait le 
        mieux pour votre ville. <br>
        Pour ce faire, remplissez simplement le petit 
        formulaire avec le nom de votre ville ainsi qu'avec le nombre de variétés 
        d'arbres que vous souhaiteriez planter.
        </p>
        
        <?php include 'components/forms/plantator_form.php'; ?>

        <?php if ($error): ?>
            <?php include 'components/error.php'; ?>
        <?php elseif (is_array($trees)): ?>
            <h2>Arbres recommandés pour <?= htmlspecialchars($_POST["ville"]) ?></h2>
            <div class="card-table-plantator">
                <?php foreach ($trees as $tree_data): ?>
                    <?= render_tree_card($tree_data, $image_path); ?>
                <?php endforeach; ?>
            </div>
            <?php include 'components/eco_tips.php'; ?>
        <?php endif; ?>
    </main>
    <?php include 'components/footer.php'; ?>
</body>
</html>
