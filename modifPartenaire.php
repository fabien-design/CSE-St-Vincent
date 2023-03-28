<?php
require('include/connexion_db.php');
if(isset($_POST['idPart'], $_POST['nompart'], $_POST['descrippart'], $_POST['lienpart'])){
    $id = $_POST['idPart'];
    $nom = $_POST['nompart'];
    $descrip = $_POST['descrippart'];
    $lien = $_POST['lienpart'];

    if (isset($_FILES['imgpart'])) {
        $nom_image = $_FILES['imgpart']['name'];
        $nom_image = rand(1000,9999)."-".$nom_image;
        move_uploaded_file($_FILES['imgpart']['tmp_name'], 'assets/' . $nom_image);
        $InsertImg = $connexion->prepare("INSERT INTO image (Nom_Image) VALUES (:nom)");
        $InsertImg->bindParam("nom",$nom_image);
        if($InsertImg->execute() === false){
            echo "Erreur lors de l'insertion de l'image";
        }else{
            $idimg = $connexion->lastInsertId();
            $req = $connexion->prepare("UPDATE partenaire SET Nom_Partenaire = :nom, Description_Partenaire = :descrip, Lien_Partenaire = :lien, Id_Image = :idimg WHERE Id_Partenaire = :id");
            $req->bindParam("id",$id);
            $req->bindParam("nom",$nom);
            $req->bindParam("descrip",$descrip);
            $req->bindParam("lien",$lien);
            $req->bindParam("idimg",$idimg);
            if($req->execute()){
                echo "Le partenaire ".$nom." a bien été modifié.";
            }else{
                echo "Error: " . $req . "<br>" . $connexion->error;
                } 
        }

        

    }else{
        $req = $connexion->prepare("UPDATE partenaire SET Nom_Partenaire = :nom, Description_Partenaire = :descrip, Lien_Partenaire = :lien WHERE Id_Partenaire = :id");
        $req->bindParam("id",$id);
        $req->bindParam("nom",$nom);
        $req->bindParam("descrip",$descrip);
        $req->bindParam("lien",$lien);
        if($req->execute()){
            echo "Le partenaire ".$nom." a bien été modifié.";
        }else{
            echo "Error: " . $req . "<br>" . $connexion->error;
        }
    }

}else{
    header('Location : backoffice.php');
}
?>
