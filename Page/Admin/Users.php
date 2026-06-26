<?php
// Page/Admin/Users.php

/**
 * en cas de bug
 * error_reporting(E_ALL);
 * ini_set('display_errors', 1);
 */

 
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

require_once __DIR__ . "/../../Model/userModel.php";
$users = getAllUsers();
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <title>TPAK - Utilisateurs</title>
        <link rel="stylesheet" href="/Style/main.css" />
        <meta name="robots" content="noindex">
    </head>
    <body>
        <header>
            <?php require_once __DIR__ . "/../../Template/header.php"; ?>
        </header>
        <main>
            <h2>Liste des utilisateurs</h2>

            <?php if (empty($users)): ?>
                <p>Aucun utilisateur pour le moment.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Rôle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['idusers']) ?></td>
                                <td><?= htmlspecialchars($user['name']) ?></td>
                                <td><?= htmlspecialchars($user['mail']) ?></td>
                                <td><?= htmlspecialchars($user['phone'] ?? '—') ?></td>
                                <td><?= $user['idrole'] == 1 ? 'Admin' : 'Utilisateur' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </main>
    </body>
</html>