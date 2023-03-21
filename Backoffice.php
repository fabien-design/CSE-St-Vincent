<?php 
    session_start();
    require('include/connexion_db.php');
    if(!empty($_POST)){
        $erreurs = [];
        if(empty($_POST['email'])){
            $erreurs['email'] = "Veuillez saisir une adresse mail.";
        }
        else{
            if(filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
                $email = $_POST['email'];
            }
            else{
                $erreurs['email'] = "Veuillez saisir une adresse mail valide.";
            }
        }
        if(empty($_POST['password'])){
            $erreurs['password'] = "Veuillez saisir une adresse mail.";
        }
        else{
            $password = $_POST['password']; 
        }
        if(empty($erreurs)){
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
                        $erreurs['password'] = "Mot de passe incorrect.";
                    }
                }
                else{
                    $erreurs['email'] = "Adresse email incorrecte.";
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
        <form action="Backoffice.php" method="post">
            <div class="formGroup">
                <h1 class="formTitle">Connexion au backoffice</h1>
                <?= isset($erreurs['email']) ||isset($erreurs['password']) ? "La combinaison email / mot de passe n'existe pas." : null ?>
                <div class="inputGroup">
                    <input type="email" id="email" required="" name="email">
                    <label for="email">Email*</label>
                    
                </div>
                <div class="inputGroup">
                    <input type="password" id="password" required="" name="password">
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
    if($_SESSION['Droit_Utilisateur'] === "Administrateur" || $_SESSION['Droit_Utilisateur'] === "Utilisateur"){ ?>
       <div class="Accueil">
            <h1 class="helloBox">
                Bonjour, vous avez les droits d'<?= $_SESSION['Droit_Utilisateur'] ?>.
            </h1>
       </div>
    <?php
    }else{
    header('Location: index.html');
    }

}?>

<!-- Fin Page HTML -->
</body>
</html>
