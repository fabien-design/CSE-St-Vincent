<?php 
    session_start();
    require('include/connexion_db.php');
    if(!empty($_POST['login'])){
        $erreursLogin = [];
        if(empty($_POST['login']['email'])){
            $erreursLogin['email'] = "Veuillez saisir une adresse mail.";
        }
        else{
            if(filter_var($_POST['login']['email'],FILTER_VALIDATE_EMAIL)){
                $email = $_POST['login']['email'];
            }
            else{
                $erreursLogin['email'] = "Veuillez saisir une adresse mail valide.";
            }
        }
        if(empty($_POST['login']['password'])){
            $erreursLogin['password'] = "Veuillez saisir une adresse mail.";
        }
        else{
            $password = $_POST['login']['password']; 
        }
        if(empty($erreursLogin)){
            try{
                $req = $connexion->prepare("SELECT * FROM utilisateur WHERE Email_Utilisateur = :email");
                $req->bindParam('email',$email);
                $req->execute();
                if($req->rowCount() == 1){
                    $utilisateur = $req->fetch();
                    if(password_verify($password,$utilisateur['Password_Utilisateur'])){
                        $_SESSION['Nom_Utilisateur'] = $utilisateur['Nom_Utilisateur'];
                        $reqDroit = $connexion->prepare("SELECT * FROM droit WHERE Id_Droit = :id");
                        $reqDroit->bindParam('id',$utilisateur['Id_Droit']);
                        $reqDroit->execute();
                        $droit = $reqDroit->fetch();
                        $_SESSION['Droit_Utilisateur'] = $droit['Libelle_Droit'];
                    }
                    else{
                        $erreursLogin['password'] = "Mot de passe incorrect.";
                    }
                }
                else{
                    $erreursLogin['email'] = "Adresse email incorrecte.";
                }
            }catch(Exception $e){
                echo "La requette n'a pas pu être faite.";
            }
    }

}
if(!empty($_POST['accueilEdit'])){
    $erreursaccueilEdit = [];
    if(empty($_POST['accueilEdit']['phone'])){
        $erreursaccueilEdit['phone'] = "Veuillez saisir un numéro de téléphone.";
    }else{
        if(preg_match('/^[0-9]{11}+$/', $_POST['accueilEdit']['phone'])) {
                $phone = $_POST['accueilEdit']['phone'];
        } else {
                $erreursaccueilEdit['phone'] = "Veuillez saisir un numéro de téléphone valide.";
        }
    }
    if(empty($_POST['accueilEdit']['email'])){
        $erreursaccueilEdit['email']="Veuillez saisir une adresse mail.";
    }else{
        if(filter_var($_POST['accueilEdit']['email'],FILTER_VALIDATE_EMAIL)){
            if(strlen($_POST['accueilEdit']['email']) >255){
                $erreursaccueilEdit['email']="L'adresse mail saisie est trop longue.";
            }else{
                $email = $_POST['accueilEdit']['email'];
            }
        }else{
            $erreursaccueilEdit['email']="Veuillez saisir une adresse mail valide.";
        }
    }
    if(empty($_POST['accueilEdit']['bureau'])){
        $erreursaccueilEdit['bureau'] = "Veuillez saisir un emplacement du bureau.";
    }else{
        $bureau = htmlspecialchars($_POST['accueilEdit']['bureau']);
        if(strlen($bureau) > 255){
            $erreursaccueilEdit['bureau'] = "Veuillez saisir un emplacement du bureau plus court.";
        }
    }
    if(empty($_POST['accueilEdit']['titre'])){
        $erreursaccueilEdit['titre'] = "Veuillez saisir un titre.";
    }else{
        if(strlen($_POST['accueilEdit']['titre']) > 255){
            $erreursaccueilEdit['titre'] = "Veuillez saisir un titre plus court.";
        }else{
            $titre = htmlspecialchars($_POST['accueilEdit']['titre']);
        }
    }
    if(empty($_POST['accueilEdit']['description'])){
        $erreursaccueilEdit['texte'] = "Veuillez saisir un texte.";
    }else{
        if(strlen($_POST['accueilEdit']['description'])>3000){
            $erreursaccueilEdit['texte'] = "Veuillez saisir un texte de moins de 3000 caractères.";
        }else{
            $description = htmlspecialchars($_POST['accueilEdit']['description']); 
        }
    }
    if(empty($erreursaccueilEdit)){
        try{
            $req = $connexion->prepare("UPDATE info_accueil SET Num_Tel_Info_Accueil = :phone, Email_Info_Accueil = :email, Emplacement_Bureau_Info_Accueil = :bureau, Titre_Info_Accueil = :titre, Texte_Info_Accueil = :descrip");
            $req->bindParam('phone',$phone);
            $req->bindParam('email',$email);
            $req->bindParam('bureau',$bureau);
            $req->bindParam('titre',$titre);
            $req->bindParam('descrip',$description);
            $req->execute();
            $msgvalidation = "<div class='msgvalide'>
                La modification a bien été effectuée.
            </div>";
        }catch(Exception $e){
            echo "Erreur lors de la modification";
        }
    }
}


// CODE MODAL POUR SUPPRESSION D'UN PARTENAIRE


if(isset($_GET['modalSupprPartenaire'])){
    $req = $connexion->prepare("SELECT * FROM partenaire WHERE Id_Partenaire = :id");
    $req->bindParam('id',$_GET['modalSupprPartenaire']);
    $req->execute();
    $partenaire = $req->fetch();
    $reqImg = $connexion->prepare("SELECT Nom_Image FROM image WHERE Id_Image = :id");
    $reqImg->bindParam('id',$partenaire['Id_Image']);
    $reqImg->execute();
    $imgPart = $reqImg->fetch();
     ?>
    <div id="modalSupprPartenaire" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div class="title">
                <h2><?php echo $partenaire['Nom_Partenaire']; ?></h2>
            </div>
            <div class="modalBox"> <?php
                if(!empty($partenaire["Id_Image"])){?>
                <img src="assets/<?= $imgPart['Nom_Image']; ?>" alt="Image <?php echo $partenaire['Nom_Partenaire']; ?>">
                <?php } ?>
                <div class="supprBox">
                    <p>Êtes-vous sûr de vouloir supprimer ce partenaire ?</p>
                    <div class="supprBtn">
                        <form id="formSupprPartenaire">
                            <input type="hidden" name="idPart" value="<?php echo $partenaire['Id_Partenaire'] ?>">
                            <button type="submit" class="formSupprOui">OUI</button>
                        </form>
                        <button class="formSupprNon">NON</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
<?php
}

// CODE MODAL POUR MODIFIER UN PARTENAIRE

if(isset($_GET['modalModifPartenaire'])){
    $req = $connexion->prepare("SELECT * FROM partenaire WHERE Id_Partenaire = :id");
    $req->bindParam('id',$_GET['modalModifPartenaire']);
    $req->execute();
    $partenaire = $req->fetch();
    $reqImg = $connexion->prepare("SELECT Nom_Image FROM image WHERE Id_Image = :id");
    $reqImg->bindParam('id',$partenaire['Id_Image']);
    $reqImg->execute();
    $imgPart = $reqImg->fetch();
     ?>
    <div id="modalModifPartenaire" class="modal">
        <div class="modal-content">
            <span class="closeModif">&times;</span>
            <div class="formBox">
                <form id="formModifPartenaire" enctype="multipart/form-data" method="POST">
                    <input type="hidden" name="idPart" value="<?php echo $partenaire['Id_Partenaire'] ?>">
                    <h1 style="margin-bottom:20px;">Modification du Partenaire</h1>

                    <label for="nompart">Nom* :</label>
                    <input type="text" name="nompart"  placeholder="Le nom du Partenaire." value="<?php echo $partenaire['Nom_Partenaire'] ?>">

                    <label for="descrippart">Description* :</label>
                    <textarea name="descrippart" cols="30" rows="10" placeholder="La description du Partenaire."><?php echo $partenaire['Description_Partenaire'] ?></textarea>

                    <label for="lienpart">Lien* :</label>
                    <input type="text" name="lienpart"  placeholder="Le lien du Partenaire." value="<?php echo $partenaire['Lien_Partenaire'] ?>">

                    <label for="imgpart">Image* :</label>
                    <div class="imgBox">
                        <div class="edit-button">
                            <img src="assets/edit-button.png" alt="edit-button" id="edit-button-img">
                            <input type="file" name="imgpart">
                        </div>
                        <img src="assets/<?php echo $imgPart['Nom_Image'] ?>" alt="Image du partenaire">
                        <input type="file" name="imgpart">
                    </div>

                    <div class="modifBtn">
                        <button type="submit" class="formModifOui">OUI</button></form>
                        <button class="formModifNon">NON</button>
                    </div>
                
            </div>
            
        </div>

    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script>
        // Code Modal modif d'un partenaire
        var modalModif = document.getElementById("modalModifPartenaire");
        var span = document.getElementsByClassName("closeModif")[0];
        var btnNon = document.getElementsByClassName("formModifNon")[0];
        var btnOui = document.getElementsByClassName("formModifOui")[0];
        var body = document.body;
        body.style.overflow= "hidden";
        // cacher modal au click de la croix ou du btn non
        span.onclick = function() {
            modalModif.style.display = "none";
            history.pushState(null, null, window.location.href.split("&")[0]);
            body.style.overflow = "auto";
        }
        btnNon.onclick = function(e) {
            e.preventDefault();
            modalModif.style.display = "none";
            history.pushState(null, null, window.location.href.split("&")[0]);
            body.style.overflow = "auto";
        }
        btnOui.onclick = function() {
            history.pushState(null, null, window.location.href.split("&")[0]);
            setTimeout(function() {modalModif.style.display = "none";}, 2000);
            body.style.overflow = "auto";
        }
        window.onclick = function(event) {
        if (event.target == modalModif) {
            modalModif.style.display = "none";
            history.pushState(null, null, window.location.href.split("&")[0]);
            body.style.overflow = "auto";
        }
        }
        
        // Code Jquery en AJAX pour la modif d'un partenaire

        $(document).ready(function(){
            $("#formModifPartenaire").submit(function(e){
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "modifPartenaire.php",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response){
                        alert(response);
                        setTimeout(function() {
                            location.reload(true);
                        }, 2000);

                    },
                    error: function(xhr, status, error) {
                        alert("Une erreur s'est produite lors de la requête AJAX : " + xhr.responseText);
                    }
                });
            }); 
        });
    </script>
<?php
}

// CODE MODAL POUR AJOUTER UN PARTENAIRE

if(isset($_GET['modalAjoutPartenaire'])){
     ?>
    <div id="modalAjoutPartenaire" class="modal">
        <div class="modal-content">
            <span class="closeAjout">&times;</span>
            <div class="formBox">
                <form id="formAjoutPartenaire" enctype="multipart/form-data" method="POST">
                    <label for="nompart">Nom* :</label>
                    <input type="text" name="nompart" placeholder="Nom du partenaire">

                    <label for="descrippart">Description* :</label>
                    <textarea name="descrippart" cols="30" rows="10" placeholder="Description du partenaire"></textarea>

                    <label for="lienpart">Lien* :</label>
                    <input type="url" name="lienpart" placeholder="Lien du partenaire">

                    <label for="imgpart">Image* :</label>
                    <div class="imgBox">
                        <input type="file" name="imgpart">
                    </div>

                    <div class="ajoutBtn">
                        <button type="submit" class="formAjoutOui">OUI</button></form>
                        <button class="formAjoutNon">NON</button>
                    </div>
                
            </div>
            
        </div>

    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script>
        // Code Modal modif d'un partenaire
        var modalAjout = document.getElementById("modalAjoutPartenaire");
        var span = document.getElementsByClassName("closeAjout")[0];
        var btnNon = document.getElementsByClassName("formAjoutNon")[0];
        var btnOui = document.getElementsByClassName("formAjoutOui")[0];
        // cacher modal au click de la croix ou du btn non
        span.onclick = function() {
            modalAjout.style.display = "none";
            history.pushState(null, null, window.location.href.split("&")[0]);
        }
        btnNon.onclick = function(e) {
            e.preventDefault();
            modalAjout.style.display = "none";
            history.pushState(null, null, window.location.href.split("&")[0]);
        }
        btnOui.onclick = function() {
            history.pushState(null, null, window.location.href.split("&")[0]);
            setTimeout(function() {modalAjout.style.display = "none";}, 2000);
        }
        window.onclick = function(event) {
        if (event.target == modalAjout) {
            modalAjout.style.display = "none";
            history.pushState(null, null, window.location.href.split("&")[0]);
        }
        }
        
        // Code Jquery en AJAX pour l'ajout d'un partenaire

        $(document).ready(function(){
            $("#formAjoutPartenaire").submit(function(e){
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "ajoutPartenaire.php",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response){
                        alert(response);
                        setTimeout(function() {
                            location.reload(true);
                        }, 2000);

                    },
                    error: function(xhr, status, error) {
                        alert("Une erreur s'est produite lors de la requête AJAX : " + xhr.responseText);
                    }
                });
            }); 
        });
    </script>
<?php
}

// CODE MODAL POUR AJOUTER UNE OFFRE 

if(isset($_GET['modalAjoutBilletterie'])){
    ?>
   <div id="modalAjoutBilletterie" class="modal">
       <div class="modal-content">
           <span class="closeAjout">&times;</span>
           <div class="formBox">
               <form id="formAjoutBilletterie" enctype="multipart/form-data" method="POST">
                   <label for="nomoffre">Nom* :</label>
                   <input type="text" name="nomoffre" placeholder="Nom de l'Offre">

                    <label for="descripoffre">Description* :</label>
                    <textarea name="descripoffre" cols="30" rows="10" placeholder="Description de l'Offre"></textarea>
                    
                    <div class="datesoffre">
                        <label for="datedeboffre">Date de début de l'offre* :</label>
                        <input type="date" name="datedeboffre" id="datedeboffre">
                        <label for="datefinoffre">Date de fin de l'offre* :</label>
                        <input type="date" name="datefinoffre" id="datefinoffre">
                    </div>

                    <label for="placeoffre">Nombre de place minimum* :</label>
                    <input type="number" name="placeoffre" placeholder="place de l'Offre" value="0" min="0">

                   <label for="partoffre">Nom du partenaire* :</label>
                   <select name="partoffre" id="partoffre">
                        <?php 
                            $reqPart = $connexion->prepare("SELECT * FROM partenaire");
                            $reqPart->execute();
                            $Part = $reqPart->fetchAll();
                            foreach($Part as $part){ ?>
                                <option value="<?= $part['Id_Partenaire'] ?>"><?= $part['Nom_Partenaire'] ?></option>
                           <?php }
                        ?>
                   </select>

                   <label for="imgoffre">Image* (Minimum une) :</label>
                   <div class="imgBox">
                       <input type="file" name="imgoffre[]">
                       <input type="file" name="imgoffre[]">
                       <input type="file" name="imgoffre[]">
                       <input type="file" name="imgoffre[]">
                   </div>

                   <div class="ajoutBtn">
                       <button type="submit" class="formAjoutOui">OUI</button></form>
                       <button class="formAjoutNon">NON</button>
                   </div>
               
           </div>
           
       </div>

   </div>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
   <script>
       // Code Modal modif d'ajout d'une offre
       var modalAjout = document.getElementById("modalAjoutBilletterie");
       var span = document.getElementsByClassName("closeAjout")[0];
       var btnNon = document.getElementsByClassName("formAjoutNon")[0];
       var btnOui = document.getElementsByClassName("formAjoutOui")[0];
       // cacher modal au click de la croix ou du btn non
       span.onclick = function() {
           modalAjout.style.display = "none";
           history.pushState(null, null, window.location.href.split("&")[0]);
       }
       btnNon.onclick = function(e) {
           e.preventDefault();
           modalAjout.style.display = "none";
           history.pushState(null, null, window.location.href.split("&")[0]);
       }
       btnOui.onclick = function() {
           history.pushState(null, null, window.location.href.split("&")[0]);
           setTimeout(function() {modalAjout.style.display = "none";}, 2000);
       }
       window.onclick = function(event) {
       if (event.target == modalAjout) {
           modalAjout.style.display = "none";
           history.pushState(null, null, window.location.href.split("&")[0]);
       }
       }
       
       // Code Jquery en AJAX pour l'ajout d'une offre

       $(document).ready(function(){
           $("#formAjoutBilletterie").submit(function(e){
               e.preventDefault();
               var formData = new FormData(this);
               $.ajax({
                   type: "POST",
                   url: "ajoutOffre.php",
                   data: formData,
                   contentType: false,
                   processData: false,
                   success: function(response){
                       alert(response);
                       setTimeout(function() {
                           location.reload(true);
                       }, 2000);

                   },
                   error: function(xhr, status, error) {
                       alert("Une erreur s'est produite lors de la requête AJAX : " + xhr.responseText);
                   }
               });
           }); 
       });
   </script>
<?php
}

// CODE MODAL POUR MODIFIER UNE OFFRE

if(isset($_GET['modalModifBilletterie'])){
    $req = $connexion->prepare("SELECT * FROM offre WHERE Id_Offre = :id");
    $req->bindParam('id',$_GET['modalModifBilletterie']);
    $req->execute();
    $offre = $req->fetch();
    $reqImg = $connexion->prepare("SELECT * FROM image WHERE Id_Image in (SELECT Id_Image FROM offre_image WHERE Id_Offre = :id)");
    $reqImg->bindParam('id',$offre['Id_Offre']);
    $reqImg->execute();
    $imgOffre = $reqImg->fetchAll();
     ?>
    <div id="modalModifBilletterie" class="modal">
        <div class="modal-content">
            <span class="closeModif">&times;</span>
            <div class="formBox">
               <form id="formModifBilletterie" enctype="multipart/form-data" method="POST">
                   <input type="hidden" name="idoffre" value="<?php echo $offre['Id_Offre'] ?>">
                   <label for="nomoffre">Nom* :</label>
                   <input type="text" name="nomoffre" placeholder="Nom de l'Offre" value="<?php echo $offre['Nom_Offre'] ?>">

                    <label for="descripoffre">Description* :</label>
                    <textarea name="descripoffre" cols="30" rows="10" placeholder="Description de l'Offre" value="<?php echo $offre['Description_Offre'] ?>"></textarea>
                    
                    <div class="datesoffre">
                        <label for="datedeboffre">Date de début de l'offre* :</label>
                        <input type="date" name="datedeboffre" id="datedeboffre" value="<?php echo $offre['Date_Debut_Offre'] ?>">
                        <label for="datefinoffre">Date de fin de l'offre* :</label>
                        <input type="date" name="datefinoffre" id="datefinoffre" value="<?php echo $offre['Date_Fin_Offre'] ?>">
                    </div>

                    <label for="placeoffre">Nombre de place minimum* :</label>
                    <input type="number" name="placeoffre" placeholder="place de l'Offre" value="<?php echo $offre['Nombre_Place_Min_Offre'] ?>" min="0" >

                   <label for="partoffre">Nom du partenaire* :</label>
                   <select name="partoffre" id="partoffre">
                        <?php 
                            $reqPart = $connexion->prepare("SELECT * FROM partenaire");
                            $reqPart->execute();
                            $Part = $reqPart->fetchAll();
                            foreach($Part as $part){ 
                                if($part['Id_Partenaire'] == $offre['Id_Partenaire']){
                                ?>
                                    <option value="<?= $part['Id_Partenaire'] ?>" selected><?= $part['Nom_Partenaire'] ?></option>
                           <?php 
                                }else{ ?>
                                    <option value="<?= $part['Id_Partenaire'] ?>" ><?= $part['Nom_Partenaire'] ?></option>
                               <?php }
                           }
                        ?>
                   </select>

                   <label for="imgoffre">Image*:</label>
                   <div class="imgBox">
                        <?php 
                            $nb = 0;
                            foreach($imgOffre as $imgO){ ?>
                                <div class="Box">
                                    <div class="edit-button">
                                        <img src="assets/edit-button.png" alt="edit-button" id="edit-button-img">
                                        <input type="file" name="imgoffre[]" onchange="document.getElementById('ImgPrev<?= $nb ?>').src = window.URL.createObjectURL(this.files[0])" value="assets/<?= $imgO['Nom_Image'] ?>">
                                    </div>
                                    <img id="ImgPrev<?= $nb ?>" src="assets/<?= $imgO['Nom_Image'] ?>" alt="Image(s) de l'offre">
                                    <input type="file" name="imgoffre[]" onchange="document.getElementById('ImgPrev<?= $nb ?>').src = window.URL.createObjectURL(this.files[0])" value="assets/<?= $imgO['Nom_Image'] ?>">
                                </div>
                          <?php 
                            $nb++;
                           }
                           if($nb<3){
                                $nbmax = 3-$nb;
                                for($i=0;$i<=$nbmax;$i++){?>
                                    <div class="Box">
                                        <div class="edit-button">
                                            <img src="assets/edit-button.png" alt="edit-button" id="edit-button-img">
                                            <input type="file" name="imgoffre[]" onchange="document.getElementById('ImgPrev<?= $nb ?>').src = window.URL.createObjectURL(this.files[0])" value="assets/<?= $imgO['Nom_Image'] ?>">
                                        </div>
                                        <img id="ImgPrev<?= $nb ?>" src="assets/individual-man.png" alt="Image(s) de l'offre">
                                        <input type="file" name="imgoffre[]" onchange="document.getElementById('ImgPrev<?= $nb ?>').src = window.URL.createObjectURL(this.files[0])" value="assets/<?= $imgO['Nom_Image'] ?>">
                                    </div>
                            <?php }
                           }
                        ?>
                   </div>

                   <div class="modifBtn">
                       <button type="submit" class="formModifOui">OUI</button></form>
                       <button class="formModifNon">NON</button>
                   </div>
               
           </div>
            
        </div>

    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script>
        // Code Modal modif d'un partenaire
        var modalModif = document.getElementById("modalModifBilletterie");
        var span = document.getElementsByClassName("closeModif")[0];
        var btnNon = document.getElementsByClassName("formModifNon")[0];
        var btnOui = document.getElementsByClassName("formModifOui")[0];
        var body = document.body;
        body.style.overflow= "hidden";
        // cacher modal au click de la croix ou du btn non
        span.onclick = function() {
            modalModif.style.display = "none";
            history.pushState(null, null, window.location.href.split("&")[0]);
            body.style.overflow = "auto";
        }
        btnNon.onclick = function(e) {
            e.preventDefault();
            modalModif.style.display = "none";
            history.pushState(null, null, window.location.href.split("&")[0]);
            body.style.overflow = "auto";
        }
        btnOui.onclick = function() {
            history.pushState(null, null, window.location.href.split("&")[0]);
            setTimeout(function() {modalModif.style.display = "none";}, 2000);
            body.style.overflow = "auto";
        }
        window.onclick = function(event) {
        if (event.target == modalModif) {
            modalModif.style.display = "none";
            history.pushState(null, null, window.location.href.split("&")[0]);
            body.style.overflow = "auto";
        }
        }
        
        // Code Jquery en AJAX pour la modif d'un partenaire

        $(document).ready(function(){
            $("#formModifBilletterie").submit(function(e){
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "modifBilletterie.php",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response){
                        alert(response);
                        setTimeout(function() {
                            location.reload(true);
                        }, 2000);

                    },
                    error: function(xhr, status, error) {
                        alert("Une erreur s'est produite lors de la requête AJAX : " + xhr.responseText);
                    }
                });
            }); 
        });
    </script>
<?php
}

?>

<!-- Debut Page HTML -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backoffice</title>
    <link rel="stylesheet" href="styleBackoffice.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
</head>
<body> 
<?php
if(empty($_SESSION['Nom_Utilisateur']) && empty($_SESSION['Droit_Utilisateur'])){
?>

    <div class="formulaire">
        <form action="backoffice.php" method="post" name="login">
            <div class="formGroup">
                <h1 class="formTitle">Connexion au backoffice</h1>
                <?= isset($erreursLogin['email']) ||isset($erreursLogin['password']) ? "La combinaison email / mot de passe n'existe pas." : null ?>
                <div class="inputGroup">
                    <input type="email" id="email" required="" name="login[email]">
                    <label for="email">Email*</label>
                    
                </div>
                <div class="inputGroup">
                    <input type="password" id="password" required="" name="login[password]">
                    <label for="password">Mot de passe*</label>
                </div>
                <div class="btnGroup">
                    <button type="submit">Se connecter</button>
                </div>
            </div>
        </form>
    </div>
<?php
}else{ 
    if($_SESSION['Droit_Utilisateur'] === "Administrateur"){ ?>
    <header>
        <div class="graynav"></div>
        <nav>
            <div class="bgLogo">
                <img src="assets/logo_lycee.png" alt="logo du lycée">
            </div>
            <ul> <?php
            if(empty($_GET) || $_GET['page'] === "accueil"){?>
                <li><a href="backoffice.php?page=accueil" class="active">Accueil</a></li>
            <?php }else{ ?>
                <li><a href="backoffice.php?page=accueil">Accueil</a></li>
            <?php }
            if(!empty($_GET) && $_GET['page'] === "partenaires"){ ?>
                <li><a href="backoffice.php?page=partenaires" class="active">Partenariats</a></li>
            <?php }else{ ?>
                <li><a href="backoffice.php?page=partenaires">Partenariats</a></li>
            <?php }if(!empty($_GET) && $_GET['page'] === "billetterie"){ ?>
                <li><a href="backoffice.php?page=billetterie" class="active">Billetterie</a></li>
            <?php }else{ ?> 
                <li><a href="backoffice.php?page=billetterie">Billetterie</a></li>
            <?php }if(!empty($_GET) && $_GET['page'] === "message"){?>
                <li><a href="backoffice.php?page=message" class="active">Messages</a></li>
            <?php }else{?>
                <li><a href="backoffice.php?page=message">Messages</a></li>
            <?php } ?>
            </ul>
        </nav>
    </header>
        <main> <?php
        if(!empty($_GET) && $_GET['page'] !== "accueil"){

            if(!empty($_GET['page']) && $_GET['page'] !== "accueil"){
                if($_GET['page'] === "partenaires"){?>

                    <div class="partenaires">

                    <?= isset($msgvalidation) ? $msgvalidation : null ?>
                    <div class="titlePage"> 
                        <h1>Modifier les partenaires</h1>
                    </div>
                    <div class="tablepartenaires">
                    <table>
                        <thead>
                            <tr>
                                <th class="tableNom">Nom</th>
                                <th class="tableDescription">Description</th>
                                <th class="tableLien">Lien du site</th>
                                <th class="tableImage">Image</th>
                                <th class="tableAction">Action</th>
                                <th class="addPart"><a href="backoffice.php?page=partenaires&modalAjoutPartenaire=partenaire"><div>Ajouter</div></a></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            try{
                                $req = $connexion->prepare("SELECT * FROM partenaire");
                                $req->execute();
                                $partenaires= $req->fetchAll();
                                foreach($partenaires as $partenaire){
                                    if(!empty($partenaire["Id_Image"])){
                                        $req = $connexion->prepare("SELECT Nom_Image FROM image WHERE Id_Image = :id");
                                        $req->bindParam('id',$partenaire["Id_Image"]);
                                        $req->execute();
                                        $imgPart= $req->fetch();}
                                    ?>
                                    <tr>
                                        <td><?php echo $partenaire["Nom_Partenaire"] ?></td>
                                        <td><?php echo $partenaire["Description_Partenaire"] ?></td>
                                        <td><?php echo $partenaire["Lien_Partenaire"] ?></td>
                                        <td class="imgPart"><?= !empty($partenaire["Id_Image"]) ? '<img src="assets/'.$imgPart["Nom_Image"].'" alt="Image du partenaire">' : "Aucune image" ?></td>
                                        <td class="actionBtn">  
                                            <a href="backoffice.php?page=partenaires&modalModifPartenaire=<?= $partenaire["Id_Partenaire"]; ?>" class="modifBtn">Modifier</a>
                                            <a href="backoffice.php?page=partenaires&modalSupprPartenaire=<?= $partenaire["Id_Partenaire"]; ?>" class="supprBtn">Supprimer</a>
                                        </td>
                                    </tr>
                               <?php }
                            }catch(Exception $e){
                                echo "Erreur lors de l'affichage";
                            }

                            ?>
                        </tbody>
                    </table>
                    </div>

                    </div>
                    
                    


          <?php }else if($_GET['page'] === "billetterie"){ ?>
                    
                    <div class="billetterie">

                    <?= isset($msgvalidation) ? $msgvalidation : null ?>
                    <div class="titlePage"> 
                        <h1>Modifier les offres</h1>
                    </div>
                    <div class="tableoffres">
                    <table>
                        <thead>
                            <tr>
                                <th class="tableNom">Nom</th>
                                <th class="tableDescription">Description</th>
                                <th class="tableDate">Dates</th>
                                <th class="tablePartenaire">Partenaire</th>
                                <th class="tablePlace">Nombres de places</th>
                                <th class="tableImage">Nombres d'images</th>
                                <th class="tableAction">Action</th>
                                <th class="addPart"><a href="backoffice.php?page=billetterie&modalAjoutBilletterie=offre"><div>Ajouter</div></a></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            try{
                                $req = $connexion->prepare("SELECT * FROM offre");
                                $req->execute();
                                $offres= $req->fetchAll();
                                foreach($offres as $offre){
                                    if(!empty($offre["Id_Partenaire"])){
                                        $reqPart = $connexion->prepare("SELECT Nom_Partenaire FROM partenaire WHERE Id_Partenaire = :idpart");
                                        $reqPart->bindParam("idpart",$offre["Id_Partenaire"]);
                                        $reqPart->execute();
                                        $NomPart = $reqPart->fetch();
                                    }
                                    $reqImgOffre = $connexion->prepare("SELECT * FROM offre_image WHERE Id_Offre = :idoffre");
                                    $reqImgOffre->bindParam("idoffre",$offre['Id_Offre']);
                                    $reqImgOffre->execute();
                                    $ImgOffres = $reqImgOffre->fetchAll();
                                    $nbImgOffre = 0;
                                    foreach($ImgOffres as $img){
                                        $nbImgOffre += 1;
                                    }
                                    ?>
                                    <tr>
                                        <td><?php echo $offre["Nom_Offre"] ?></td>
                                        <td class="colonneDescription"><?php echo $offre["Description_Offre"] ?></td>
                                        <td><?php echo date_format(DateTime::createFromFormat('Y-m-d', $offre["Date_Debut_Offre"]),"d-m-Y")." - ".date_format(DateTime::createFromFormat('Y-m-d', $offre["Date_Fin_Offre"]),"d-m-Y") ?></td>
                                        <td><?= !empty($offre["Id_Partenaire"]) ? $NomPart['Nom_Partenaire'] : "Aucun partenaire Associé" ?></td>
                                        <td><?php echo $offre['Nombre_Place_Min_Offre'] ?></td>
                                        <td><?php echo $nbImgOffre." image(s)" ?></td>
                                        <td class="actionBtn">  
                                            <a href="backoffice.php?page=billetterie&modalModifBilletterie=<?= $offre["Id_Offre"]; ?>" class="modifBtn">Modifier</a>
                                            <a href="backoffice.php?page=billetterie&modalSupprBilletterie=<?= $offre["Id_Offre"]; ?>" class="supprBtn">Supprimer</a>
                                        </td>
                                    </tr>
                               <?php }
                            }catch(Exception $e){
                                echo "Erreur lors de l'affichage";
                            }

                            ?>
                        </tbody>
                    </table>
                    </div>

                    </div>

               <?php }else if($_GET['page'] === "message"){
                    /**/ 
                }
            }

    }else{ ?>
    <div class="accueil">
        <?= isset($msgvalidation) ? $msgvalidation : null ?>
        <div class="titlePage"> 
            <h1>Modifier les informations de la page d'accueil</h1>
        </div>
        <div class="formEdit">
            <form action="backoffice.php" method="post" name="accueilEdit">
                <?php 
                    $req = $connexion->prepare("SELECT * FROM info_accueil");
                    $req->execute();
                    $infoAccueil = $req->fetch();
                ?>
                    <label for="tel">Numéro de téléphone<span>*</span> :</label>
                    <input type="tel" name="accueilEdit[phone]" id="phone" required="" value="<?php echo $infoAccueil['Num_Tel_Info_Accueil'] ?>">
                    <?= isset($erreursaccueilEdit['phone']) ? $erreursaccueilEdit['phone'] : null ?>

                    <label for="email">Email<span>*</span> :</label>
                    <input type="email" name="accueilEdit[email]" id="email" required="" value="<?php echo $infoAccueil['Email_Info_Accueil'] ?>">
                    <?= isset($erreursaccueilEdit['email']) ? $erreursaccueilEdit['email'] : null ?>
                    
                    <label for="bureau">Emplacement du Bureau<span>*</span> :</label>
                    <textarea type="text" name="accueilEdit[bureau]" id="bureau" required=""><?php echo $infoAccueil['Emplacement_Bureau_Info_Accueil'] ?></textarea>
                    <?= isset($erreursaccueilEdit['bureau']) ? $erreursaccueilEdit['bureau'] : null ?>
                    
                    <label for="titre">Titre de la page<span>*</span> :</label>
                    <input type="text" name="accueilEdit[titre]" id="titre" required="" value="<?php echo $infoAccueil['Titre_Info_Accueil'] ?>">
                    <?= isset($erreursaccueilEdit['titre']) ? $erreursaccueilEdit['titre'] : null ?>
                    
                    <label for="description">Description de la page<span>*</span> :</label>
                    <textarea type="text" name="accueilEdit[description]" id="description" required=""><?php echo $infoAccueil['Texte_Info_Accueil'] ?></textarea>
                    <?= isset($erreursaccueilEdit['description']) ? $erreursaccueilEdit['description'] : null ?>
                <div class="btnGroup">
                    <button type="submit"><div class="bghover"></div><p>Valider</p></button>
                </div>
            </form>
        </div>
    </div>
    <?php 
    }
     ?>
            
        </main>
    <?php
    }else{
        header('Location: index.html');
    }

}?>

<!-- Fin Page HTML -->
<script src="scriptBackoffice.js"></script>
</body>
</html>