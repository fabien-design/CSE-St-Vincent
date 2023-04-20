
<?php

require 'include/connexion_db.php';

// Si $_GET est vide alors renvoie sur la page billetterie
if(empty($_GET) || empty($_GET['id'])){
    header('Location: billetterie.php');
}

//Selection des valeurs de la table offre
$selectOffre = $connexion->prepare('SELECT * FROM offre WHERE Id_Offre = :id;');
$selectOffre->bindParam(':id', $_GET['id']);
$selectOffre->execute();
$DescOffres = $selectOffre->fetch(PDO::FETCH_ASSOC);

//Selection du nom de l'image par rapport a l'id de l'offre
$imgContenuBilletterie = $connexion -> prepare("SELECT image.Nom_Image, image.Id_Image FROM image INNER JOIN partenaire ON image.Id_Image = partenaire.Id_Image INNER JOIN offre ON partenaire.Id_Partenaire = offre.Id_Partenaire WHERE offre.Id_Offre = :id");
$imgContenuBilletterie -> bindParam(":id", $_GET["id"]);
$imgContenuBilletterie -> execute();
$imgContenu = $imgContenuBilletterie->fetch();

//Selection des données de la table partenaire en fonction de l'id de l'offre
$modalLink = $connexion->prepare("SELECT* FROM partenaire INNER JOIN offre ON partenaire.Id_Partenaire = offre.Id_Partenaire WHERE offre.Id_Offre = :id");
$modalLink-> bindParam(":id", $_GET["id"]);
$modalLink -> execute();
$link = $modalLink->fetch();

                     

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
    <?php require 'include/header.php'?>
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
            <div class="img_partenaire">
                <div class="contain_img_partenaire">
                    <h1>Partenaire</h1>
                    <a href="partenariats.php?modalOuvirPartenaire=<?php echo $link['Id_Partenaire'] ?>">
                        <p>Voir plus</p>
                        <img src="<?php echo "assets/".$imgContenu['Nom_Image']."" ?>" alt="Image du partenaire">
                    </a>
                </div>
            </div>
            </div>
            <div class="back">
                <a href="billetterie.php"><img src="assets/chevron-droit.png" class="chevron-droit" alt="chevron-droit">Retour</a>
            </div>
        </div>
    </main>
    <?php require 'include/footer.php'?>
    <script src="scriptaside.js"></script>
</body>

</html>