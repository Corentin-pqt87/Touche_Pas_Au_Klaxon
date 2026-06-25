<?php 
//require "../Core/defaultModel.php";
require __DIR__ . "/../Core/defaultModel.php";

function getPosts() {
    return findAll('SELECT * FROM posts');
}