<?php

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


/**
 * connexion de l'utilisateur
 */
if( !empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])){
    $uri = 'https://';
} else {
    $uri = 'http://';
}
$uri .= $_SERVER['HTTP_HOST'];
header('Location: '.$uri.'/Template/login_Template.php');