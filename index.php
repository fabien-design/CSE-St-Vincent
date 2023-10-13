<?php 
 require 'include/connexion_db.php';
 error_reporting(E_ERROR | E_PARSE);
 //Récupération des données de Téléphone
$tel = $connexion->prepare('SELECT Num_TEL_Info_Accueil FROM info_accueil');
$tel->execute();
$phone = $tel->fetch();
$phone = $phone['Num_TEL_Info_Accueil'];

//Récpération des données de l'email
$email = $connexion->prepare('SELECT Email_Info_Accueil FROM info_accueil');
$email->execute();
$adresseEmail = $email->fetch();
$adresseEmail = $adresseEmail['Email_Info_Accueil'];

//Récupération des données de l'emplacement du bureau du CSE
$place = $connexion->prepare('SELECT Emplacement_Bureau_Info_Accueil FROM info_accueil');
$place->execute();
$office = $place->fetch();
$office = $office['Emplacement_Bureau_Info_Accueil'];

//Images partenaires
$imgPart = $connexion->prepare("SELECT DISTINCT * FROM image ORDER BY RAND() LIMIT 3");
$imgPart->execute();
$nomImg = $imgPart->fetchAll();

//Récupération des données du Titre de la page d'accueil
$titreInfoAccueil = $connexion->prepare('SELECT Titre_Info_Accueil FROM info_accueil');
$titreInfoAccueil->execute();
$TitreAccueil = $titreInfoAccueil->fetch();
$TitreAccueil = $TitreAccueil['Titre_Info_Accueil'];

//Récupération des données de la description de la page d'accueil
$texteInfoAccueil = $connexion->prepare('SELECT Texte_Info_Accueil FROM info_accueil');
$texteInfoAccueil->execute();
$TexteAccueil = $texteInfoAccueil->fetch();
$TexteAccueil = $TexteAccueil['Texte_Info_Accueil'];

//Images partenaires
$offres = $connexion->prepare("SELECT DISTINCT * FROM offre ORDER BY Id_Offre DESC LIMIT 4");
$offres->execute();
$chaqueOffre = $offres->fetchAll();

setlocale(LC_TIME,"fr_FR.utf8");


?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@700&display=swap" rel="stylesheet">
    <link rel="icon" href="assets/sv_logo.png">
    <title>CSE Saint-Vincent - Accueil</title>
</head>

<body id="body" class="no-transition">
    <div class="bodyDiv">
    <?php require 'include/header.php'?>
    <main>
        <?php require 'include/aside.php'?>
        <div class="right">
            <div class="info_accueil">
                <h2 class="titre"><?=$TitreAccueil?></h2>
                <p><?=$TexteAccueil?></p>
                <p>Découvrez <span style="color:#1B3168"><u>l'équipe</u></span> et le <span style="color:#1B3168"><u>rôle</u></span> et missions de votre CSE.</p>
            </div>
            <h1>Dernières offres de la Billetterie</h1>

            <?php foreach($chaqueOffre as $offre ){
                
                $monthsEnglish = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                    $monthsFrench = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];

                    $datedeb = $offre['Date_Debut_Offre'];
                    $datefin = $offre['Date_Fin_Offre'];

                    $datedeb_formattee = strftime("%d %B %Y", strtotime($datedeb));
                    $datedeb_formattee = explode(" ", $datedeb_formattee);
                    for ($i = 0; $i < count($monthsEnglish); $i++) {
                        if ($datedeb_formattee[1] == $monthsEnglish[$i]) {
                            $datedeb_formattee[1] = $monthsFrench[$i];
                        }
                    }
                    $datedeb_formattee = implode(" ", $datedeb_formattee);

                    $datefin_formattee = strftime("%d %B %Y", strtotime($datefin));
                    $datefin_formattee = explode(" ", $datefin_formattee);
                    for ($i = 0; $i < count($monthsEnglish); $i++) {
                        if ($datefin_formattee[1] == $monthsEnglish[$i]) {
                            $datefin_formattee[1] = $monthsFrench[$i];
                        }
                    }
                    $datefin_formattee = implode(" ", $datefin_formattee);
                    ?>
            <div class="offre_billetterie">
                <div class="offre_billetterie_header">
                    <span class="tag_offre">OFFRE</span>
                    <span class="date_offre">Offre valable du <?php echo $datedeb_formattee ?> au <?php echo $datefin_formattee?>.</span>
                </div>
                <p><?=$offre['Description_Offre']?></p>
                
                    <span class="offre_learnmore"><a href="contenu_offre_billetterie.php?id=<?=$offre['Id_Offre']?>">EN SAVOIR PLUS <img class="chevron-droit"
                            src="assets/chevron-droit.png" alt="chevron-droit"> </a></span>
               
            </div>
            <?php } ?>
                
            <div class="div_offre_decouvrir">
                <a href="billetterie.php?page=1">
                    <span id="offres_decouvrir">Découvrir toutes nos offres 〉</span>
                </a>
            </div>
        </div>

    </main>
    <?php require 'include/footer.php'?>
    <script src="scriptaside.js"></script>
            
    </div>
</body>

</html>