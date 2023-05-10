<?php
require('../include/connexion_db.php');
if(isset($_POST['iduser'])){
    $id = $_POST['iduser'];
    $erreurs = [];

    if(empty($erreurs)){
        $Deletereq = $connexion->prepare("DELETE FROM utilisateur WHERE Id_Utilisateur = :id");
        $Deletereq->bindParam("id",$id);
        if($Deletereq->execute() == false){
            echo "Erreur de suppression.";
        }else{
            echo "Utilisateur supprimé.";
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
