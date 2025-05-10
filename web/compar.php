<?php
require_once 'includes/tree_card.php';
require_once 'includes/validate.php';
require_once 'includes/logic.php';


$result = handle_form_submission();

$error = $result['error'];
$success = $result['success'];
$best_trees = $result['best_trees'];
$worst_trees = $result['worst_trees'];
$ville = $result['ville'];
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
    <?php include 'components/header.php'; ?>

    <main class="container">
        <?php include 'components/forms/form_ville.php'; ?>

        <?php if ($error): ?>
            <?php include 'components/error.php'; ?>
        <?php elseif ($success): ?>
            <?php include 'components/success.php'; ?>
        <?php endif; ?>

        <?php if ($best_trees && $ville): ?>
            <?php
                $title = "Arbres les mieux adaptés présents dans la ville de " . htmlspecialchars($ville);
                $trees = $best_trees;
                include 'components/tree_list.php';
            ?>
        <?php endif; ?>

        <?php if ($worst_trees): ?>
            <?php
                $title = "Arbres les moins adaptés";
                $trees = $worst_trees;
                include 'components/tree_list.php';
            ?>
        <?php endif; ?>
    </main>

    <?php include 'components/footer.php'; ?>
</body>
</html>
