<?php
    // Sécurité : si ce fragment est chargé sans passer par nouveau_trajet.php,
    // on évite l'erreur "$agences non définie"
    if (!isset($agences)) {
        $agences = [];
    }
?>
<div class="nouveau-trajet">
    <form action="nouveau_trajet.php" method="post">
        <fieldset>
            <legend>Détails du trajet</legend>

            <br><label for="title">Titre</label><br>
            <input type="text" id="title" name="title" required><br>

            <label for="departure">Agence de départ</label><br>
            <select id="departure" name="departure" required>
                <option value="">-- Choisir --</option>
                <?php foreach ($agences as $agence): ?>
                    <option value="<?= $agence['idagences'] ?>">
                        <?= htmlspecialchars($agence['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select><br>

            <label for="arrival">Agence d'arrivée</label><br>
            <select id="arrival" name="arrival" required>
                <option value="">-- Choisir --</option>
                <?php foreach ($agences as $agence): ?>
                    <option value="<?= $agence['idagences'] ?>">
                        <?= htmlspecialchars($agence['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select><br>

            <label for="travel_date">Date et heure de départ</label><br>
            <input type="datetime-local" id="travel_date" name="travel_date" required><br>

            <label for="arrival_date">Date et heure d'arrivée (optionnel)</label><br>
            <input type="datetime-local" id="arrival_date" name="arrival_date"><br>

            <label for="seats">Places disponibles</label><br>
            <input type="number" id="seats" name="seats" min="1" value="1" required><br><br>

            <input type="submit" value="Créer le trajet">
        </fieldset>
    </form>
</div>