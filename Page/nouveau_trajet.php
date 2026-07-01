<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Sécurité : Un utilisateur doit être connecté (qu'il soit admin ou utilisateur standard)
if (!isset($_SESSION['user_id'])) {
    header('Location: ./Accueil.php');
    exit();
}

require_once __DIR__ . "/../Model/postModel.php";
require_once __DIR__ . "/../Model/agenceModel.php";

$message = '';

// Traitement de la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title        = trim($_POST['title']);
    $departure    = intval($_POST['departure']);
    $arrival      = intval($_POST['arrival']);
    $travel_date  = $_POST['travel_date'];
    $arrival_date = !empty($_POST['arrival_date']) ? $_POST['arrival_date'] : null;
    $seats        = intval($_POST['seats']);
    
    // Sécurité stricte : On force l'idusers avec celui de l'utilisateur connecté en session
    $idusers      = $_SESSION['user_id'];

    if (!empty($title) && $departure > 0 && $arrival > 0 && !empty($travel_date) && $seats > 0) {
        // On appelle la fonction addPost() existante de postModel.php
        $success = addPost($title, $departure, $arrival, $travel_date, $arrival_date, $seats, $idusers);
        
        if ($success) {
            $message = "Votre trajet a été créé avec succès !";
            // Optionnel : Redirection vers l'accueil après succès
            // header('Location: ./Accueil.php');
            // exit();
        } else {
            $message = "Une erreur est survenue lors de la création du trajet.";
        }
    } else {
        $message = "Veuillez remplir tous les champs obligatoires correctement.";
    }
}

// Récupération de la liste des agences pour les menus déroulants <select>
// Utilise la fonction de agenceModel.php (ou adaptée selon ton code réel)
$agences = getAllAgences(); 
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <title>Touche Pas Au Klaxon - Nouveau Trajet</title>
        <link rel="stylesheet" href="/../Style/main.css" />
    </head>
    <body>
        <header>
            <?php require_once __DIR__ . "/../Template/header.php"; ?>
        </header>
        
        <main class="nouveau-trajet">
            <h1>Proposer un nouveau trajet</h1>

            <?php if (!empty($message)): ?>
                <p>
                    <?= htmlspecialchars($message) ?>
                </p>
            <?php endif; ?>

            <form action="nouveau_trajet.php" method="post">
                
                <div>
                    <label for="title" >Titre ou description sommaire :</label>
                    <input type="text" name="title" id="title" required placeholder="Nom du trajet">
                </div>

                <div>
                    <label for="departure">Agence de départ :</label>
                    <select name="departure" id="departure" required>
                        <option value="">-- Sélectionner l'agence --</option>
                        <?php foreach ($agences as $agence): ?>
                            <option value="<?= $agence['idagences'] ?>"><?= htmlspecialchars($agence['name']) ?> (<?= htmlspecialchars($agence['location'] ?? '') ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="arrival">Agence d'arrivée :</label>
                    <select name="arrival" id="arrival" required>
                        <option value="">-- Sélectionner l'agence --</option>
                        <?php foreach ($agences as $agence): ?>
                            <option value="<?= $agence['idagences'] ?>"><?= htmlspecialchars($agence['name']) ?> (<?= htmlspecialchars($agence['location'] ?? '') ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="travel_date">Date et heure de départ :</label>
                    <input type="datetime-local" name="travel_date" id="travel_date" required>
                </div>

                <div>
                    <label for="arrival_date">Date et heure d'arrivée (Optionnel) :</label>
                    <input type="datetime-local" name="arrival_date" id="arrival_date">
                </div>

                <div>
                    <label for="seats">Nombre de places disponibles :</label>
                    <input type="number" name="seats" id="seats" min="1" max="8" value="1" required>
                </div>

                <div>
                    <button type="submit" class="btn">Publier le trajet</button>
                    <a href="./Accueil.php">Annuler</a>
                </div>

            </form>
        </main>
        
        <footer>
            <?php require_once __DIR__ . "/../Template/footer.php"; ?>
        </footer>
    </body>
</html>