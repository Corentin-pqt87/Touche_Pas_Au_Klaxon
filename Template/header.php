<!DOCTYPE html>
<div class="header">
    <h1>Touche Pas Au Klaxon</h1>
    <nav>
        <?php 
            /**
             * Si l'utilisateur n'est pas connecté {
             *      boutton : "Connexion/Inscription";
             * };
             * Si l'utilisateur est connecté {
             *      Si l'utilisateur est admin {
             *          boutton : "Tableau de bord administrateur";
             *      } Sinon {
             *          boutton : "Création d'un nouveau trajet";
             *      };
             * };
             */
        ?>
        <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="Template/login_Template.php" class="btn">Connexion/Inscription</a>
        <?php else: ?>
            <p>Bonjour, <?= htmlspecialchars($_SESSION['user_name']) ?> !</p>
            
            <?php if ($_SESSION['user_role'] == 1): //1 = Admin ?>
                <a href="Page/admin.php" class="btn">Tableau de bord administrateur</a>
            <?php else: ?>
                <a href="Page/nouveau_trajet.php" class="btn">Création d'un nouveau trajet</a>
            <?php endif; ?>

            <a href="Core/logout.php">Déconnexion</a>
        <?php endif; ?>
    </nav>
</div>