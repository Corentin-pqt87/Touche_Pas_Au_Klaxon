<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
    header('Location: ../../index.php');
    exit();
}

require_once __DIR__ . "/../../Model/agenceModel.php";

$message  = '';
$editAgence = null;

/**
 * POST
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        addAgence(trim($_POST['name']), trim($_POST['location']))
            ? $message = "Agence ajoutée."
            : $message = "Erreur lors de l'ajout.";

    } elseif ($action === 'edit') {
        updateAgence(intval($_POST['idagences']), trim($_POST['name']), trim($_POST['location']))
            ? $message = "Agence modifiée."
            : $message = "Erreur lors de la modification.";

    } elseif ($action === 'delete') {
        deleteAgence(intval($_POST['idagences']))
            ? $message = "Agence supprimée."
            : $message = "Erreur lors de la suppression.";
    }

    header('Location: Agences.php?message=' . urlencode($message));
    exit();
}

if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
}

if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'edit') {
    $editAgence = getAgenceById(intval($_GET['id']));
}

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
        <?php require_once __DIR__ . "/../../Template/admin_header.php"; ?>
    </header>
    <main>
        <h2><?= $editAgence ? 'Modifier une agence' : 'Ajouter une agence' ?></h2>

        <?php if ($message): ?>
            <p><?= $message ?></p>
        <?php endif; ?>

        <form action="Agences.php" method="post">
            <input type="hidden" name="action" value="<?= $editAgence ? 'edit' : 'add' ?>">
            <?php if ($editAgence): ?>
                <input type="hidden" name="idagences" value="<?= $editAgence['idagences'] ?>">
            <?php endif; ?>

            <label>Nom<br>
                <input type="text" name="name" required value="<?= htmlspecialchars($editAgence['name'] ?? '') ?>">
            </label><br>

            <label>Localisation<br>
                <input type="text" name="location" value="<?= htmlspecialchars($editAgence['location'] ?? '') ?>">
            </label><br>

            <button type="submit"><?= $editAgence ? 'Enregistrer les modifications' : 'Ajouter' ?></button>
            <?php if ($editAgence): ?>
                <a href="Agences.php">Annuler</a>
            <?php endif; ?>
        </form>

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
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($agences as $agence): ?>
                        <tr>
                            <td><?= htmlspecialchars($agence['idagences']) ?></td>
                            <td><?= htmlspecialchars($agence['name']) ?></td>
                            <td><?= htmlspecialchars($agence['location'] ?? '—') ?></td>
                            <td>
                                <a href="Agences.php?action=edit&id=<?= $agence['idagences'] ?>">Modifier</a>

                                <form action="Agences.php" method="post" style="display:inline"
                                      onsubmit="return confirm('Supprimer cette agence ?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="idagences" value="<?= $agence['idagences'] ?>">
                                    <button type="submit">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>
</body>
</html>