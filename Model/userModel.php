<?php
require_once __DIR__ . "/../Core/defaultModel.php";

function getUserByUsername($name) {
    return findOnePrepared(
        'SELECT * FROM users WHERE name = :name', 
        ['name' => $name]
    );
}