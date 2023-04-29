<?php
require 'include/connexion_db.php';

$parts = explode('/', $_SERVER["SCRIPT_NAME"]);
$file = $parts[count($parts) - 1];



 //Récupération des données de Téléphone
$tel = $connexion -> prepare('SELECT Num_TEL_Info_Accueil FROM info_accueil');
$tel ->execute();
$phone = $tel->fetch();
$phone = $phone['Num_TEL_Info_Accueil'];

//Récpération des données de l'email
$email = $connexion -> prepare('SELECT Email_Info_Accueil FROM info_accueil');
$email ->execute();
$adresseEmail = $email->fetch();
$adresseEmail = $adresseEmail['Email_Info_Accueil'];

//Récupération des données de l'emplacement du bureau du CSE
$place = $connexion -> prepare('SELECT Emplacement_Bureau_Info_Accueil FROM info_accueil');
$place ->execute();
$office = $place->fetch();
$office = $office['Emplacement_Bureau_Info_Accueil'];

//Images partenaires
$imgPart = $connexion -> prepare("SELECT DISTINCT * FROM image WHERE Id_Image in (SELECT Id_Image FROM partenaire) ORDER BY RAND() LIMIT 3 ");
$imgPart -> execute();
$nomImg = $imgPart->fetchAll();

?>

<aside class="left">
            <div class="home">
                <?php
                if($file === "index.php"){
                   
                ?>
                <img src="assets/homeIcon.png">
                <img src="assets/chevron-droit.png" class="chevron-droit">
                <h1 class="sectiontitle">Accueil</h1>
                <?php }?>
                <?php
                if($file === "partenariats.php"){
                    
                ?>
                <img src="assets/partenaireIcon.png">
                <img src="assets/chevron-droit.png" class="chevron-droit">
                <h1 class="sectiontitle">Partenariats</h1>
                <?php }?>
                <?php
                if($file === "billetterie.php"){
                    
                ?>
                <img src="assets/billetIcon.png">
                <img src="assets/chevron-droit.png" class="chevron-droit">
                <h1 class="sectiontitle">Billetterie</h1>
                <?php }?>
                <?php
                if($file === "contact.php"){
                   
                ?>
                <img src="assets/contactsIcon.png">
                <img src="assets/chevron-droit.png" class="chevron-droit">
                <h1 class="sectiontitle">Contact</h1>
                <?php }?>
                
            </div>
            <div class="fastaccess">
                <h1 class="sectiontitle">Accès rapide</h1>
                <div class="offreaccess">
                    <img src="assets/chevron-droit.png" class="chevron-droit">
                    <a href="billetterie.php?page=1">Offres / Billetterie</a>
                </div>
                <div class="contactaccess">
                    <img src="assets/chevron-droit.png" class="chevron-droit">
                    <a href="contact.php">Nous contacter</a>
                </div>
            </div>
            <div class="infocontact">
                <h1 class="sectiontitle">Informations de contact</h1>
                <div class="tel">
                    <p><img src="assets/chevron-droit.png" class="chevron-droit">Par téléphone : <a target="_blank" href="tel:+3330303030303">+<?=$phone ?></a>
                    </p>
                </div>
                <div class="email">
                    <p>
                        <img src="assets/chevron-droit.png" class="chevron-droit">Par email : <a target="_blank"
                            href="mailto:cse@lyceestvincent.fr"><?= $adresseEmail ?></a>
                    </p>
                </div>
                <div class="place">
                    <p><img src="assets/chevron-droit.png" class="chevron-droit">Au lycée : <a target="_blank"
                            href="https://www.google.com/maps/place/49%C2%B012'08.1%22N+2%C2%B035'18.9%22E/@49.202244,2.5880054,19z/data=!3m1!4b1!4m4!3m3!8m2!3d49.202244!4d2.58857"><?= $office ?></a>
                    </p>
                </div>
            </div>
            <div class="partenaire">
                <h1 class="sectiontitle">Nos partenaires</h1>
                <div class="boxSlider">
                    <div class="slideshow-container">
                        <?php foreach($nomImg as $image ){?>
                        <div class="mySlides"> 
                        <?php $SelectPart = $connexion->prepare("SELECT Id_Partenaire, Nom_Partenaire FROM partenaire WHERE Id_Image = :idimg");
                            $SelectPart->bindParam('idimg',$image['Id_Image']);
                            $SelectPart->execute();
                            $Part = $SelectPart->fetch();
                                    
                            ?>
                            <a href="partenariats.php?modalOuvirPartenaire=<?php echo $Part['Id_Partenaire'] ?>">
                               <p>Voir plus</p>
                                <img src="<?php echo "assets/".$image['Nom_Image']."" ?>" alt="Image du partenaire <?php echo $Part['Nom_Partenaire'] ?>">
                            </a>
                            
                        </div>
                        <?php } ?>
                    </div>
                    <img class="prev" onclick="plusSlides(-1)" src="assets/chevron-gauche.png">
                    <img class="next" onclick="plusSlides(1)" src="assets/chevron-droit.png">
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
        </aside>