<?php

require 'include/connexion_db.php';
$erreurs = [];

if (empty($_POST) === false) {

    // Vérification des données saisies
    if (empty($_POST['email'])) {
        $erreurs['email'] = '<span class="erreur">Veuillez saisir une adresse email.</span>';
    } else {
        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
            $erreurs['email'] = '<span class="erreur">Veuillez saisir une adresse email valide.</span>';
        }
    }

    if (empty($_POST['contenu'])) {
        $erreurs['contenu'] = '<span class="erreur">Veuillez saisir un contenu.';
    } else {
        if (strlen($_POST['contenu']) > 2000) {
            $erreurs['contenu'] = '<span class="erreur">Le contenu ne doit pas dépasser 2000 caractères.</span>';
        }
    }

    $expressionReguliere = '/[\d\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/';

    if (empty($_POST['prenom']) === false) {
        if (preg_match($expressionReguliere, $_POST['prenom'])) {
            $erreurs['prenom'] = '<span class="erreur">Le prénom ne doit pas contenir de chiffres et de caractères spéciaux.</span>';
        }
    }

    if (empty($_POST['nom']) === false) {
        if (preg_match($expressionReguliere, $_POST['nom'])) {
            $erreurs['nom'] = '<span class="erreur">Le nom ne doit pas contenir de chiffres et de caractères spéciaux.</span>';
        }
    }


    if (empty($erreurs)) {
        try {
            $requeteInsertion = $connexion->prepare('INSERT INTO message (Nom_Message, Prenom_Message, Email_Message, Contenu_Message) VALUES (:Nom_Message, :Prenom_Message, :Email_Message, :Contenu_Message)');
            $requeteInsertion->bindParam(':Nom_Message', $_POST['nom']);
            $requeteInsertion->bindParam(':Prenom_Message', $_POST['prenom']);
            $requeteInsertion->bindParam(':Email_Message', $_POST['email']);
            $requeteInsertion->bindParam(':Contenu_Message', $_POST['contenu']);

            $requeteInsertion->execute();

            $valider = '<span class="success">Votre message a bien été envoyé avec succès!</span>';
        } catch (\Exception $exception) {
            $valider = '<span class="erreur">Erreur lors de l\'envoi du message. Veuillez contactez un admin.</span>';
            // Debug de l'erreur :
            // var_dump($exception->getMessage());
        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="styles/styleContact.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@700&display=swap" rel="stylesheet">
    <link rel="icon" href="assets/sv_logo.png">
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    <title>CSE Saint-Vincent - Contact</title>
</head>

<body id="body" class="no-transition">
    <div class="bodyDiv">
        <?php require 'include/header.php' ?>
        <main>
            <?php require 'include/aside.php' ?>
            <div class="right_contact">
                <h1>Contactez-nous !</h1>

                <section class="contact" id="linkToContact">
                    <div class="contactForm">

                        <form action="#" method="POST">

                            <label for="nom">Nom</label>
                            <?= isset($erreurs['nom']) ? $erreurs['nom'] : null; ?>
                            <input type="text" name="nom" value="<?= isset($_POST['nom']) ? $_POST['nom'] : null; ?>" placeholder="Votre Nom (facultatif)">

                            <label for="prenom">Prénom</label>
                            <?= isset($erreurs['prenom']) ? $erreurs['prenom'] : null; ?>
                            <input type="text" name="prenom" value="<?= isset($_POST['prenom']) ? $_POST['prenom'] : null; ?>" placeholder="Votre Prénom (facultatif)">

                            <label for="email">Email <span style="color: red;">*</span></label>
                            <?= isset($erreurs['email']) ? $erreurs['email'] : null; ?>
                            <input type="email" name="email" value="<?= isset($_POST['email']) ? $_POST['email'] : null; ?>" placeholder="Votre adresse Email">

                            <label for="contenu">Contenu <span style="color: red;">*</span></label>
                            <?= isset($erreurs['contenu']) ? $erreurs['contenu'] : null; ?>
                            <textarea name="contenu" placeholder="Saisir votre message"><?= isset($_POST['contenu']) ? $_POST['contenu'] : null; ?></textarea>

                            <div class="cf-turnstile" data-sitekey="0x4AAAAAAAEgyIjb34yA_KXM" data-callback="javascriptCallback"></div>
                            <div style="margin-bottom: 1px;">
                                <input type="submit" name="validation" value="Soumettre">
                            </div>

                            <?= isset($valider) ? $valider : null; ?>
                        </form>
                    </div>
                </section>
            </div>
        </main>
        <?php require 'include/footer.php' ?>
        <script src="contactScript.js"></script>
        <script src="scriptaside.js"></script>
    </div>
</body>

</html>