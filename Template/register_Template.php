<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Inscription</title>
        <meta name="robots" content="noindex">
        <link rel="stylesheet" href="/../Style/main.css" />
    </head>
    <body>
        <main>
            <h1>Touche Pas Au Klaxon</h1>
            <h2>Inscription</h2>
            <form action="../Core/register.php" method="post">
                <fieldset>
                    <legend>Créez votre compte</legend>
                    
                    <br><label for="name">Nom d'utilisateur</label><br>
                    <input type="text" id="name" name="name" required><br>

                    <label for="mail">Adresse Email</label><br>
                    <input type="email" id="mail" name="mail" required><br>

                    <label for="phone">Téléphone</label><br>
                    <input type="tel" id="phone" name="phone"><br>

                    <label for="keypass">Mot de passe</label><br>
                    <input type="password" id="keypass" name="keypass" required><br><br>

                    <input type="submit" value="S'inscrire">
                </fieldset>
            </form>
            <p>Déjà inscrit ? <a href="login_Template.php">Connectez-vous ici</a></p>
        </main>
    </body>
</html>