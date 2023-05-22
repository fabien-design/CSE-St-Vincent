<?php
require('../include/connexion_db.php');
if(isset($_POST['Id_Img'])){
    $id = $_POST['Id_Img'];
    $erreurs = [];
    $Nomreq = $connexion->prepare("SELECT * FROM image WHERE Id_Image = :id");
    $Nomreq->bindParam("id",$id);
    $Nomreq->execute();
    $NomImg = $Nomreq->fetch();

    if(empty($erreurs)){
        $Deletereq = $connexion->prepare("DELETE FROM offre_image WHERE Id_Image = :id");
        $Deletereq->bindParam("id",$id);
        $Deletereq->execute();
        $Deletereq = $connexion->prepare("DELETE FROM image WHERE Id_Image = :id");
        $Deletereq->bindParam("id",$id);
        if($Deletereq->execute() == false){
            echo "Erreur de suppression.";
        }else{ 
            unlink('../assets/'.$NomImg['Nom_Image'].'');
        }
        

    }else{
        $erreur_message = "Veuillez supprimer une image existante : \n";

    }
}else{
    header('Location: ../backoffice.php');
}
?>
