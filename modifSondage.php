<?php
require('include/connexion_db.php');

if(isset($_POST['questionSondage'], $_POST['choix1Sondage'], $_POST['choix2Sondage'], $_POST['choix3Sondage'])){
    $req = $connexion->prepare("UPDATE survey SET survey_question = :survey_question, survey_option1 = :survey_option1, survey_option2 = :survey_option2, survey_option3 = :survey_option3 WHERE survey_id = :survey_id");
    $req->bindParam("survey_id",$_POST['survey_id']);
    $req->bindParam("survey_question",$_POST['questionSondage']);
    $req->bindParam("survey_option1",$_POST['choix1Sondage']);
    $req->bindParam("survey_option2",$_POST['choix2Sondage']);
    $req->bindParam("survey_option3",$_POST['choix3Sondage']);
    if($req->execute()){
        echo "Le sondage a bien été modifié.";
    }else{
        echo "Erreur de mise à jour du sondage";
    }
}

?>
