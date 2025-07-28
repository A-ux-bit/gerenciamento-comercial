<?php 
include '../includes/config.php';
include '../includes/functions.php';

$pedidos = getPedidos();
$produtos = getProdutos();

// Calcular total de vendas
$totalVendas = 0;
foreach ($pedidos as $pedido) {
    $totalVendas += $pedido['total'];
}

// Calcular vendas por produto
$vendasPorProduto = [];
foreach ($produtos as $produto) {
    $vendasPorProduto[$produto['id']] = [
        'nome' => $produto['nome'],
        'quantidade' => 0,
        'total' => 0
    ];
}

foreach ($pedidos as $pedido) {
    $itens = getItensPedido($pedido['id']);
    foreach ($itens as $item) {
        $vendasPorProduto[$item['produto_id']]['quantidade'] += $item['quantidade'];
        $vendasPorProduto[$item['produto_id']]['total'] += $item['quantidade'] * $item['preco_unitario'];
    }
}
?>

<?php include '../includes/header.php'; ?>

<h2>Relatório de Vendas</h2>

<div class="card mb-4">
    <div class="card-header">
        Resumo Geral
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Total de Pedidos:</strong> <?php echo count($pedidos); ?></p>
            </div>
            <div class="col-md-6">
                <p><strong>Valor Total Vendido:</strong> R$ <?php echo number_format($totalVendas, 2, ',', '.'); ?></p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        Vendas por Produto
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Quantidade Vendida</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vendasPorProduto as $produto_id => $dados): ?>
                    <?php if ($dados['quantidade'] > 0): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($dados['nome']); ?></td>
                            <td><?php echo $dados['quantidade']; ?></td>
                            <td>R$ <?php echo number_format($dados['total'], 2, ',', '.'); ?></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>