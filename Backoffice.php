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

}?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backoffice</title>
    <link rel="stylesheet" href="styleBackoffice.css">
</head>
<body> 
<?php
if(empty($_SESSION['Nom_Utilisateur']) && empty($_SESSION['Droit_Utilisateur'])){
?>

 
    <div class="formulaire">
        <form action="Backoffice.php" method="post" name="login">
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
            <div class="gris">
            </div>
            <div class="blue">
                <nav>
                    <div class="logo"><img src="assets/logo_lycee.png" alt="logo_st_vincent"></div>
                    <ul>
                        <li>
                            <a href="Backoffice.php?page=accueil">Accueil</a>
                        </li>
                        <li><a href="Backoffice.php?page=partenaires">Partenariats</a></li>
                        <li><a href="Backoffice.php?page=billetterie">Billetterie</a></li>
                        <li><a href="Backoffice.php?page=message">Messages</a></li>
                    </ul>
                </nav>
            </div>
        </header>
        <main> <?php
        if(!empty($_GET)){
        if(!empty($_GET['page']) && $_GET['page'] !== "accueil"){
            if($_GET['page'] === "partenaires"){
                /**/ 
            }else if($_GET['page'] === "billetterie"){
                /**/ 
            }else if($_GET['page'] === "message"){
                /**/ 
            }
        }else{ ?>
            <div class="titlePage">
                <h1>Modifier les informations de la page d'accueil</h1>
            </div>
            <div class="formEdit">
                <form action="Backoffice.php" method="post" name="accueilEdit">
                    <?php 
                        $req = $connexion->prepare("SELECT * FROM info_accueil");
                        $req->execute();
                        $infoAccueil = $req->fetch();
                    ?>
                    <div class="inputgroup">
                        <label for="tel">Numéro de téléphone* :</label>
                        <input type="tel" name="accueilEdit[phone]" id="phone" required="" value="<?php echo $infoAccueil['Num_Tel_Info_Accueil'] ?>">
                    </div>
                    <div class="inputgroup">
                        <label for="email">Email* :</label>
                        <input type="email" name="accueilEdit[email]" id="email" required="" value="<?php echo $infoAccueil['Email_Info_Accueil'] ?>">
                    </div>
                    <div class="inputgroup">
                        <label for="bureau">Emplacement du Bureau* :</label>
                        <textarea type="text" name="accueilEdit[bureau]" id="bureau" required=""><?php echo $infoAccueil['Emplacement_Bureau_Info_Accueil'] ?></textarea>
                    </div>
                    <div class="inputgroup">
                        <label for="titre">Titre de la page* :</label>
                        <input type="text" name="accueilEdit[titre]" id="titre" required="" value="<?php echo $infoAccueil['Titre_Info_Accueil'] ?>">
                    </div>
                    <div class="inputgroup">
                        <label for="description">Description de la page* :</label>
                        <input type="text" name="accueilEdit[description]" id="description" required="" value="<?php echo $infoAccueil['Texte_Info_Accueil'] ?>">
                    </div>
                    <div class="btnGroup">
                        <button type="submit">Valider</button>
                    </div>
                </form>
            </div>
        <?php 
        }
    }
     ?>
            
        </main>
    <?php
    }else{
        header('Location: index.html');
    }

}?>

<!-- Fin Page HTML -->
</body>
</html>
