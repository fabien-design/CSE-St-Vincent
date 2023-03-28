<?php
require('include/connexion_db.php');
$id = $_POST['idPart'];
$req = $connexion->prepare("DELETE FROM partenaire WHERE Id_Partenaire = :id");
$req->bindParam("id",$id);

if ($req->execute() === true) {
    echo "Le partenaire a bien été supprimé.";
} else {
    echo "Error: " . $req . "<br>" . $connexion->error;
}
?>
