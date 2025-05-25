<?php
function drawServiceCard($service, $show_username = true, $show_actions = false, $user_role = null) {
?>
    <div class="service-card">
        <img src="<?= htmlspecialchars($service['image_path'] ?? '/img/default.jpeg') ?>" alt="<?= htmlspecialchars($service['title']) ?>">
        <div class="service-card-content">
            <h3><?= htmlspecialchars($service['title']) ?></h3>
            
            <?php if ($show_username && isset($service['username'])): ?>
                <p>por <strong><?= htmlspecialchars($service['username']) ?></strong></p>
            <?php endif; ?>
            
            <p class="price">Desde <?= number_format($service['price'], 2) ?>€</p>
            
            <?php if ($show_actions): ?>
                <div class="service-actions">
                    <?php if ($user_role === 'admin'): ?>
                        <a href="/admin/edit_service.php?id=<?= $service['id'] ?>" class="edit-btn">Editar</a>
                        <a href="/admin/delete_service.php?id=<?= $service['id'] ?>" class="delete-btn" 
                           onclick="return confirm('Tem a certeza que quer eliminar este serviço?')">Eliminar</a>
                    <?php elseif ($user_role === 'freelancer'): ?>
                        <a href="/pages/edit_service.php?id=<?= $service['id'] ?>" class="edit-btn">Editar</a>
                        <a href="/pages/delete_service.php?id=<?= $service['id'] ?>" class="delete-btn"
                           onclick="return confirm('Tem a certeza que quer eliminar este serviço?')">Eliminar</a>
                    <?php else: ?>
                        <a href="/pages/view_service.php?id=<?= $service['id'] ?>" class="view-btn">Ver Serviço</a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <a href="/pages/view_service.php?id=<?= $service['id'] ?>" class="view-btn">Ver Serviço</a>
            <?php endif; ?>
        </div>
    </div>
<?php
}
?>