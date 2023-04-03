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
    <title>CSE Saint-Vincent - Contact</title>
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
                        <li>
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
                        <li class="active">Contact</li>
                    </a>
                </ul>

            </nav>
        </div>
    </header>

    <main>
        <aside class="left">
            <div class="home">
                <img src="assets/homeIcon.png">
                <img src="assets/chevron-droit.png" class="chevron-droit">
                <a href="index.php" class="sectiontitle">Accueil</a>
            </div>
            <div class="fastaccess">
                <h1 class="sectiontitle">Accès rapide</h1>
                <div class="offreaccess">
                    <img src="assets/chevron-droit.png" class="chevron-droit">
                    <a href="billetterie.php">Offres / Billeterie</a>
                </div>
                <div class="contactaccess">
                    <img src="assets/chevron-droit.png" class="chevron-droit">
                    <a href="contact.php">Nous contacter</a>
                </div>
            </div>
            <div class="infocontact">
                <h1 class="sectiontitle">Informations de contact</h1>
                <div class="tel">
                    <p><img src="assets/chevron-droit.png" class="chevron-droit">
                        Par téléphone : <a target="_blank" href="tel:+3330303030303"><span>+3330303030303</span></a>
                    </p>
                </div>
                <div class="email">
                    <p><img src="assets/chevron-droit.png" class="chevron-droit">
                        Par email : <a target="_blank"
                            href="mailto:cse@lyceestvincent.fr"><span>cse@lyceestvincent.fr</span></a>
                    </p>
                </div>
                <div class="lieu">
                    <p><img src="assets/chevron-droit.png" class="chevron-droit">
                        Au lycée : <a target="_blank"
                            href="https://www.google.com/maps/place/49%C2%B012'08.1%22N+2%C2%B035'18.9%22E/@49.202244,2.5880054,19z/data=!3m1!4b1!4m4!3m3!8m2!3d49.202244!4d2.58857"><span>
                                Bureau du CSE (1er étage bâtiment Saint-Vincent)</span></a>
                    </p>
                </div>
            </div>
            <div class="partenaire">
                <h1 class="sectiontitle">Nos partenaires</h1>
                <div class="boxSlider">
                    <div class="slideshow-container">
                        <div class="mySlides">
                            <img src="assets/leonidas.jpg" style="width:100%">
                        </div>
                        <div class="mySlides">
                            <img src="assets/lego.png" style="width:100%">
                        </div>
                        <div class="mySlides">
                            <img src="assets/leonidas.jpg" style="width:100%">
                        </div>
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
            <div class="decouverte">
                <a target="_blank" href="partenariats.php">Découvrir tous nos partenaires</a>
            </div>
        </aside>
        <div class="right">
            <h1>Page de contact</h1>
        </div>
    </main>
    <?php require 'include/footer.php'?>
    <script src="scriptaside.js"></script>
</body>

</html>