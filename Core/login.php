<?php

if (isset($_POST["name"]) && !empty($_POST["name"]) ){
    $name = $_POST["name"];
}
if (isset($_POST["keypass"]) && !empty($_POST["keypass"]) ){
    $keypass = $_POST["keypass"];
}

echo("Nom $name , mdp $keypass");