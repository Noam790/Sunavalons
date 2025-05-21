<?php
$arbre = $_GET["tree"];
$arbre = json_decode($arbre, true);

$pluie_texte = [
    "Cet arbre est très résistant à la sécheresse et peut survivre avec très peu d’eau.",
    "Il a un faible besoin en eau et s’adapte bien aux zones semi-arides.",
    "Cet arbre présente un besoin modéré en eau, idéal pour les climats tempérés.",
    "Il préfère les sols frais et humides, sans pour autant nécessiter une humidité constante.",
    "Il s’agit d’un végétal hygrophile, souvent présent en bordure de rivières ou dans des zones très humides."
];

$sol_texte = [
    "Cet arbre pionnier est très tolérant et s’adapte à tous les types de sols, même les plus contraignants.",
    "Il pousse facilement dans des sols légers ou pauvres, avec une bonne tolérance aux conditions difficiles.",
    "Il préfère les sols moyens bien drainés, sans exigences particulières.",
    "Cet arbre est exigeant et se développe idéalement dans des sols riches, profonds et bien structurés.",
    "Très sensible, il nécessite un sol aux caractéristiques précises, comme un pH particulier ou une forte teneur en humus."
];

$climat_texte = [
    "Cet arbre est ultra-rustique et peut supporter des températures inférieures à -25°C, adapté aux zones USDA 5 ou moins.",
    "Rustique, il tolère des températures jusqu’à -25°C, convenant bien à la zone USDA 6.",
    "Moyennement rustique, il résiste à des températures entre -10°C et -20°C, typiques de la zone USDA 7.",
    "Peu rustique, il supporte des hivers doux entre -5°C et -10°C, correspondant à la zone USDA 8.",
    "Cet arbre est adapté uniquement aux climats tropicaux ou méditerranéens, avec une tolérance minimale supérieure à -5°C."
];

$exposition_texte = [
    "Cet arbre préfère les zones ombragées ou à très faible ensoleillement, avec moins de 2000 Wh/m²/jour en juin.",
    "Il tolère une ombre légère, recevant entre 2000 et 2999 Wh/m²/jour.",
    "Adapté à la mi-ombre ou au soleil partiel, il se développe bien avec 3000 à 3999 Wh/m²/jour.",
    "Cet arbre apprécie un bon ensoleillement, entre 4000 et 4999 Wh/m²/jour.",
    "Il nécessite une exposition en plein soleil, avec un rayonnement supérieur ou égal à 5000 Wh/m²/jour."
];



$pluie = ["Précipitations",$pluie_texte,$arbre["eau"]-1] ;
$sol = ["Type de sol",$sol_texte,$arbre["sol"]-1] ;
$climat = ["Climats",$climat_texte,$arbre["climat"]-1] ;
$exposition = ["Exposition",$exposition_texte,$arbre["exposition"]-1] ;
$infos = [$pluie, $sol, $climat, $exposition];


$image_path = "assets/trees/" ;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/styleHeaderFooter.css">
    <link rel="stylesheet" href="style/styleInfoCard.css">
    <title>Sunavalons: Page Informative</title>
</head>
<body>
    <?php include 'components/header.php'; ?>
        <main>
        <div class="tree-center">
            <div class="tree-card">
                <img src=<?=$image_path.htmlspecialchars($arbre["nom"]).".jpg";?> class="tree-image">
                <div class="tree-content">
                    <h3 class="tree-name"><?= str_replace("_"," ",htmlspecialchars($arbre["nom"]))?></h3>
                </div>
            </div>
        </div>
        <div class="info-titre">
                <h2 class="main-title"> Informations </h2>
                <div class="info-cards-table">
                    <?php foreach($infos as $info):?>
                    <div class="info-card">
                        <h3>  <?=$info[0]?></h3>

                        <div class="info-note">
                            <p>
                                <?php for ($i = 0; $i < $info[2]; $i++) :?> ★
                                <?php endfor?>
                            </p>
                        </div>
                                <div class="info-paragraphe">
                                    <?=$info[1][$info[2]]?>
                                </div>
                    </div>
                    <?php endforeach?>
                </div>
        </div>
    </main>
    <?php include 'components/footer.php'; ?>
</body>
