<?php
function getClientes() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM clientes ORDER BY nome");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getClienteById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM clientes WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getProdutos() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM produtos ORDER BY nome");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getProdutoById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getPedidos() {
    global $pdo;
    $stmt = $pdo->query("SELECT p.*, c.nome as cliente_nome FROM pedidos p JOIN clientes c ON p.cliente_id = c.id ORDER BY p.data DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getItensPedido($pedido_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT ip.*, pr.nome as produto_nome FROM itens_pedido ip JOIN produtos pr ON ip.produto_id = pr.id WHERE ip.pedido_id = ?");
    $stmt->execute([$pedido_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getCompras() {
    global $pdo;
    $stmt = $pdo->query("SELECT c.*, p.nome as produto_nome FROM compras c JOIN produtos p ON c.produto_id = p.id ORDER BY c.data DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function atualizarEstoque($produto_id, $quantidade) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE produtos SET estoque = estoque + ? WHERE id = ?");
    return $stmt->execute([$quantidade, $produto_id]);
}
?>