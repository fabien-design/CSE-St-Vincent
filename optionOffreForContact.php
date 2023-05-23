<?php 

require("include/connexion_db.php");
if(isset($_POST['IdPart'])){
    $id = $_POST['IdPart'];
    $req = $connexion->prepare("SELECT * FROM offre WHERE Id_Partenaire = :id ");
    $req->bindParam("id", $id);
    $req->execute();
    $Offres = $req->fetchAll();
    $resultat = "<option selected>Aucune offre associée</option>";

    foreach ($Offres as $offre) {
        $resultat .= "<option value='". $offre['Id_Offre']."' > ".$offre['Nom_Offre']."</option>";
    }
    echo $resultat;
}

?>