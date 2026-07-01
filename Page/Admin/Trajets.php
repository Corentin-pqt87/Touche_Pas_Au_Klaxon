<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
    header('Location: ../../index.php');
    exit();
}

require_once __DIR__ . "/../../Model/postModel.php";
require_once __DIR__ . "/../../Model/agenceModel.php";
require_once __DIR__ . "/../../Model/userModel.php";

$message  = '';
$editPost = null;

// Traitement POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $title        = trim($_POST['title']);
        $departure    = intval($_POST['departure']);
        $arrival      = intval($_POST['arrival']);
        $travel_date  = $_POST['travel_date'];
        $arrival_date = !empty($_POST['arrival_date']) ? $_POST['arrival_date'] : null;
        $seats        = intval($_POST['seats']);
        $idusers      = intval($_POST['idusers']);

        addPost($title, $departure, $arrival, $travel_date, $arrival_date, $seats, $idusers)
            ? $message = "Trajet ajouté."
            : $message = "Erreur lors de l'ajout.";

    } elseif ($action === 'edit') {
        $id           = intval($_POST['idposts']);
        $title        = trim($_POST['title']);
        $departure    = intval($_POST['departure']);
        $arrival      = intval($_POST['arrival']);
        $travel_date  = $_POST['travel_date'];
        $arrival_date = !empty($_POST['arrival_date']) ? $_POST['arrival_date'] : null;
        $seats        = intval($_POST['seats']);
        $idusers      = intval($_POST['idusers']);

        updatePost($id, $title, $departure, $arrival, $travel_date, $arrival_date, $seats, $idusers)
            ? $message = "Trajet modifié."
            : $message = "Erreur lors de la modification.";

    } elseif ($action === 'delete') {
        deletePost(intval($_POST['idposts']))
            ? $message = "Trajet supprimé."
            : $message = "Erreur lors de la suppression.";
    }

    header('Location: Trajets.php?message=' . urlencode($message));
    exit();
}

// Récupération du message après redirection
if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
}

// Chargement pour édition
if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'edit') {
    $editPost = getPostById(intval($_GET['id']));
}

$posts   = getAllPosts();
$agences = getAllAgences();
$users   = findAllUsersForSelect();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <title>TPAK - Trajets</title>
    <link rel="stylesheet" href="/Style/main.css" />
    <meta name="robots" content="noindex">
</head>
<body>
    <header>
        <?php require_once __DIR__ . "/../../Template/header.php"; ?>
    </header>
    <main>
        <h2><?= $editPost ? 'Modifier un trajet' : 'Ajouter un trajet' ?></h2>

        <?php if ($message): ?>
            <p><?= $message ?></p>
        <?php endif; ?>

        <form action="Trajets.php" method="post">
            <input type="hidden" name="action" value="<?= $editPost ? 'edit' : 'add' ?>">
            <?php if ($editPost): ?>
                <input type="hidden" name="idposts" value="<?= $editPost['idposts'] ?>">
            <?php endif; ?>

            <label>Titre<br>
                <input type="text" name="title" required value="<?= htmlspecialchars($editPost['title'] ?? '') ?>">
            </label><br>

            <label>Agence de départ<br>
                <select name="departure" required>
                    <option value="">-- Choisir --</option>
                    <?php foreach ($agences as $agence): ?>
                        <option value="<?= $agence['idagences'] ?>"
                            <?= (($editPost['departure'] ?? null) == $agence['idagences']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($agence['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label><br>

            <label>Agence d'arrivée<br>
                <select name="arrival" required>
                    <option value="">-- Choisir --</option>
                    <?php foreach ($agences as $agence): ?>
                        <option value="<?= $agence['idagences'] ?>"
                            <?= (($editPost['arrival'] ?? null) == $agence['idagences']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($agence['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label><br>

            <label>Date et heure du trajet<br>
                <input type="datetime-local" name="travel_date" required
                    value="<?= $editPost ? date('Y-m-d\TH:i', strtotime($editPost['travel_date'])) : '' ?>">
            </label><br>

            <label>Date et heure d'arrivée<br>
                <input type="datetime-local" name="arrival_date"
                    value="<?= ($editPost && $editPost['arrival_date']) ? date('Y-m-d\TH:i', strtotime($editPost['arrival_date'])) : '' ?>">
            </label><br>

            <label>Places disponibles<br>
                <input type="number" name="seats" min="1" required value="<?= htmlspecialchars($editPost['seats'] ?? 1) ?>">
            </label><br>

            <label>Conducteur<br>
                <select name="idusers" required>
                    <option value="">-- Choisir --</option>
                    <?php foreach ($users as $u): ?>
                        <option value="<?= $u['idusers'] ?>"
                            <?= (($editPost['idusers'] ?? null) == $u['idusers']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($u['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label><br><br>

            <button type="submit"><?= $editPost ? 'Enregistrer les modifications' : 'Ajouter' ?></button>
            <?php if ($editPost): ?>
                <a href="Trajets.php">Annuler</a>
            <?php endif; ?>
        </form>

        <h2>Liste des trajets</h2>

        <?php if (empty($posts)): ?>
            <p>Aucun trajet pour le moment.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Départ</th>
                        <th>Arrivée</th>
                        <th>Date de départ</th>
                        <th>Date d'arrivée</th>
                        <th>Places</th>
                        <th>Conducteur</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($posts as $post): ?>
                        <tr>
                            <td><?= htmlspecialchars($post['idposts']) ?></td>
                            <td><?= htmlspecialchars($post['title']) ?></td>
                            <td><?= htmlspecialchars($post['departure_name'] ?? '—') ?></td>
                            <td><?= htmlspecialchars($post['arrival_name'] ?? '—') ?></td>
                            <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($post['travel_date']))) ?></td>
                            <td><?= $post['arrival_date'] ? htmlspecialchars(date('d/m/Y H:i', strtotime($post['arrival_date']))) : '—' ?></td>
                            <td><?= htmlspecialchars($post['seats']) ?></td>
                            <td><?= htmlspecialchars($post['user_name'] ?? '—') ?></td>
                            <td>
                                <a href="Trajets.php?action=edit&id=<?= $post['idposts'] ?>">Modifier</a>

                                <form action="Trajets.php" method="post" style="display:inline"
                                      onsubmit="return confirm('Supprimer ce trajet ?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="idposts" value="<?= $post['idposts'] ?>">
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