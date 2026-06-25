<?php
// Core/dataBase.php

/**
 * Charge les variables du fichier .env dans l'environnement PHP
 */
function loadEnv($filePath) {
    if (!file_exists($filePath)) {
        return;
    }

    // Lit le fichier ligne par ligne
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Ignore les commentaires commençant par #
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // Sépare la clé et la valeur autour du premier "="
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        // Enregistre dans $_ENV et getenv()
        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
        }
    }
}

/**
 * Initialise la connexion PDO en utilisant les variables d'environnement
 */
function connection(){
    try {
        loadEnv(__DIR__ . '/../.env');

        $host = getenv('DB_HOST') ?: 'localhost';
        $port = getenv('DB_PORT') ?: '3306';
        $dbname = getenv('DB_NAME') ?: 'TPAK';
        $user = getenv('DB_USER') ?: 'root';
        $password = getenv('DB_PASSWORD') ?: '';

        $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8";

        $bdd = new PDO($dsn, $user, $password);
        
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        return $bdd;
    } catch (Exception $e) {
        die('Erreur de connexion à la base de données : ' . $e->getMessage());
    }
}