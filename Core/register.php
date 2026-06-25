<?php
// Core/register.php

// REMPLACE l'inclusion de postModel.php par userModel.php :
require_once __DIR__ . "/../Model/userModel.php";

// Le reste de ton code de traitement de l'inscription...
if (
    isset($_POST["name"]) && !empty($_POST["name"]) &&
    isset($_POST["mail"]) && !empty($_POST["mail"]) &&
    isset($_POST["keypass"]) && !empty($_POST["keypass"])
) {
    $name = trim($_POST["name"]);
    $mail = trim($_POST["mail"]);
    $phone = isset($_POST["phone"]) && !empty($_POST["phone"]) ? intval($_POST["phone"]) : null;
    
    $hashedPassword = password_hash($_POST["keypass"], PASSWORD_DEFAULT);

    // Maintenant, PHP va bien trouver la fonction saveUser() !
    $success = saveUser($name, $mail, $phone, $hashedPassword);

    if ($success) {
        header('Location: ../Template/login_Template.php?registered=1');
        exit();
    } else {
        echo "Une erreur est survenue lors de l'inscription.";
    }
}