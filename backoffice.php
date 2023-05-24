<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backoffice</title>
    <link rel="stylesheet" href="styles/styleBackoffice.css">
    <link rel="icon" href="assets/sv_logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
</head>

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
if(!empty($_SESSION['Nom_Utilisateur']) && !empty($_SESSION['Droit_Utilisateur']) && $_SESSION['Droit_Utilisateur'] === "Administrateur"){ 
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script>
        // Code Modal suppression d'un partenaire
        var modalSuppr = document.getElementById("modalSupprPartenaire");
        var span = document.getElementsByClassName("close")[0];
        var btnNon = document.getElementsByClassName("formSupprNon")[0];
        var btnOui = document.getElementsByClassName("formSupprOui")[0];
        // cacher modal au click de la croix ou du btn non
        span.onclick = function() {
        modalSuppr.style.display = "none";
        //suppr get dans l'url -- Depend si &numpage existe ou pas
            $searchGet = new URLSearchParams(window.location.href);
            if($searchGet.has("numpage")){
                history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
            }
            else{
                history.pushState(null, null, window.location.href.split("&")[0]);
            }
        }
        btnNon.onclick = function() {
            modalSuppr.style.display = "none";
            //suppr get dans l'url -- Depend si &numpage existe ou pas
            $searchGet = new URLSearchParams(window.location.href);
            if($searchGet.has("numpage")){
                history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
            }
            else{
                history.pushState(null, null, window.location.href.split("&")[0]);
            }
        }
        btnOui.onclick = function() {
            //suppr get dans l'url -- Depend si &numpage existe ou pas
            $searchGet = new URLSearchParams(window.location.href);
            if($searchGet.has("numpage")){
                history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
            }
            else{
                history.pushState(null, null, window.location.href.split("&")[0]);
            }
            setTimeout(modalSuppr.style.display = "none", 2000);
        }
        window.onclick = function(event) {
        if (event.target == modalSuppr) {
            modalSuppr.style.display = "none";
            //suppr get dans l'url -- Depend si &numpage existe ou pas
            $searchGet = new URLSearchParams(window.location.href);
            if($searchGet.has("numpage")){
                history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
            }
            else{
                history.pushState(null, null, window.location.href.split("&")[0]);
            }
        }
        }

        // Code Jquery en AJAX pour la suppression d'un partenaire

        $(document).ready(function(){
            $("#formSupprPartenaire").submit(function(e){
            e.preventDefault();
            var formData = $("#formSupprPartenaire").serialize();
            $.ajax({
                type: "POST",
                url: "supprPartenaire.php",
                data: formData,
                success: function(response){
                alert(response);
                setTimeout(location.reload(true) , 3000);
                }
            });
            });
        });
    </script>
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
                            <input type="file" name="imgpart" onchange="document.getElementById('imgPrev').src = window.URL.createObjectURL(this.files[0])">
                        </div>
                        <?php if(!empty($imgPart['Nom_Image'])){ ?>
                        <img id="imgPrev" src='assets/<?php echo $imgPart['Nom_Image'] ?>' alt="Image du partenaire" > 
                        <?php }
                        else{
                             ?>
                            <img id="imgPrev" src="assets/individual-man.png" alt="Image(s) de l'offre"> 
                    <?php
                        } ?> 
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
            //suppr get dans l'url -- Depend si &numpage existe ou pas
            $searchGet = new URLSearchParams(window.location.href);
            if($searchGet.has("numpage")){
                history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
            }
            else{
                history.pushState(null, null, window.location.href.split("&")[0]);
            }
            body.style.overflow = "auto";
        }
        btnNon.onclick = function(e) {
            e.preventDefault();
            modalModif.style.display = "none";
            //suppr get dans l'url -- Depend si &numpage existe ou pas
            $searchGet = new URLSearchParams(window.location.href);
            if($searchGet.has("numpage")){
                history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
            }
            else{
                history.pushState(null, null, window.location.href.split("&")[0]);
            }
            body.style.overflow = "auto";
        }
        btnOui.onclick = function() {
            //suppr get dans l'url -- Depend si &numpage existe ou pas
            $searchGet = new URLSearchParams(window.location.href);
            if($searchGet.has("numpage")){
                history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
            }
            else{
                history.pushState(null, null, window.location.href.split("&")[0]);
            }
            setTimeout(function() {modalModif.style.display = "none";}, 2000);
            body.style.overflow = "auto";
        }
        window.onclick = function(event) {
        if (event.target == modalModif) {
            modalModif.style.display = "none";
            //suppr get dans l'url -- Depend si &numpage existe ou pas
            $searchGet = new URLSearchParams(window.location.href);
            if($searchGet.has("numpage")){
                history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
            }
            else{
                history.pushState(null, null, window.location.href.split("&")[0]);
            }
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
                        <div class="Box">
                            <div class="edit-button">
                                <img src="assets/edit-button.png" alt="edit-button" id="edit-button-img">
                                <input type="file" name="imgpart" onchange="document.getElementById('ImgPrev').src = window.URL.createObjectURL(this.files[0])" value="assets/<?= $imgO['Nom_Image'] ?>">
                            </div>
                            <img id="ImgPrev" src="assets/individual-man.png" alt="Image(s) de l'offre">
                        </div>
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
            //suppr get dans l'url -- Depend si &numpage existe ou pas
            $searchGet = new URLSearchParams(window.location.href);
            if($searchGet.has("numpage")){
                history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
            }
            else{
                history.pushState(null, null, window.location.href.split("&")[0]);
            }
        }
        btnNon.onclick = function(e) {
            e.preventDefault();
            modalAjout.style.display = "none";
            //suppr get dans l'url -- Depend si &numpage existe ou pas
            $searchGet = new URLSearchParams(window.location.href);
            if($searchGet.has("numpage")){
                history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
            }
            else{
                history.pushState(null, null, window.location.href.split("&")[0]);
            }
        }
        btnOui.onclick = function() {
            //suppr get dans l'url -- Depend si &numpage existe ou pas
            $searchGet = new URLSearchParams(window.location.href);
            if($searchGet.has("numpage")){
                history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
            }
            else{
                history.pushState(null, null, window.location.href.split("&")[0]);
            }
            setTimeout(function() {modalAjout.style.display = "none";}, 2000);
        }
        window.onclick = function(event) {
        if (event.target == modalAjout) {
            modalAjout.style.display = "none";
            //suppr get dans l'url -- Depend si &numpage existe ou pas
            $searchGet = new URLSearchParams(window.location.href);
            if($searchGet.has("numpage")){
                history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
            }
            else{
                history.pushState(null, null, window.location.href.split("&")[0]);
            }
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
                        <div>
                            <label for="datedeboffre">Date de début de l'offre* :</label>
                            <input type="date" name="datedeboffre" id="datedeboffre">
                        </div>
                        <div>
                            <label for="datefinoffre">Date de fin de l'offre* :</label>
                            <input type="date" name="datefinoffre" id="datefinoffre">
                        </div>
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
                        <?php
                        $nb1 = 0;
                        for($i=0;$i<4;$i++){?>
                        <div class="Box">
                            <div class="edit-button">
                                <img src="assets/edit-button.png" alt="edit-button" id="edit-button-img">
                                <input type="file" name="imgoffre[]" onchange="document.getElementById('ImgPrev<?= $nb1 ?>').src = window.URL.createObjectURL(this.files[0])" value="assets/<?= $imgO['Nom_Image'] ?>">
                            </div>
                            <img id="ImgPrev<?= $nb1 ?>" src="assets/individual-man.png" alt="Image(s) de l'offre">
                        </div>
                        <?php 
                            $nb1++;
                        } ?>
                        <!--
                       <input type="file" name="imgoffre[]">
                       <input type="file" name="imgoffre[]">
                       <input type="file" name="imgoffre[]"> -->
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
           //suppr get dans l'url -- Depend si &numpage existe ou pas
            $searchGet = new URLSearchParams(window.location.href);
            if($searchGet.has("numpage")){
                history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
            }
            else{
                history.pushState(null, null, window.location.href.split("&")[0]);
            }
       }
       btnNon.onclick = function(e) {
           e.preventDefault();
           modalAjout.style.display = "none";
           //suppr get dans l'url -- Depend si &numpage existe ou pas
            $searchGet = new URLSearchParams(window.location.href);
            if($searchGet.has("numpage")){
                history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
            }
            else{
                history.pushState(null, null, window.location.href.split("&")[0]);
            }
       }
       btnOui.onclick = function() {
           //suppr get dans l'url -- Depend si &numpage existe ou pas
            $searchGet = new URLSearchParams(window.location.href);
            if($searchGet.has("numpage")){
                history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
            }
            else{
                history.pushState(null, null, window.location.href.split("&")[0]);
            }
           setTimeout(function() {modalAjout.style.display = "none";}, 2000);
       }
       window.onclick = function(event) {
       if (event.target == modalAjout) {
           modalAjout.style.display = "none";
           //suppr get dans l'url -- Depend si &numpage existe ou pas
            $searchGet = new URLSearchParams(window.location.href);
            if($searchGet.has("numpage")){
                history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
            }
            else{
                history.pushState(null, null, window.location.href.split("&")[0]);
            }
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
                    <textarea name="descripoffre" cols="30" rows="10" placeholder="Description de l'Offre" value="<?php echo $offre['Description_Offre'] ?>"><?php echo $offre['Description_Offre'] ?></textarea>
                    
                    <div class="datesoffre">
                        <div>
                            <label for="datedeboffre">Date de début de l'offre* :</label>
                            <input type="date" name="datedeboffre" id="datedeboffre" value="<?php echo $offre['Date_Debut_Offre'] ?>">
                        </div>
                        <div>
                            <label for="datefinoffre">Date de fin de l'offre* :</label>
                            <input type="date" name="datefinoffre" id="datefinoffre" value="<?php echo $offre['Date_Fin_Offre'] ?>">
                        </div>
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
                                    <img class="deleteImg" src="assets/fermer.png" onclick="supprImg(<?= $imgO['Id_Image'] ?>)">
                                    <div class="edit-button">
                                        <img src="assets/edit-button.png" alt="edit-button" id="edit-button-img">
                                        <input type="file" name="imgoffre[]" onchange="document.getElementById('ImgPrev<?= $nb ?>').src = window.URL.createObjectURL(this.files[0])" value="assets/<?= $imgO['Nom_Image'] ?>">
                                    </div>
                                    <img id="ImgPrev<?= $nb ?>" src="assets/<?= $imgO['Nom_Image'] ?>" alt="Image(s) de l'offre">
                                </div>
                          <?php 
                            $nb++;
                           }
                           if($nb<4){
                                $nbmax = 3-$nb;
                                $nb1 = $nb;
                                for($i=0;$i<=$nbmax;$i++){?>
                                    <div class="Box">
                                        <div class="edit-button">
                                            <img src="assets/edit-button.png" alt="edit-button" id="edit-button-img">
                                            <input type="file" name="imgoffre[]" onchange="document.getElementById('ImgPrev<?= $nb1 ?>').src = window.URL.createObjectURL(this.files[0])" value="assets/<?= $imgO['Nom_Image'] ?>">
                                        </div>
                                        <img id="ImgPrev<?= $nb1 ?>" src="assets/individual-man.png" alt="Image(s) de l'offre">
                                    </div>
                            <?php 
                                $nb1++;
                            }
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
        // Code Modal modif d'une offre
        var modalModif = document.getElementById("modalModifBilletterie");
        var span = document.getElementsByClassName("closeModif")[0];
        var btnNon = document.getElementsByClassName("formModifNon")[0];
        var btnOui = document.getElementsByClassName("formModifOui")[0];
        var body = document.body;
        body.style.overflow= "hidden";
        // cacher modal au click de la croix ou du btn non
        span.onclick = function() {
            modalModif.style.display = "none";
            //suppr get dans l'url -- Depend si &numpage existe ou pas
            $searchGet = new URLSearchParams(window.location.href);
            if($searchGet.has("numpage")){
                history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
            }
            else{
                history.pushState(null, null, window.location.href.split("&")[0]);
            }
            body.style.overflow = "auto";
        }
        btnNon.onclick = function(e) {
            e.preventDefault();
            modalModif.style.display = "none";
            //suppr get dans l'url -- Depend si &numpage existe ou pas
            $searchGet = new URLSearchParams(window.location.href);
            if($searchGet.has("numpage")){
                history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
            }
            else{
                history.pushState(null, null, window.location.href.split("&")[0]);
            }
            body.style.overflow = "auto";
        }
        btnOui.onclick = function() {
            //suppr get dans l'url -- Depend si &numpage existe ou pas
            $searchGet = new URLSearchParams(window.location.href);
            if($searchGet.has("numpage")){
                history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
            }
            else{
                history.pushState(null, null, window.location.href.split("&")[0]);
            }
            setTimeout(function() {modalModif.style.display = "none";}, 2000);
            body.style.overflow = "auto";
        }
        window.onclick = function(event) {
        if (event.target == modalModif) {
            modalModif.style.display = "none";
            //suppr get dans l'url -- Depend si &numpage existe ou pas
            $searchGet = new URLSearchParams(window.location.href);
            if($searchGet.has("numpage")){
                history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
            }
            else{
                history.pushState(null, null, window.location.href.split("&")[0]);
            }
            body.style.overflow = "auto";
        }
        }

        function supprImg(n) {
            var formData = new FormData();
            formData.append('Id_Img', n);

            $.ajax({
                type: "POST",
                url: "BackofficePHP/supprImg.php",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response){
                        location.reload(true);
                    },
                error: function(xhr, status, error) {
                    alert("Une erreur s'est produite lors de la requête AJAX : " + xhr.responseText);
                }
            });
        }


        
        // Code Jquery en AJAX pour la modif d'une offre

        $(document).ready(function(){
            $("#formModifBilletterie").submit(function(e){
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "modifOffre.php",
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

// CODE MODAL POUR SUPPRIMER UNE OFFRE

if(isset($_GET['modalSupprBilletterie'])){
    $req = $connexion->prepare("SELECT * FROM offre WHERE Id_Offre = :id");
    $req->bindParam('id',$_GET['modalSupprBilletterie']);
    $req->execute();
    $offre = $req->fetch();
    ?>
    <div id="modalSupprBilletterie" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div class="title">
                <h2><?php echo $offre['Nom_Offre']; ?></h2>
            </div>
            <div class="modalBox">
            <?php 
            $SelectOffre_Img = $connexion->prepare("SELECT Nom_Image FROM image WHERE Id_Image in (SELECT Id_Image FROM offre_image WHERE Id_Offre = :idoffre)");
            $SelectOffre_Img->bindParam("idoffre",$_GET['modalSupprBilletterie']);
            $SelectOffre_Img->execute();
            $offres_images = $SelectOffre_Img->fetchAll();
            if(!empty($offres_images)){ ?>
                <div class="slider">
                    <div class="boxSlider">
                        <div class="slideshow-container"> <?php
                            foreach($offres_images as $image ){?>
                                <div class="mySlides"> 
                                    <img src="<?php echo "assets/".$image['Nom_Image']."" ?>" alt="Image(s) de l'offre">
                                </div>
                            <?php } ?>
                        </div>
                        <img class="prev" onclick="plusSlides(-1)" src="assets/chevron-gauche.png">
                        <img class="next" onclick="plusSlides(1)" src="assets/chevron-droit.png">
                        <div style="text-align:center">
                            <span class="dot" onclick="currentSlide(1)"></span>
                            <span class="dot" onclick="currentSlide(2)"></span>
                            <span class="dot" onclick="currentSlide(3)"></span>
                        </div>
                    </div>
                </div>
                <script src="scriptaside.js"></script>
                <?php } ?>
                <div class="supprBox">
                    <p>Êtes-vous sûr de vouloir supprimer cette offre ?</p>
                    <div class="supprBtn">
                        <form id="formSupprBilletterie">
                            <input type="hidden" name="idOffre" value="<?php echo $offre['Id_Offre'] ?>">
                            <button type="submit" class="formSupprOui">OUI</button>
                        </form>
                        <button class="formSupprNon">NON</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script>
        // Code Modal suppression d'une offre

        var modalSuppr = document.getElementById("modalSupprBilletterie");
        var span = document.getElementsByClassName("close")[0];
        var btnNon = document.getElementsByClassName("formSupprNon")[0];
        var btnOui = document.getElementsByClassName("formSupprOui")[0];
        var body = document.body;
        body.style.overflow= "hidden";
        // cacher modal au click de la croix ou du btn non
        span.onclick = function() {
            modalSuppr.style.display = "none";
            //suppr get dans l'url -- Depend si &numpage existe ou pas
            $searchGet = new URLSearchParams(window.location.href);
            if($searchGet.has("numpage")){
                history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
            }
            else{
                history.pushState(null, null, window.location.href.split("&")[0]);
            }
            body.style.overflow = "auto";
        }
        btnNon.onclick = function(e) {
            e.preventDefault();
            modalSuppr.style.display = "none";
            //suppr get dans l'url -- Depend si &numpage existe ou pas
            $searchGet = new URLSearchParams(window.location.href);
            if($searchGet.has("numpage")){
                history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
            }
            else{
                history.pushState(null, null, window.location.href.split("&")[0]);
            }
            body.style.overflow = "auto";
        }
        btnOui.onclick = function() {
            //suppr get dans l'url -- Depend si &numpage existe ou pas
            $searchGet = new URLSearchParams(window.location.href);
            if($searchGet.has("numpage")){
                history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
            }
            else{
                history.pushState(null, null, window.location.href.split("&")[0]);
            }
            setTimeout(function() {modalSuppr.style.display = "none";}, 2000);
            body.style.overflow = "auto";
        }
        window.onclick = function(event) {
        if (event.target == modalSuppr) {
            modalSuppr.style.display = "none";
            //suppr get dans l'url -- Depend si &numpage existe ou pas
            $searchGet = new URLSearchParams(window.location.href);
            if($searchGet.has("numpage")){
                history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
            }
            else{
                history.pushState(null, null, window.location.href.split("&")[0]);
            }
            body.style.overflow = "auto";
        }
        }


        // Code Jquery en AJAX pour la suppression d'une offre

        $(document).ready(function(){
            $("#formSupprBilletterie").submit(function(e){
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: "POST",
                url: "supprOffre.php",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response){
                    alert(response);
                    setTimeout(location.reload(true) , 3000);
                }
            });
            });
        });
    </script>
<?php
}

// CODE MODAL POUR SUPPRIMER UN MESSAGE

if(isset($_GET['modalSupprMessage'])){
    $req = $connexion->prepare("SELECT * FROM message WHERE Id_Message = :id");
    $req->bindParam('id',$_GET['modalSupprMessage']);
    $req->execute();
    $message = $req->fetch();
    ?>
    <div id="modalSupprMessage" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div class="title">
                <h2>Message de <?php echo $message['Nom_Message']." ".$message['Prenom_Message']; ?></h2>
            </div>
            <div class="modalBox">
                <div class="supprBox">
                    <p>Êtes-vous sûr de vouloir supprimer ce message ?</p>
                    <div class="supprBtn">
                        <form id="formSupprMessage">
                            <input type="hidden" name="idMsg" value="<?php echo $message['Id_Message'] ?>">
                            <button type="submit" class="formSupprOui">OUI</button>
                        </form>
                        <button class="formSupprNon">NON</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script>
        // Code Modal suppression d'une offre

        var modalSuppr = document.getElementById("modalSupprMessage");
        var span = document.getElementsByClassName("close")[0];
        var btnNon = document.getElementsByClassName("formSupprNon")[0];
        var btnOui = document.getElementsByClassName("formSupprOui")[0];
        var body = document.body;
        body.style.overflow= "hidden";
        // cacher modal au click de la croix ou du btn non
        span.onclick = function() {
            modalSuppr.style.display = "none";
            //suppr get dans l'url -- Depend si &numpage existe ou pas
            $searchGet = new URLSearchParams(window.location.href);
            if($searchGet.has("numpage")){
                history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
            }
            else{
                history.pushState(null, null, window.location.href.split("&")[0]);
            }
            body.style.overflow = "auto";
        }
        btnNon.onclick = function(e) {
            e.preventDefault();
            modalSuppr.style.display = "none";
            //suppr get dans l'url -- Depend si &numpage existe ou pas
            $searchGet = new URLSearchParams(window.location.href);
            if($searchGet.has("numpage")){
                history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
            }
            else{
                history.pushState(null, null, window.location.href.split("&")[0]);
            }
            body.style.overflow = "auto";
        }
        btnOui.onclick = function() {
            //suppr get dans l'url -- Depend si &numpage existe ou pas
            $searchGet = new URLSearchParams(window.location.href);
            if($searchGet.has("numpage")){
                history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
            }
            else{
                history.pushState(null, null, window.location.href.split("&")[0]);
            }
            setTimeout(function() {modalSuppr.style.display = "none";}, 2000);
            body.style.overflow = "auto";
        }
        window.onclick = function(event) {
        if (event.target == modalSuppr) {
            modalSuppr.style.display = "none";
            //suppr get dans l'url -- Depend si &numpage existe ou pas
            $searchGet = new URLSearchParams(window.location.href);
            if($searchGet.has("numpage")){
                history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
            }
            else{
                history.pushState(null, null, window.location.href.split("&")[0]);
            }
            body.style.overflow = "auto";
        }
        }


        // Code Jquery en AJAX pour la suppression d'une offre

        $(document).ready(function(){
            $("#formSupprMessage").submit(function(e){
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: "POST",
                url: "supprMessage.php",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response){
                    alert(response);
                    setTimeout(location.reload(true) , 3000);
                }
            });
            });
        });
    </script>
<?php
}

// CODE MODAL POUR AJOUTER UN UTILISATEUR 

if(isset($_GET['modalAjoutUtilisateur'])){
    ?>
   <div id="modalAjoutUtilisateur" class="modal">
       <div class="modal-content">
           <span class="closeAjout">&times;</span>
           <div class="formBox">
               <form id="formAjoutUtilisateur" enctype="multipart/form-data" method="POST">
                   <label for="nomuser">Nom* :</label>
                   <input type="text" name="nomuser" placeholder="Nom de l'utilisateur">

                    <label for="prenomuser">Prénom* :</label>
                    <input type="text" name="prenomuser" placeholder="Prénom de l'utilisateur">
                    
                    <label for="emailuser">Email* :</label>
                    <input type="text" name="emailuser" placeholder="Email de l'utilisateur">

                    <label for="passuser">Mot de passe* :</label>
                    <input type="password" name="passuser" placeholder="Mot de passe de l'utilisateur">
                    <label for="passuser2">Confirmez le mot de passe* :</label>
                    <input type="password" name="passuser2" placeholder="Confirmez le mot de passe">

                    <label for="droituser">Droit* :</label>
                    <select name="droituser" class="droituser">
                            <?php 
                                    $reqPart = $connexion->prepare("SELECT * FROM droit");
                                    $reqPart->execute();
                                    $Part = $reqPart->fetchAll();
                                    foreach($Part as $part){ 
                                        if($part['Id_Droit'] == 2){ ?>
                                            <option value="<?= $part['Id_Droit'] ?>" selected><?= $part['Libelle_Droit'] ?></option>
                                  <?php }else{
                                        ?>
                                            <option value="<?= $part['Id_Droit'] ?>"><?= $part['Libelle_Droit'] ?></option>
                                    <?php }    
                                    }
                                ?>
                    </select>

                   <div class="ajoutBtn">
                       <button type="submit" class="formAjoutOui">OUI</button></form>
                       <button class="formAjoutNon">NON</button>
                   </div>
               
           </div>
           
       </div>

   </div>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
   <script>
       // Code Modal modif d'ajout d'un utilisateur
       var modalAjout = document.getElementById("modalAjoutUtilisateur");
       var span = document.getElementsByClassName("closeAjout")[0];
       var btnNon = document.getElementsByClassName("formAjoutNon")[0];
       var btnOui = document.getElementsByClassName("formAjoutOui")[0];
       // cacher modal au click de la croix ou du btn non
       span.onclick = function() {
           modalAjout.style.display = "none";
           //suppr get dans l'url -- Depend si &numpage existe ou pas
            $searchGet = new URLSearchParams(window.location.href);
            if($searchGet.has("numpage")){
                history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
            }
            else{
                history.pushState(null, null, window.location.href.split("&")[0]);
            }
       }
       btnNon.onclick = function(e) {
           e.preventDefault();
           modalAjout.style.display = "none";
           //suppr get dans l'url -- Depend si &numpage existe ou pas
            $searchGet = new URLSearchParams(window.location.href);
            if($searchGet.has("numpage")){
                history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
            }
            else{
                history.pushState(null, null, window.location.href.split("&")[0]);
            }
       }
       btnOui.onclick = function() {
           //suppr get dans l'url -- Depend si &numpage existe ou pas
            $searchGet = new URLSearchParams(window.location.href);
            if($searchGet.has("numpage")){
                history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
            }
            else{
                history.pushState(null, null, window.location.href.split("&")[0]);
            }
           setTimeout(function() {modalAjout.style.display = "none";}, 2000);
       }
       window.onclick = function(event) {
       if (event.target == modalAjout) {
           modalAjout.style.display = "none";
           //suppr get dans l'url -- Depend si &numpage existe ou pas
            $searchGet = new URLSearchParams(window.location.href);
            if($searchGet.has("numpage")){
                history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
            }
            else{
                history.pushState(null, null, window.location.href.split("&")[0]);
            }
       }
       }
       
       // Code Jquery en AJAX pour l'ajout d'un utilisateur

       $(document).ready(function(){
           $("#formAjoutUtilisateur").submit(function(e){
               e.preventDefault();
               var formData = new FormData(this);
               $.ajax({
                   type: "POST",
                   url: "BackofficePHP/ajoutUser.php",
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

// CODE MODAL POUR MODIFIER UN UTILISATEUR

if(isset($_GET['modalModifUtilisateur'])){
    $req = $connexion->prepare("SELECT * FROM utilisateur WHERE Id_Utilisateur = :id");
    $req->bindParam('id',$_GET['modalModifUtilisateur']);
    $req->execute();
    $user = $req->fetch();
     ?>
    <div id="modalModifUtilisateur" class="modal">
        <div class="modal-content">
            <span class="closeModif">&times;</span>
            <div class="formBox">
               <form id="formModifUtilisateur" enctype="multipart/form-data" method="POST">
                    <input type="hidden" name="iduser" value="<?php echo $user['Id_Utilisateur'] ?>">

                    <label for="nomuser">Nom* :</label>
                    <input type="text" name="nomuser" placeholder="Nom de l'utilisateur" value="<?php echo $user['Nom_Utilisateur'] ?>">

                    <label for="prenomuser">Prenom* :</label>
                    <input type="text" name="prenomuser" placeholder="Prenom de l'utilisateur" value="<?php echo $user['Prenom_Utilisateur'] ?>">
                   
                    <label for="emailuser">Email* :</label>
                    <input type="text" name="emailuser" placeholder="Email de l'utilisateur" value="<?php echo $user['Email_Utilisateur'] ?>">

                    <label for="passuser">Mot de passe* :</label>
                    <input type="password" name="passuser" placeholder="Mot de passe de l'utilisateur">
                    <label for="passuser2">Confirmez le mot de passe* :</label>
                    <input type="password" name="passuser2" placeholder="Confirmez le mot de passe">

                   
                    <label for="droituser">Droit* :</label>
                    <select name="droituser">
                        <?php 
                        $req = $connexion->prepare("SELECT * FROM droit");
                        $req->execute();
                        $droits = $req->fetchAll();
                        var_dump($droits);
                        foreach($droits as $droit){
                            if($user['Id_Droit'] == $droit["Id_Droit"]){ ?>
                                <option value="<?= $droit["Id_Droit"] ?>" selected><?= $droit["Libelle_Droit"] ?></option>
                        <?php }else{ ?>
                            <option value="<?= $droit["Id_Droit"] ?>"><?= $droit["Libelle_Droit"] ?></option>
                        <?php }
                        }
                        ?>
                    </select>

                   
                   <div class="modifBtn">
                       <button type="submit" class="formModifOui">OUI</button></form>
                       <button class="formModifNon">NON</button>
                   </div>
               
           </div>
            
        </div>

    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script>
        // Code Modal modif d'un utilisateur
        var modalModif = document.getElementById("modalModifUtilisateur");
        var span = document.getElementsByClassName("closeModif")[0];
        var btnNon = document.getElementsByClassName("formModifNon")[0];
        var btnOui = document.getElementsByClassName("formModifOui")[0];
        var body = document.body;
        body.style.overflow= "hidden";
        // cacher modal au click de la croix ou du btn non
        span.onclick = function() {
            modalModif.style.display = "none";
            //suppr get dans l'url -- Depend si &numpage existe ou pas
            $searchGet = new URLSearchParams(window.location.href);
            if($searchGet.has("numpage")){
                history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
            }
            else{
                history.pushState(null, null, window.location.href.split("&")[0]);
            }
            body.style.overflow = "auto";
        }
        btnNon.onclick = function(e) {
            e.preventDefault();
            modalModif.style.display = "none";
            //suppr get dans l'url -- Depend si &numpage existe ou pas
            $searchGet = new URLSearchParams(window.location.href);
            if($searchGet.has("numpage")){
                history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
            }
            else{
                history.pushState(null, null, window.location.href.split("&")[0]);
            }
            body.style.overflow = "auto";
        }
        btnOui.onclick = function() {
            //suppr get dans l'url -- Depend si &numpage existe ou pas
            $searchGet = new URLSearchParams(window.location.href);
            if($searchGet.has("numpage")){
                history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
            }
            else{
                history.pushState(null, null, window.location.href.split("&")[0]);
            }
            setTimeout(function() {modalModif.style.display = "none";}, 2000);
            body.style.overflow = "auto";
        }
        window.onclick = function(event) {
        if (event.target == modalModif) {
            modalModif.style.display = "none";
            //suppr get dans l'url -- Depend si &numpage existe ou pas
            $searchGet = new URLSearchParams(window.location.href);
            if($searchGet.has("numpage")){
                history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
            }
            else{
                history.pushState(null, null, window.location.href.split("&")[0]);
            }
            body.style.overflow = "auto";
        }
        }
        
        // Code Jquery en AJAX pour la modif d'un utilisateur

        $(document).ready(function(){
            $("#formModifUtilisateur").submit(function(e){
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "BackofficePHP/modifUser.php",
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

// CODE MODAL POUR SUPPRIMER UN UTILISATEUR

if(isset($_GET['modalSupprUtilisateur'])){
    $req = $connexion->prepare("SELECT * FROM utilisateur WHERE Id_Utilisateur = :id");
    $req->bindParam('id',$_GET['modalSupprUtilisateur']);
    $req->execute();
    $user = $req->fetch();
    $reqDroit = $connexion->prepare("SELECT * FROM droit WHERE Id_Droit = :id");
    $reqDroit->bindParam('id',$user['Id_Droit']);
    $reqDroit->execute();
    $droit = $reqDroit->fetch();
    ?>
    <div id="modalSupprUtilisateur" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div class="title">
                <h2><?= $droit['Libelle_Droit'] == "Administrateur" ? "<span style='color:red; font-weight:600'>[".$droit['Libelle_Droit']."]</span>" : "<span style='color:#1B3168; font-weight:600'>[".$droit['Libelle_Droit']."]</span>"?><?= " ".$user["Nom_Utilisateur"]." ".$user["Prenom_Utilisateur"] ?></h2>
            </div>
            <div class="modalBox">
                <div class="supprBox">
                    <p>Êtes-vous sûr de vouloir supprimer cet<?= " ".$droit['Libelle_Droit']." " ?>?</p>
                    <div class="supprBtn">
                        <form id="formSupprUtilisateur">
                            <input type="hidden" name="iduser" value="<?php echo $user['Id_Utilisateur'] ?>">
                            <button type="submit" class="formSupprOui">OUI</button>
                        </form>
                        <button class="formSupprNon">NON</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script>
        // Code Modal suppression d'une offre

        var modalSuppr = document.getElementById("modalSupprUtilisateur");
        var span = document.getElementsByClassName("close")[0];
        var btnNon = document.getElementsByClassName("formSupprNon")[0];
        var btnOui = document.getElementsByClassName("formSupprOui")[0];
        var body = document.body;
        body.style.overflow= "hidden";
        // cacher modal au click de la croix ou du btn non
        span.onclick = function() {
            modalSuppr.style.display = "none";
            //suppr get dans l'url -- Depend si &numpage existe ou pas
            $searchGet = new URLSearchParams(window.location.href);
            if($searchGet.has("numpage")){
                history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
            }
            else{
                history.pushState(null, null, window.location.href.split("&")[0]);
            }
            body.style.overflow = "auto";
        }
        btnNon.onclick = function(e) {
            e.preventDefault();
            modalSuppr.style.display = "none";
            //suppr get dans l'url -- Depend si &numpage existe ou pas
            $searchGet = new URLSearchParams(window.location.href);
            if($searchGet.has("numpage")){
                history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
            }
            else{
                history.pushState(null, null, window.location.href.split("&")[0]);
            }
            body.style.overflow = "auto";
        }
        btnOui.onclick = function() {
            //suppr get dans l'url -- Depend si &numpage existe ou pas
            $searchGet = new URLSearchParams(window.location.href);
            if($searchGet.has("numpage")){
                history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
            }
            else{
                history.pushState(null, null, window.location.href.split("&")[0]);
            }
            setTimeout(function() {modalSuppr.style.display = "none";}, 2000);
            body.style.overflow = "auto";
        }
        window.onclick = function(event) {
        if (event.target == modalSuppr) {
            modalSuppr.style.display = "none";
            //suppr get dans l'url -- Depend si &numpage existe ou pas
            $searchGet = new URLSearchParams(window.location.href);
            if($searchGet.has("numpage")){
                history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
            }
            else{
                history.pushState(null, null, window.location.href.split("&")[0]);
            }
            body.style.overflow = "auto";
        }
        }


        // Code Jquery en AJAX pour la suppression d'un utilisateur

        $(document).ready(function(){
            $("#formSupprUtilisateur").submit(function(e){
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: "POST",
                url: "BackofficePHP/supprUser.php",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response){
                    alert(response);
                    setTimeout(location.reload(true) , 3000);
                }
            });
            });
        });
    </script>
<?php
}

}

// MODAL AFFICHER

if (!empty($_SESSION['Nom_Utilisateur']) && !empty($_SESSION['Droit_Utilisateur'])){
        
    // CODE MODAL POUR AFFICHER UN MESSAGE

    if(isset($_GET['modalAfficherMessage'])){
        $req = $connexion->prepare("SELECT * FROM message WHERE Id_Message = :id");
        $req->bindParam('id',$_GET['modalAfficherMessage']);
        $req->execute();
        $message = $req->fetch();
        ?>
        <div id="modalAfficher" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <div class="title">
                    <h2>Message de <span class="ModalAfficherTitle"><?php echo $message['Nom_Message']." ".$message['Prenom_Message']; ?></span></h2>
                </div>
                <div class="modalBox">
                    <div class="afficherBox">
                        <div>
                            <h3>Email de contact : </h3>
                            <p class="styleBoxAfficher styleBoxAfficherEmail"><a href="mailto:<?= $message['Email_Message'] ?>"><?= $message['Email_Message'] ?></a></p>
                        </div>
                        <div>
                            <h3>Message : </h3>
                            <p class="styleBoxAfficher styleBoxAfficherMessage"><?= $message['Contenu_Message'] ?></p>
                        </div>
                        <div>
                            <h3>Offre associée :</h3>
                            <?php 
                                if (!empty($message['Id_Offre'])){
                                    $reqOffre = $connexion->prepare("SELECT * FROM offre WHERE Id_Offre = :id");
                                    $reqOffre->bindParam("id",$message['Id_Offre']);
                                    $reqOffre->execute();
                                    $offre = $reqOffre->fetch();
                                    ?> <p class="styleBoxAfficher"><a href="contenu_offre_billetterie.php?id=<?= $offre['Id_Offre'] ?>&pageoffre=1" target="_blank"><?= $offre['Nom_Offre'] ?></a></p> <?php
                                }else{ ?>
                                    <p class="styleBoxAfficher">Pas d'offre associée</p> <?php
                                }?>
                                <h3>Partenaire associé :</h3> <?php

                                if(!empty($message['Id_Partenaire'])){
                                    $reqPart = $connexion->prepare("SELECT * FROM partenaire WHERE Id_Partenaire = :id");
                                    $reqPart->bindParam("id",$message['Id_Partenaire']);
                                    $reqPart->execute();
                                    $part = $reqPart->fetch();
                                    ?>
                                    <p class="styleBoxAfficher"><a href="partenariats.php?modalOuvirPartenaire=<?= $part['Id_Partenaire'] ?>" target="_blank"><?= $part['Nom_Partenaire'] ?></a></p> <?php
                                }else{?>
                                    <p class="styleBoxAfficher">Pas de partenaire associé</p> <?php
                                }
                            ?>
                        </div>
                        
                        <div class="closeBtn">
                            <button class="formSupprNon">FERMER</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
        <script>
            // Code Modal suppression d'une offre

            var modalSuppr = document.getElementById("modalAfficher");
            var span = document.getElementsByClassName("close")[0];
            var btnNon = document.getElementsByClassName("formSupprNon")[0];
            var body = document.body;
            body.style.overflow= "hidden";
            // cacher modal au click de la croix ou du btn non
            span.onclick = function() {
                modalSuppr.style.display = "none";
                //suppr get dans l'url -- Depend si &numpage existe ou pas
                $searchGet = new URLSearchParams(window.location.href);
                if($searchGet.has("numpage")){
                    history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
                }
                else{
                    history.pushState(null, null, window.location.href.split("&")[0]);
                }
                body.style.overflow = "auto";
            }
            btnNon.onclick = function(e) {
                e.preventDefault();
                modalSuppr.style.display = "none";
                //suppr get dans l'url -- Depend si &numpage existe ou pas
                $searchGet = new URLSearchParams(window.location.href);
                if($searchGet.has("numpage")){
                    history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
                }
                else{
                    history.pushState(null, null, window.location.href.split("&")[0]);
                }
                body.style.overflow = "auto";
            }
            window.onclick = function(event) {
            if (event.target == modalSuppr) {
                modalSuppr.style.display = "none";
                //suppr get dans l'url -- Depend si &numpage existe ou pas
                $searchGet = new URLSearchParams(window.location.href);
                if($searchGet.has("numpage")){
                    history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
                }
                else{
                    history.pushState(null, null, window.location.href.split("&")[0]);
                }
                body.style.overflow = "auto";
            }
            }

        </script>
    <?php
    }
        
    // CODE MODAL POUR AFFICHER UN PARTENAIRE

    if(isset($_GET['modalAfficherPartenaire'])){
        $req = $connexion->prepare("SELECT * FROM partenaire WHERE Id_Partenaire = :id");
        $req->bindParam('id',$_GET['modalAfficherPartenaire']);
        $req->execute();
        $partenaire = $req->fetch();
        $reqImg = $connexion->prepare("SELECT Nom_Image FROM image WHERE Id_Image = :id");
        $reqImg->bindParam('id',$partenaire['Id_Image']);
        $reqImg->execute();
        $imgPart = $reqImg->fetch();
        ?>
        <div id="modalAfficher" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <div class="title">
                    <h2><span class="ModalAfficherTitle"><?php echo $partenaire['Nom_Partenaire']; ?></span></h2>
                </div>
                <div class="modalBox">
                    <div class="afficherBox BoxModal Special">
                        <div class="imageBox">
                            <img class="image" src="assets/<?php echo $imgPart['Nom_Image']  ?>" alt="Image du partenaire">
                        </div>
                        <div>
                            <h3>Description : </h3>
                            <p class="styleBoxAfficher styleBoxAfficherMessage"><?= $partenaire['Description_Partenaire'] ?></p>
                        </div>
                        <a target='blank' style="justify-content: center; display: flex;" href="<?= $partenaire['Lien_Partenaire'] ?>">
                            <div id="offres_decouvrir">Voir Site du Partenaire</div>
                        </a>
                        <div class="closeBtn">
                            <button class="formSupprNon">FERMER</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
        <script>
            // Code Modal suppression d'une offre

            var modalSuppr = document.getElementById("modalAfficher");
            var span = document.getElementsByClassName("close")[0];
            var btnNon = document.getElementsByClassName("formSupprNon")[0];
            var body = document.body;
            body.style.overflow= "hidden";
            // cacher modal au click de la croix ou du btn non
            span.onclick = function() {
                modalSuppr.style.display = "none";
                //suppr get dans l'url -- Depend si &numpage existe ou pas
                $searchGet = new URLSearchParams(window.location.href);
                if($searchGet.has("numpage")){
                    history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
                }
                else{
                    history.pushState(null, null, window.location.href.split("&")[0]);
                }
                body.style.overflow = "auto";
            }
            btnNon.onclick = function(e) {
                e.preventDefault();
                modalSuppr.style.display = "none";
                //suppr get dans l'url -- Depend si &numpage existe ou pas
                $searchGet = new URLSearchParams(window.location.href);
                if($searchGet.has("numpage")){
                    history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
                }
                else{
                    history.pushState(null, null, window.location.href.split("&")[0]);
                }
                body.style.overflow = "auto";
            }
            window.onclick = function(event) {
            if (event.target == modalSuppr) {
                modalSuppr.style.display = "none";
                //suppr get dans l'url -- Depend si &numpage existe ou pas
                $searchGet = new URLSearchParams(window.location.href);
                if($searchGet.has("numpage")){
                    history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
                }
                else{
                    history.pushState(null, null, window.location.href.split("&")[0]);
                }
                body.style.overflow = "auto";
            }
            }

        </script>
    <?php
    }
        
    // CODE MODAL POUR AFFICHER UNE OFFRE

    if(isset($_GET['modalAfficherBilletterie'])){
        $req = $connexion->prepare("SELECT * FROM offre WHERE Id_Offre = :id");
        $req->bindParam('id',$_GET['modalAfficherBilletterie']);
        $req->execute();
        $offre = $req->fetch();
        $reqImg = $connexion->prepare("SELECT * FROM image WHERE Id_Image in (SELECT Id_Image FROM offre_image WHERE Id_Offre = :id)");
        $reqImg->bindParam('id',$offre['Id_Offre']);
        $reqImg->execute();
        $imgOffre = $reqImg->fetchAll();
        ?>
        <div id="modalAfficher" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <div class="title">
                    <h2><span class="ModalAfficherTitle"><?php echo $offre['Nom_Offre']; ?></span></h2>
                </div>
                <div class="modalBox">
                    <div class="afficherBox BoxModal">
                        <div class="imageBox <?= count($imgOffre) > 0 ? "styleBoxAfficher Offre" : "" ?>"> <?php
                            foreach($imgOffre as $img){ ?>
                                <div>
                                    <img class="image" src="assets/<?php echo $img['Nom_Image']  ?>" alt="Image de l'offre">
                                </div>
                        <?php
                            }
                            ?>
                        </div>
                        <div>
                            <h3>Dates de l'offre : </h3>
                            <?php 
                                $monthsEnglish = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                                $monthsFrench = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
                                
                                $datedeb = $offre['Date_Debut_Offre'];
                                $datefin = $offre['Date_Fin_Offre'];
                                
                                $datedeb_formattee = strftime("%d %B %Y", strtotime($datedeb));
                                $datedeb_formattee = explode(" ", $datedeb_formattee);
                                for ($i = 0; $i < count($monthsEnglish); $i++) {
                                    if ($datedeb_formattee[1] == $monthsEnglish[$i]) {
                                        $datedeb_formattee[1] = $monthsFrench[$i];
                                    }
                                }
                                $datedeb_formattee = implode(" ", $datedeb_formattee);
                                
                                $datefin_formattee = strftime("%d %B %Y", strtotime($datefin));
                                $datefin_formattee = explode(" ", $datefin_formattee);
                                for ($i = 0; $i < count($monthsEnglish); $i++) {
                                    if ($datefin_formattee[1] == $monthsEnglish[$i]) {
                                        $datefin_formattee[1] = $monthsFrench[$i];
                                    }
                                }
                                $datefin_formattee = implode(" ", $datefin_formattee);                                

                            ?>
                            <p class="styleBoxAfficher"><?= $datedeb_formattee." - ".$datefin_formattee ?></p>
                        </div>
                        <div>
                            <h3>Partenaire : </h3>
                            <?php 
                                $req = $connexion->prepare("SELECT * FROM partenaire WHERE Id_Partenaire = :id");
                                $req->bindParam("id", $offre['Id_Partenaire']);
                                $req->execute();
                                $partenaire = $req->fetch();
                            ?>
                            <p class="styleBoxAfficher"><a href="partenariats.php?modalOuvirPartenaire=<?= $partenaire['Id_Partenaire'] ?>" target="_blank"><?= $partenaire['Nom_Partenaire'] ?></a></p>
                        </div>
                        <div>
                            <h3>Contenu : </h3>
                            <p class="styleBoxAfficher styleBoxAfficherMessage"><?= $offre['Description_Offre'] ?></p>
                        </div>
                        <div>
                            <h3>Nombres de places : </h3>
                            <p class="styleBoxAfficher"><?= $offre['Nombre_Place_Min_Offre'] ?><?= $offre['Nombre_Place_Min_Offre'] > 1 ? " places" : " place" ?></p>
                        </div>
                        
                        <div class="closeBtn">
                            <button class="formSupprNon">FERMER</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
        <script>
            // Code Modal suppression d'une offre

            var modalSuppr = document.getElementById("modalAfficher");
            var span = document.getElementsByClassName("close")[0];
            var btnNon = document.getElementsByClassName("formSupprNon")[0];
            var body = document.body;
            body.style.overflow= "hidden";
            // cacher modal au click de la croix ou du btn non
            span.onclick = function() {
                modalSuppr.style.display = "none";
                //suppr get dans l'url -- Depend si &numpage existe ou pas
                $searchGet = new URLSearchParams(window.location.href);
                if($searchGet.has("numpage")){
                    history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
                }
                else{
                    history.pushState(null, null, window.location.href.split("&")[0]);
                }
                body.style.overflow = "auto";
            }
            btnNon.onclick = function(e) {
                e.preventDefault();
                modalSuppr.style.display = "none";
                //suppr get dans l'url -- Depend si &numpage existe ou pas
                $searchGet = new URLSearchParams(window.location.href);
                if($searchGet.has("numpage")){
                    history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
                }
                else{
                    history.pushState(null, null, window.location.href.split("&")[0]);
                }
                body.style.overflow = "auto";
            }
            window.onclick = function(event) {
            if (event.target == modalSuppr) {
                modalSuppr.style.display = "none";
                //suppr get dans l'url -- Depend si &numpage existe ou pas
                $searchGet = new URLSearchParams(window.location.href);
                if($searchGet.has("numpage")){
                    history.pushState(null, null, window.location.href.split("&").slice(0, 2).join("&"));
                }
                else{
                    history.pushState(null, null, window.location.href.split("&")[0]);
                }
                body.style.overflow = "auto";
            }
            }

        </script>
    <?php
    }
}
?>

<!-- Debut Page HTML -->

<body> 
<div class="bodyDiv">
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
                <a href="index.php">
                    <img class="img_base" src="assets/logo_lycee.png" alt="logo_st_vincent">
                    <img class="img_responsive" src="assets/Logo_St_Vincent_2.jpg" alt="logo_st_vincent_responsive">
                </a>
            </div>
            <ul class="links"> <?php
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
            <?php if(!empty($_GET) && $_GET['page'] === "gestion"){?>
                <li><a href="backoffice.php?page=gestion" class="active">Gestion</a></li>
            <?php }else{?>
                <li><a href="backoffice.php?page=gestion">Gestion</a></li>
            <?php } ?>
                <li class="logoutLi">
                        <form method="post" action="BackofficePHP/logout.php" class="logoutForm">
                            <button type="submit" value="Logout"><img class="logout" src="assets/logout.png" alt="Se deconnecter" ></button>
                        </form>
                    </li>
            </ul>
            <form method="post" action="BackofficePHP/logout.php" class="logoutDiv">
                <button type="submit" value="Logout"><img class="logout" src="assets/logout.png" alt="Se deconnecter" ></button>
            </form>
            <img class="menu-burger" src="assets/menu.png" alt="menu-burger">
            <script src="scriptMenuBurger.js"></script>
        </nav>
    </header>
        <main> <?php
        if(!empty($_GET) && $_GET['page'] !== "accueil"){

            if(!empty($_GET['page']) && $_GET['page'] !== "accueil"){ ?>
                <style>
                    .actionBtn{
                        display: grid;
                    }

                </style>
                <?php 
                if($_GET['page'] === "partenaires"){?>
                    <?php 
                        $count = $connexion -> prepare("SELECT COUNT(Id_Partenaire)  as infos FROM partenaire");
                        $count->setFetchMode(PDO::FETCH_ASSOC);
                        $count -> execute();
                        $tcount = $count->fetchAll();
                        
                        $nb_elements_par_page = 7;
                        $pages =ceil($tcount[0]['infos']/$nb_elements_par_page);
                        @$page = $_GET["numpage"];
                        // Verif validité 
                        if(empty($page)){
                            $page = 1;
                        }
                        $page = max(1, min($pages, $page));
                        $debut = ($page - 1) * $nb_elements_par_page;
                        // Nombre de pages à afficher avant et après la page courante
                        $pagesAffiche = 1;
                        // Calcul du début et de la fin de la plage de pages à afficher
                        $startPage = max(1, $page - $pagesAffiche);
                        $endPage = min($pages, $page + $pagesAffiche);

                        //recup param de l'url
                        $params = $_GET;
                        if(isset($params['modalModifPartenaire'])){
                            unset($params['modalModifPartenaire']);
                        }
                        if(isset($params['modalSupprPartenaire'])){
                            unset($params['modalSupprPartenaire']);
                        }
                        if(isset($params['modalAfficherPartenaire'])){
                            unset($params['modalAfficherPartenaire']);
                        }
                        //Creation GET + Construct url
                        $params['modalAjoutPartenaire'] = "partenaire";
                        $urlajout = http_build_query($params);
                        unset($params['modalAjoutPartenaire']);// je suppr la colonne pour pas l'avoir dans les autres url (urlmodif et urlsuppr)

                    ?>
                    <div class="partenaires">

                    <?= isset($msgvalidation) ? $msgvalidation : null ?>
                    <div class="titlePage"> 
                        <h1>Modifier les partenaires</h1>
                        <div class="addBtn"><a href="backoffice.php?<?= $urlajout; ?>"><div>Ajouter</div></a></div>
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
                                <th class="addPart"><a href="backoffice.php?<?= $urlajout; ?>"><div>Ajouter</div></a></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            try{
                                $req = $connexion->prepare("SELECT * FROM partenaire LIMIT $debut, $nb_elements_par_page");
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
                                        <td data-title="Nom"><?php echo $partenaire["Nom_Partenaire"] ?></td>
                                        <td data-title="Description" class="colonneDescription"><div><?php echo $partenaire["Description_Partenaire"] ?></div></td>
                                        <td data-title="Site" class="colonneLien"><a href="<?php echo $partenaire["Lien_Partenaire"] ?>" target="_blank"><?php echo $partenaire["Lien_Partenaire"] ?></a></td>
                                        <td data-title="Image" class="imgPart"><?= !empty($partenaire["Id_Image"]) ? '<div  style="display: flex;height: 100%;justify-content: center;"><img src="assets/'.$imgPart["Nom_Image"].'" alt="Image du partenaire"></div>' : "Aucune image" ?></td>
                                        <td data-title="Action" class="actionBtn">  
                                            <?php 
                                            $params['modalModifPartenaire'] = $partenaire["Id_Partenaire"];
                                            $urlmodif = http_build_query($params);
                                            unset($params['modalModifPartenaire']); // je suppr la colonne pour pas l'avoir dans urlsuppr
                    
                                            $params['modalSupprPartenaire'] = $partenaire["Id_Partenaire"];
                                            $urlsuppr = http_build_query($params);
                                            unset($params['modalSupprPartenaire']);
 
                                            $params['modalAfficherPartenaire'] = $partenaire["Id_Partenaire"];
                                            $urlafficher = http_build_query($params);
                                            unset($params['modalAfficherPartenaire']);

                                            ?>
                                            <a href="backoffice.php?<?= $urlafficher; ?>" class="modifBtn afficherBtn">Afficher</a>
                                            <a href="backoffice.php?<?= $urlmodif; ?>" class="modifBtn">Modifier</a>
                                            <a href="backoffice.php?<?= $urlsuppr; ?>" class="supprBtn">Supprimer</a>
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
                    <div class="pagination">
                    <?php
                        // Vérification si les points de suspension doivent être affichés au début
                        if ($startPage > 1) {
                            // Afficher la première page
                            echo '<a href="?page=partenaires&numpage=1"><span class="page">1</span></a>';
                            // Afficher les points de suspension au début
                            if ($startPage > 2 && $page >= 3) {
                                echo '<a><span class="page" id="troisPoints">...</span></a>';
                            }
                        }
                        // Affichage des numéros de page ou des points de suspension pour les pages au milieu
                        for ($pag = $startPage; $pag <= $endPage; $pag++) {
                            if($page != $pag){ 
                                echo '<a href="?page=partenaires&numpage='.$pag.'"><span class="page">' . $pag . '</span></a>';
                            }else{
                                echo '<a href="?page=partenaires&numpage='.$pag.'"><span class="page activepage">' . $pag . '</span></a>';
                            }
                        }
                        // Vérification si les points de suspension doivent être affichés à la fin
                        if ($endPage < $pages) {
                            // Afficher les points de suspension à la fin
                            if ($endPage < $pages - 1) {
                                echo '<a><span class="page" id="troisPoints">...</span></a>';
                            }
                            // Afficher la dernière page
                            echo '<a href="?page=partenaires&numpage='.$pages.'"><span class="page">'.$pages.'</span></a>';
                        }
                        ?>
                    </div>
                    </div>
                    
                    


            <?php }else if($_GET['page'] === "billetterie"){ ?>
                    <?php 
                    $count = $connexion -> prepare("SELECT COUNT(Id_Offre)  as infos FROM offre");
                    $count->setFetchMode(PDO::FETCH_ASSOC);
                    $count -> execute();
                    $tcount = $count->fetchAll();
                    
                    $nb_elements_par_page = 7;
                    $pages =ceil($tcount[0]['infos']/$nb_elements_par_page);
                    @$page = $_GET["numpage"];
                    // Verif validité 
                    if(empty($page)){
                        $page = 1;
                    }
                    $page = max(1, min($pages, $page));
                    $debut = ($page - 1) * $nb_elements_par_page;
                    // Nombre de pages à afficher avant et après la page courante
                    $pagesAffiche = 1;
                    // Calcul du début et de la fin de la plage de pages à afficher
                    $startPage = max(1, $page - $pagesAffiche);
                    $endPage = min($pages, $page + $pagesAffiche);

                    //recup param de l'url
                    $params = $_GET;
                    if(isset($params['modalModifBilletterie'])){
                        unset($params['modalModifBilletterie']);
                    }
                    if(isset($params['modalSupprBilletterie'])){
                        unset($params['modalSupprBilletterie']);
                    }
                    if(isset($params['modalAfficherBilletterie'])){
                        unset($params['modalAfficherBilletterie']);
                    }
                    

                    $params['modalAjoutBilletterie'] = "offre";
                    $urlajout = http_build_query($params);
                    unset($params['modalAjoutBilletterie']);
                    ?>
                    <div class="billetterie">

                    <?= isset($msgvalidation) ? $msgvalidation : null ?>
                    <div class="titlePage"> 
                        <h1>Modifier les offres</h1>
                        <div class="addBtn"><a href="backoffice.php?<?= $urlajout ?>"><div>Ajouter</div></a></div>
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
                                <th class="addPart"><a href="backoffice.php?<?= $urlajout ?>"><div>Ajouter</div></a></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            try{
                                $req = $connexion->prepare("SELECT * FROM offre ORDER BY Date_Debut_Offre DESC LIMIT $debut, $nb_elements_par_page");
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
                                        <td data-title="Nom"><?php echo $offre["Nom_Offre"] ?></td>
                                        <td data-title="Description" class="colonneDescription"><div><?php echo $offre["Description_Offre"] ?></div></td>
                                        <?php 
                                            $monthsEnglish = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                                            $monthsFrench = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
                                            
                                            $datedeb = $offre['Date_Debut_Offre'];
                                            $datefin = $offre['Date_Fin_Offre'];
                                            
                                            $datedeb_formattee = strftime("%d %B %Y", strtotime($datedeb));
                                            $datedeb_formattee = explode(" ", $datedeb_formattee);
                                            for ($i = 0; $i < count($monthsEnglish); $i++) {
                                                if ($datedeb_formattee[1] == $monthsEnglish[$i]) {
                                                    $datedeb_formattee[1] = $monthsFrench[$i];
                                                }
                                            }
                                            $datedeb_formattee = implode(" ", $datedeb_formattee);
                                            
                                            $datefin_formattee = strftime("%d %B %Y", strtotime($datefin));
                                            $datefin_formattee = explode(" ", $datefin_formattee);
                                            for ($i = 0; $i < count($monthsEnglish); $i++) {
                                                if ($datefin_formattee[1] == $monthsEnglish[$i]) {
                                                    $datefin_formattee[1] = $monthsFrench[$i];
                                                }
                                            }
                                            $datefin_formattee = implode(" ", $datefin_formattee);
                                        ?>
                                        <td data-title="Dates"><?= $datedeb_formattee." - ".$datefin_formattee ?></td>
                                        <td data-title="Partenaires"><?= !empty($offre["Id_Partenaire"]) ? $NomPart['Nom_Partenaire'] : "Aucun partenaire Associé" ?></td>
                                        <td data-title="Places"><?php echo $offre['Nombre_Place_Min_Offre']?><?= $offre['Nombre_Place_Min_Offre'] > 1 ? " places" : " place" ?></td>
                                        <td data-title="Images"><?php echo $nbImgOffre?><?= $nbImgOffre > 1 ? " images" : " image" ?></td>
                                        <td data-title="Action" class="actionBtn"> 
                                            <?php 

                                            //Creation GET + Construct url
                                            $params['modalModifBilletterie'] = $offre["Id_Offre"];
                                            $urlmodif = http_build_query($params);
                                            unset($params['modalModifBilletterie']); // je suppr la colonne pour pas l'avoir dans urlsuppr

                                            $params['modalSupprBilletterie'] = $offre["Id_Offre"];
                                            $urlsuppr = http_build_query($params);
                                            unset($params['modalSupprBilletterie']);

                                            $params['modalAfficherBilletterie'] = $offre["Id_Offre"];
                                            $urlafficher = http_build_query($params);
                                            unset($params['modalAfficherBilletterie']);

                                            ?>
                                            <a href="backoffice.php?<?= $urlafficher; ?>" class="modifBtn afficherBtn">Afficher</a>
                                            <a href="backoffice.php?<?= $urlmodif; ?>" class="modifBtn">Modifier</a>
                                            <a href="backoffice.php?<?= $urlsuppr; ?>" class="supprBtn">Supprimer</a>
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
                    <div class="pagination">
                    <?php
                        // Vérification si les points de suspension doivent être affichés au début
                        if ($startPage > 1) {
                            // Afficher la première page
                            echo '<a href="?page=billetterie&numpage=1"><span class="page">1</span></a>';
                            // Afficher les points de suspension au début
                            if ($startPage > 2 && $page >= 3) {
                                echo '<a><span class="page" id="troisPoints">...</span></a>';
                            }
                        }
                        // Affichage des numéros de page ou des points de suspension pour les pages au milieu
                        for ($pag = $startPage; $pag <= $endPage; $pag++) {
                            if($page != $pag){ 
                                echo '<a href="?page=billetterie&numpage='.$pag.'"><span class="page">' . $pag . '</span></a>';
                            }else{
                                echo '<a href="?page=billetterie&numpage='.$pag.'"><span class="page activepage">' . $pag . '</span></a>';
                            }
                        }
                        // Vérification si les points de suspension doivent être affichés à la fin
                        if ($endPage < $pages) {
                            // Afficher les points de suspension à la fin
                            if ($endPage < $pages - 1) {
                                echo '<a><span class="page" id="troisPoints">...</span></a>';
                            }
                            // Afficher la dernière page
                            echo '<a href="?page=billetterie&numpage='.$pages.'"><span class="page">'.$pages.'</span></a>';
                        }
                        ?>
                        
                    </div>
                    </div>

            <?php }else if($_GET['page'] === "message"){ ?>
                <?php 
                    $count = $connexion -> prepare("SELECT COUNT(Id_Message)  as infos FROM message");
                    $count->setFetchMode(PDO::FETCH_ASSOC);
                    $count -> execute();
                    $tcount = $count->fetchAll();
                    
                    $nb_elements_par_page = 7;
                    $pages =ceil($tcount[0]['infos']/$nb_elements_par_page);
                    @$page = $_GET["numpage"];
                    // Verif validité 
                    if(empty($page)){
                        $page = 1;
                    }
                    $page = max(1, min($pages, $page));
                    $debut = ($page - 1) * $nb_elements_par_page;
                    // Nombre de pages à afficher avant et après la page courante
                    $pagesAffiche = 1;
                    // Calcul du début et de la fin de la plage de pages à afficher
                    $startPage = max(1, $page - $pagesAffiche);
                    $endPage = min($pages, $page + $pagesAffiche);

                    //recup param de l'url
                    $params = $_GET;
                    if(isset($params['modaAfficherMessage'])){
                        unset($params['modaAfficherMessage']);
                    }
                    if(isset($params['modalSupprMessage'])){
                        unset($params['modalSupprMessage']);
                    }
                ?>
                <div class="message">

                <?= isset($msgvalidation) ? $msgvalidation : null ?>
                <div class="titlePage"> 
                    <h1>Tous les messages</h1>
                </div>
                <div class="tablemessages">
                <table>
                    <thead>
                        <tr>
                            <th class="tableNom">Nom Prénom</th>
                            <th class="tableEmail">Email</th>
                            <th class="tableContenu">Contenu</th>
                            <th class="tableOffre">Offre associée</th>
                            <th class="tablePart">Partenaire associé</th>
                            <th class="tableAction">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        try{
                            $req = $connexion->prepare("SELECT * FROM message LIMIT $debut, $nb_elements_par_page");
                            $req->execute();
                            $messages= $req->fetchAll();
                            foreach($messages as $message){
                                if(!empty($message["Id_Offre"])){
                                    $req = $connexion->prepare("SELECT Nom_Offre FROM offre WHERE Id_Offre = :id");
                                    $req->bindParam('id',$message["Id_Offre"]);
                                    $req->execute();
                                    $Offre= $req->fetch();
                                }
                                if(!empty($message["Id_Partenaire"])){
                                    $req = $connexion->prepare("SELECT Nom_Partenaire FROM partenaire WHERE Id_Partenaire = :id");
                                    $req->bindParam('id',$message["Id_Partenaire"]);
                                    $req->execute();
                                    $Partenaire= $req->fetch();
                                }
                                ?>
                                <tr>
                                    <td data-title="Nom Prénom"><?php echo $message["Nom_Message"]." ".$message["Prenom_Message"]  ?></td>
                                    <td data-title="Email" class="colonneEmail"><a href="mailto:"><?php echo $message["Email_Message"] ?></a></td>
                                    <td data-title="Contenu" class="colonneContenu"><div><?php echo $message["Contenu_Message"] ?></div></td>
                                    <td data-title="Offre"><?= !empty($message["Id_Offre"]) ? $Offre['Nom_Offre'] : "Aucune offre associée" ?></td>
                                    <td data-title="Partenaire"><?= !empty($message["Id_Partenaire"]) ? $Partenaire['Nom_Partenaire'] : "Aucun partenaire associé" ?></td>
                                    <td data-title="Action" class="actionBtn">  
                                        <?php
                                            $params['modalAfficherMessage'] = $message["Id_Message"];
                                            $urlafficher = http_build_query($params);
                                            unset($params['modalAfficherMessage']);

                                            $params['modalSupprMessage'] = $message["Id_Message"];
                                            $urlsuppr = http_build_query($params);
                                            unset($params['modalSupprMessage']);
                                        ?>
                                        <a href="backoffice.php?<?= $urlafficher; ?>" class="afficherBtn">Afficher</a>
                                        <a href="backoffice.php?<?= $urlsuppr; ?>" class="supprBtn">Supprimer</a>
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
                <div class="pagination">
                        <?php
                        // Vérification si les points de suspension doivent être affichés au début
                        if ($startPage > 1) {
                            // Afficher la première page
                            echo '<a href="?page=message&numpage=1"><span class="page">1</span></a>';
                            // Afficher les points de suspension au début
                            if ($startPage > 2 && $page >= 3) {
                                echo '<a><span class="page" id="troisPoints">...</span></a>';
                            }
                        }
                        // Affichage des numéros de page ou des points de suspension pour les pages au milieu
                        for ($pag = $startPage; $pag <= $endPage; $pag++) {
                            if($page != $pag){ 
                                echo '<a href="?page=message&numpage='.$pag.'"><span class="page">' . $pag . '</span></a>';
                            }else{
                                echo '<a href="?page=message&numpage='.$pag.'"><span class="page activepage">' . $pag . '</span></a>';
                            }
                        }
                        // Vérification si les points de suspension doivent être affichés à la fin
                        if ($endPage < $pages) {
                            // Afficher les points de suspension à la fin
                            if ($endPage < $pages - 1) {
                                echo '<a><span class="page" id="troisPoints">...</span></a>';
                            }
                            // Afficher la dernière page
                            echo '<a href="?page=message&numpage='.$pages.'"><span class="page">'.$pages.'</span></a>';
                        }
                        ?>
                       
                    </div>
                </div><?php 
            }else if($_GET['page'] === "gestion"){ ?>
                <?php 
                    $count = $connexion -> prepare("SELECT COUNT(Id_Utilisateur)  as infos FROM utilisateur");
                    $count->setFetchMode(PDO::FETCH_ASSOC);
                    $count -> execute();
                    $tcount = $count->fetchAll();
                    
                    $nb_elements_par_page = 7;
                    $pages =ceil($tcount[0]['infos']/$nb_elements_par_page);
                    @$page = $_GET["numpage"];
                    // Verif validité 
                    if(empty($page)){
                        $page = 1;
                    }
                    $page = max(1, min($pages, $page));
                    $debut = ($page - 1) * $nb_elements_par_page;
                    // Nombre de pages à afficher avant et après la page courante
                    $pagesAffiche = 1;
                    // Calcul du début et de la fin de la plage de pages à afficher
                    $startPage = max(1, $page - $pagesAffiche);
                    $endPage = min($pages, $page + $pagesAffiche);

                    //recup param de l'url
                    $params = $_GET;
                    if(isset($params['modalModifUtilisateur'])){
                        unset($params['modalModifUtilisateur']);
                    }
                    if(isset($params['modalSupprUtilisateur'])){
                        unset($params['modalSupprUtilisateur']);
                    }
                    $params['modalAjoutUtilisateur'] = "user";
                    $urlajout = http_build_query($params);
                    unset($params['modalAjoutUtilisateur']);
                ?>
                <div class="message">

                <?= isset($msgvalidation) ? $msgvalidation : null ?>
                <div class="titlePage"> 
                    <h1>Tous les Administrateurs</h1>
                    <div class="addBtn"><a href="backoffice.php?<?= $urlajout ?>"><div>Ajouter</div></a></div>
                </div>
                <div class="tablemessages">
                <table>
                    <thead>
                        <tr>
                            <th class="tableNom">Nom</th>
                            <th class="tablePrenom">Prénom</th>
                            <th class="tableEmail">Email</th>
                            <th class="tableOffre">Niveau</th>
                            <th class="tableAction">Action</th>
                            <th class="addPart"><a href="backoffice.php?<?= $urlajout ?>"><div>Ajouter</div></a></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        try{
                            $req = $connexion->prepare("SELECT Id_Utilisateur, Nom_Utilisateur, Prenom_Utilisateur, Email_Utilisateur, Id_Droit FROM utilisateur LIMIT $debut, $nb_elements_par_page");
                            $req->execute();
                            $users = $req->fetchAll();
                            foreach($users as $user){
                                if(!empty($user["Id_Droit"])){
                                    $req = $connexion->prepare("SELECT Libelle_Droit FROM droit WHERE Id_Droit = :id");
                                    $req->bindParam('id',$user["Id_Droit"]);
                                    $req->execute();
                                    $Droit= $req->fetch();
                                }
                                ?>
                                <tr>
                                    <td data-title="Nom"><?php echo $user["Nom_Utilisateur"] ?></td>
                                    <td data-title="Prénom"><?php echo $user["Prenom_Utilisateur"] ?></td>
                                    <td data-title="Email"><a href="mailto:"><?php echo $user["Email_Utilisateur"] ?></a></td>
                                    <td data-title="Niveau"><?= !empty($user["Id_Droit"]) ? $Droit['Libelle_Droit'] : "Aucun droit associé" ?></td>
                                    <td data-title="Action" class="actionBtn">  
                                        <?php
                                            $params['modalModifUtilisateur'] = $user["Id_Utilisateur"];
                                            $urlmodif = http_build_query($params);
                                            unset($params['modalModifUtilisateur']);

                                            $params['modalSupprUtilisateur'] = $user["Id_Utilisateur"];
                                            $urlsuppr = http_build_query($params);
                                            unset($params['modalSupprUtilisateur']);
                                        ?>
                                        <a href="backoffice.php?<?= $urlmodif; ?>" class="modifBtn">Modifier</a>
                                        <a href="backoffice.php?<?= $urlsuppr; ?>" class="supprBtn">Supprimer</a>
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
                <div class="pagination">
                        <?php
                        // Vérification si les points de suspension doivent être affichés au début
                        if ($startPage > 1) {
                            // Afficher la première page
                            echo '<a href="?page=gestion&numpage=1"><span class="page">1</span></a>';
                            // Afficher les points de suspension au début
                            if ($startPage > 2 && $page >= 3) {
                                echo '<a><span class="page" id="troisPoints">...</span></a>';
                            }
                        }
                        // Affichage des numéros de page ou des points de suspension pour les pages au milieu
                        for ($pag = $startPage; $pag <= $endPage; $pag++) {
                            if($page != $pag){ 
                                echo '<a href="?page=gestion&numpage='.$pag.'"><span class="page">' . $pag . '</span></a>';
                            }else{
                                echo '<a href="?page=gestion&numpage='.$pag.'"><span class="page activepage">' . $pag . '</span></a>';
                            }
                        }
                        // Vérification si les points de suspension doivent être affichés à la fin
                        if ($endPage < $pages) {
                            // Afficher les points de suspension à la fin
                            if ($endPage < $pages - 1) {
                                echo '<a><span class="page" id="troisPoints">...</span></a>';
                            }
                            // Afficher la dernière page
                            echo '<a href="?page=gestion&numpage='.$pages.'"><span class="page">'.$pages.'</span></a>';
                        }
                        ?>
                        
                    </div>
                </div>
                <?php 
            }
        }

    }else{ ?>
    <div class="accueil">
        <?= isset($msgvalidation) ? $msgvalidation : null ?>
        <div class="titlePage"> 
            <h1>Modifier les informations de&nbsp;la page&nbsp;d'accueil</h1>
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
    }else{ ?>
        <header>
            <div class="graynav"></div>
            <nav>
                <div class="bgLogo">
                    <a href="index.php">
                        <img class="img_base" src="assets/logo_lycee.png" alt="logo_st_vincent">
                        <img class="img_responsive" src="assets/Logo_St_Vincent_2.jpg" alt="logo_st_vincent_responsive">
                    </a>
                </div>
                <ul class="links"> <?php
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
                    <li class="logoutLi">
                        <form method="post" action="BackofficePHP/logout.php" class="logoutForm">
                            <button type="submit" value="Logout"><img class="logout" src="assets/logout.png" alt="Se deconnecter" ></button>
                        </form>
                    </li>
                </ul>
                <form method="post" action="BackofficePHP/logout.php" class="logoutDiv">
                    <button type="submit" value="Logout"><img class="logout" src="assets/logout.png" alt="Se deconnecter" ></button>
                </form>
                <img class="menu-burger" src="assets/menu.png" alt="menu-burger">
                <script src="scriptMenuBurger.js"></script>
            </nav>
        </header>
        <main> <?php
        if(!empty($_GET) && $_GET['page'] !== "accueil"){
            if(!empty($_GET['page']) && $_GET['page'] !== "accueil"){
                ?><style>
                    .actionBtn{
                        display: table-cell;
                    }

                </style> <?php
                if($_GET['page'] === "partenaires"){
                        $count = $connexion -> prepare("SELECT COUNT(Id_Partenaire)  as infos FROM partenaire");
                        $count->setFetchMode(PDO::FETCH_ASSOC);
                        $count -> execute();
                        $tcount = $count->fetchAll();
                        
                        $nb_elements_par_page = 7;
                        $pages =ceil($tcount[0]['infos']/$nb_elements_par_page);
                        @$page = $_GET["numpage"];
                        // Verif validité 
                        if(empty($page)){
                            $page = 1;
                        }
                        $page = max(1, min($pages, $page));
                        $debut = ($page - 1) * $nb_elements_par_page;
                        // Nombre de pages à afficher avant et après la page courante
                        $pagesAffiche = 1;
                        // Calcul du début et de la fin de la plage de pages à afficher
                        $startPage = max(1, $page - $pagesAffiche);
                        $endPage = min($pages, $page + $pagesAffiche);


                        //recup param de l'url
                        $params = $_GET;


                    ?>
                    <div class="partenaires">

                    <?= isset($msgvalidation) ? $msgvalidation : null ?>
                    <div class="titlePage"> 
                        <h1>Les partenaires</h1>
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
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            try{
                                $req = $connexion->prepare("SELECT * FROM partenaire LIMIT $debut, $nb_elements_par_page");
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
                                        <td data-title="Nom"><?php echo $partenaire["Nom_Partenaire"] ?></td>
                                        <td data-title="Description" class="colonneDescription"><div><?php echo $partenaire["Description_Partenaire"] ?></div></td>
                                        <td data-title="Site" class="colonneLien"><a href="<?php echo $partenaire["Lien_Partenaire"] ?>" target="_blank"><?php echo $partenaire["Lien_Partenaire"] ?></a></td>
                                        <td data-title="Image" class="imgPart"><?= !empty($partenaire["Id_Image"]) ? '<div  style="display: flex;height: 100%;justify-content: center;"><img src="assets/'.$imgPart["Nom_Image"].'" alt="Image du partenaire"></div>' : "Aucune image" ?></td>
                                        <td data-title="Action" class="actionBtn">  
                                            <?php 
                                            $params['modalAfficherPartenaire'] = $partenaire["Id_Partenaire"];
                                            $urlafficher = http_build_query($params);
                                            unset($params['modalAfficherPartenaire']);

                                            ?>
                                            <a href="backoffice.php?<?= $urlafficher; ?>" class="modifBtn afficherBtn">Afficher</a>
                                            
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
                    <div class="pagination">
                        <?php
                        // Vérification si les points de suspension doivent être affichés au début
                        if ($startPage > 1) {
                            // Afficher la première page
                            echo '<a href="?page=partenaires&numpage=1"><span class="page">1</span></a>';
                            // Afficher les points de suspension au début
                            if ($startPage > 2 && $page >= 3) {
                                echo '<a><span class="page" id="troisPoints">...</span></a>';
                            }
                        }
                        // Affichage des numéros de page ou des points de suspension pour les pages au milieu
                        for ($pag = $startPage; $pag <= $endPage; $pag++) {
                            if($page != $pag){ 
                                echo '<a href="?page=partenaires&numpage='.$pag.'"><span class="page">' . $pag . '</span></a>';
                            }else{
                                echo '<a href="?page=partenaires&numpage='.$pag.'"><span class="page activepage">' . $pag . '</span></a>';
                            }
                        }
                        // Vérification si les points de suspension doivent être affichés à la fin
                        if ($endPage < $pages) {
                            // Afficher les points de suspension à la fin
                            if ($endPage < $pages - 1) {
                                echo '<a><span class="page" id="troisPoints">...</span></a>';
                            }
                            // Afficher la dernière page
                            echo '<a href="?page=partenaires&numpage='.$pages.'"><span class="page">'.$pages.'</span></a>';
                        }
                        ?>
                        
                    </div>
                    </div>
                    


            <?php }else if($_GET['page'] === "billetterie"){ ?>
                    <?php 
                    $count = $connexion -> prepare("SELECT COUNT(Id_Offre)  as infos FROM offre");
                    $count->setFetchMode(PDO::FETCH_ASSOC);
                    $count -> execute();
                    $tcount = $count->fetchAll();
                    
                    $nb_elements_par_page = 7;
                    $pages =ceil($tcount[0]['infos']/$nb_elements_par_page);
                    @$page = $_GET["numpage"];
                    // Verif validité 
                    if(empty($page)){
                        $page = 1;
                    }
                    $page = max(1, min($pages, $page));
                    $debut = ($page - 1) * $nb_elements_par_page;
                    // Nombre de pages à afficher avant et après la page courante
                    $pagesAffiche = 1;
                    // Calcul du début et de la fin de la plage de pages à afficher
                    $startPage = max(1, $page - $pagesAffiche);
                    $endPage = min($pages, $page + $pagesAffiche);


                    //recup param de l'url
                    $params = $_GET;
                    ?>
                    <div class="billetterie">

                    <?= isset($msgvalidation) ? $msgvalidation : null ?>
                    <div class="titlePage"> 
                        <h1>Les offres</h1>
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
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            try{
                                $req = $connexion->prepare("SELECT * FROM offre LIMIT $debut, $nb_elements_par_page");
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
                                        <td data-title="Nom"><?php echo $offre["Nom_Offre"] ?></td>
                                        <td data-title="Description" class="colonneDescription"><div><?php echo $offre["Description_Offre"] ?></div></td>
                                        <?php 
                                            $monthsEnglish = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                                            $monthsFrench = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
                                            
                                            $datedeb = $offre['Date_Debut_Offre'];
                                            $datefin = $offre['Date_Fin_Offre'];
                                            
                                            $datedeb_formattee = strftime("%d %B %Y", strtotime($datedeb));
                                            $datedeb_formattee = explode(" ", $datedeb_formattee);
                                            for ($i = 0; $i < count($monthsEnglish); $i++) {
                                                if ($datedeb_formattee[1] == $monthsEnglish[$i]) {
                                                    $datedeb_formattee[1] = $monthsFrench[$i];
                                                }
                                            }
                                            $datedeb_formattee = implode(" ", $datedeb_formattee);
                                            
                                            $datefin_formattee = strftime("%d %B %Y", strtotime($datefin));
                                            $datefin_formattee = explode(" ", $datefin_formattee);
                                            for ($i = 0; $i < count($monthsEnglish); $i++) {
                                                if ($datefin_formattee[1] == $monthsEnglish[$i]) {
                                                    $datefin_formattee[1] = $monthsFrench[$i];
                                                }
                                            }
                                            $datefin_formattee = implode(" ", $datefin_formattee);
                                        ?>
                                        <td data-title="Dates"><?= $datedeb_formattee." - ".$datefin_formattee ?></td>
                                        <td data-title="Partenaires"><?= !empty($offre["Id_Partenaire"]) ? $NomPart['Nom_Partenaire'] : "Aucun partenaire Associé" ?></td>
                                        <td data-title="Places"><?php echo $offre['Nombre_Place_Min_Offre']?><?= $offre['Nombre_Place_Min_Offre'] > 1 ? " places" : " place" ?></td>
                                        <td data-title="Images"><?php echo $nbImgOffre?><?= $nbImgOffre > 1 ? " images" : " image" ?></td>
                                        <td data-title="Action" class="actionBtn"> 
                                            <?php 
                                            $params['modalAfficherBilletterie'] = $offre["Id_Offre"];
                                            $urlafficher = http_build_query($params);
                                            unset($params['modalAfficherBilletterie']);

                                            ?>
                                            <a href="backoffice.php?<?= $urlafficher; ?>" class="modifBtn afficherBtn">Afficher</a>
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
                    <div class="pagination">
                        <?php
                        // Vérification si les points de suspension doivent être affichés au début
                        if ($startPage > 1) {
                            // Afficher la première page
                            echo '<a href="?page=billetterie&numpage=1"><span class="page">1</span></a>';
                            // Afficher les points de suspension au début
                            if ($startPage > 2 && $page >= 3) {
                                echo '<a><span class="page" id="troisPoints">...</span></a>';
                            }
                        }
                        // Affichage des numéros de page ou des points de suspension pour les pages au milieu
                        for ($pag = $startPage; $pag <= $endPage; $pag++) {
                            if($page != $pag){ 
                                echo '<a href="?page=billetterie&numpage='.$pag.'"><span class="page">' . $pag . '</span></a>';
                            }else{
                                echo '<a href="?page=billetterie&numpage='.$pag.'"><span class="page activepage">' . $pag . '</span></a>';
                            }
                        }
                        // Vérification si les points de suspension doivent être affichés à la fin
                        if ($endPage < $pages) {
                            // Afficher les points de suspension à la fin
                            if ($endPage < $pages - 1) {
                                echo '<a><span class="page" id="troisPoints">...</span></a>';
                            }
                            // Afficher la dernière page
                            echo '<a href="?page=billetterie&numpage='.$pages.'"><span class="page">'.$pages.'</span></a>';
                        }
                        ?>
                    </div>
                    </div>

            <?php }else if($_GET['page'] === "message"){ ?>
                <?php 
                    $count = $connexion -> prepare("SELECT COUNT(Id_Message)  as infos FROM message");
                    $count->setFetchMode(PDO::FETCH_ASSOC);
                    $count -> execute();
                    $tcount = $count->fetchAll();
                    
                    $nb_elements_par_page = 7;
                    $pages =ceil($tcount[0]['infos']/$nb_elements_par_page);
                    @$page = $_GET["numpage"];
                    // Verif validité 
                    if(empty($page)){
                        $page = 1;
                    }
                    $page = max(1, min($pages, $page));
                    $debut = ($page - 1) * $nb_elements_par_page;
                    // Nombre de pages à afficher avant et après la page courante
                    $pagesAffiche = 1;
                    // Calcul du début et de la fin de la plage de pages à afficher
                    $startPage = max(1, $page - $pagesAffiche);
                    $endPage = min($pages, $page + $pagesAffiche);

                    //recup param de l'url
                    $params = $_GET;
                ?>
                <div class="message">

                <?= isset($msgvalidation) ? $msgvalidation : null ?>
                <div class="titlePage"> 
                    <h1>Tous les messages</h1>
                </div>
                <div class="tablemessages">
                <table>
                    <thead>
                        <tr>
                            <th class="tableNom">Nom Prénom</th>
                            <th class="tableEmail">Email</th>
                            <th class="tableContenu">Contenu</th>
                            <th class="tableOffre">Offre associée</th>
                            <th class="tablePart">Partenaire associé</th>
                            <th class="tableAction">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        try{
                            $req = $connexion->prepare("SELECT * FROM message LIMIT $debut, $nb_elements_par_page");
                            $req->execute();
                            $messages= $req->fetchAll();
                            foreach($messages as $message){
                                if(!empty($message["Id_Offre"])){
                                    $req = $connexion->prepare("SELECT Nom_Offre FROM offre WHERE Id_Offre = :id");
                                    $req->bindParam('id',$message["Id_Offre"]);
                                    $req->execute();
                                    $Offre= $req->fetch();
                                }
                                if(!empty($message["Id_Partenaire"])){
                                    $req = $connexion->prepare("SELECT Nom_Partenaire FROM partenaire WHERE Id_Partenaire = :id");
                                    $req->bindParam('id',$message["Id_Partenaire"]);
                                    $req->execute();
                                    $Partenaire= $req->fetch();
                                }
                                ?>
                                <tr>
                                    <td data-title="Nom Prénom"><?php echo $message["Nom_Message"]." ".$message["Prenom_Message"]  ?></td>
                                    <td data-title="Email" class="colonneEmail"><a href="mailto:"><?php echo $message["Email_Message"] ?></a></td>
                                    <td data-title="Contenu" class="colonneContenu"><div><?php echo $message["Contenu_Message"] ?></div></td>
                                    <td data-title="Offre"><?= !empty($message["Id_Offre"]) ? $Offre['Nom_Offre'] : "Aucune offre associée" ?></td>
                                    <td data-title="Partenaire"><?= !empty($message["Id_Partenaire"]) ? $Partenaire['Nom_Partenaire'] : "Aucun partenaire associé" ?></td>
                                    <td data-title="Action" class="actionBtn">  
                                        <?php
                                            $params['modalAfficherMessage'] = $message["Id_Message"];
                                            $urlafficher = http_build_query($params);
                                            unset($params['modalAfficherMessage']);
                                        ?>
                                        <a href="backoffice.php?<?= $urlafficher; ?>" class="afficherBtn">Afficher</a>
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
                <div class="pagination">
                        <?php
                        // Vérification si les points de suspension doivent être affichés au début
                        if ($startPage > 1) {
                            // Afficher la première page
                            echo '<a href="?page=message&numpage=1"><span class="page">1</span></a>';
                            // Afficher les points de suspension au début
                            if ($startPage > 2 && $page >= 3) {
                                echo '<a><span class="page" id="troisPoints">...</span></a>';
                            }
                        }
                        // Affichage des numéros de page ou des points de suspension pour les pages au milieu
                        for ($pag = $startPage; $pag <= $endPage; $pag++) {
                            if($page != $pag){ 
                                echo '<a href="?page=message&numpage='.$pag.'"><span class="page">' . $pag . '</span></a>';
                            }else{
                                echo '<a href="?page=message&numpage='.$pag.'"><span class="page activepage">' . $pag . '</span></a>';
                            }
                        }
                        // Vérification si les points de suspension doivent être affichés à la fin
                        if ($endPage < $pages) {
                            // Afficher les points de suspension à la fin
                            if ($endPage < $pages - 1) {
                                echo '<a><span class="page" id="troisPoints">...</span></a>';
                            }
                            // Afficher la dernière page
                            echo '<a href="?page=message&numpage='.$pages.'"><span class="page">'.$pages.'</span></a>';
                        }
                        ?>
                    </div>
                </div><?php 
            }

            }
        }else{
            ?>
            <div class="accueil">
                <?= isset($msgvalidation) ? $msgvalidation : null ?>
                <div class="titlePage"> 
                    <h1>Les informations de&nbsp;la page&nbsp;d'accueil</h1>
                </div>
                <div class="formEdit">
                    <form name="accueilEdit">
                        <?php 
                            $req = $connexion->prepare("SELECT * FROM info_accueil");
                            $req->execute();
                            $infoAccueil = $req->fetch();
                        ?>
                            <label for="tel">Numéro de téléphone<span>*</span> :</label>
                            <input type="tel" name="accueilEdit[phone]" id="phone" required="" value="<?php echo $infoAccueil['Num_Tel_Info_Accueil'] ?>" disabled="disabled">
                            <?= isset($erreursaccueilEdit['phone']) ? $erreursaccueilEdit['phone'] : null ?>
        
                            <label for="email">Email<span>*</span> :</label>
                            <input type="email" name="accueilEdit[email]" id="email" required="" value="<?php echo $infoAccueil['Email_Info_Accueil'] ?>" disabled="disabled" >
                            <?= isset($erreursaccueilEdit['email']) ? $erreursaccueilEdit['email'] : null ?>
                            
                            <label for="bureau">Emplacement du Bureau<span>*</span> :</label>
                            <textarea type="text" name="accueilEdit[bureau]" id="bureau" required="" disabled="disabled" ><?php echo $infoAccueil['Emplacement_Bureau_Info_Accueil'] ?></textarea>
                            <?= isset($erreursaccueilEdit['bureau']) ? $erreursaccueilEdit['bureau'] : null ?>
                            
                            <label for="titre">Titre de la page<span>*</span> :</label>
                            <input type="text" name="accueilEdit[titre]" id="titre" required="" value="<?php echo $infoAccueil['Titre_Info_Accueil'] ?>" disabled="disabled" >
                            <?= isset($erreursaccueilEdit['titre']) ? $erreursaccueilEdit['titre'] : null ?>
                            
                            <label for="description">Description de la page<span>*</span> :</label>
                            <textarea type="text" name="accueilEdit[description]" id="description" required="" disabled="disabled" ><?php echo $infoAccueil['Texte_Info_Accueil'] ?></textarea>
                            <?= isset($erreursaccueilEdit['description']) ? $erreursaccueilEdit['description'] : null ?>
                    </form>
                </div>
            </div>
            <?php 
        }

    }

}?>

<!-- Fin Page HTML -->
<script src="scriptBackoffice.js"></script>
</div>
</body>
</html>