<?php
// Page/Profil.php

/**
 * Démarrage de session si ce n'est pas déjà fait
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Protection : seuls les utilisateurs connectés peuvent voir leur profil
 */
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

require_once __DIR__ . "/../Model/postModel.php";

$message = '';

/**
 * Traitement de la désinscription à un trajet
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'unsubscribe') {
    $idinscription = intval($_POST['idinscription'] ?? 0);

    if ($idinscription && unsubscribeUser($idinscription, $_SESSION['user_id'])) {
        header('Location: Profil.php?message=' . urlencode('Désinscription effectuée.'));
        exit();
    } else {
        header('Location: Profil.php?message=' . urlencode("Erreur lors de la désinscription."));
        exit();
    }
}

/**
 * Traitement de la suppression d'un trajet créé par l'utilisateur
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete_post') {
    $idposts = intval($_POST['idposts'] ?? 0);

    // Sécurité : on vérifie que le trajet appartient bien à l'utilisateur connecté
    $post = $idposts ? getPostById($idposts) : null;

    if ($post && $post['idusers'] == $_SESSION['user_id'] && deletePost($idposts)) {
        header('Location: Profil.php?message=' . urlencode('Trajet supprimé.'));
        exit();
    } else {
        header('Location: Profil.php?message=' . urlencode("Erreur lors de la suppression du trajet."));
        exit();
    }
}

if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
}

/**
 * Données affichées sur la page :
 * - les trajets créés par l'utilisateur (conducteur)
 * - les trajets auxquels l'utilisateur est inscrit (passager)
 */
$mesTrajets      = getPostsByUser($_SESSION['user_id']);
$mesInscriptions = getInscriptionsByUser($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TPAK - Mon profil</title>
    <link rel="stylesheet" href="/Style/main.css" />
</head>
<body>
    <header>
        <?php require_once __DIR__ . "/../Template/header.php"; ?>
    </header>
    <main>
        <h2>Mon profil</h2>
        <p>Bonjour, <?= htmlspecialchars($_SESSION['user_name']) ?>.</p>

        <?php if ($message): ?>
            <p><?= $message ?></p>
        <?php endif; ?>

        <?php require_once __DIR__ . "/../Template/profil_mes_trajets.php"; ?>

        <?php require_once __DIR__ . "/../Template/profil_mes_inscriptions.php"; ?>
    </main>
    <footer>
        <?php require_once __DIR__ . "/../Template/footer.php"; ?>
    </footer>
</body>
</html>