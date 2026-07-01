<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Sécurité : Vérification Admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
    header('Location: ../../index.php');
    exit();
}

require_once __DIR__ . "/../../Core/dataBase.php";
require_once __DIR__ . "/../../Model/userModel.php";
require_once __DIR__ . "/../../Model/postModel.php";

$message = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // Ajouter une inscription
    if ($action === 'add') {
        $idusers = intval($_POST['idusers']);
        $idposts = intval($_POST['idposts']);

        if ($idusers > 0 && $idposts > 0) {
            try {
                $bdd = connection();
                
                // pas de doublons d'inscription
                $check = $bdd->prepare("SELECT * FROM inscription WHERE idusers = :idusers AND idposts = :idposts");
                $check->execute(['idusers' => $idusers, 'idposts' => $idposts]);
                
                if ($check->fetch()) {
                    $message = "Erreur : Cet utilisateur est déjà inscrit à ce trajet.";
                } else {
                    $stmt = $bdd->prepare("INSERT INTO inscription (idusers, idposts) VALUES (:idusers, :idposts)");
                    if ($stmt->execute(['idusers' => $idusers, 'idposts' => $idposts])) {
                        // place -- 1
                        $updateSeats = $bdd->prepare("UPDATE posts SET seats = seats - 1 WHERE idposts = :idposts AND seats > 0");
                        $updateSeats->execute(['idposts' => $idposts]);
                        
                        $message = "Inscription ajoutée avec succès !";
                    } else {
                        $message = "Erreur lors de l'inscription.";
                    }
                }
            } catch (Exception $e) {
                $message = "Erreur BDD : " . $e->getMessage();
            }
        } else {
            $message = "Veuillez sélectionner un utilisateur et un trajet valides.";
        }

    // Supprimer une inscription
    } elseif ($action === 'delete') {
        $idinscription = intval($_POST['idinscription']);
        $idposts = intval($_POST['idposts']); // Récupéré pour rendre la place disponible
        
        if (deleteInscription($idinscription)) {
            // Réaugmenter le nombre de places sur le trajet
            $bdd = connection();
            $updateSeats = $bdd->prepare("UPDATE posts SET seats = seats + 1 WHERE idposts = :idposts");
            $updateSeats->execute(['idposts' => $idposts]);
            
            $message = "Inscription supprimée.";
        } else {
            $message = "Erreur lors de la suppression.";
        }
    }
}

$bdd = connection();

// toute les inscriptions
$queryInscriptions = $bdd->query('
    SELECT i.idinscription, i.idusers, i.idposts, 
           u.name AS user_name, 
           p.title AS post_title, p.travel_date,
           da.name AS departure_name, aa.name AS arrival_name
    FROM inscription i
    INNER JOIN users u ON i.idusers = u.idusers
    INNER JOIN posts p ON i.idposts = p.idposts
    INNER JOIN agences da ON p.departure = da.idagences
    INNER JOIN agences aa ON p.arrival = aa.idagences
    ORDER BY p.travel_date DESC
');
$inscriptions = $queryInscriptions->fetchAll(PDO::FETCH_ASSOC);

// utilisateus + trajets
$allUsers = getAllUsers(); 
$allPosts = getAllPosts(); // Utilise la fonction existante de ton postModel.php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Gestion des Inscriptions</title>
    <link rel="stylesheet" href="../../Style/main.css">
</head>
<body>
    <header>
        <?php require_once __DIR__ . "/../../Template/admin_header.php"; ?>
    </header>

    <main>
        <h2>Gestion des Inscriptions aux trajets</h2>

        <?php if (!empty($message)): ?>
            <p>
                <?= htmlspecialchars($message) ?>
            </p>
        <?php endif; ?>

        <fieldset >
            <legend><h3>Inscrire un utilisateur à un trajet</h3></legend>
            <form action="Inscriptions.php" method="post">
                <input type="hidden" name="action" value="add">

                <label for="idusers">Choisir l'utilisateur :</label>
                <select name="idusers" id="idusers" required>
                    <option value="">-- Sélectionner --</option>
                    <?php foreach ($allUsers as $u): ?>
                        <option value="<?= $u['idusers'] ?>"><?= htmlspecialchars($u['name']) ?> (<?= htmlspecialchars($u['mail']) ?>)</option>
                    <?php endforeach; ?>
                </select>

                <br><br>

                <label for="idposts">Choisir le trajet :</label>
                <select name="idposts" id="idposts" required>
                    <option value="">-- Sélectionner --</option>
                    <?php foreach ($allPosts as $p): ?>
                        <option value="<?= $p['idposts'] ?>">
                            [ID: <?= $p['idposts'] ?>] <?= htmlspecialchars($p['title'] ?? 'Sans titre') ?> | 
                            De <?= htmlspecialchars($p['departure_name']) ?> à <?= htmlspecialchars($p['arrival_name']) ?> 
                            (Le <?= date('d/m/Y H:i', strtotime($p['travel_date'])) ?>, Places restantes : <?= $p['seats'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>

                <br><br>
                <button type="submit" class="btn">Créer l'inscription</button>
            </form>
        </fieldset>

        <h3>Liste des inscriptions actives</h3>
        <?php if (empty($inscriptions)): ?>
            <p>Aucune inscription enregistrée.</p>
        <?php else: ?>
            <table border="1" cellpadding="10">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Passager / Utilisateur</th>
                        <th>Trajet (Titre)</th>
                        <th>Itinéraire</th>
                        <th>Date du voyage</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inscriptions as $insc): ?>
                        <tr>
                            <td><?= $insc['idinscription'] ?></td>
                            <td><strong><?= htmlspecialchars($insc['user_name']) ?></strong> (ID: <?= $insc['idusers'] ?>)</td>
                            <td><?= htmlspecialchars($insc['post_title'] ?? 'Sans titre') ?></td>
                            <td>De <em><?= htmlspecialchars($insc['departure_name']) ?></em> à <em><?= htmlspecialchars($insc['arrival_name']) ?></em></td>
                            <td><?= date('d/m/Y H:i', strtotime($insc['travel_date'])) ?></td>
                            <td>
                                <form action="Inscriptions.php" method="post" 
                                      onsubmit="return confirm('Désinscrire cet utilisateur de ce trajet ? Cela lui réattribuera sa place.')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="idinscription" value="<?= $insc['idinscription'] ?>">
                                    <input type="hidden" name="idposts" value="<?= $insc['idposts'] ?>">
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