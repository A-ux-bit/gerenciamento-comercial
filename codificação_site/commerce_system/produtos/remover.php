<?php
include '../includes/config.php';
include '../includes/functions.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$produto_id = $_GET['id'];

// Verificar se o produto existe
$produto = getProdutoById($produto_id);
if (!$produto) {
    $_SESSION['error'] = "Produto não encontrado.";
    header("Location: index.php");
    exit();
}

// Verificar se o produto está em algum pedido
$stmt = $pdo->prepare("SELECT COUNT(*) FROM itens_pedido WHERE produto_id = ?");
$stmt->execute([$produto_id]);
$count = $stmt->fetchColumn();

if ($count > 0) {
    $_SESSION['error'] = "Não é possível remover este produto pois ele está associado a pedidos.";
    header("Location: index.php");
    exit();
}

// Remover o produto
$stmt = $pdo->prepare("DELETE FROM produtos WHERE id = ?");
if ($stmt->execute([$produto_id])) {
    $_SESSION['success'] = "Produto removido com sucesso!";
} else {
    $_SESSION['error'] = "Erro ao remover produto.";
}

header("Location: index.php");
exit();
?>