<?php
require('include/connexion_db.php');
$id = $_POST['idOffre'];

// Récup les id des images de cette offre dans la table offre_image

$IdOffre_Img = $connexion->prepare("SELECT Id_Image FROM offre_image WHERE Id_Offre = :id");
$IdOffre_Img->bindParam("id",$id);
if($IdOffre_Img->execute() === true){
    $Offre_Img = $IdOffre_Img->fetchAll();

    // Suppression de l'association de l'offre et de l'image

    $pas_img = false;
    $verif = false;
    if(!empty($Offre_Img)){
        $DeleteOffre_Img = $connexion->prepare("DELETE FROM offre_image WHERE Id_Offre = :id");
        $DeleteOffre_Img->bindParam("id",$id);
        $verif = $DeleteOffre_Img->execute();
    }else{
        $pas_img = true;
    }
    if($verif === true || $pas_img === true){

        // Suppression offre dans la table offre car plus besoin

        $DeleteOffre = $connexion->prepare("DELETE FROM offre WHERE Id_Offre = :id");
        $DeleteOffre->bindParam("id",$id);
        if ($DeleteOffre->execute() === true) {

            

            // Suppression des images dans la table image et  Récup des noms des images de l'offre
            $erreur = false;
            foreach($Offre_Img as $idimg){

                $SelectImage = $connexion->prepare("SELECT * FROM image WHERE Id_Image = :id");
                $SelectImage->bindParam("id",$idimg['Id_Image']);
                $SelectImage->execute();
                $images = $SelectImage->fetchAll();

                $DeleteImage = $connexion->prepare("DELETE FROM image WHERE Id_Image = :id");
                $DeleteImage->bindParam("id",$idimg['Id_Image']);
                if($DeleteImage->execute() === false){
                    echo "Erreur lors de la suppression d'une image";
                    $erreur = true;
                    break;
                }
            }
            if($erreur !== true){
                foreach($images as $img){
                    unlink('assets/'.$img['Nom_Image']);
                }
                echo "La suppression a bien été faite";
            }
        
        } else {
            echo "Error: " . $DeleteOffre . "<br>" . $connexion->error;
        }
    }else {
        echo "Erreur lors de la suppression de l'offre";
    }
}else {
    echo "Erreur lors de la suppression de l'offre";
}
?>
