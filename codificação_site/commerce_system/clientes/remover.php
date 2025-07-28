<?php
include '../includes/config.php';
include '../includes/functions.php';

// Verifica se o ID do cliente foi passado
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$cliente_id = intval($_GET['id']);
$cliente = getClienteById($cliente_id);

// Verifica se o cliente existe
if (!$cliente) {
    $_SESSION['error'] = "Cliente não encontrado.";
    header("Location: index.php");
    exit();
}

// Verifica se o cliente tem pedidos associados
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM pedidos WHERE cliente_id = ?");
    $stmt->execute([$cliente_id]);
    $pedidos_count = $stmt->fetchColumn();
    
    if ($pedidos_count > 0) {
        $_SESSION['error'] = "Não é possível remover este cliente pois existem pedidos associados a ele.";
        header("Location: index.php");
        exit();
    }
    
    // Remove o cliente
    $stmt = $pdo->prepare("DELETE FROM clientes WHERE id = ?");
    if ($stmt->execute([$cliente_id])) {
        $_SESSION['success'] = "Cliente removido com sucesso!";
    } else {
        $_SESSION['error'] = "Erro ao remover cliente.";
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Erro no banco de dados: " . $e->getMessage();
}

header("Location: index.php");
exit();
?>