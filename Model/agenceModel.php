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