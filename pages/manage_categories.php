<?php
require_once '../database/db.php';
include_once '../includes/header.php';

// Buscar categorias existentes
$stmt = $db->query("SELECT * FROM categories");
$categories = $stmt->fetchAll();
?>

<h2>Categorias</h2>

<form method="post" action="../actions/add_category.php">
    <input type="text" name="name" placeholder="Nova categoria" required>
    <button type="submit">Adicionar</button>
</form>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Ações</th>
    </tr>
    <?php foreach ($categories as $category): ?>
    <tr>
        <td><?= $category['id']; ?></td>
        <td><?= htmlspecialchars($category['name']); ?></td>
        <td>
            <a href="../actions/delete_category.php?id=<?= $category['id']; ?>" onclick="return confirm('Apagar esta categoria?');">Eliminar</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php include_once '../includes/footer.php'; ?>
