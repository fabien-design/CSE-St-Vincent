<?php
require('include/connexion_db.php');
if(isset($_POST['idPart'], $_POST['nompart'], $_POST['descrippart'], $_POST['lienpart'])){
    $id = $_POST['idPart'];
    $nom = $_POST['nompart'];
    $descrip = $_POST['descrippart'];
    $lien = $_POST['lienpart'];

    if (!empty($_FILES['imgpart']['name'])) {
        $nom_image = $_FILES['imgpart']['name'];
        $nomexplode = explode(".",$nom_image);
        $ext = end($nomexplode);
        $nom_image = substr($nom_image,0,-(strlen($ext)+1));
        $nom_image .= "-".rand(1000,9999).".".$ext;
        move_uploaded_file($_FILES['imgpart']['tmp_name'], 'assets/' . $nom_image);
        $SelectLastImg = $connexion->prepare("SELECT Id_Image FROM partenaire WHERE Id_Partenaire = :idpart ");
        $SelectLastImg->bindParam("idpart",$id);
        if($SelectLastImg->execute() === false){
            echo "modification non effectuée due a une erreur.";
        }else{
            
            $LastImg = $SelectLastImg->fetch();
            if(!empty($LastImg['Id_Image'])){
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
            }else{
                $InsertImg = $connexion->prepare("INSERT INTO image (Nom_Image) VALUES (:nom)");
                $InsertImg->bindParam("nom",$nom_image);
                if($InsertImg->execute() === false){
                    echo "Erreur lors de l'insertion de l'image";
                }else{
                    $LastImg = $connexion->lastInsertId();
                    $req = $connexion->prepare("UPDATE partenaire SET Nom_Partenaire = :nom, Description_Partenaire = :descrip, Lien_Partenaire = :lien, Id_Image = :idimg WHERE Id_Partenaire = :id");
                    $req->bindParam("id",$id);
                    $req->bindParam("nom",$nom);
                    $req->bindParam("descrip",$descrip);
                    $req->bindParam("lien",$lien);
                    $req->bindParam("idimg",$LastImg);
                    if($req->execute()){
                        echo "Le partenaire ".$nom." a bien été modifié.";
                    }else{
                        echo "Error: " . $req . "<br>" . $connexion->error;
                        } 
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
