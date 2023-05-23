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
// Nombre de pages à afficher avant et après la page courante
$pagesAffiche = 1;
// Calcul du début et de la fin de la plage de pages à afficher
$startPage = max(1, $page - $pagesAffiche);
$endPage = min($pages, $page + $pagesAffiche);

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
    <link rel="stylesheet" href="styles/stylePartenariats.css">
    <link rel="stylesheet" href="styles/style.css">
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
                    <?php 
                    //recup param de l'url
                    $params = $_GET;
                    if(isset($params['modalOuvirPartenaire'])){
                        unset($params['modalOuvirPartenaire']);
                    }
                    
                    foreach ($nomImgPartenaire as $index => $image) {
                        $index2 = $index;
                        if ($page > 1){
                            $index = $index + (($page - 1) * $nb_elements_par_page);
                        }
                        $test = $idPartenaire[$index];
                        //Creation GET + Construct url
                        $params['modalOuvirPartenaire'] = $test['Id_Partenaire'];
                        $urlopen = http_build_query($params);
                        unset($params['modalOuvirPartenaire']);// je suppr la colonne pour pas l'avoir dans les autres url (urlmodif et urlsuppr)
                        ?>
                        <div class="partenaires_grid-item">
                            <a href="partenariats.php?<?= $urlopen ?>">
                                <img src="assets/<?= $tab[$index2]['Nom_Image'] ?>" alt="Image du partenaire">
                            </a>
                        </div>
                    <?php } 
                    ?>
                </div>

                <div class="pagination">
                    <?php
                        // Vérification si les points de suspension doivent être affichés au début
                        if ($startPage > 1) {
                            // Afficher la première page
                            echo '<a href="?page=1"><span class="page">1</span></a>';
                            // Afficher les points de suspension au début
                            if ($startPage > 2 && $page >= 3) {
                                echo '<a><span class="page" id="troisPoints">...</span></a>';
                            }
                        }
                        // Affichage des numéros de page ou des points de suspension pour les pages au milieu
                        for ($pag = $startPage; $pag <= $endPage; $pag++) {
                            if($page != $pag){ 
                                echo '<a href="?page='.$pag.'"><span class="page">' . $pag . '</span></a>';
                            }else{
                                echo '<a href="?page='.$pag.'"><span class="page activepage">' . $pag . '</span></a>';
                            }
                        }
                        // Vérification si les points de suspension doivent être affichés à la fin
                        if ($endPage < $pages) {
                            // Afficher les points de suspension à la fin
                            if ($endPage < $pages - 1) {
                                echo '<a><span class="page" id="troisPoints">...</span></a>';
                            }
                            // Afficher la dernière page
                            echo '<a href="?page='.$pages.'"><span class="page">'.$pages.'</span></a>';
                        }
                    ?>
                    
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
                    body.style.overflowY = "auto";
                    body.style.overflowX = "hidden";
                }

                window.onclick = function(event) {
                    if (event.target == modalModif) {
                        modalModif.style.display = "none";
                        history.pushState(null, null, window.location.href.split("?")[0]);
                        body.style.overflowY = "auto";
                        body.style.overflowX = "hidden";
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