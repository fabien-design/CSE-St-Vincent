<?php
require 'include/connexion_db.php';
session_start();

$parts = explode('/', $_SERVER["SCRIPT_NAME"]);
$file = $parts[count($parts) - 1];


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
$imgPart = $connexion->prepare("SELECT DISTINCT * FROM image WHERE Id_Image in (SELECT Id_Image FROM partenaire) ORDER BY RAND() LIMIT 3 ");
$imgPart->execute();
$nomImg = $imgPart->fetchAll();

$surveyActive = $connexion -> prepare("SELECT settings_value FROM settings WHERE settings_key = 'surveyCurrentlyActive'");
$surveyActive->setFetchMode(PDO::FETCH_ASSOC);
$surveyActive -> execute();
$surveyActive = $surveyActive->fetch();

if($surveyActive['settings_value'] == 'true'){
    //survey
    $survey = $connexion->prepare("SELECT * FROM survey ORDER BY RAND() LIMIT 1 ");
    $survey->execute();
    $survey = $survey->fetch();

    // print_r($_POST); UNCOMMENT TO SHOP HOW IT WORKS

    if (isset($_POST[('survey_id_forchoice')]) && isset($_POST[('surveyChoice')])) {
        $surveyCurrentResultForChoice = $_POST['survey_option'.$_POST[('surveyChoice')].'results'];
        $surveyNewResultForChoice = $surveyCurrentResultForChoice + 1;
        try {
            $req = $connexion->prepare("UPDATE survey SET survey_option".$_POST[("surveyChoice")]."results = $surveyNewResultForChoice WHERE survey_id = :survey_id_forchoice");
            $req->bindParam('survey_id_forchoice', $_POST["survey_id_forchoice"]);
            $req->execute();
            // var_dump('SUCCESS UPDATING RESULTS'); UNCOMMENT TO SHOP HOW IT WORKS
        } catch (Exception $e) {
            // var_dump('ERROR UPDATING RESULTS'); UNCOMMENT TO SHOP HOW IT WORKS
        }
    }
};


?>

<aside class="left">
    <div class="home">
        <?php
        if ($file === "index.php") {

        ?>
            <img src="assets/homeIcon.png" alt="homeIcon.png">
            <img src="assets/chevron-droit.png" class="chevron-droit" alt="chevron-droit">
            <h1 class="sectiontitle">Accueil</h1>
        <?php } ?>
        <?php
        if ($file === "partenariats.php") {

        ?>
            <img src="assets/partenaireIcon.png" alt="partenaireIcon.png">
            <img src="assets/chevron-droit.png" class="chevron-droit" alt="chevron-droit">
            <h1 class="sectiontitle">Partenariats</h1>
        <?php } ?>
        <?php
        if ($file === "billetterie.php") {

        ?>
            <img src="assets/billetIcon.png" alt="billetIcon.png">
            <img src="assets/chevron-droit.png" class="chevron-droit" alt="chevron-droit">
            <h1 class="sectiontitle">Billetterie</h1>
        <?php } ?>
        <?php
        if ($file === "contact.php") {

        ?>
            <img src="assets/contactsIcon.png" alt="contactsIcon.png">
            <img src="assets/chevron-droit.png" class="chevron-droit" alt="chevron-droit">
            <h1 class="sectiontitle">Contact</h1>
        <?php } ?>

    </div>
    <?php 
        if($surveyActive['settings_value'] == 'true'){
    ?>
    <div class="survey">
        <form action="#" method="POST">
            <div class="surveyTitle">
                <span>
                    Votre avis compte
                </span>
            </div>
            <div class="surveyQuestion">
                <?php
                if(empty($_POST['surveyChoice']) && empty($_SESSION['hasVoted'])){

                ?>
                <span>
                    <?php echo $survey['survey_question'] ?>
                </span>
                <ul>
                    <li>
                        <input type="radio" id="surveyChoice1" name="surveyChoice" value="1" />
                        <label for="surveyChoice1"><?php echo $survey['survey_option1'] ?></label>
                    </li>
                    <li>
                        <input type="radio" id="surveyChoice2" name="surveyChoice" value="2" />
                        <label for="surveyChoice2"><?php echo $survey['survey_option2'] ?></label>
                    </li>
                    <li>
                        <input type="radio" id="surveyChoice3" name="surveyChoice" value="3" />
                        <label for="surveyChoice3"><?php echo $survey['survey_option3'] ?></label>
                    </li>
                </ul>
                <?php }else{ 
                    $_SESSION['hasVoted']='yes';
                    ?>
                    <h1>Merci d'avoir voté!</h1>
                <?php } ?>
            </div>
            <div class="surveyValidate">
                <a href="http://localhost/dashboard/Projet%20Fin%20Annee%20Lycee%20BTS1/backoffice.php?page=survey"><span>Voir les réponses</span></a>
                <input type="hidden" name="survey_id_forchoice" value="<?php echo $survey['survey_id']; ?>">
                <input type="hidden" name="survey_option1results" value="<?php echo $survey['survey_option1results'];?>">
                <input type="hidden" name="survey_option2results" value="<?php echo $survey['survey_option2results'];?>">
                <input type="hidden" name="survey_option3results" value="<?php echo $survey['survey_option3results'];?>">
                <input type="submit" value="Valider">
            </div>
        </form>
    </div>

    <?php } ?>
    <div class="fastaccess">
        <h1 class="sectiontitle">Accès rapide</h1>
        <div class="offreaccess">
            <img src="assets/chevron-droit.png" class="chevron-droit" alt="chevron-droit">
            <a href="billetterie.php?page=1">Offres / Billetterie</a>
        </div>
        <div class="contactaccess">
            <img src="assets/chevron-droit.png" class="chevron-droit" alt="chevron-droit">
            <a href="contact.php">Nous contacter</a>
        </div>
    </div>
    <div class="infocontact">
        <h1 class="sectiontitle">Informations de contact</h1>
        <div class="tel">
            <p><img src="assets/chevron-droit.png" class="chevron-droit" alt="chevron-droit">Par téléphone : <a target="_blank" href="tel:+3330303030303">+<?= $phone ?></a>
            </p>
        </div>
        <div class="email">
            <p>
                <img src="assets/chevron-droit.png" class="chevron-droit" alt="chevron-droit">Par email : <a target="_blank" href="mailto:cse@lyceestvincent.fr"><?= $adresseEmail ?></a>
            </p>
        </div>
        <div class="place">
            <p><img src="assets/chevron-droit.png" class="chevron-droit" alt="chevron-droit">Au lycée : <a target="_blank" href="https://www.google.com/maps/place/49%C2%B012'08.1%22N+2%C2%B035'18.9%22E/@49.202244,2.5880054,19z/data=!3m1!4b1!4m4!3m3!8m2!3d49.202244!4d2.58857"><?= $office ?></a>
            </p>
        </div>
    </div>
    <div>
        <div class="partenaire">
            <h1 class="sectiontitle">Nos partenaires</h1>
            <div class="boxSlider">
                <div class="slideshow-container">
                    <?php foreach ($nomImg as $image) { ?>
                        <div class="mySlides">
                            <?php $SelectPart = $connexion->prepare("SELECT Id_Partenaire, Nom_Partenaire FROM partenaire WHERE Id_Image = :idimg");
                            $SelectPart->bindParam('idimg', $image['Id_Image']);
                            $SelectPart->execute();
                            $Part = $SelectPart->fetch();

                            ?>
                            <a href="partenariats.php?modalOuvirPartenaire=<?php echo $Part['Id_Partenaire'] ?>">
                                <p>Voir plus</p>
                                <img src="<?php echo "assets/" . $image['Nom_Image'] . "" ?>" alt="Image du partenaire <?php echo $Part['Nom_Partenaire'] ?>">
                            </a>

                        </div>
                    <?php } ?>
                </div>
                <img class="prev" onclick="plusSlides(-1)" src="assets/chevron-gauche.png" alt="chevron précédent">
                <img class="next" onclick="plusSlides(1)" src="assets/chevron-droit.png" alt="chevron suivant">
                <div style="text-align:center">
                    <span class="dot" onclick="currentSlide(1)"></span>
                    <span class="dot" onclick="currentSlide(2)"></span>
                    <span class="dot" onclick="currentSlide(3)"></span>
                </div>
            </div>
        </div>
        <div class="decouverte">
            <a href="partenariats.php">Découvrir tous nos partenaires</a>
        </div>
    </div>
</aside>