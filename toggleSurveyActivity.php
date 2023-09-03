<?php
require('include/connexion_db.php');

$surveyActive = $connexion -> prepare("SELECT settings_value FROM settings WHERE settings_key = 'surveyCurrentlyActive'");
$surveyActive->setFetchMode(PDO::FETCH_ASSOC);
$surveyActive -> execute();
$surveyActive = $surveyActive->fetch();

    if($surveyActive['settings_value'] == 'false'){
        $toggleSurveyActivity = $connexion -> prepare("UPDATE settings SET settings_value = 'true' WHERE settings_key = 'surveyCurrentlyActive'");
        $toggleSurveyActivity-> execute();
    }elseif($surveyActive['settings_value'] =='true'){
        $toggleSurveyActivity = $connexion -> prepare("UPDATE settings SET settings_value = 'false' WHERE settings_key = 'surveyCurrentlyActive'");
        $toggleSurveyActivity-> execute();
    }


?>
