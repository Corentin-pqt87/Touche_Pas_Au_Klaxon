<?php
// Core/subscribe.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Sécurité : Si l'utilisateur n'est pas connecté, on le redirige vers l'accueil
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Page/Accueil.php");
    exit();
}

// On charge la connexion à la base de données
require_once __DIR__ . "/dataBase.php";

if (isset($_POST['idposts']) && !empty($_POST['idposts'])) {
    $idusers = $_SESSION['user_id'];
    $idposts = intval($_POST['idposts']);

    try {
        $bdd = connection();

        $check = $bdd->prepare("SELECT * FROM inscription WHERE idusers = :idusers AND idposts = :idposts");
        $check->execute([
            'idusers' => $idusers,
            'idposts' => $idposts
        ]);
        
        if ($check->fetch()) {
            // Déjà inscrit = redirection vers l'accueil avec un paramètre d'information
            header("Location: ../Page/Accueil.php?info=already_subscribed");
            exit();
        }

        // Ajout de l'inscription dans la base de données
        $stmt = $bdd->prepare("INSERT INTO inscription (idusers, idposts) VALUES (:idusers, :idposts)");
        $success = $stmt->execute([
            'idusers' => $idusers,
            'idposts' => $idposts
        ]);

        if ($success) {
            // réduit le nombre de places disponibles sur le trajet de 1
            $updateSeats = $bdd->prepare("UPDATE posts SET seats = seats - 1 WHERE idposts = :idposts AND seats > 0");
            $updateSeats->execute(['idposts' => $idposts]);

            header("Location: ../Page/Accueil.php?success=1");
            exit();
        } else {
            die("Une erreur est survenue lors de l'enregistrement.");
        }

    } catch (Exception $e) {
        die("Erreur lors de l'inscription au trajet : " . $e->getMessage());
    }
} else {
    header("Location: ../Page/Accueil.php");
    exit();
}