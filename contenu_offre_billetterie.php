
<?php

require 'include/connexion_db.php';

var_dump($_GET['id']);

if(empty($_GET) || empty($_GET['id'])){
    header('Location: billetterie.php');
}


$selectOffre = $connexion->prepare('SELECT * FROM offre WHERE Id_Offre = :id;');
$selectOffre->bindParam(':id', $_GET['id']);
$selectOffre->execute();
$DescOffres = $selectOffre->fetch(PDO::FETCH_ASSOC);

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
    <title>CSE Saint-Vincent - <?= $DescOffres['Nom_Offre']?></title>
</head>

<body id="body" class="no-transition">
    <header>
        <div class="gris">

        </div>
        <div class="blue">
            <nav>
                <div class="logo"><img src="assets/logo_lycee.png" alt="logo_st_vincent"></div>
                <ul>
                    <a href="index.php">
                        <li>
                            Accueil
                        </li>
                    </a>
                    <a href="partenariats.php">
                        <li>Partenariats</li>
                    </a>
                    <a href="billetterie.php">
                        <li class="active">Billetterie</li>
                    </a>
                    <a href="contact.php">
                        <li>Contact</li>
                    </a>
                </ul>
            </nav>
        </div>
    </header>

    <main>
    <?php require 'include/aside.php'?>
        <div class="right">
            <h1><?= $DescOffres['Nom_Offre']?></h1>
            <div class="Description_Offre">
                <?= $DescOffres['Description_Offre'] ?>
                <p>
                    Pour les groupes d'un minimum de 4 personnes, profitez d'une réduction de -50% sur un large choix de sucreries pendant vos concerts. Offre valable dans tout nos établissements.
                </p>
            </div>
            <div class="date_contenu_offre_billetterie">
            <span class="date_contenu_offre">Publié le <?php echo date('d F Y',strtotime($DescOffres['Date_Debut_Offre']))?></span>
            </div>
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