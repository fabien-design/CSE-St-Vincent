<?php
require('include/connexion_db.php');
var_dump($_POST);
if(isset($_POST['nomoffre'], $_POST['descripoffre'], $_POST['datedeboffre'], $_POST['datefinoffre'], $_POST['placeoffre'], $_POST['partoffre'])){
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
    if(empty($erreurs)){

        if (isset($_FILES['imgoffre'])) {
            $req = $connexion->prepare("INSERT INTO offre (Nom_Offre, Description_Offre, Date_Debut_Offre, Date_Fin_Offre, Nombre_Place_Min_Offre, Id_Partenaire) VALUES (:nom, :descrip, :datedeb, :datefin, :nbplace, :idpart)");
            $req->bindParam("nom",$nom);
            $req->bindParam("descrip",$descrip);
            $req->bindParam("datedeb",$datedeb);
            $req->bindParam("datefin",$datedeb);
            $req->bindParam('nbplace', $places);
            $req->bindParam('idpart', $_POST['partoffre']);
            if($req->execute()){
                $idoffre = $req->lastInsertId();
                foreach($_FILES['imgoffre'] as $img){
                    $nom_image = $img['name'];
                    $nomexplode = explode(".",$nom_image);
                    $ext = end($nomexplode);
                    $nom_image = substr($nom_image,0,-(strlen($ext)+1));
                    $nom_image .= "-".rand(1000,9999).".".$ext;
                    move_uploaded_file($img['tmp_name'], 'assets/' . $nom_image);
                    $InsertImg = $connexion->prepare("INSERT INTO image (Nom_Image) VALUES (:nom)");
                    $InsertImg->bindParam("nom",$nom_image);
                    if($InsertImg->execute() === false){
                        echo "Erreur lors de l'insertion de l'image";
                    }else{
                        $idimg = $connexion->lastInsertId();
                        $req = $connexion->prepare("INSERT INTO offre_image (Id_Image, Id_Offre) VALUES (:idimg ,:idoffre)");
                        $req->bindParam("idimg", $idimg);
                        $req->bindParam("idoffre", $idoffre);
                        if($req->execute() == false){
                           echo "Probleme avec l'insertion de l'image";
                        }
                    };
                }
                echo "L'offre ".$nom." a bien été ajouté.";
            }else{
                echo "Erreur: " . $req . "<br>" . $connexion->error;
            }
            
        }else{
            echo "Veuillez ajouter au moins une image";
        }

    }else{
        echo "Veuillez remplir correctement tous les champs.";
    }
}else{
    header('Location : backoffice.php');
}
?>
