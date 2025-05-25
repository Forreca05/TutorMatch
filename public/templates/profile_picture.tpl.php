<?php
function drawProfilePicture($pic_path, $alt_text = 'Foto de perfil', $size = '120px') {
?>
    <img src="<?= htmlspecialchars($pic_path ?: '/img/default.jpeg') ?>" 
         alt="<?= htmlspecialchars($alt_text) ?>" 
         class="profile-pic"
         style="width: <?= $size ?>; height: <?= $size ?>;">
<?php
}
?>