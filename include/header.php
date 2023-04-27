<?php
$parts = explode('/', $_SERVER["SCRIPT_NAME"]);
$file = $parts[count($parts) - 1];
$link_index ="";
$link_partenaires ="";
$link_billetterie ="";
$link_contact ="";

//var_dump($file);
// Conditions si le lien fini par... alors le lien prends la valeur active
if($file === "index.php"){
    $link_index = 'active';
}
if($file === "partenariats.php"){
    $link_partenaires = 'active';
}
if($file == "billetterie.php"){
    $link_billetterie = 'active';
}
if($file == "contenu_offre_billetterie.php"){
    $link_billetterie = 'active';
}
if($file == "contact.php"){
    $link_contact = 'active';
}

?>
<header>
    <div class="gris">
    </div>
    <div class="blue">
        <nav>
            <div class="logo">
                <img class="img_base" src="assets/logo_lycee.png" alt="logo_st_vincent">
                <img class="img_responsive" src="assets/Logo_St_Vincent_2.jpg" alt="logo_st_vincent_responsive">
            </div>
            <ul>
                <a href="index.php">
                    <li class="<?= $link_index ?>">
                        Accueil
                    </li>
                </a>
                <a href="partenariats.php">
                    <li class="<?= $link_partenaires ?>">Partenariats</li>
                </a>
                <a href="billetterie.php?page=1">
                    <li class="<?= $link_billetterie ?>">Billetterie</li>
                </a>
                <a href="contact.php">
                    <li class="<?= $link_contact ?>">Contact</li>
                </a>
                
            </ul>
            <img class="menu-burger" src="assets/menu.png" alt="menu-burger">
        </nav>
    </div>
</header>