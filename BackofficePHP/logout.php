<?php
session_start(); // démarrer la session
session_destroy(); // détruire la session
header("Location: ../backoffice.php"); // rediriger l'utilisateur vers la page de connexion
exit;
?>