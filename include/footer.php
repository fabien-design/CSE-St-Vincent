<?php

$end_link = $_SERVER['PHP_SELF'];
?>
<footer>
    <div class="left_footer">
        <div class="logo_footer">
            <img src="assets/logo_lycee.png" alt="logo_st_vincent">
        </div>
    </div>
    <div class="right_footer">
        <div class="title_footer">
            <h1><strong> CSE Lycée Saint-Vincent</strong></h1>
        </div>
        <div class="links_footer">
            <ul class="links_list_footer">
                <?php if($end_link=='/CSE SAINT VINCENT/CSE-St-Vincent/index.php'){?>
                <a href="partenariats.php">
                    <li><img src="assets/chevron-droit.png" class="chevron-droit" alt=""> Partenariats</li>
                </a>
                <a href="billetterie.php">
                    <li><img src="assets/chevron-droit.png" class="chevron-droit" alt=""> Billetterie</li>
                </a>
                <a href="contact.php">
                    <li><img src="assets/chevron-droit.png" class="chevron-droit" alt=""> Contact</li>
                </a>
                <?php }?>
                <?php if($end_link=='/CSE SAINT VINCENT/CSE-St-Vincent/partenariats.php'){?>
                <a href="index.php">
                    <li><img src="assets/chevron-droit.png" class="chevron-droit" alt=""> Accueil</li>
                </a>
                <a href="billetterie.php">
                    <li><img src="assets/chevron-droit.png" class="chevron-droit" alt=""> Billetterie</li>
                </a>
                <a href="contact.php">
                    <li><img src="assets/chevron-droit.png" class="chevron-droit" alt=""> Contact</li>
                </a>
                <?php }?>
                <?php if($end_link=='/CSE SAINT VINCENT/CSE-St-Vincent/billetterie.php'){?>
                <a href="index.php">
                    <li><img src="assets/chevron-droit.png" class="chevron-droit" alt=""> Accueil</li>
                </a>
                <a href="partenariats.php">
                    <li><img src="assets/chevron-droit.png" class="chevron-droit" alt=""> Partenariats</li>
                </a>
                <a href="contact.php">
                    <li><img src="assets/chevron-droit.png" class="chevron-droit" alt=""> Contact</li>
                </a>
                <?php }?>
                <?php if($end_link=='/CSE SAINT VINCENT/CSE-St-Vincent/contact.php'){?>
                <a href="index.php">
                    <li><img src="assets/chevron-droit.png" class="chevron-droit" alt=""> Accueil</li>
                </a>
                <a href="partenariats.php">
                    <li><img src="assets/chevron-droit.png" class="chevron-droit" alt=""> Partenariats</li>
                </a>
                <a href="billetterie.php">
                    <li><img src="assets/chevron-droit.png" class="chevron-droit" alt=""> Billetterie</li>
                </a>
                <?php }?>
            </ul>
        </div>
    </div>
</footer>