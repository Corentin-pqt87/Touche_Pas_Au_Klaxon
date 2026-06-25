<?php
// Core/login.php

session_start();

require_once __DIR__ . "/../Model/userModel.php";

if (isset($_POST["name"]) && !empty($_POST["name"]) && isset($_POST["keypass"]) && !empty($_POST["keypass"])) {
    $name = trim($_POST["name"]);
    $keypass = $_POST["keypass"];

    /**
     * recuperer l'utilisateur
    */
    $user = getUserByUsername($name);

    /**
     * Verifier si l'utilisateur existe et si le mot de passe est correct
     */
    if ($user && password_verify($keypass, $user['password'])) {
        /**
         * connexion réussie
         */
        $_SESSION['user_id'] = $user['idusers'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['idrole']; // Utile pour ton header (admin ou non)

        /**
         * Redirection vers l'accueil
         */
        header('Location: ../index.php');
        exit();
    } else {
        /**
         * Identifiants incorrects
         */
        $error = "Identifiants incorrects.";
        header('Location: ../Template/login_Template.php?error=1');
        exit();
    }
} else {
    header('Location: ../Template/login_Template.php');
    exit();
}