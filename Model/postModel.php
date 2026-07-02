<?php 
//require "../Core/defaultModel.php";
require_once __DIR__ . "/../Core/defaultModel.php";

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

    try {
        $bdd->beginTransaction();

        // On supprime d'abord les inscriptions liées à ce trajet,
        // sinon la contrainte de clé étrangère inscription_posts_FK bloque la suppression.
        $stmtInscriptions = $bdd->prepare('DELETE FROM inscription WHERE idposts = :id');
        $stmtInscriptions->execute(['id' => $id]);

        // On supprime ensuite le trajet lui-même.
        $stmt = $bdd->prepare('DELETE FROM posts WHERE idposts = :id');
        $result = $stmt->execute(['id' => $id]);

        $bdd->commit();
        return $result;
    } catch (Exception $e) {
        $bdd->rollBack();
        throw $e;
    }
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

/**
 * PROFIL UTILISATEUR
 */

/**
 * Trajets créés par un utilisateur (en tant que conducteur)
 */
function getPostsByUser($idusers) {
    $bdd = connection();
    $stmt = $bdd->prepare(
        'SELECT
            p.idposts, p.title, p.travel_date, p.arrival_date, p.seats,
            da.name AS departure_name,
            aa.name AS arrival_name
         FROM posts p
         INNER JOIN agences da ON p.departure = da.idagences
         INNER JOIN agences aa ON p.arrival   = aa.idagences
         WHERE p.idusers = :idusers
         ORDER BY p.travel_date DESC'
    );
    $stmt->execute(['idusers' => $idusers]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Trajets auxquels un utilisateur est inscrit (en tant que passager)
 */
function getInscriptionsByUser($idusers) {
    $bdd = connection();
    $stmt = $bdd->prepare(
        'SELECT
            i.idinscription, i.idposts,
            p.title, p.travel_date, p.arrival_date,
            da.name AS departure_name,
            aa.name AS arrival_name
         FROM inscription i
         INNER JOIN posts p   ON i.idposts   = p.idposts
         INNER JOIN agences da ON p.departure = da.idagences
         INNER JOIN agences aa ON p.arrival   = aa.idagences
         WHERE i.idusers = :idusers
         ORDER BY p.travel_date DESC'
    );
    $stmt->execute(['idusers' => $idusers]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Désinscrit un utilisateur d'un trajet (vérifie que l'inscription lui appartient)
 * et réattribue la place sur le trajet concerné.
 */
function unsubscribeUser($idinscription, $idusers) {
    $bdd = connection();

    try {
        $bdd->beginTransaction();

        // On vérifie que l'inscription appartient bien à cet utilisateur
        $check = $bdd->prepare('SELECT idposts FROM inscription WHERE idinscription = :id AND idusers = :idusers');
        $check->execute(['id' => $idinscription, 'idusers' => $idusers]);
        $inscription = $check->fetch(PDO::FETCH_ASSOC);

        if (!$inscription) {
            $bdd->rollBack();
            return false;
        }

        $delete = $bdd->prepare('DELETE FROM inscription WHERE idinscription = :id');
        $delete->execute(['id' => $idinscription]);

        // On réattribue la place disponible sur le trajet
        $updateSeats = $bdd->prepare('UPDATE posts SET seats = seats + 1 WHERE idposts = :idposts');
        $updateSeats->execute(['idposts' => $inscription['idposts']]);

        $bdd->commit();
        return true;
    } catch (Exception $e) {
        $bdd->rollBack();
        throw $e;
    }
}

/**
 * INSCRIPTION 
 */

function deleteInscription($id) {
    $bdd = connection();
    $stmt = $bdd->prepare('DELETE FROM inscription WHERE idinscription = :id');
    return $stmt->execute(['id' => $id]);
}