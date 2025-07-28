<?php 
include '../includes/config.php';
include '../includes/functions.php';

$clientes = getClientes();
$pedidos = getPedidos();

// Calcular número de pedidos por cliente
$pedidosPorCliente = [];
foreach ($pedidos as $pedido) {
    if (!isset($pedidosPorCliente[$pedido['cliente_id']])) {
        $pedidosPorCliente[$pedido['cliente_id']] = 0;
    }
    $pedidosPorCliente[$pedido['cliente_id']]++;
}

// Encontrar máximo
$maxPedidos = $pedidosPorCliente ? max($pedidosPorCliente) : 0;
?>

<?php include '../includes/header.php'; ?>

<h2>Análise de Clientes</h2>

<?php if (empty($pedidos)): ?>
    <div class="alert alert-info">Nenhum pedido registrado ainda.</div>
<?php else: ?>
    <div class="card mb-4">
        <div class="card-header">
            Clientes Mais Ativos
        </div>
        <div class="card-body">
            <?php if ($maxPedidos > 0): ?>
                <ul class="list-group">
                    <?php foreach ($clientes as $cliente): ?>
                        <?php if (isset($pedidosPorCliente[$cliente['id']]) && $pedidosPorCliente[$cliente['id']] == $maxPedidos): ?>
                            <li class="list-group-item">
                                <?php echo htmlspecialchars($cliente['nome']); ?> - 
                                <?php echo $pedidosPorCliente[$cliente['id']]; ?> pedidos
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Nenhum pedido foi feito ainda.</p>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>