<?php
// Page/admin.php
 
/**
 * Démarrage de session si ce n'est pas déjà fait
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
 
/**
 * Protection : seuls les admins (idrole = 1) peuvent accéder à cette page
 */
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
    header('Location: ../index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <title>TPAK-Admin</title>
        <link rel="stylesheet" href="/../Style/main.css" />
        <meta name="robots" content="noindex">
    </head>
    <body>
        <header>
            <?php require_once __DIR__ . "/../Template/header.php"; ?>
        </header>
        <main>
            <h2>Tableau de bord administrateur</h2>
            <p>Bienvenue, <?= htmlspecialchars($_SESSION['user_name']) ?>.</p>
        </main>
    </body>
</html>