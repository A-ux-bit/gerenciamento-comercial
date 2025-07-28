<?php 
include '../includes/config.php';
include '../includes/functions.php';

$pedidos = getPedidos();
?>

<?php include '../includes/header.php'; ?>

<h2>Pedidos</h2>
<a href="criar.php" class="btn btn-primary mb-3">Criar Novo Pedido</a>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Data</th>
            <th>Cliente</th>
            <th>Total</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pedidos as $pedido): ?>
        <tr>
            <td><?php echo $pedido['id']; ?></td>
            <td><?php echo date('d/m/Y', strtotime($pedido['data'])); ?></td>
            <td><?php echo htmlspecialchars($pedido['cliente_nome']); ?></td>
            <td>R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?></td>
            <td>
                <a href="detalhes.php?id=<?php echo $pedido['id']; ?>" class="btn btn-sm btn-info">Detalhes</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../includes/footer.php'; ?>