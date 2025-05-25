<?php
function drawServiceCard($service, $show_username = true, $show_actions = false, $user_role = null)
{
  $mediaPath = $service['image_path'] ?? '/img/default.jpeg';
  $isVideo = preg_match('/\.(mp4|webm|ogg)$/i', $mediaPath);
?>
  <div class="service-card">
    <?php if ($isVideo): ?>
      <video 
        src="<?= htmlspecialchars($mediaPath) ?>" 
        muted 
        loop 
        preload="metadata"
        class="service-media"
        onmouseover="this.play()" 
        onmouseout="this.pause(); this.currentTime=0;">
        Your browser does not support the video tag.
      </video>
    <?php else: ?>
      <img 
        src="<?= htmlspecialchars($mediaPath) ?>" 
        alt="<?= htmlspecialchars($service['title']) ?>" 
        class="service-media">
    <?php endif; ?>

    <div class="service-card-content">
      <h3><?= htmlspecialchars($service['title']) ?></h3>

      <?php if ($show_username && isset($service['username'])): ?>
        <p>por <strong><?= htmlspecialchars($service['username']) ?></strong></p>
      <?php endif; ?>

      <p class="price">Desde <?= number_format($service['price'], 2) ?>€</p>

      <?php if ($show_actions): ?>
        <div class="service-actions">
          <?php if ($user_role === 'admin'): ?>
            <a href="/admin/edit_service.php?id=<?= $service['id'] ?>" class="btn btn-success btn-small">Editar</a>
            <a href="/admin/delete_service.php?id=<?= $service['id'] ?>" class="btn btn-danger btn-small"
              onclick="return confirm('Tem a certeza que quer eliminar este serviço?')">Eliminar</a>
          <?php elseif ($user_role === 'freelancer'): ?>
            <a href="/pages/edit_service.php?id=<?= $service['id'] ?>" class="btn btn-success btn-small">Editar</a>
            <a href="/actions/delete_service.php?id=<?= $service['id'] ?>" class="btn btn-danger btn-small"
              onclick="return confirm('Tem a certeza que quer eliminar este serviço?')">Eliminar</a>
          <?php else: ?>
            <a href="/pages/view_service.php?id=<?= $service['id'] ?>" class="btn btn-primary">Ver Serviço</a>
          <?php endif; ?>
        </div>
      <?php else: ?>
        <a href="/pages/view_service.php?id=<?= $service['id'] ?>" class="btn btn-primary">Ver Serviço</a>
      <?php endif; ?>
    </div>
  </div>
<?php
}
?>
