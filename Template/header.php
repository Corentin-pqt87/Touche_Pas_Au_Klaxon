<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}
?>
<!DOCTYPE html>
<div class="header">
    <a href="/Page/Accueil.php"><h1>Touche Pas Au Klaxon</h1></a>    
    <nav>
        <?php 
            /**
             * Si l'utilisateur n'est pas connecté {
             *      boutton : "Connexion/Inscription";
             * };
             * Si l'utilisateur est connecté {
             *      boutton : "Mon profil";
             *      Si l'utilisateur est admin {
             *          boutton : "Tableau de bord administrateur";
             *      } Sinon {
             *          boutton : "Création d'un nouveau trajet";
             *      };
             * };
             */
        ?>
        <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="/Template/login_Template.php" class="btn">Connexion</a> | 
            <a href="/Template/register_Template.php" class="btn">Inscription</a>
        <?php else: ?>
            <p>Bonjour, <?= htmlspecialchars($_SESSION['user_name']) ?> !</p>

            <a href="/Page/Profil.php" class="btn">Mon profil</a>

            <?php if ($_SESSION['user_role'] == 1): // Admin ?>
                <a href="/Page/admin.php" class="btn">Tableau de bord administrateur</a>
            <?php else: ?>
                <a href="/Page/nouveau_trajet.php" class="btn">Création d'un nouveau trajet</a>
            <?php endif; ?>

            <a href="/Core/logout.php" class="btn-logout">Déconnexion</a>
        <?php endif; ?>
    </nav>
</div>