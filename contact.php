<?php

require 'include/connexion_db.php';
$erreurs = [];

if (empty($_POST) === false) {

    // Vérification des données saisies
    if (empty($_POST['email'])) {
        $erreurs['email'] = 'Veuillez saisir une adresse email.';
    } else {
        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
            $erreurs['email'] = 'Veuillez saisir une adresse email valide.';
        }
    }

    if (empty($_POST['contenu'])) {
        $erreurs['contenu'] = 'Veuillez saisir un contenu.';
    } else {
        if (strlen($_POST['contenu']) > 2000) {
            $erreurs['contenu'] = 'Le contenu ne doit pas dépasser 2000 caractères.';
        }
    }

    $expressionReguliere = '/[\d\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/';

    if (empty($_POST['prenom']) === false) {
        if (preg_match($expressionReguliere, $_POST['prenom'])) {
            $erreurs['prenom'] = 'Le prénom ne doit pas contenir de chiffres et de caractères spéciaux.';
        }
    }

    if (empty($_POST['nom']) === false) {
        if (preg_match($expressionReguliere, $_POST['nom'])) {
            $erreurs['nom'] = 'Le nom ne doit pas contenir de chiffres et de caractères spéciaux.';
        }
    }

    if (isset($sujets[$_POST['sujet']]) === false) {
        $erreurs['sujet'] = 'Veuillez préciser un sujet valide.';
    }


    if (empty($erreurs)) {
        try {
            $requeteInsertion = $connexion->prepare('INSERT INTO contact (contact_nom, contact_prenom, contact_email, contact_sujet, contact_contenu) VALUES (:contact_nom, :contact_prenom, :contact_email, :contact_sujet, :contact_contenu)');
            $requeteInsertion->bindParam(':contact_nom', $_POST['nom']);
            $requeteInsertion->bindParam(':contact_prenom', $_POST['prenom']);
            $requeteInsertion->bindParam(':contact_email', $_POST['email']);
            $requeteInsertion->bindParam(':contact_sujet', $_POST['sujet']);
            $requeteInsertion->bindParam(':contact_contenu', $_POST['contenu']);

            $requeteInsertion->execute();

            echo 'Votre demande a bien été prise en compte.';
        } catch (\Exception $exception) {
            echo 'Erreur lors de l\'ajout de la demande de contact.';
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
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="responsive.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="styleContact.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@700&display=swap" rel="stylesheet">
    <link rel="icon" href="assets/sv_logo.png">
    <title>CSE Saint-Vincent - Contact</title>
</head>

<body id="body" class="no-transition">
    <?php require 'include/header.php' ?>
    <main>
        <?php require 'include/aside.php' ?>
        <div class="right_contact">
            <h1>Contactez-nous !</h1>

            <section class="contact" id="linkToContact">
                <div class="contactForm">

                    <form action="#" method="POST">

                            <label for="nom">Nom</label>
                            <input type="text" name="nom" value="<?= isset($_POST['nom']) ? $_POST['nom'] : null; ?>" placeholder="Votre Nom">
                            <?= isset($erreurs['nom']) ? $erreurs['nom'] : null; ?>

                            <label for="prenom">Prénom</label>
                            <input type="text" name="prenom" value="<?= isset($_POST['prenom']) ? $_POST['prenom'] : null; ?>" placeholder="Votre Prénom">
                            <?= isset($erreurs['prenom']) ? $erreurs['prenom'] : null; ?>

                            <label for="email">Email <span style="color: red;">*</span></label>
                            <?= isset($erreurs['email']) ? $erreurs['email'] : null; ?>
                            <input type="email" name="email" value="<?= isset($_POST['email']) ? $_POST['email'] : null; ?>" placeholder="Votre adresse Email">

                            <label for="contenu">Contenu <span style="color: red;">*</span></label>
                            <textarea name="contenu" placeholder="Saisir votre message"><?= isset($_POST['contenu']) ? $_POST['contenu'] : null; ?></textarea>
                            <?= isset($erreurs['contenu']) ? $erreurs['contenu'] : null; ?>

                        <div>
                            <input type="submit" name="validation" value="Soumettre">
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </main>
    <?php require 'include/footer.php' ?>
    <script src="scriptaside.js"></script>
</body>

</html>