<div class="home">
    <h2>Trajets disponibles</h2>

    <?php if (isset($_GET['success'])): ?>
        <p style="color: green; font-weight: bold;">Inscrit avec succès à ce trajet !</p>
    <?php elseif (isset($_GET['info']) && $_GET['info'] === 'already_subscribed'): ?>
        <p style="color: orange; font-weight: bold;">Vous êtes déjà inscrit à ce trajet.</p>
    <?php endif; ?>

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
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <th>Action</th>
                    <?php endif; ?>
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
                        
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <td>
                                <?php if (isset($post['idusers']) && $post['idusers'] == $_SESSION['user_id']): ?>
                                    <span style="color: gray; font-style: italic;">Mon trajet</span>
                                <?php else: ?>
                                    <form action="../Core/subscribe.php" method="post" style="margin:0;">
                                        <input type="hidden" name="idposts" value="<?= $post['idposts'] ?>">
                                        <button type="submit" class="btn-subscribe" style="cursor: pointer;">S'inscrire</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>