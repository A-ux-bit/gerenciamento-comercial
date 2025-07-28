<?php
include '../includes/config.php';
include '../includes/functions.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$compra_id = $_GET['id'];

try {
    // Obter detalhes da compra antes de remover
    $stmt = $pdo->prepare("SELECT * FROM compras WHERE id = ?");
    $stmt->execute([$compra_id]);
    $compra = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$compra) {
        $_SESSION['error'] = "Compra não encontrada.";
        header("Location: index.php");
        exit();
    }
    
    $pdo->beginTransaction();
    
    // Remover a compra
    $stmt = $pdo->prepare("DELETE FROM compras WHERE id = ?");
    $stmt->execute([$compra_id]);
    
    // Atualizar o estoque (remover a quantidade comprada)
    $stmt = $pdo->prepare("UPDATE produtos SET estoque = estoque - ? WHERE id = ?");
    $stmt->execute([$compra['quantidade'], $compra['produto_id']]);
    
    $pdo->commit();
    
    $_SESSION['success'] = "Compra removida com sucesso!";
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['error'] = "Erro ao remover compra: " . $e->getMessage();
}

header("Location: index.php");
exit();
?>