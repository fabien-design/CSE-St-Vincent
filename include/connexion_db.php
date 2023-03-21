<?php
    try{
        $dbname = "cse_lsv";
        $connexion= new PDO('mysql:host=localhost;dbname='.$dbname.'', 'root', '');
    }catch(Exception $e){
        echo "Erreur de connexion.";
    }
?>