<?php 
//require "../Core/defaultModel.php";
require __DIR__ . "/../Core/defaultModel.php";

function getPosts() {
    return findAll('SELECT * FROM posts');
}

function getAllPosts() {
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

function getPostById($id) {
    return findOnePrepared('SELECT * FROM posts WHERE idposts = :id', ['id' => $id]);
}

function addPost($title, $departure, $arrival, $travel_date, $seats, $idusers) {
    $bdd = connection();
    $stmt = $bdd->prepare('INSERT INTO posts (title, departure, arrival, travel_date, seats, idusers) VALUES (:title, :departure, :arrival, :travel_date, :seats, :idusers)');
    return $stmt->execute([
        'title' => $title,
        'departure' => $departure,
        'arrival' => $arrival,
        'travel_date' => $travel_date,
        'seats' => $seats,
        'idusers' => $idusers
    ]);
}

function updatePost($id, $title, $departure, $arrival, $travel_date, $seats, $idusers) {
    $bdd = connection();
    $stmt = $bdd->prepare('UPDATE posts SET title = :title, departure = :departure, arrival = :arrival, travel_date = :travel_date, seats = :seats, idusers = :idusers WHERE idposts = :id');
    return $stmt->execute([
        'title' => $title,
        'departure' => $departure,
        'arrival' => $arrival,
        'travel_date' => $travel_date,
        'seats' => $seats,
        'idusers' => $idusers,
        'id' => $id
    ]);
}

function deletePost($id) {
    $bdd = connection();
    $stmt = $bdd->prepare('DELETE FROM posts WHERE idposts = :id');
    return $stmt->execute(['id' => $id]);
}