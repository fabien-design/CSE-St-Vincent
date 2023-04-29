<?php

$parts = explode('/', $_SERVER["SCRIPT_NAME"]);
$file = $parts[count($parts) - 1];

?>
<footer>
    <div class="left_footer">
        <div class="logo_footer">
            <img class="img_base" src="assets/logo_lycee.png" alt="logo_st_vincent">
            <img class="img_responsive" src="assets/zyro-image.png" alt="logo_st_vincent">
        </div>
    </div>
    <div class="right_footer">
        <div class="title_footer">
            <h1><strong> CSE Lycée Saint-Vincent</strong></h1>
        </div>
        <div class="links_footer">
            <ul class="links_list_footer">
                <?php if($file=='index.php'){?>
                <a href="partenariats.php">
                    <li><img src="assets/chevron-droit.png" class="chevron-droit" alt="chevron-droit"> Partenariats</li>
                </a>
                <a href="billetterie.php?page=1">
                    <li><img src="assets/chevron-droit.png" class="chevron-droit" alt="chevron-droit"> Billetterie</li>
                </a>
                <a href="contact.php">
                    <li><img src="assets/chevron-droit.png" class="chevron-droit" alt="chevron-droit"> Contact</li>
                </a>
                <?php }?>
                <?php if($file=='partenariats.php'){?>
                <a href="index.php">
                    <li><img src="assets/chevron-droit.png" class="chevron-droit" alt="chevron-droit"> Accueil</li>
                </a>
                <a href="billetterie.php?page=1">
                    <li><img src="assets/chevron-droit.png" class="chevron-droit" alt="chevron-droit"> Billetterie</li>
                </a>
                <a href="contact.php">
                    <li><img src="assets/chevron-droit.png" class="chevron-droit" alt="chevron-droit"> Contact</li>
                </a>
                <?php }?>
                <?php if($file=='billetterie.php'){?>
                <a href="index.php">
                    <li><img src="assets/chevron-droit.png" class="chevron-droit" alt="chevron-droit"> Accueil</li>
                </a>
                <a href="partenariats.php">
                    <li><img src="assets/chevron-droit.png" class="chevron-droit" alt="chevron-droit"> Partenariats</li>
                </a>
                <a href="contact.php">
                    <li><img src="assets/chevron-droit.png" class="chevron-droit" alt="chevron-droit"> Contact</li>
                </a>
                <?php }?>
                <?php if($file=='contact.php'){?>
                <a href="index.php">
                    <li><img src="assets/chevron-droit.png" class="chevron-droit" alt="chevron-droit"> Accueil</li>
                </a>
                <a href="partenariats.php">
                    <li><img src="assets/chevron-droit.png" class="chevron-droit" alt="chevron-droit"> Partenariats</li>
                </a>
                <a href="billetterie.php?page=1">
                    <li><img src="assets/chevron-droit.png" class="chevron-droit" alt="chevron-droit"> Billetterie</li>
                </a>
                <?php }?>

                <?php if($file=='contenu_offre_billetterie.php'){?>
                <a href="index.php">
                    <li><img src="assets/chevron-droit.png" class="chevron-droit" alt="chevron-droit"> Accueil</li>
                </a>
                <a href="partenariats.php">
                    <li><img src="assets/chevron-droit.png" class="chevron-droit" alt="chevron-droit"> Partenariats</li>
                </a>
                <a href="contact.php">
                    <li><img src="assets/chevron-droit.png" class="chevron-droit" alt="chevron-droit"> contact</li>
                </a>
                <?php }?>
            </ul>
        </div>
    </div>
</footer>