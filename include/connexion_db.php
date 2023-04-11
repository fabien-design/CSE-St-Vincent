<?php
    try{
        $dbname = "lyceestvincent_csebts1g1";
        $host = "mysql-lyceestvincent.alwaysdata.net";
        $user = "116313_csebts1g1";
        $mdp = "CSE!TDR";
        $connexion= new PDO('mysql:host='.$host.';dbname='.$dbname.'', $user, $mdp);
    }catch(Exception $e){
        echo "Erreur de connexion.";
    }
?>