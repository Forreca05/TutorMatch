<?php
session_start();
require_once '../database/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die('Acesso negado.');
}

$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    if (!empty($name)) {
        try {
            $stmt = $db->prepare("INSERT INTO categories (name) VALUES (?)");
            $stmt->execute([$name]);
            $success = "Categoria adicionada com sucesso.";
        } catch (PDOException $e) {
            $error = "Erro: " . $e->getMessage();
        }
    } else {
        $error = "O nome da categoria não pode estar vazio.";
    }
}

// Buscar categorias ordenadas alfabeticamente
$stmt = $db->query("SELECT * FROM categories ORDER BY name ASC");
$categories = $stmt->fetchAll();
?>

<?php include_once '../includes/header.php'; ?>

<link rel="stylesheet" href="../css/admin.css">

<div class="category-container">
    <h2>Gestão de Categorias</h2>

    <?php if ($success): ?>
        <p class="flash-message success"><?= $success ?></p>
    <?php elseif ($error): ?>
        <p class="flash-message error"><?= $error ?></p>
    <?php endif; ?>

    <form method="post" action="" class="category-form">
        <input type="text" name="name" placeholder="Nova categoria" required>
        <button type="submit">Adicionar</button>
    </form>

    <table class="category-table">
        <thead>
            <tr>
                <th>Nome da Categoria</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($categories as $category): ?>
            <tr>
                <td><?= htmlspecialchars($category['name']); ?></td>
                <td>
                    <a href="../actions/delete_category.php?id=<?= $category['id']; ?>" onclick="return confirm('Apagar esta categoria?');" class="delete-link">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="../js/messages.js"></script>

<?php include_once '../includes/footer.php'; ?>
