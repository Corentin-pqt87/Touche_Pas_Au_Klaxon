<div class="home">
    <h2>Trajets disponibles</h2>

    <?php if (empty($availablePosts)): ?>
        <p>Aucun trajet disponible pour le moment.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Agence de départ</th>
                    <th>Date de départ</th>
                    <th>Agence d'arrivée</th>
                    <th>Date d'arrivée</th>
                    <th>Places disponibles</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($availablePosts as $post): ?>
                    <tr>
                        <td><?= htmlspecialchars($post['departure_name']) ?></td>
                        <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($post['travel_date']))) ?></td>
                        <td><?= htmlspecialchars($post['arrival_name']) ?></td>
                        <td>
                            <?= $post['arrival_date']
                                ? htmlspecialchars(date('d/m/Y H:i', strtotime($post['arrival_date'])))
                                : '—' ?>
                        </td>
                        <td><?= htmlspecialchars($post['seats']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>