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
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="styleBackoffice.css">
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

            <?php
            // CODE MODAL POUR MODIFIER UN PARTENAIRE
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
                <div id="modalOuvirPartenaire" id="modalModifPartenaire" class="modal">
                    <div class="modal-content">
                        <span class="closeModif">&times;</span>
                        <div class="formBox">
                            <form id="formModifPartenaire" enctype="multipart/form-data" method="POST">
                                <input type="hidden" name="idPart" value="<?php echo $partenaire['Id_Partenaire'] ?>">
                                <h1 style="margin-bottom:20px;">AFFICHAGE du Partenaire</h1>

                                <label for="nompart">Nom* :</label>
                                <input type="text" name="nompart" placeholder="Le nom du Partenaire." value="<?php echo $partenaire['Nom_Partenaire'] ?>">

                                <label for="descrippart">Description* :</label>
                                <textarea name="descrippart" cols="30" rows="10" placeholder="La description du Partenaire."><?php echo $partenaire['Description_Partenaire'] ?></textarea>

                                <label for="lienpart">Lien* :</label>
                                <input type="text" name="lienpart" placeholder="Le lien du Partenaire." value="<?php echo $partenaire['Lien_Partenaire'] ?>">

                                <label for="imgpart">Image* :</label>
                                <div class="imgBox">
                                    <div class="edit-button">
                                        <img src="assets/edit-button.png" alt="edit-button" id="edit-button-img">
                                        <input type="file" name="imgpart">
                                    </div>
                                    <img src="assets/<?php echo $imgPart['Nom_Image'] ?>" alt="Image du partenaire">
                                    <input type="file" name="imgpart">
                                </div>

                                <div class="modifBtn">
                                    <button type="submit" class="formModifOui">OUI</button>
                            </form>
                            <button class="formModifNon">NON</button>
                        </div>

                    </div>

                </div>


                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
                <script>
                    // Code Modal modif d'un partenaire
                    var modalModif = document.getElementById("modalOuvirPartenaire");
                    var span = document.getElementsByClassName("closeModif")[0];
                    var btnNon = document.getElementsByClassName("formModifNon")[0];
                    var btnOui = document.getElementsByClassName("formModifOui")[0];
                    var body = document.body;
                    body.style.overflow = "hidden";
                    // cacher modal au click de la croix ou du btn non
                    span.onclick = function() {
                        modalModif.style.display = "none";
                        history.pushState(null, null, window.location.href.split("&")[0]);
                        body.style.overflow = "auto";
                    }
                    btnNon.onclick = function(e) {
                        e.preventDefault();
                        modalModif.style.display = "none";
                        history.pushState(null, null, window.location.href.split("&")[0]);
                        body.style.overflow = "auto";
                    }
                    btnOui.onclick = function() {
                        history.pushState(null, null, window.location.href.split("&")[0]);
                        setTimeout(function() {
                            modalModif.style.display = "none";
                        }, 2000);
                        body.style.overflow = "auto";
                    }
                    window.onclick = function(event) {
                        if (event.target == modalModif) {
                            modalModif.style.display = "none";
                            history.pushState(null, null, window.location.href.split("&")[0]);
                            body.style.overflow = "auto";
                        }
                    }
                </script>
            <?php }

            // FIN CODE

            ?>




            <h1>Tous nos partenaires</h1>
            <div class="partenaires_grid-container">
                <?php
                foreach ($nomImgPartenaire as $index => $image) {
                    $test = $idPartenaire[$index];

                ?>

                    <div class="partenaires_grid-item"><a href="partenariats.php?page=partenariats&modalOuvirPartenaire=<?= $test['Id_Partenaire'] ?>"><img src="assets/<?= $image['Nom_Image'] ?>" alt="erreur_image_partenaire"></a></div>
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

    <script src="scriptaside.js"></script>
</body>

</html>