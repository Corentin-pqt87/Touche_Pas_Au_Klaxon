<?php
// Page/nouveau_trajet.php

/**
 * DEBUG TEMPORAIRE : à retirer une fois le problème résolu
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * Démarrage de session si ce n'est pas déjà fait
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Protection : seuls les utilisateurs connectés peuvent créer un trajet
 */
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

require_once __DIR__ . "/../Model/agenceModel.php";
require_once __DIR__ . "/../Model/postModel.php";

$message = '';

/**
 * Traitement du formulaire
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title          = trim($_POST['title'] ?? '');
    $departureRaw   = $_POST['departure'] ?? '';
    $arrivalRaw     = $_POST['arrival'] ?? '';
    $departure      = intval($departureRaw);
    $arrival        = intval($arrivalRaw);
    $travel_date    = $_POST['travel_date'] ?? '';
    $arrival_date   = !empty($_POST['arrival_date']) ? $_POST['arrival_date'] : null;
    $seats          = intval($_POST['seats'] ?? 1);
    $idusers        = $_SESSION['user_id'];

    // Note : on valide sur la valeur brute (chaîne) du POST, pas sur l'entier converti,
    // car une agence peut avoir l'id 0, qui est "faux" (falsy) en PHP.
    if ($title !== '' && $departureRaw !== '' && $arrivalRaw !== '' && $travel_date !== '') {
        try {
            $success = addPost($title, $departure, $arrival, $travel_date, $arrival_date, $seats, $idusers);

            if ($success) {
                header('Location: ../index.php?message=' . urlencode('Trajet créé avec succès.'));
                exit();
            } else {
                $message = "Une erreur est survenue lors de la création du trajet.";
            }
        } catch (Exception $e) {
            // DEBUG TEMPORAIRE : affiche l'erreur SQL réelle
            $message = "Erreur SQL : " . $e->getMessage();
        }
    } else {
        // DEBUG TEMPORAIRE : montre ce qui a été reçu et ce qui manque
        $message = "Veuillez remplir tous les champs obligatoires. (title=" . var_export($title, true)
            . ", departure=" . var_export($departure, true)
            . ", arrival=" . var_export($arrival, true)
            . ", travel_date=" . var_export($travel_date, true) . ")";
    }
}

/**
 * Liste des agences pour les selects du formulaire
 */
$agences = getAllAgences();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TPAK - Nouveau trajet</title>
    <link rel="stylesheet" href="/Style/main.css" />
</head>
<body>
    <header>
        <?php require_once __DIR__ . "/../Template/header.php"; ?>
    </header>
    <main>
        <h2>Création d'un nouveau trajet</h2>

        <?php if ($message): ?>
            <p><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <?php require_once __DIR__ . "/../Template/nouveau_trajet_form.php"; ?>
    </main>
    <footer>
        <?php require_once __DIR__ . "/../Template/footer.php"; ?>
    </footer>
</body>
</html>