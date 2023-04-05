<?php
require('include/connexion_db.php');
if(isset($_POST['idoffre'], $_POST['nomoffre'], $_POST['descripoffre'], $_POST['datedeboffre'], $_POST['datefinoffre'], $_POST['placeoffre'], $_POST['partoffre'])){
    $id = $_POST['idoffre'];
    $nom = htmlspecialchars($_POST['nomoffre']);
    $descrip = htmlspecialchars($_POST['descripoffre']);
    $places = $_POST['placeoffre'];
    $erreurs = [];
    if(strlen($nom) > 255){
        $erreurs['nom'] = "Nom trop long";
    }
    if(strlen($descrip) > 3000){
        $erreurs['descrip'] = "Description trop long";
    }
    if($places < 0){
        $erreurs['place'] = "Nombre de place négatif";
    }
    $datedeb = $_POST['datedeboffre'];
    $datefin = $_POST['datefinoffre'];

    if (!empty($_FILES['imgpart']['name'])) {
        $nom_image = $_FILES['imgpart']['name'];
        $nomexplode = explode(".",$nom_image);
        $ext = end($nomexplode);
        $nom_image = substr($nom_image,0,-(strlen($ext)+1));
        $nom_image .= "-".rand(1000,9999).".".$ext;
        move_uploaded_file($_FILES['imgpart']['tmp_name'], 'assets/' . $nom_image);
        $SelectLastImg = $connexion->prepare("SELECT Id_Image FROM image WHERE Id_Image in (SELECT Id_Image FROM offre_image WHERE Id_Offre = :idoffre)");
        $SelectLastImg->bindParam("idoffre",$id);
        if($SelectLastImg->execute() === false){
            echo "modification non effectuée due a une erreur.";
        }else{
            $LastImg = $SelectLastImg->fetch();

            $reqLastImg = $connexion->prepare("SELECT Nom_Image FROM image WHERE Id_Image = :idimg ");
            $reqLastImg->bindParam("idimg",$LastImg['Id_Image']);
            $reqLastImg->execute();
            $LastnomImg = $reqLastImg->fetch();
            try{
                unlink('assets/'.$LastnomImg['Nom_Image']);
            }catch(Exception $e){
            }
            $InsertImg = $connexion->prepare("UPDATE image SET Nom_Image = :nom WHERE Id_Image = :idimg");
            $InsertImg->bindParam("nom",$nom_image);
            $InsertImg->bindParam("idimg",$LastImg['Id_Image']);
            if($InsertImg->execute() === false){
                echo "Erreur lors de l'insertion de l'image";
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
