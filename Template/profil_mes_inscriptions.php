<?php
    /**
     * Sécurité : si ce fragment est chargé sans passer par Profil.php,
     * on évite l'erreur "$mesInscriptions non définie"
     */
    if (!isset($mesInscriptions)) {
        $mesInscriptions = [];
    }
?>
<section class="profil-section">
    <h3>Trajets auxquels je suis inscrit</h3>

    <?php if (empty($mesInscriptions)): ?>
        <p>Vous n'êtes inscrit à aucun trajet pour le moment.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Départ</th>
                    <th>Arrivée</th>
                    <th>Date de départ</th>
                    <th>Date d'arrivée</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($mesInscriptions as $inscription): ?>
                    <tr>
                        <td><?= htmlspecialchars($inscription['title'] ?? 'Sans titre') ?></td>
                        <td><?= htmlspecialchars($inscription['departure_name']) ?></td>
                        <td><?= htmlspecialchars($inscription['arrival_name']) ?></td>
                        <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($inscription['travel_date']))) ?></td>
                        <td>
                            <?= $inscription['arrival_date']
                                ? htmlspecialchars(date('d/m/Y H:i', strtotime($inscription['arrival_date'])))
                                : '—' ?>
                        </td>
                        <td>
                            <form action="Profil.php" method="post" style="display:inline"
                                  onsubmit="return confirm('Vous désinscrire de ce trajet ?')">
                                <input type="hidden" name="action" value="unsubscribe">
                                <input type="hidden" name="idinscription" value="<?= $inscription['idinscription'] ?>">
                                <button type="submit">Se désinscrire</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>