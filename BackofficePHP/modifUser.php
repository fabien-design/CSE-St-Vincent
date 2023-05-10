<?php
require('../include/connexion_db.php');
if(isset($_POST['iduser'],$_POST['nomuser'], $_POST['prenomuser'], $_POST['emailuser'], $_POST['passuser'], $_POST['passuser2'], $_POST['droituser'])){
    $id= $_POST['iduser'];
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
    if(!empty($password)){
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
        if(!empty($password)){
            $Updatereq = $connexion->prepare("UPDATE utilisateur SET Nom_Utilisateur = :nom, Prenom_Utilisateur= :prenom, Email_utilisateur = :email, Password_Utilisateur = :pass, Id_Droit = :droit WHERE Id_Utilisateur = :id");
            $Updatereq->bindParam("id",$id);
            $Updatereq->bindParam("nom",$nom);
            $Updatereq->bindParam("prenom",$prenom);
            $Updatereq->bindParam("email",$email);
            $Updatereq->bindParam("pass",$password);
            $Updatereq->bindParam("droit",$droit);
            if($Updatereq->execute() == false){
                echo "Erreur de mise à jour";
            }else{
                echo "Mise à jour de l'utilisateur effectué";
            }
        }else{
            $Updatereq = $connexion->prepare("UPDATE utilisateur SET Nom_Utilisateur = :nom, Prenom_Utilisateur= :prenom, Email_utilisateur = :email, Id_Droit = :droit WHERE Id_Utilisateur = :id");
            $Updatereq->bindParam("id",$id);
            $Updatereq->bindParam("nom",$nom);
            $Updatereq->bindParam("prenom",$prenom);
            $Updatereq->bindParam("email",$email);
            $Updatereq->bindParam("droit",$droit);
            if($Updatereq->execute() == false){
                echo "Erreur de mise à jour";
            }else{
                echo "Mise à jour de l'utilisateur effectué";
            }
        }
        
        

    }else{
        $erreur_message = "Veuillez remplir correctement tous les champs :\n";
        foreach ($erreurs as $cle => $valeur) {
            $erreur_message .= "-> " . $valeur . "\n";
        }
        echo $erreur_message;

    }
}else{
    header('Location : ../backoffice.php');
}
?>
