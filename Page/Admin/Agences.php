<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
    header('Location: ../../index.php');
    exit();
}

require_once __DIR__ . "/../../Model/agenceModel.php";
$agences = getAllAgences();
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <title>TPAK - Agences</title>
        <link rel="stylesheet" href="/Style/main.css" />
        <meta name="robots" content="noindex">
    </head>
    <body>
        <header>
            <?php require_once __DIR__ . "/../../Template/header.php"; ?>
        </header>
        <main>
            <h2>Liste des agences</h2>

            <?php if (empty($agences)): ?>
                <p>Aucune agence pour le moment.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Localisation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($agences as $agence): ?>
                            <tr>
                                <td><?= htmlspecialchars($agence['idagences']) ?></td>
                                <td><?= htmlspecialchars($agence['name']) ?></td>
                                <td><?= htmlspecialchars($agence['location'] ?? '—') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </main>
    </body>
</html>