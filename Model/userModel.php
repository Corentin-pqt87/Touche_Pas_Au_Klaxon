<?php
require_once __DIR__ . "/../Core/defaultModel.php";

function getUserByUsername($name) {
    return findOnePrepared(
        'SELECT * FROM users WHERE name = :name', 
        ['name' => $name]
    );
}

function saveUser($name, $mail, $phone, $hashedPassword) {
    $bdd = connection();
    
    // Par défaut, on attribue un rôle standard (ex: idrole = 2 pour "utilisateur")
    $idrole = 2; 
    /**
     * On génère un ID unique
     */
    $idusers = rand(1, 1000000); 

    $stmt = $bdd->prepare('INSERT INTO users (idusers, name, mail, phone, idrole, password) VALUES (:id, :name, :mail, :phone, :idrole, :password)');
    
    return $stmt->execute([
        'id' => $idusers,
        'name' => $name,
        'mail' => $mail,
        'phone' => $phone,
        'idrole' => $idrole,
        'password' => $hashedPassword
    ]);
}

function getAllUsers() {
    return findAll('SELECT idusers, name, mail, phone, idrole FROM users');
}