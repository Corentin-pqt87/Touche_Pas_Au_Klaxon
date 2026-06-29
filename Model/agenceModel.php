<?php
// Model/agencesModel.php

require_once __DIR__ . "/../Core/defaultModel.php";

function getAllAgences() {
    return findAll('SELECT * FROM agences ORDER BY name ASC');
}

function getAgenceById($idagence) {
    return findOnePrepared(
        'SELECT * FROM agences WHERE idagences = :id', 
        ['id' => $idagence]
    );
}

function addAgence($name, $location) {
    $bdd = connection();
    $id = rand(1, 1000000);
    $stmt = $bdd->prepare('INSERT INTO agences (idagences, name, location) VALUES (:id, :name, :location)');
    return $stmt->execute(['id' => $id, 'name' => $name, 'location' => $location]);
}

function updateAgence($id, $name, $location) {
    $bdd = connection();
    $stmt = $bdd->prepare('UPDATE agences SET name = :name, location = :location WHERE idagences = :id');
    return $stmt->execute(['name' => $name, 'location' => $location, 'id' => $id]);
}

function deleteAgence($id) {
    $bdd = connection();
    $stmt = $bdd->prepare('DELETE FROM agences WHERE idagences = :id');
    return $stmt->execute(['id' => $id]);
}