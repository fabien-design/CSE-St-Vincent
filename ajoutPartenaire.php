<?php
require('include/connexion_db.php');
if(isset($_POST['nompart'], $_POST['descrippart'], $_POST['lienpart'])){
    $nom = htmlspecialchars($_POST['nompart']);
    $descrip = htmlspecialchars($_POST['descrippart']);
    $lien = $_POST['lienpart'];
    $erreurs = [];
    if(strlen($nom) > 255){
        $erreurs['nom'] = "Nom trop long";
    }
    if(strlen($descrip) > 3000){
        $erreurs['descrip'] = "Description trop long";
    }
    if(strlen($lien) > 500){
        $erreurs['lien'] = "Lien trop long";
    }
    if(empty($erreurs)){

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
                $req = $connexion->prepare("INSERT INTO partenaire (Nom_Partenaire, Description_Partenaire, Lien_Partenaire, Id_Image) VALUES (:nom, :descrip, :lien, :idimg)");
                $req->bindParam("nom",$nom);
                $req->bindParam("descrip",$descrip);
                $req->bindParam("lien",$lien);
                $req->bindParam('idimg', $idimg);
                if($req->execute()){
                    echo "Le partenaire ".$nom." a bien été ajouté.";
                }else{
                    echo "Error: " . $req . "<br>" . $connexion->error;
                    } 
            }

        }else{
            echo "Veuillez ajouter un logo au partenaire";
        }

    }else{
        echo "Veuillez remplir correctement tous les champs.";
    }
}else{
    header('Location : backoffice.php');
}
?>
