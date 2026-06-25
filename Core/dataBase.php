<?php

function connection(){
    try {
        $bdd = new PDO(
            'mysql:host=localhost:33306;dbname=TPAK;charset=utf8',
            'root',
            'root'
        );
        return $bdd;
    } catch (Exception $e) {
        die('Erreur :'.$e->getMessage());
    }
}