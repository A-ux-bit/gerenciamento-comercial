<?php 
include '../includes/config.php';
include '../includes/functions.php';

$compras = getCompras();
?>

<?php include '../includes/header.php'; ?>

<h2>Registro de Compras</h2>
<a href="registrar.php" class="btn btn-primary mb-3">Registrar Nova Compra</a>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Data</th>
            <th>Produto</th>
            <th>Quantidade</th>
            <th>Valor Total</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($compras as $compra): ?>
        <tr>
            <td><?php echo $compra['id']; ?></td>
            <td><?php echo date('d/m/Y', strtotime($compra['data'])); ?></td>
            <td><?php echo htmlspecialchars($compra['produto_nome']); ?></td>
            <td><?php echo $compra['quantidade']; ?></td>
            <td>R$ <?php echo number_format($compra['preco_total'], 2, ',', '.'); ?></td>
            <td>
                <a href="remover.php?id=<?php echo $compra['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir esta compra?')">Excluir</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../includes/footer.php'; ?>