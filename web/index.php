<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style/style.css">

    <title>Sunavalons: Page principale</title>
</head>
<body>
    <?php include 'components/header.php'; ?>
    <main class="container">
        <h1>Projet Sunavalons</h1>
        <p>
            Vous êtes vous déjà demandé quel arbre serait le mieux adapté pour votre ville ?
            Et bien Sunavalons est le site qui vous permet de trouver les arbres idéaux à planter dans
            les villes françaises.<br>
            Grâce à l'étude de l'acidité des sols, le taux d'humidité des villes,
            le temps d'exposition au soleil ainsi que les températures moyennes les plus basses enregistrées,
            Sunavalons vous permet de trouver quel(s) arbre(s) serai(en)t parfait(s) pour votre ville.<br>
            Mais ce n'est pas tout, Sunavalons possède d'autres outils qui vous seront peut-être utiles
            si vous vous intéressez aux arbres, alors n'hésitez pas à les regarder.
        </p>
        <section class="tree-container">
            <div class="tree-card">
                <div class="tree-content tree-content--no-padding">
                    <a href="plantator.php" class="tree-btn">Plantator</a>
                    <p>
                        Plantator est l'outil principal de Sunavalons.<br>
                        Il vous permet de trouver les arbres idéaux à planter dans votre ville.
                    </p>
                </div>
            </div>
            <div class="tree-card">
                <div class="tree-content tree-content--no-padding">
                    <a href="comparator.php" class="tree-btn">Comparator</a>
                    <p>
                        Comparator permet de voir les meilleurs et pires arbres plantés dans votre commune.
                    </p>
                </div>
            </div>
            <div class="tree-card">
                <div class="tree-content tree-content--no-padding">
                    <a href="statisticator.php" class="tree-btn">Statisticator</a>
                    <p>
                        Statisticator permet d'afficher les arbres et leurs caractéristiques sous forme de diagrammes.
                    </p>
                </div>
            </div>
    </section>
    </main>
    <?php include 'components/footer.php'; ?>
</body>
</html>
