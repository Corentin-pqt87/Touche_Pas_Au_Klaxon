<?php 
//require "../Core/defaultModel.php";
require __DIR__ . "/../Core/defaultModel.php";

function getPosts() {
    return findAll('
        SELECT 
            p.*, 
            u.name AS driver_name,
            dep.name AS departure_name, dep.location AS departure_location,
            arr.name AS arrival_name, arr.location AS arrival_location
        FROM posts p
        INNER JOIN users u ON p.idusers = u.idusers
        INNER JOIN agences dep ON p.departure = dep.idagences
        INNER JOIN agences arr ON p.arrival = arr.idagences
    ');
}