<?php

require 'include/connexion_db.php';

$offres = $connexion -> prepare("SELECT DISTINCT * FROM offre ORDER BY Id_Offre DESC LIMIT 5");
$offres -> execute();
$chaqueOffre = $offres->fetchAll();

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@700&display=swap" rel="stylesheet">
    <link rel="icon" href="assets/sv_logo.png">
    <title>CSE Saint-Vincent - Billetterie</title>
</head>

<body id="body" class="no-transition">
    <?php require 'include/header.php'?>
    <main>
    <?php require 'include/aside.php'?>
        <div class="right">
            <h1>Billetterie</h1>
            <?php foreach($chaqueOffre as $offre ){?>
            <div class="offre_billetterie">
                <div class="offre_billetterie_header">
                    <span class="tag_offre">OFFRE</span>
                    <span class="date_offre">Publié le <?php echo date('d F Y',strtotime($offre['Date_Debut_Offre']))?></span>
                </div>
                <p><?=$offre['Description_Offre']?></p>
                
                    <span class="offre_learnmore"><a href="contenu_offre_billetterie.php?id=<?=$offre['Id_Offre']?>">EN SAVOIR PLUS <img class="chevron-droit"
                            src="assets/chevron-droit.png" alt="chevron-droit"> </a></span>
               
            </div>
                <?php } ?>
                <div class="pagination">
                <span class="page activepage">1</span>
                <span class="page">2</span>
                <span class="page">3</span>
                <span class="etc">...</span>
                <span class="page">10</span>
            </div>
        </div>
    </main>
    <?php require 'include/footer.php'?>
    <script src="scriptaside.js"></script>
</body>

</html>