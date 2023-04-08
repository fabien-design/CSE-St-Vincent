<?php

require 'include/connexion_db.php';

// CODE POUR RECCUPERER IMAGE DE CHAQUE PARTENAIRE POUR AFFICHAGE FRONT
$imgPartenaire = $connexion->prepare("SELECT Nom_Image FROM image WHERE Id_Image in (SELECT Id_Image FROM partenaire)");
$imgPartenaire->execute();
$nomImgPartenaire = $imgPartenaire->fetchAll();



$req = $connexion->prepare("SELECT Id_Partenaire FROM partenaire");
$req->execute();
$idPartenaire = $req->fetchAll();

?>



<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylePartenariats.css">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@700&display=swap" rel="stylesheet">
    <link rel="icon" href="assets/sv_logo.png">
    <title>CSE Saint-Vincent - Partenariats</title>
</head>

<body>
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
                        <li class="active">Partenariats</li>
                    </a>
                    <a href="billetterie.php">
                        <li>Billetterie</li>
                    </a>
                    <a href="contact.php">
                        <li>Contact</li>
                    </a>
                </ul>

            </nav>
        </div>
    </header>

    <main>
        <?php require 'include/aside.php' ?>
        <div class="right">






            <h1>Tous nos partenaires</h1>
            <div class="partenaires_grid-container">
                <?php
                foreach ($nomImgPartenaire as $index => $image) {
                    $test = $idPartenaire[$index];

                ?>

                    <div class="partenaires_grid-item"><a href="partenariats.php?modalOuvirPartenaire=<?= $test['Id_Partenaire'] ?>"><img src="assets/<?= $image['Nom_Image'] ?>" alt="Image du partenaire"></a></div>
                <?php } ?>
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
                <span class="closeModif">&times;</span>
                <div class="formBox">

                    <h1 style="margin-bottom:20px;"><?= $partenaire['Nom_Partenaire'] ?></h1>
                    
                    <div class="imagePartenaire"><img class="imagePartenaire" src="assets/<?php echo $imgPart['Nom_Image'] ?>" alt="Image du partenaire"></div>
                    
                    <p><?= $partenaire['Description_Partenaire'] ?></p>

                    <a target="_blank" href="<?= $partenaire['Lien_Partenaire'] ?>"><div id="offres_decouvrir">Voir Site du Partenaire</div></a>
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
</body>

</html>