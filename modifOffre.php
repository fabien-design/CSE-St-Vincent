<?php
require('include/connexion_db.php');
if(isset($_POST['idoffre'], $_POST['nomoffre'], $_POST['descripoffre'], $_POST['datedeboffre'], $_POST['datefinoffre'], $_POST['placeoffre'], $_POST['partoffre'])){
    $id = $_POST['idoffre'];
    $nom = htmlspecialchars($_POST['nomoffre']);
    $descrip = htmlspecialchars($_POST['descripoffre']);
    $places = $_POST['placeoffre'];
    $partenaire = $_POST['partoffre'];
    $erreurs = [];
    if(strlen($nom) > 255){
        $erreurs['nom'] = "Nom trop long";
        echo 'Le nom est trop long';
    }
    if(strlen($descrip) > 3000){
        $erreurs['descrip'] = "Description trop long";
        echo 'La description est trop longue';
    }
    if($places < 0){
        $erreurs['place'] = "Nombre de place négatif";
        echo "Le nombre de place entré ne dois pas être négatif";
    }
    
    $datedeb = $_POST['datedeboffre'];
    $datefin = $_POST['datefinoffre'];

    if($datefin < $datedeb){
        $erreurs['date'] = "Pb dates incoherentes";
        echo "La date de fin se finit plus tôt que le début de l'offre";
    }
    if(empty($erreurs)){

        if (!empty($_FILES['imgoffre'])) {
            $pos = -1;
            
            foreach($_FILES['imgoffre']["name"] as $img){ 
                //Vu que c'est un tableau j'utilise $pos pour me balader dans les différents noms
                $pos++;
                if(!empty($_FILES['imgoffre']['name'][$pos])){
                    $nom_image = $_FILES['imgoffre']['name'][$pos];
                    $nomexplode = explode(".",$nom_image);
                    $ext = end($nomexplode);
                    $nom_image = substr($nom_image,0,-(strlen($ext)+1));
                    $nom_image .= "-".rand(1000,9999).".".$ext;
                    move_uploaded_file($_FILES['imgoffre']['tmp_name'][$pos], 'assets/' . $nom_image);
                    $SelectLastImg = $connexion->prepare("SELECT * FROM image WHERE Id_Image in (SELECT Id_Image FROM offre_image WHERE Id_Offre = :idoffre ORDER BY Id_Image ASC)");
                    $SelectLastImg->bindParam("idoffre",$id);
                    if($SelectLastImg->execute() === false){
                        echo "modification non effectuée due a une erreur.";
                    }else{
                        $LastImg = $SelectLastImg->fetchAll();
                        // if/else pour verifier si les images remplacent des existantes ou se sont des nouvelles
                        if($pos < count($LastImg)){
                            // MAJ de la table image avec les nouvelles valeurs
                            $UpdateImg = $connexion->prepare("UPDATE image SET Nom_Image = :nom WHERE Id_Image = :idimg");
                            $UpdateImg->bindParam("nom",$nom_image);
                            $UpdateImg->bindParam("idimg",$LastImg[$pos]['Id_Image']);
                            if($UpdateImg->execute() === false){
                                echo "Erreur lors de la mise à jour de l'image";
                                $ErreurUpdate = TRUE;
                            }else{
                                $ErreurUpdate = FALSE;
                            }
                        }else{
                            echo "Nouvelle image ".$nom_image;
                            $InsertImg = $connexion->prepare("INSERT INTO image (Nom_Image) VALUES (:nom) ");
                            $InsertImg->bindParam("nom",$nom_image);
                            if($InsertImg->execute() === false){
                                echo "Erreur lors de l'insertion de l'image";
                            }else{
                                //Recup dernier id inséré (celui au-dessus) + insertion dans offre_image
                                $LastInsertImgId = $connexion-> lastInsertId();
                                $InsertOffre_Img = $connexion->prepare("INSERT INTO offre_image (Id_Offre,Id_Image) VALUES (:idoffre, :idimage)");
                                $InsertOffre_Img->bindParam("idoffre",$id);
                                $InsertOffre_Img->bindParam("idimage",$LastInsertImgId);
                                if($InsertOffre_Img->execute() === false){
                                    echo "Erreur lors de l'insertion de l'image";
                                    $ErreurUpdate = TRUE;
                                }else{
                                    $ErreurUpdate = FALSE;
                                    
                                }
                            }
                        }
                    }
                }
            }
            if($ErreurUpdate == FALSE){
                $UpdateOffre = $connexion->prepare("UPDATE offre SET Nom_Offre = :nom, Description_Offre = :descrip, Date_Debut_Offre = :datedeb, Date_Fin_Offre = :datefin, Nombre_Place_Min_Offre = :nbplace, Id_Partenaire = :idpart WHERE Id_Offre = :id");
                $UpdateOffre->bindParam("id",$id);
                $UpdateOffre->bindParam("nom",$nom);
                $UpdateOffre->bindParam("descrip",$descrip);
                $UpdateOffre->bindParam("datedeb",$datedeb);
                $UpdateOffre->bindParam("datefin",$datefin);
                $UpdateOffre->bindParam("nbplace",$places);
                $UpdateOffre->bindParam("idpart",$partenaire);
                if($UpdateOffre->execute()){
                    echo "L'offre ".$nom." a bien été modifié.";
                }else{
                    echo "Erreur de mise à jour de l'offre";
                }
            }
           } 
    }
}else{
    header('Location : backoffice.php');
}

?>
