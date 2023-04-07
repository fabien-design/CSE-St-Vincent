<?php
require('include/connexion_db.php');
$id = $_POST['idMsg'];
$req = $connexion->prepare("DELETE FROM message WHERE Id_Message = :id");
$req->bindParam("id",$id);

if ($req->execute() === true) {
    echo "Le message a bien été supprimé.";
} else {
    echo "Error: " . $req . "<br>" . $connexion->error;
}
?>
