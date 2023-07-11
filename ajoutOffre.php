<?php

require('include/connexion_db.php');
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
    if($datedeb > $datefin){
        $erreurs['date'] = "Date début suppérieur à la date de fin.";
    }

    if(empty($erreurs)){

        if (isset($_FILES['imgoffre'])) {
            $req = $connexion->prepare("INSERT INTO offre (Nom_Offre, Description_Offre, Date_Debut_Offre, Date_Fin_Offre, Nombre_Place_Min_Offre, Id_Partenaire) VALUES (:nom, :descrip, :datedeb, :datefin, :nbplace, :idpart)");
            $req->bindParam("nom",$nom);
            $req->bindParam("descrip",$descrip);
            $req->bindParam("datedeb",$datedeb);
            $req->bindParam("datefin",$datefin);
            $req->bindParam('nbplace', $places);
            $req->bindParam('idpart', $_POST['partoffre']);
            if($req->execute()){
                $idoffre = $connexion->lastInsertId();
                $pos = 0;
                foreach($_FILES['imgoffre']["name"] as $img){
                    if(!empty($_FILES['imgoffre']["name"][$pos]) && $_FILES['imgoffre']["name"][$pos] !== ""){
                        $nom_image = $_FILES['imgoffre']["name"][$pos];
                        $nomexplode = explode(".",$nom_image);
                        $ext = end($nomexplode);
                        $nom_image = substr($nom_image,0,-(strlen($ext)+1));
                        $nom_image .= "-".rand(1000,9999).".".$ext;
                        move_uploaded_file($_FILES['imgoffre']["tmp_name"][$pos], 'assets/' . $nom_image);
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
                        $pos+=1;
                    }
                }
                echo "L'offre ".$nom." a bien été ajouté.";
            }else{
                echo "Erreur: " . $req . "<br>" . $connexion->error;
            }
            
        }else{
            echo "Veuillez ajouter au moins une image";
        }

    }else{
        $erreur_message = "Veuillez remplir correctement tous les champs :\n";
        foreach ($erreurs as $cle => $valeur) {
            $erreur_message .= "-> " . $valeur . "\n";
        }
        echo $erreur_message;
    }
}else{
    header('Location : backoffice.php');
}
?>
