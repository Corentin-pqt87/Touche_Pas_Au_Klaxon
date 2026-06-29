<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="widt=device-width, initial-scale=1.0">
        <meta name="robots" content="noindex">
        <link rel="stylesheet" href="/../Style/main.css" />
        <title>Connexion</title>
    </head>
    <body>
        <main>
            <h1>Touche Pas Au Klaxon</h1>
            <h2>Connexion</h2>
            <form action="../Core/login.php" method="post">
                <fieldset>
                    <legend>Saisissez votre nom et votre mot de passe</legend>
                    <br><label for="name">Nom</label><br>
                    <input type="text" id="name" name="name" required><br>

                    <label for="keypass">Mot de passe</label><br>
                    <input type="password" id="keypass" name="keypass" required><br><br>

                    <input type="submit" value="Soumettre">
                </fieldset>
            </form>
        </main>
    </body>
</html>