<?php
    /**
     * Sécurité : si ce fragment est chargé sans passer par Profil.php,
     * on évite l'erreur "$mesTrajets non définie"
     */
    if (!isset($mesTrajets)) {
        $mesTrajets = [];
    }
?>
<section class="profil-section">
    <h3>Trajets que j'ai créés</h3>

    <?php if (empty($mesTrajets)): ?>
        <p>Vous n'avez créé aucun trajet pour le moment.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Départ</th>
                    <th>Arrivée</th>
                    <th>Date de départ</th>
                    <th>Date d'arrivée</th>
                    <th>Places restantes</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($mesTrajets as $trajet): ?>
                    <tr>
                        <td><?= htmlspecialchars($trajet['title'] ?? 'Sans titre') ?></td>
                        <td><?= htmlspecialchars($trajet['departure_name']) ?></td>
                        <td><?= htmlspecialchars($trajet['arrival_name']) ?></td>
                        <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($trajet['travel_date']))) ?></td>
                        <td>
                            <?= $trajet['arrival_date']
                                ? htmlspecialchars(date('d/m/Y H:i', strtotime($trajet['arrival_date'])))
                                : '—' ?>
                        </td>
                        <td><?= htmlspecialchars($trajet['seats']) ?></td>
                        <td>
                            <form action="Profil.php" method="post" style="display:inline"
                                  onsubmit="return confirm('Supprimer ce trajet ? Les inscriptions liées seront également supprimées.')">
                                <input type="hidden" name="action" value="delete_post">
                                <input type="hidden" name="idposts" value="<?= $trajet['idposts'] ?>">
                                <button type="submit">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>