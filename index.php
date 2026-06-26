<?php

session_start();

/**
 * connexion de l'utilisateur
 */
if (!isset($_SESSION['user_id'])) {
    header('Location: ./Page/Accueil.php');
    exit();
}

/** 
* on charge le model
* require "./model/postModel.php";
*/
require __DIR__ . "/Model/postModel.php";
/** 
 * on appelle la fonction getPosts et on stocke les résultats dans une variable $posts
*/ 
$posts = getPosts();
/** 
 * on charge le temple de la page
 * require "./templates/home.php";
*/ 
require __DIR__ . "/Templates/home.php";
