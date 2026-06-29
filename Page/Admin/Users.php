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

$message = '';
$editUser = null;

/**
 * POST
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $name  = trim($_POST['name']);
        $mail  = trim($_POST['mail']);
        $phone = !empty($_POST['phone']) ? intval($_POST['phone']) : null;
        $idrole = intval($_POST['idrole']);
        $hashedPassword = password_hash($_POST['keypass'], PASSWORD_DEFAULT);
        addUser($name, $mail, $phone, $idrole, $hashedPassword)
            ? $message = "Utilisateur ajouté."
            : $message = "Erreur lors de l'ajout.";

    } elseif ($action === 'edit') {
        $id    = intval($_POST['idusers']);
        $name  = trim($_POST['name']);
        $mail  = trim($_POST['mail']);
        $phone = !empty($_POST['phone']) ? intval($_POST['phone']) : null;
        $idrole = intval($_POST['idrole']);
        updateUser($id, $name, $mail, $phone, $idrole);
        if (!empty($_POST['keypass'])) {
            updateUserPassword($id, password_hash($_POST['keypass'], PASSWORD_DEFAULT));
        }
        $message = "Utilisateur modifié.";

    } elseif ($action === 'delete') {
        deleteUser(intval($_POST['idusers']))
            ? $message = "Utilisateur supprimé."
            : $message = "Erreur lors de la suppression.";
    }

    header('Location: Users.php?message=' . urlencode($message));
    exit();
}

if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
}

if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'edit') {
    $editUser = getUserById(intval($_GET['id']));
}

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
        <h2><?= $editUser ? 'Modifier un utilisateur' : 'Ajouter un utilisateur' ?></h2>

        <?php if ($message): ?>
            <p><?= $message ?></p>
        <?php endif; ?>

        <form action="Users.php<?= $editUser ? '?action=edit&id=' . $editUser['idusers'] : '' ?>" method="post">
            <input type="hidden" name="action" value="<?= $editUser ? 'edit' : 'add' ?>">
            <?php if ($editUser): ?>
                <input type="hidden" name="idusers" value="<?= $editUser['idusers'] ?>">
            <?php endif; ?>

            <label>Nom<br>
                <input type="text" name="name" required value="<?= htmlspecialchars($editUser['name'] ?? '') ?>">
            </label><br>

            <label>Email<br>
                <input type="email" name="mail" required value="<?= htmlspecialchars($editUser['mail'] ?? '') ?>">
            </label><br>

            <label>Téléphone<br>
                <input type="tel" name="phone" value="<?= htmlspecialchars($editUser['phone'] ?? '') ?>">
            </label><br>

            <label>Rôle<br>
                <select name="idrole">
                    <option value="2" <?= (($editUser['idrole'] ?? 2) == 2) ? 'selected' : '' ?>>Utilisateur</option>
                    <option value="1" <?= (($editUser['idrole'] ?? 2) == 1) ? 'selected' : '' ?>>Admin</option>
                </select>
            </label><br>

            <label>Mot de passe <?= $editUser ? '(laisser vide pour ne pas changer)' : '' ?><br>
                <input type="password" name="keypass" <?= $editUser ? '' : 'required' ?>>
            </label><br>

            <button type="submit"><?= $editUser ? 'Enregistrer les modifications' : 'Ajouter' ?></button>
            <?php if ($editUser): ?>
                <a href="Users.php">Annuler</a>
            <?php endif; ?>
        </form>

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
                        <th>Actions</th>
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
                            <td>
                                <a href="Users.php?action=edit&id=<?= $user['idusers'] ?>">Modifier</a>

                                <form action="Users.php" method="post" style="display:inline"
                                      onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="idusers" value="<?= $user['idusers'] ?>">
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