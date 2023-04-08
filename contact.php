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

<body id="body" class="no-transition">
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
        <?php require 'include/aside.php'?>
        <div class="right">
            <h1>Page de contact</h1>
        </div>
    </main>
    <?php require 'include/footer.php'?>
    <script src="scriptaside.js"></script>
</body>

</html>