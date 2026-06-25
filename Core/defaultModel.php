<?php

//require "database.php";
require __DIR__ . "/database.php";

function findAll($stmt) {
    // on charge la connection a la base de donnée
    $bdd = connection();
    // on exécute notre requete sql 
    $query = $bdd->query($stmt);

    $result = $query->fetchAll();

    if ($result) {
        // on retourne le résultat
        return $result;
    } else {
        die("Une erreur s'est produite lors de lz récupération des données");
    }
}