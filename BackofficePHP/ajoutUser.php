<?php
require('../include/connexion_db.php');
if(isset($_POST['nomuser'], $_POST['prenomuser'], $_POST['emailuser'], $_POST['passuser'], $_POST['passuser2'], $_POST['droituser'])){
    $nom = htmlspecialchars($_POST['nomuser']);
    $prenom = htmlspecialchars($_POST['prenomuser']);
    $password = $_POST['passuser'];
    $email = filter_var($_POST['emailuser'], FILTER_SANITIZE_EMAIL);
    $erreurs = [];
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email = $email;
    }else{
        $erreurs['email'] = "l'email ".$_POST['emailuser']." est invalide.";
    }
    if(strlen($nom) > 255){
        $erreurs['nom'] = "Nom trop long";
    }
    if(strlen($nom) < 1){
        $erreurs['nom'] = "Nom trop court";
    }
    if(strlen($prenom) > 255){
        $erreurs['prenom'] = "Prenom trop long";
    }
    if(strlen($prenom) < 1){
        $erreurs['prenom'] = "Prenom trop court";
    }
    if(strlen($password) < 6){
        $erreurs['password'] = "Mot de passe trop court (< 6 caracteres) ";
    }else{
        if($password == $_POST['passuser2']){
            $password = password_hash($password, PASSWORD_ARGON2I);
        }
        else{
            $erreurs['password'] = "Les mots de passes sont différents";
        }
        
    }
    
    $verifdroit = $connexion->prepare('SELECT Id_Droit FROM droit');
    $verifdroit->execute();
    $vdroit = $verifdroit->fetchAll();
    $droitsId = [];
    foreach($vdroit as $droit){
        array_push($droitsId,$droit['Id_Droit']);
    }
    if(!in_array($_POST['droituser'],$droitsId)){
        $erreurs['droit'] = "Aucun Droit avec cet id existe.";
    }else{
        $droit = $_POST['droituser'];
    }

    if(empty($erreurs)){
        $Ajoutreq = $connexion->prepare("INSERT INTO utilisateur (Nom_Utilisateur, Prenom_Utilisateur, Email_Utilisateur, Password_Utilisateur, Id_Droit) VALUES (:nom, :prenom, :email, :pass, :droit)");
        $Ajoutreq->bindParam("nom",$nom);
        $Ajoutreq->bindParam("prenom",$prenom);
        $Ajoutreq->bindParam("email",$email);
        $Ajoutreq->bindParam("pass",$password);
        $Ajoutreq->bindParam("droit",$droit);
        if($Ajoutreq->execute() == false){
            echo "Erreur d'insertion";
        }else{
            echo "Utilisateur ajouté";
        }
        

    }else{
        $erreur_message = "Veuillez remplir correctement tous les champs : \n";
        foreach ($erreurs as $cle => $valeur) {
            $erreur_message .= "-> " . $valeur . "\n";
        }
        echo $erreur_message;

    }
}else{
    header('Location : ../backoffice.php');
}
?>
