<?php
include '../includes/config.php';
include '../includes/functions.php';

// Verifica se a requisição é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

// Verifica se os campos necessários foram enviados
if (!isset($_POST['produto_id']) || !isset($_POST['quantidade'])) {
    $_SESSION['error'] = "Dados incompletos para atualização do estoque.";
    header("Location: index.php");
    exit();
}

// Sanitiza os inputs
$produto_id = intval($_POST['produto_id']);
$quantidade = intval($_POST['quantidade']);

// Verifica se o produto existe
$produto = getProdutoById($produto_id);
if (!$produto) {
    $_SESSION['error'] = "Produto não encontrado.";
    header("Location: index.php");
    exit();
}

// Verifica se a operação deixará o estoque negativo
$novo_estoque = $produto['estoque'] + $quantidade;
if ($novo_estoque < 0) {
    $_SESSION['error'] = "Operação inválida. Estoque não pode ficar negativo.";
    header("Location: index.php");
    exit();
}

try {
    // Atualiza o estoque no banco de dados
    $stmt = $pdo->prepare("UPDATE produtos SET estoque = estoque + ? WHERE id = ?");
    $stmt->execute([$quantidade, $produto_id]);
    
    // Registra a movimentação no histórico (opcional)
    registrarMovimentacaoEstoque($produto_id, $quantidade, 'Ajuste manual');
    
    $_SESSION['success'] = "Estoque atualizado com sucesso! Novo estoque: " . $novo_estoque;
} catch (PDOException $e) {
    $_SESSION['error'] = "Erro ao atualizar estoque: " . $e->getMessage();
}

header("Location: index.php");
exit();

/**
 * Função para registrar movimentação de estoque (opcional)
 */
function registrarMovimentacaoEstoque($produto_id, $quantidade, $motivo) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("INSERT INTO historico_estoque 
                              (produto_id, quantidade, motivo, data) 
                              VALUES (?, ?, ?, NOW())");
        $stmt->execute([$produto_id, $quantidade, $motivo]);
    } catch (PDOException $e) {
        // Não interrompe o fluxo principal se falhar o registro do histórico
        error_log("Erro ao registrar histórico de estoque: " . $e->getMessage());
    }
}
?>