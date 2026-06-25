<?php


/**
 * connexion de l'utilisateur
 */
if (!isset($_SESSION['user_id'])) {
    header('Location: ./Template/home.php');
    exit();
}

/** 
* on charge le model
* require "./model/postModel.php";
*/
require __DIR__ . "/model/postModel.php";
/** 
 * on appelle la fonction getPosts et on stocke les résultats dans une variable $posts
*/ 
$posts = getPosts();
/** 
 * on charge le temple de la page
 * require "./templates/home.php";
*/ 
require __DIR__ . "/templates/home.php";
