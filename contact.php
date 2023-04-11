<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="styleContact.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@700&display=swap" rel="stylesheet">
    <link rel="icon" href="assets/sv_logo.png">
    <title>CSE Saint-Vincent - Contact</title>
</head>

<body id="body" class="no-transition">
    <?php require 'include/header.php'?>
    <main>
        <?php require 'include/aside.php'?>
        <div class="right">
            <h1>Page de contact</h1>

            <section class="contact" id="linkToContact">
		<div class="contactForm">
				<form action="">
					<label for="fname">Nom</label>
					<input type="text" id="fname" name="firstname" placeholder="Votre Nom">
				
                    <label for="lname">Prénom</label>
					<input type="text" id="lname" name="lastname" placeholder="Votre Prénom"> 

					<label for="lname">Email</label>
					<input type="text" id="lname" name="lastname" placeholder="Votre adresse Email"> 

					<label for="subject">Contenu</label>
					<textarea id="subject" name="subject" placeholder="Saisir votre message" style="height:200px"></textarea>
				
					<input type="submit" value="Soumettre">
				  </form>
		</div>
	</section>
        </div>
    </main>
    <?php require 'include/footer.php'?>
    <script src="scriptaside.js"></script>
</body>

</html>