<?php
require('include/connexion_db.php');
if(isset($_POST['questionSondage'], $_POST['choix1Sondage'], $_POST['choix2Sondage'], $_POST['choix3Sondage'])){
    $req = $connexion->prepare("INSERT INTO survey (survey_question, survey_option1, survey_option2, survey_option3) VALUES (:survey_question, :survey_option1, :survey_option2, :survey_option3)");
    $req->bindParam("survey_question",$_POST['questionSondage']);
    $req->bindParam("survey_option1",$_POST['choix1Sondage']);
    $req->bindParam("survey_option2",$_POST['choix2Sondage']);
    $req->bindParam("survey_option3",$_POST['choix3Sondage']);
    if($req->execute()){
        echo "Le Sondage a bien été ajouté.";
    }else{
        echo "Erreur d'ajout de sondage: " . $req . "<br>" . $connexion->error;
    }
}
