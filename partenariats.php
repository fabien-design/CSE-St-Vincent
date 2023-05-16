<?php

require 'include/connexion_db.php';



// CODE POUR RECCUPERER IMAGE DE CHAQUE PARTENAIRE POUR AFFICHAGE FRONT


$req = $connexion->prepare("SELECT Id_Partenaire FROM partenaire");
$req->execute();
$idPartenaire = $req->fetchAll();


$count = $connexion->prepare("SELECT COUNT(Id_Partenaire) as parten FROM partenaire");
$count->setFetchMode(PDO::FETCH_ASSOC);
$count->execute();
$tcount = $count->fetchAll();

$nb_elements_par_page = 6 ;
$pages = ceil($tcount[0]['parten'] / $nb_elements_par_page);
@$page = $_GET["page"];
// Verif validité 
if (empty($page)) {
    $page = 1;
}
$page = max(1, min($pages, $page));
// Verif Si contenu offre renvoie une valeur de page 
if (isset($_GET["anciennepage"])) {
    try {
        $valeur = intval($_GET["anciennepage"]);
        if (gettype($valeur == "integer")) {
            $page = $valeur;
        }
    } catch (Exception $e) {
        $page = 1;
    }
}
if ($page === 0) {
    $page = 1;
}

$debut = ($page - 1) * $nb_elements_par_page;

$select = $connexion->prepare("SELECT Nom_Image FROM image WHERE Id_Image IN (SELECT Id_Image FROM partenaire ) LIMIT $debut, $nb_elements_par_page");
$select->setFetchMode(PDO::FETCH_ASSOC);
$select->execute();
$tab = $select->fetchAll();

$imgPartenaire = $connexion->prepare("SELECT Nom_Image FROM image WHERE Id_Image in (SELECT Id_Image FROM partenaire) LIMIT $debut , $nb_elements_par_page");
$imgPartenaire->execute();
$nomImgPartenaire = $imgPartenaire->fetchAll();
?>



<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylePartenariats.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="responsive.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@700&display=swap" rel="stylesheet">
    <link rel="icon" href="assets/sv_logo.png">
    <title>CSE Saint-Vincent - Partenariats</title>
</head>

<body id="body" class="no-transition">
    <div class="bodyDiv">
        <?php require 'include/header.php' ?>
        <main>
            <?php require 'include/aside.php' ?>
            <div class="right_partenaire">
                <h1>Tous nos partenaires</h1>
                <div class="partenaires_grid-container">
                    <?php foreach ($nomImgPartenaire as $index => $image) {
                        $test = $idPartenaire[$index]; ?>
                        <div class="partenaires_grid-item">
                            <a href="partenariats.php?modalOuvirPartenaire=<?= $test['Id_Partenaire'] ?>">
                                <img src="assets/<?= $tab[$index]['Nom_Image'] ?>" alt="Image du partenaire">
                            </a>
                        </div>
                    <?php } ?>
                </div>

                <div class="pagination">
                    <?php
                    for ($i = 1; $i <= $pages; $i++) {
                        if ($page != $i) {
                    ?>
                            <a href="?page=<?= $i ?>"> <span class="page"><?= $i ?></span></a>
                        <?php } else { ?>
                            <a href="?page=<?= $i ?>"> <span class="page activepage"><?= $i ?></span></a>
                    <?php }
                    } ?>
                </div>

            </div>
        </main>
        <?php require 'include/footer.php' ?>
        <?php
        // CODE MODAL POUR AFFICHER UN PARTENAIRE
        if (isset($_GET['modalOuvirPartenaire'])) {
            $req = $connexion->prepare("SELECT * FROM partenaire WHERE Id_Partenaire = :id");
            $req->bindParam('id', $_GET['modalOuvirPartenaire']);
            $req->execute();
            $partenaire = $req->fetch();
            $reqImg = $connexion->prepare("SELECT Nom_Image FROM image WHERE Id_Image = :id");
            $reqImg->bindParam('id', $partenaire['Id_Image']);
            $reqImg->execute();
            $imgPart = $reqImg->fetch();
        ?>
            <div id="modalModifPartenaire" class="modal">
                <div class="modal-content">
                    <div class="closeModifDiv">
                        <span class="closeModif">&times;</span>
                    </div>
                    <div class="formBox">

                        <h1 style="margin-bottom:20px;"><?= $partenaire['Nom_Partenaire'] ?></h1>

                        <div class="imagePartenaire"><img class="imagePartenaire" src="assets/<?php echo $imgPart['Nom_Image']  ?>" alt="Image du partenaire"></div>

                        <p><?= $partenaire['Description_Partenaire'] ?></p>

                        <a target='blank' href="<?= $partenaire['Lien_Partenaire'] ?>">
                            <div id="offres_decouvrir">Voir Site du Partenaire</div>
                        </a>
                    </div>

                </div>

            </div>


            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
            <script>
                // Code Modal afficher d'un partenaire
                var modalModif = document.getElementById("modalModifPartenaire");
                var span = document.getElementsByClassName("closeModif")[0];
                var body = document.body;
                body.style.overflow = "hidden";
                // cacher modal au click de la croix ou du btn non
                span.onclick = function() {
                    modalModif.style.display = "none";
                    history.pushState(null, null, window.location.href.split("?")[0]);
                    body.style.overflow = "auto";
                }

                window.onclick = function(event) {
                    if (event.target == modalModif) {
                        modalModif.style.display = "none";
                        history.pushState(null, null, window.location.href.split("?")[0]);
                        body.style.overflow = "auto";
                    }
                }
            </script>
        <?php }

        // FIN CODE

        ?>

        <script src="scriptaside.js"></script>
    </div>
</body>

</html>