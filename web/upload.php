<?php
require_once 'includes/validate.php';
require_once 'includes/logic.php';

$ville = $_GET['ville'] ?? null;
$result = handle_form_submission();
$error = $result['error'];
$success = $result['success'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mise à jour - <?= htmlspecialchars($ville) ?></title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/styleHeaderFooter.css">
</head>
<body>
    <?php include 'components/header.php'; ?>

    <main class="container">
        <h1 class="main-title">Mise à jour des arbres</h1>

        <?php if ($error): ?>
            <?php include 'components/error.php'; ?>
        <?php elseif ($success): ?>
            <?php include 'components/success.php'; ?>
        <?php endif; ?>

        <?php
        if (validate_ville($ville)) {
            include 'components/upload_ville.php';
        }
        ?>
    </main>

    <?php include 'components/footer.php'; ?>
</body>
</html>
