<?php

//require "database.php";
require __DIR__ . "/dataBase.php";

function findAll($stmt) {
    // on charge la connection a la base de donnée
    $bdd = connection();
    // on exécute notre requete sql 
    $query = $bdd->query($stmt);

    $result = $query->fetchAll();

    if ($result !== false) {
        // on retourne le résultat
        return $result;
    } else {
        die("Une erreur s'est produite lors de lz récupération des données");
    }
}

function findOnePrepared($stmt, $params) {
    $bdd = connection();
    $query = $bdd->prepare($stmt);
    $query->execute($params);
    return $query->fetch(PDO::FETCH_ASSOC);
}