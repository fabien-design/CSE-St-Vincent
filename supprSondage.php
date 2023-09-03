<?php
require('include/connexion_db.php');
if(isset($_POST['survey_id'])){
    $req = $connexion->prepare("DELETE FROM survey WHERE survey_id = :survey_id");
    $req->bindParam("survey_id",$_POST['survey_id']);
    if($req->execute()){
        echo "Le sondage a bien été supprimé.";
    }else{
        echo "Erreur de suppression du sondage";
    }
}
?>
