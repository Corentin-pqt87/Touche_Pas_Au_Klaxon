<?php

/**
 * connexion de l'utilisateur
 */
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ./Page/Accueil.php');
    exit();
}

/** 
* on charge le model
*/
require __DIR__ . "/Model/postModel.php";


/** 
 * on appelle la fonction getPosts et on stocke les résultats dans une variable $posts
*/ 
$posts = getPosts();


/** 
 * redirige vers la page d'accueil
*/ 

require_once __DIR__ . "/Page/Accueil.php";