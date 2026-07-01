<?php 
//require "../Core/defaultModel.php";
require __DIR__ . "/../Core/defaultModel.php";

function getPosts() {
    return findAll('SELECT * FROM posts');
}

function getAllPosts() {
    $bdd  = connection();
    $stmt = $bdd->query(
        'SELECT
            p.idposts, p.title, p.travel_date, p.arrival_date, p.seats, p.idusers,
            da.name AS departure_name,
            aa.name AS arrival_name,
            u.name  AS user_name
         FROM posts p
         INNER JOIN agences da ON p.departure = da.idagences
         INNER JOIN agences aa ON p.arrival   = aa.idagences
         LEFT  JOIN users   u  ON p.idusers   = u.idusers
         ORDER BY p.travel_date DESC'
    );
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getPostById($id) {
    $bdd  = connection();
    $stmt = $bdd->prepare('SELECT * FROM posts WHERE idposts = :id');
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
 

function addPost($title, $departure, $arrival, $travel_date, $arrival_date, $seats, $idusers) {
    $bdd  = connection();
    $stmt = $bdd->prepare(
        'INSERT INTO posts (title, departure, arrival, travel_date, arrival_date, seats, idusers)
         VALUES (:title, :departure, :arrival, :travel_date, :arrival_date, :seats, :idusers)'
    );
    return $stmt->execute([
        'title'        => $title,
        'departure'    => $departure,
        'arrival'      => $arrival,
        'travel_date'  => $travel_date,
        'arrival_date' => $arrival_date,
        'seats'        => $seats,
        'idusers'      => $idusers,
    ]);
}

function updatePost($id, $title, $departure, $arrival, $travel_date, $arrival_date, $seats, $idusers) {
    $bdd  = connection();
    $stmt = $bdd->prepare(
        'UPDATE posts
         SET title        = :title,
             departure    = :departure,
             arrival      = :arrival,
             travel_date  = :travel_date,
             arrival_date = :arrival_date,
             seats        = :seats,
             idusers      = :idusers
         WHERE idposts = :id'
    );
    return $stmt->execute([
        'id'           => $id,
        'title'        => $title,
        'departure'    => $departure,
        'arrival'      => $arrival,
        'travel_date'  => $travel_date,
        'arrival_date' => $arrival_date,
        'seats'        => $seats,
        'idusers'      => $idusers,
    ]);
}

function deletePost($id) {
    $bdd = connection();
    $stmt = $bdd->prepare('DELETE FROM posts WHERE idposts = :id');
    return $stmt->execute(['id' => $id]);
}

function getAvailablePosts() {
    $bdd = connection();
    $stmt = $bdd->query(
        'SELECT
            p.idposts,
            p.travel_date,
            p.arrival_date,
            p.seats,
            p.idusers, -- Ajout nécessaire ici
            da.name AS departure_name,
            aa.name AS arrival_name
         FROM posts p
         INNER JOIN agences da ON p.departure = da.idagences
         INNER JOIN agences aa ON p.arrival  = aa.idagences
         WHERE p.travel_date > NOW()
           AND p.seats > 0
         ORDER BY p.travel_date ASC'
    );
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}