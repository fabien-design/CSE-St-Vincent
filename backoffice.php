<?php 
    session_start();
    require('include/connexion_db.php');
    if(!empty($_POST['login'])){
        $erreursLogin = [];
        if(empty($_POST['login']['email'])){
            $erreursLogin['email'] = "Veuillez saisir une adresse mail.";
        }
        else{
            if(filter_var($_POST['login']['email'],FILTER_VALIDATE_EMAIL)){
                $email = $_POST['login']['email'];
            }
            else{
                $erreursLogin['email'] = "Veuillez saisir une adresse mail valide.";
            }
        }
        if(empty($_POST['login']['password'])){
            $erreursLogin['password'] = "Veuillez saisir une adresse mail.";
        }
        else{
            $password = $_POST['login']['password']; 
        }
        if(empty($erreursLogin)){
            try{
                $req = $connexion->prepare("SELECT * FROM utilisateur WHERE Email_Utilisateur = :email");
                $req->bindParam('email',$email);
                $req->execute();
                if($req->rowCount() == 1){
                    $utilisateur = $req->fetch();
                    if(password_verify($password,$utilisateur['Password_Utilisateur'])){
                        $_SESSION['Nom_Utilisateur'] = $utilisateur['Nom_Utilisateur'];
                        $reqDroit = $connexion->prepare("SELECT * FROM droit WHERE Id_Droit = :id");
                        $reqDroit->bindParam('id',$utilisateur['Id_Droit']);
                        $reqDroit->execute();
                        $droit = $reqDroit->fetch();
                        $_SESSION['Droit_Utilisateur'] = $droit['Libelle_Droit'];
                    }
                    else{
                        $erreursLogin['password'] = "Mot de passe incorrect.";
                    }
                }
                else{
                    $erreursLogin['email'] = "Adresse email incorrecte.";
                }
            }catch(Exception $e){
                echo "La requette n'a pas pu être faite.";
            }
    }

}
if(!empty($_POST['accueilEdit'])){
    $erreursaccueilEdit = [];
    if(empty($_POST['accueilEdit']['phone'])){
        $erreursaccueilEdit['phone'] = "Veuillez saisir un numéro de téléphone.";
    }else{
        if(preg_match('/^[0-9]{11}+$/', $_POST['accueilEdit']['phone'])) {
                $phone = $_POST['accueilEdit']['phone'];
        } else {
                $erreursaccueilEdit['phone'] = "Veuillez saisir un numéro de téléphone valide.";
        }
    }
    if(empty($_POST['accueilEdit']['email'])){
        $erreursaccueilEdit['email']="Veuillez saisir une adresse mail.";
    }else{
        if(filter_var($_POST['accueilEdit']['email'],FILTER_VALIDATE_EMAIL)){
            if(strlen($_POST['accueilEdit']['email']) >255){
                $erreursaccueilEdit['email']="L'adresse mail saisie est trop longue.";
            }else{
                $email = $_POST['accueilEdit']['email'];
            }
        }else{
            $erreursaccueilEdit['email']="Veuillez saisir une adresse mail valide.";
        }
    }
    if(empty($_POST['accueilEdit']['bureau'])){
        $erreursaccueilEdit['bureau'] = "Veuillez saisir un emplacement du bureau.";
    }else{
        $bureau = htmlspecialchars($_POST['accueilEdit']['bureau']);
        if(strlen($bureau) > 255){
            $erreursaccueilEdit['bureau'] = "Veuillez saisir un emplacement du bureau plus court.";
        }
    }
    if(empty($_POST['accueilEdit']['titre'])){
        $erreursaccueilEdit['titre'] = "Veuillez saisir un titre.";
    }else{
        if(strlen($_POST['accueilEdit']['titre']) > 255){
            $erreursaccueilEdit['titre'] = "Veuillez saisir un titre plus court.";
        }else{
            $titre = htmlspecialchars($_POST['accueilEdit']['titre']);
        }
    }
    if(empty($_POST['accueilEdit']['description'])){
        $erreursaccueilEdit['texte'] = "Veuillez saisir un texte.";
    }else{
        if(strlen($_POST['accueilEdit']['description'])>3000){
            $erreursaccueilEdit['texte'] = "Veuillez saisir un texte de moins de 3000 caractères.";
        }else{
            $description = htmlspecialchars($_POST['accueilEdit']['description']); 
        }
    }
    if(empty($erreursaccueilEdit)){
        try{
            $req = $connexion->prepare("UPDATE info_accueil SET Num_Tel_Info_Accueil = :phone, Email_Info_Accueil = :email, Emplacement_Bureau_Info_Accueil = :bureau, Titre_Info_Accueil = :titre, Texte_Info_Accueil = :descrip");
            $req->bindParam('phone',$phone);
            $req->bindParam('email',$email);
            $req->bindParam('bureau',$bureau);
            $req->bindParam('titre',$titre);
            $req->bindParam('descrip',$description);
            $req->execute();
            $msgvalidation = "<div class='msgvalide'>
                La modification a bien été effectuée.
            </div>";
        }catch(Exception $e){
            echo "Erreur lors de la modification";
        }
    }
}


// CODE MODAL POUR SUPPRESSION D'UN PARTENAIRE


if(isset($_GET['modalSupprPartenaire'])){
    $req = $connexion->prepare("SELECT * FROM partenaire WHERE Id_Partenaire = :id");
    $req->bindParam('id',$_GET['modalSupprPartenaire']);
    $req->execute();
    $partenaire = $req->fetch();
    $reqImg = $connexion->prepare("SELECT Nom_Image FROM image WHERE Id_Image = :id");
    $reqImg->bindParam('id',$partenaire['Id_Image']);
    $reqImg->execute();
    $imgPart = $reqImg->fetch();
     ?>
    <div id="modalSupprPartenaire" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div class="title">
                <h2><?php echo $partenaire['Nom_Partenaire']; ?></h2>
            </div>
            <div class="modalBox"> <?php
                if(!empty($partenaire["Id_Image"])){?>
                <img src="assets/<?= $imgPart['Nom_Image']; ?>" alt="Image <?php echo $partenaire['Nom_Partenaire']; ?>">
                <?php } ?>
                <div class="supprBox">
                    <p>Êtes-vous sûr de vouloir supprimer ce partenaire ?</p>
                    <div class="supprBtn">
                        <form id="formSupprPartenaire">
                            <input type="hidden" name="idPart" value="<?php echo $partenaire['Id_Partenaire'] ?>">
                            <button type="submit" class="formSupprOui">OUI</button>
                        </form>
                        <button class="formSupprNon">NON</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
<?php
}
?>

<!-- Debut Page HTML -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backoffice</title>
    <link rel="stylesheet" href="styleBackoffice.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
</head>
<body> 
<?php
if(empty($_SESSION['Nom_Utilisateur']) && empty($_SESSION['Droit_Utilisateur'])){
?>

 
    <div class="formulaire">
        <form action="backoffice.php" method="post" name="login">
            <div class="formGroup">
                <h1 class="formTitle">Connexion au backoffice</h1>
                <?= isset($erreursLogin['email']) ||isset($erreursLogin['password']) ? "La combinaison email / mot de passe n'existe pas." : null ?>
                <div class="inputGroup">
                    <input type="email" id="email" required="" name="login[email]">
                    <label for="email">Email*</label>
                    
                </div>
                <div class="inputGroup">
                    <input type="password" id="password" required="" name="login[password]">
                    <label for="password">Mot de passe*</label>
                </div>
                <div class="btnGroup">
                    <button type="submit">Se connecter</button>
                </div>
            </div>
        </form>
    </div>
<?php
}else{ 
    if($_SESSION['Droit_Utilisateur'] === "Administrateur"){ ?>
    <header>
        <div class="graynav"></div>
        <nav>
            <div class="bgLogo">
                <img src="assets/logo_lycee.png" alt="logo du lycée">
            </div>
            <ul> <?php
            if(empty($_GET) || $_GET['page'] === "accueil"){?>
                <li><a href="backoffice.php?page=accueil" class="active">Accueil</a></li>
            <?php }else{ ?>
                <li><a href="backoffice.php?page=accueil">Accueil</a></li>
            <?php }
            if(!empty($_GET) && $_GET['page'] === "partenaires"){ ?>
                <li><a href="backoffice.php?page=partenaires" class="active">Partenariats</a></li>
            <?php }else{ ?>
                <li><a href="backoffice.php?page=partenaires">Partenariats</a></li>
            <?php }if(!empty($_GET) && $_GET['page'] === "billetterie"){ ?>
                <li><a href="backoffice.php?page=billetterie" class="active">Billeterie</a></li>
            <?php }else{ ?> 
                <li><a href="backoffice.php?page=billetterie">Billeterie</a></li>
            <?php }if(!empty($_GET) && $_GET['page'] === "message"){?>
                <li><a href="backoffice.php?page=message" class="active">Messages</a></li>
            <?php }else{?>
                <li><a href="backoffice.php?page=message">Messages</a></li>
            <?php } ?>
            </ul>
        </nav>
    </header>
        <main> <?php
        if(!empty($_GET) && $_GET['page'] !== "accueil"){

            if(!empty($_GET['page']) && $_GET['page'] !== "accueil"){
                if($_GET['page'] === "partenaires"){?>


                    <div class="partenaires">

                    <?= isset($msgvalidation) ? $msgvalidation : null ?>
                    <div class="titlePage"> 
                        <h1>Modifier les partenaires</h1>
                    </div>
                    <div class="tablepartenaires">
                    <table>
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Description</th>
                                <th>Lien du site</th>
                                <th>Image</th>
                                <th>Action</th>
                                <th><a href="backoffice.php?page=partenaires&modalAjout=partenaire">Ajouter un partenaire</a></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            try{
                                $req = $connexion->prepare("SELECT * FROM partenaire");
                                $req->execute();
                                $partenaires= $req->fetchAll();
                                foreach($partenaires as $partenaire){
                                    if(!empty($partenaire["Id_Image"])){
                                        $req = $connexion->prepare("SELECT Nom_Image FROM image WHERE Id_Image = :id");
                                        $req->bindParam('id',$partenaire["Id_Image"]);
                                        $req->execute();
                                        $imgPart= $req->fetch();}
                                    ?>
                                    <tr>
                                        <td><?php echo $partenaire["Nom_Partenaire"] ?></td>
                                        <td><?php echo $partenaire["Description_Partenaire"] ?></td>
                                        <td><?php echo $partenaire["Lien_Partenaire"] ?></td>
                                        <td class="imgPart"><?= !empty($partenaire["Id_Image"]) ? '<img src="assets/'.$imgPart["Nom_Image"].'" alt="Image du partenaire">' : "Aucune image" ?></td>
                                        <td class="actionBtn">  
                                            <a href="backoffice.php?page=partenaires&modalModifPartenaire=<?= $partenaire["Id_Partenaire"]; ?>" class="modifBtn">Modifier</a>
                                            <a href="backoffice.php?page=partenaires&modalSupprPartenaire=<?= $partenaire["Id_Partenaire"]; ?>" class="supprBtn">Supprimer</a>
                                        </td>
                                    </tr>
                               <?php }
                            }catch(Exception $e){
                                echo "Erreur lors de l'affichage";
                            }

                            ?>
                        </tbody>
                    </table>
                    </div>

                    </div>
                    
                    


          <?php }else if($_GET['page'] === "billetterie"){
                    /**/ 
                }else if($_GET['page'] === "message"){
                    /**/ 
                }
            }

    }else{ ?>
    <div class="accueil">
        <?= isset($msgvalidation) ? $msgvalidation : null ?>
        <div class="titlePage"> 
            <h1>Modifier les informations de la page d'accueil</h1>
        </div>
        <div class="formEdit">
            <form action="backoffice.php" method="post" name="accueilEdit">
                <?php 
                    $req = $connexion->prepare("SELECT * FROM info_accueil");
                    $req->execute();
                    $infoAccueil = $req->fetch();
                ?>
                    <label for="tel">Numéro de téléphone<span>*</span> :</label>
                    <input type="tel" name="accueilEdit[phone]" id="phone" required="" value="<?php echo $infoAccueil['Num_Tel_Info_Accueil'] ?>">
                    <?= isset($erreursaccueilEdit['phone']) ? $erreursaccueilEdit['phone'] : null ?>

                    <label for="email">Email<span>*</span> :</label>
                    <input type="email" name="accueilEdit[email]" id="email" required="" value="<?php echo $infoAccueil['Email_Info_Accueil'] ?>">
                    <?= isset($erreursaccueilEdit['email']) ? $erreursaccueilEdit['email'] : null ?>
                    
                    <label for="bureau">Emplacement du Bureau<span>*</span> :</label>
                    <textarea type="text" name="accueilEdit[bureau]" id="bureau" required=""><?php echo $infoAccueil['Emplacement_Bureau_Info_Accueil'] ?></textarea>
                    <?= isset($erreursaccueilEdit['bureau']) ? $erreursaccueilEdit['bureau'] : null ?>
                    
                    <label for="titre">Titre de la page<span>*</span> :</label>
                    <input type="text" name="accueilEdit[titre]" id="titre" required="" value="<?php echo $infoAccueil['Titre_Info_Accueil'] ?>">
                    <?= isset($erreursaccueilEdit['titre']) ? $erreursaccueilEdit['titre'] : null ?>
                    
                    <label for="description">Description de la page<span>*</span> :</label>
                    <textarea type="text" name="accueilEdit[description]" id="description" required=""><?php echo $infoAccueil['Texte_Info_Accueil'] ?></textarea>
                    <?= isset($erreursaccueilEdit['description']) ? $erreursaccueilEdit['description'] : null ?>
                <div class="btnGroup">
                    <button type="submit"><div class="bghover"></div><p>Valider</p></button>
                </div>
            </form>
        </div>
    </div>
    <?php 
    }
     ?>
            
        </main>
    <?php
    }else{
        header('Location: index.html');
    }

}?>

<!-- Fin Page HTML -->
<script src="scriptBackoffice.js"></script>
</body>
</html>
