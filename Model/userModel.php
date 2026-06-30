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

function addUser($name, $mail, $phone, $idrole, $hashedPassword) {
    $bdd = connection();
    $idusers = rand(1, 1000000);
    $stmt = $bdd->prepare('INSERT INTO users (idusers, name, mail, phone, idrole, password) VALUES (:id, :name, :mail, :phone, :idrole, :password)');
    return $stmt->execute([
        'id' => $idusers, 'name' => $name, 'mail' => $mail,
        'phone' => $phone, 'idrole' => $idrole, 'password' => $hashedPassword
    ]);
}

function updateUser($id, $name, $mail, $phone, $idrole) {
    $bdd = connection();
    $stmt = $bdd->prepare('UPDATE users SET name = :name, mail = :mail, phone = :phone, idrole = :idrole WHERE idusers = :id');
    return $stmt->execute([
        'name' => $name, 'mail' => $mail,
        'phone' => $phone, 'idrole' => $idrole, 'id' => $id
    ]);
}

function updateUserPassword($id, $hashedPassword) {
    $bdd = connection();
    $stmt = $bdd->prepare('UPDATE users SET password = :password WHERE idusers = :id');
    return $stmt->execute(['password' => $hashedPassword, 'id' => $id]);
}

function deleteUser($id) {
    $bdd = connection();
    $stmt = $bdd->prepare('DELETE FROM users WHERE idusers = :id');
    return $stmt->execute(['id' => $id]);
}

function getAllUsers() {
    return findAll('SELECT idusers, name, mail, phone, idrole FROM users');
}

function getUserById($id) {
    return findOnePrepared('SELECT idusers, name, mail, phone, idrole FROM users WHERE idusers = :id', ['id' => $id]);
}

function findAllUsersForSelect() {
    return findAll('SELECT idusers, name FROM users');
}