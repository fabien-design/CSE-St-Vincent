<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@700&display=swap" rel="stylesheet">
    <link rel="icon" href="assets/sv_logo.png">
    <title>CSE Saint-Vincent - Accueil</title>
</head>

<body>
    <header>
        <div class="gris">

        </div>
        <div class="blue">
            <nav>
                <div class="logo"><img src="assets/logo_lycee.png" alt="logo_st_vincent"></div>
                <ul>
                    <a href="index.php">
                        <li class="active">
                            Accueil
                        </li>
                    </a>
                    <a href="partenariats.php">
                        <li>Partenariats</li>
                    </a>
                    <a href="billetterie.php">
                        <li>Billetterie</li>
                    </a>
                    <a href="contact.php">
                        <li>Contact</li>
                    </a>
                </ul>

            </nav>
        </div>
    </header>

    <main>
        <?php require 'include/aside.php'?>
        <div class="right">
            <div class="info_accueil">
                <h2 class="titre">CSE Lycée Saint-Vincent</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quisquam quas veritatis mollitia libero
                    provident eius. <br>Perferendis eos aperiam pariatur accusantium minus quo vitae! Totam voluptate
                    soluta necessitatibus harum praesentium officia.</p>
            </div>
            <h1>Dernières offres de la Billetterie</h1>
            <div class="offre_billetterie">
                <div class="offre_billetterie_header">
                    <span class="tag_offre">OFFRE</span>
                    <span class="date_offre">Publiée le 11 septembre 2001</span>
                </div>
                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Minima, delectus et numquam amet quis
                    cumque natus sunt quos odit similique itaque enim sint atque, mollitia quidem officia a nulla! Vel.
                </p>
                <a href="">
                    <span class="offre_learnmore">EN SAVOIR PLUS <img class="chevron-droit"
                            src="assets/chevron-droit.png" alt="chevron-droit"></span>
                </a>
            </div>
            <a target="_blank" href="billetterie.php">
                <span id="offres_decouvrir">Découvrir toutes nos offres 〉</span>
            </a>
        </div>

    </main>
    <?php require 'include/footer.php'?>
    <script src="scriptaside.js"></script>
</body>

</html>